<?php

namespace App\Controller;

use App\Command\ImportarDatosCommand;
use App\Entity\Staff;

use App\Form\StaffEditarType;
use App\Form\StaffNewType;

use App\Repository\StaffRepository;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class StaffController extends AbstractController
{
    /**
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
     * @Route("staff/detalle/{userId}", name="miCuentaStaff")
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
     * 
     * @Route("staff/upload_jornada" name="uploadJornada")
     */
    public function uploadJornada(Request $request, ImportarDatosCommand $importarDatos)
    {
        $form = $this->createFormBuilder()
            ->add('nJornada', TextType::class,[
                'label' => 'Introduce el Número de la Jornada',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'minMessage' => 'El nombre no puede ser inferior a {{ limit }} carácteres',
                        'max' => 2,
                        'maxMessage' => 'El nombre no pude ser superior a {{ limit }} carácteres',
                    ])
                ],
            ])
            ->add('jornada', FileType::class, [
                'label' => "Adjuntar nueva jornada en formato jornada_'nºjornada'.csv",
            ])
            ->getForm()
        ;
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $request->files->get('jornada');
            $fileName = ($form->get('nJornada')->getData())->prepend('jornada'); 

            if ($file) {
                $file->move(
                    $this->getParameter('projectDir') . 'public/DatosPartidos/',
                    $file
                );
            }else{
                $this->render('error.html.twig',[
                    'error' => 'Se ha producido un error, vuelva a subir el archivo'
                ]);
            }

            $projectDir = $this->getParameter('projectDir');
            
            $importarDatos();
            
        }

        return $this->render('staff/miCuenta_editar.html.twig', [
            'form' => $form->createView(),
            'titulo' => 'Subir Jornada'
        ]);
    

    }
}
