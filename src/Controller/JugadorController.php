<?php

namespace App\Controller;

use App\Entity\Jugador;
use App\Form\JugadorEditarType;
use App\Form\JugadorNewType;
use App\Repository\JugadorRepository;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JugadorController extends AbstractController
{
    /**
     * 
     * @Route("/jugador", name="new_jugador")
     */
    public function newJugador(Request $request, EntityManagerInterface $entityManager, UserRepository $repository): Response
    {

        $user = $repository->find($this->getUser()->getId());

        $jugador = new Jugador();
        $form = $this->createForm(JugadorNewType::class, $jugador);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $jugador->setUser($user)
                    ->setNombre($form->get('nombre')->getData())
                    ->setApellido($form->get('apellido')->getData())
                    ->setTelefono($form->get('telefono')->getData())
                    ->setAnoNacimiento($form->get('anoNacimiento')->getData())
                    ->setAltura($form->get('altura')->getData())
                    ->setPeso($form->get('peso')->getData())
                    ->setPlacajesTotal("0")
                    ->setPlacajesDone("0")
                    ->setTarjetaAmarilla(0)
                    ->setTarjetaRoja(0)
                    ->setEnsayo(0)
                    ->setMinutosJugados(0)
                    ->setLesionado(False)
                    ->setCapitan($form->get('capitan')->getData())
                    ->setPosicion($form->get('posicion')->getData())
                    ->setEsChutador($form->get('esChutador')->getData());
            
            if($form->get('esChutador')){
                $jugador->setChutePalosTotal("0");
                $jugador->setChutePalosDone("0");
            }

            if($jugador->getPosicion() < 9 || $jugador->getPosicion() == 'Segunda' || $jugador->getPosicion() == 'Flanker'){
                $jugador->setMeleeTotal("0")
                        ->setMeleeDone("0")
                        ->setTouchTotal("0")
                        ->setTouchDone("0");
            }

            $entityManager->persist($jugador);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('jugador/form_new_jugador.html.twig', [
            'form' => $form->createView(),
            'titulo' => 'Alta Nuevo Jugador'
        ]);

    }

    /**
     * @Route("jugador/miCuenta/{userId}", name="miCuentaJugador")
     */
    public function detalleCuenta(JugadorRepository $jugadorRepository, $userId)
    {
        if ($this->getUser()->getId() != $userId){
           
            return $this->render('error.html.twig', [
                'error' => 'Se ha producido un error, intentelo de nuevo '
            ]);
        }else{
            
            return $this->render('jugador/miCuenta_detalle.html.twig', [
                'jugador' => $jugadorRepository->findOneBy(['user' => $userId]),
                'email' => $this->getUser()->getEmail()
            ]);
        }
    }

    /**
     * @Route("jugador/editar/{userId}", name="editarJugador")
     */
    public function editarCuenta(Request $request, EntityManagerInterface $entityManager, JugadorRepository $jugadorRepository, UserRepository $userRepository,$userId)
    {
        if ($this->getUser()->getId() != $userId){
           
            return $this->render('error.html.twig', [
                'error' => 'Se ha producido un error, intentelo de nuevo'
            ]);
        }else{
            
            $jugador = $jugadorRepository->findOneBy(['user' => $userId]);
            $user = $userRepository->find($userId);

            $form =$this->createForm(JugadorEditarType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                if($form->get('peso')->getData() != null){
                    $jugador->setPeso($form->get('peso')->getData());
                }
                if($form->get('altura')->getData() != null){
                    $jugador->setAltura(($form->get('altura')->getData()));
                }
                if($form->get('lesionado')->getData() != null){
                    $jugador->setLesionado(($form->get('lesionado')->getData()));
                }
                if($form->get('posicion')->getData() != null){

                    $jugador->setPosicion(($form->get('posicion')->getData()));
                }
                if($form->get('esChutador')->getData() != null){

                    $jugador->setEsChutador(($form->get('esChutador')->getData()));
                }
                if($form->get('telefono')->getData() != null){

                    $jugador->setTelefono(($form->get('telefono')->getData()));
                }

                $entityManager->persist($jugador, $user);
                $entityManager->flush();

                return $this->redirectToRoute('miCuentaJugador', [
                    'userId' => $this->getUser()->getId(),
                ]);
            }

            return $this->render('jugador/miCuenta_editar.html.twig', [
                'formEditar' => $form->createView(),
                'titulo' => 'Editar mi Cuenta'
            ]);
        }
    }

    /**
     * 
     * @Route("jugador/listar", name="listar_jugadores")
     */
    public function listarJugadores(JugadorRepository $jugadorRepository)
    {
        return $this->render('jugador/listar_jugadores.html.twig', [
            'jugadores' =>$jugadorRepository->findAll()
        ]);
    }

    /**
     * 
     * @Route("jugador/detalle/{jugadorId}", name="jugador_detalle")
     */
    public function jugadorDetalle(JugadorRepository $jugadorRepository, UserRepository $userRepository, $jugadorId)
    {
        $jugador = $jugadorRepository->find($jugadorId);
        $email = $userRepository->findOneBy(['id' => $jugador->getUser()])->getEmail();
       
        return $this->render('jugador/jugador_detalle.html.twig', [
            'jugador' => $jugador,
            'email' => $email
        ]);
    }


}
