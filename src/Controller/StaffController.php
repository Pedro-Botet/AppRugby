<?php

namespace App\Controller;

use App\Entity\Jornadas;
use App\Entity\Staff;

use App\Form\StaffEditarType;
use App\Form\StaffNewType;
use App\Form\UploadJornadaType;

use App\Repository\JugadorRepository;
use App\Repository\StaffRepository;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\String\Slugger\SluggerInterface;

class StaffController extends AbstractController
{
    /**
     * Crear Nuevo Miembro del Staff
     *
     * @Route("/staff", name="new_staff")
     */
    public function newStaff(Request $request, EntityManagerInterface $entityManager, UserRepository $repository): Response
    {
       
        $user = $repository->find($this->getUser()->getId());
       
        $staff = new Staff();
        $form = $this->createForm(StaffNewType::class, $staff);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $staff->setUser($user)
                    ->setNombre($form->get('nombre')->getData())
                    ->setApellido($form->get('apellido')->getData())
                    ->setTelefono($form->get('telefono')->getData());
            
            $entityManager->persist($staff);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('staff/form_new_staff.html.twig', [
            'form' => $form->createView(),
            'titulo' => 'Alta Nuevo Miembre del Staff'
        ]);
    }

    /**
     * Muestra la cuenta de un miembro del Staff y permite editarla
     * 
     * @Route("staff/miCuenta/{userId}", name="miCuentaStaff")
     */
    public function detalleCuenta(StaffRepository $staffRepository, $userId)
    {
        if ($this->getUser()->getId() != $userId){
           
            return $this->render('error.html.twig', [
                'error' => 'Se ha producido un error, intentelo de nuevo '
            ]);
        }else{
            
            return $this->render('staff/miCuenta_detalle.html.twig', [
                'staff' => $staffRepository->findOneBy(['user' => $userId]),
                'email' => $this->getUser()->getEmail()
            ]);
        }
    }

    /**
     * Lista a Todos los Miembros del Staff
     * 
     * @Route("staff/listar", name="listar_staff")
     */
    public function listarJugadores(StaffRepository $staffRepository)
    {
        return $this->render('staff/listar_staff.html.twig', [
            'staff' =>$staffRepository->findAll()
        ]);
    }

    /**
     * Muestra la Información de unn Miembro del Staff a Cualquier Usuario
     * 
     * @Route("staff/detalle/{staffId}", name="staff_detalle")
     */
    public function jugadorDetalle(StaffRepository $staffRepository, UserRepository $userRepository, $staffId)
    {
        $staff = $staffRepository->find($staffId);
        $email = $userRepository->findOneBy(['id' => $staff->getUser()])->getEmail();
       
        return $this->render('staff/staff_detalle.html.twig', [
            'staff' => $staff,
            'email' => $email
        ]);
    }

    /**
     * Permite al Staff Editar sus Propios Datos
     * 
     * @Route("staff/editar/{userId}", name="editarStaff")
     */
    public function editarCuenta(Request $request, EntityManagerInterface $entityManager, StaffRepository $staffRepository,$userId)
    {
        if ($this->getUser()->getId() != $userId){
           
            return $this->render('error.html.twig', [
                'error' => 'Se ha producido un error, intentelo de nuevo'
            ]);
        }else{
            
            $staff = $staffRepository->findOneBy(['user' => $userId]);

            $form =$this->createForm(StaffEditarType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                if($form->get('telefono')->getData() != null){

                    $staff->setTelefono(($form->get('telefono')->getData()));
                }

                $entityManager->persist($staff);
                $entityManager->flush();

                return $this->redirectToRoute('miCuentaStaff', [
                    'userId' => $this->getUser()->getId(),
                ]);
            }

            return $this->render('staff/miCuenta_editar.html.twig', [
                'formEditar' => $form->createView(),
                'titulo' => 'Editar mi Cuenta'
            ]);
        }
    }

    /**
     * Sube los datos de cada jornada en CSV y los guarda
     * 
     * @Route("staff/jornada_upload", name="uploadJornada")
     */
    public function uploadJornada(Request $request, JugadorRepository $jugadoresRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {

        if($this->getUSer()->getUserType() == 'Staff'){

            $form = $this->createForm(UploadJornadaType::class);
            
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

                /** @var UploadedFile $file */
                $file = $form->get('jornada')->getData();
            
                if ($file) {

                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);

                    $newFilename = $safeFilename.'-'.uniqid().'.csv';

                    try{
                    $file->move(
                        $this->getParameter('uploads_dir'),
                        $newFilename
                    );
                    }catch (FileException $e) {
                        return $this->render('error.html.twig',[
                            'error' => 'Se ha producido un error, vuelva a subir el archivo'
                        ]);
                    }
                }else{
                    return $this->render('error.html.twig',[
                        'error' => 'Se ha producido un error, vuelva a subir el archivo'
                    ]);
                }

                $datosPartido = $this->getCsvRowsAsArrays($newFilename);
                
                $actualizados = 0;

                try{
                    foreach ($datosPartido as $dato) {

                        if ($jugadorExiste = $jugadoresRepository->findOneBy(['nombre' => $dato['jugador']])){
                            
                            $this->updateJugador($jugadorExiste, $dato);
                            
                            $actualizados++;
                        }

                    }
                }catch (\Exception $e){
                    return $this->render('error.html.twig',[
                        'error' => 'Se ha producido un error al leer el archivo, vuelva a subir el archivo en formato .csv'
                    ]);
                }

                $entityManager->flush();

                return $this->render('staff/jornada_exito.html.twig', [
                    'actualizados' => $actualizados
                ]);
            }

            return $this->render('staff/jornada_upload.html.twig', [
                'form' => $form->createView(),
                'titulo' => 'Subir Jornada'
            ]);

        }else{
            return $this->render('error.html.twig',[
                'error' => 'Se ha producido un error'
            ]);
        }
    }

    
    /**
     * Función que recibe el nombre del archivo de la jornada y devuelve un array de los datos
     */
    public function getCsvRowsAsArrays($newFilename)
    {
        $inputFile = $this->getParameter('uploads_dir') . $newFilename;
        
        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder([';', '"', '', '.'])]);

        return $decoder->decode(file_get_contents($inputFile), 'csv');
    }


    /***
     * Función que comprueba que el jugador tiene datos que actualizar y en tal caso los actualiza
     */
    public function updateJugador($jugadorExiste, $dato)
    {
        if ($dato['placajes_intentados'] != null) {
            $placajesToltal = $dato['placajes_intentados'] + $jugadorExiste->getPlacajesTotal();

            $jugadorExiste->setPlacajesTotal($placajesToltal);

        }
       
        if ($dato['placajes_conseguidos'] != null) {
            $placajesHechos = $dato['placajes_conseguidos'] + $jugadorExiste->getPlacajesDone();

            $jugadorExiste->setPlacajesDone($placajesHechos);
        }
        
        if ($dato['touch_totales'] != null) {
            $touchTotales = $dato['touch_totales'] + $jugadorExiste->getTouchTotal();

            $jugadorExiste->setTouchTotal($touchTotales);

        }
        
        if ($dato['touch_ganadas'] != null) {
          $touchDone = $dato['touch_ganadas'] + $jugadorExiste->getTouchDone();

          $jugadorExiste->setTouchDone($touchDone);;  
        }
        
        if ($dato['melee_totales'] != null) {
          $meleeTotal = $dato['melee_totales'] + $jugadorExiste->getMeleeTotal();  

            $jugadorExiste->setMeleeTotal($meleeTotal);
        }

        if ($dato['melee_ganadas'] != null) {
            $meleeDone = $dato['melee_ganadas'] + $jugadorExiste->getMeleeDone();  

              $jugadorExiste->setMeleeDone($meleeDone);
          }
        
        if ($dato['chutes_palos'] != null) {
            $chutesPalosTotal = $dato['chutes_palos'] + $jugadorExiste->getChutePalosTotal();

            $jugadorExiste->setChutePalosTotal($chutesPalosTotal);
        }
        
        if ($dato['chutes_palos_dentro'] != null) {
            $chutesPalosDone = $dato['chutes_palos_dentro'] + $jugadorExiste->getChutePalosDone();

            $jugadorExiste->setchutePalosDone($chutesPalosDone);;
        }
        
        if ($dato['tarjeta_amarilla'] != null) {
            $tarjetaAmarilla = $dato['tarjeta_amarilla'] + $jugadorExiste->getTarjetaAmarilla();

            $jugadorExiste->setTarjetaAmarilla($tarjetaAmarilla);
        }
        
        if ($dato['tarjeta_roja'] != null) {
            $tarjetaRoja = $dato['tarjeta_roja'] + $jugadorExiste->getTarjetaRoja();

            $jugadorExiste->setTarjetaRoja($tarjetaRoja);;
        }
        
        if ($dato['ensayo'] != null) {
            $ensayo = $dato['ensayo'] + $jugadorExiste->getEnsayo();

            $jugadorExiste->setEnsayo($ensayo);
        }
        
        if ($dato['minutos_jugados'] != null) {
            $minutosJugados = $dato['minutos_jugados'] + $jugadorExiste->getMinutosJugados();

            $jugadorExiste->setMinutosJugados($minutosJugados);
        }
        
        if ($dato['lesionado'] != null) {
            $jugadorExiste->setLesionado($dato['lesionado']);
        }

        $this->entityManager->persist($jugadorExiste);
    }

}
