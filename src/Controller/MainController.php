<?php

namespace App\Controller;

use App\Repository\JugadorRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Página de inicio
     * 
     * @Route("/", name="app_homepage")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * Template para los errores
     * 
     * @Route("/", name="error")
     */
    public function error($error)
    {
        if($error == 1){
            $mensaje = 'Haz Login para acceder a tu carrito';
        }
        return $this->render('error.html.twig', [
            'error' => $mensaje
        ]);
    }

    /**
     * Muestra las estidísticas del equipo
     * 
     * @Route("/estadisticas", name="estadisticas")
     */
    public function estaditicas(JugadorRepository $jugadorRepository)
    {

        $jugadores = $jugadorRepository->findAll();

        // Maximo y jugador
        $maxPlacajes = 0;
        $placajes = 0;
        $maxTarjetasAmarilla = 0;
        $tarjetasAmarilla = 0;
        $maxTarjetasRoja = 0;
        $tarjetasRoja = 0;
        $maxEnsayo = 0;
        $Ensayo = 0;
        $maxchutesPalos = 0;
        $chutesPalos = 0;
        $maxMinutos = 0;
        $minutos = 0;
        $maxMelee = 0;
        $melee = 0;
        $maxTouch = 0;
        $touch = 0;

        // Total equipo
        $placajesTotal = 0;
        $placajesDone = 0;
        $totalTarjetasAmarilla = 0;
        $totalTarjetasRoja = 0;
        $totalEnsayo = 0;
        $chutesPalosTotal = 0;
        $chutesPalosDone = 0;
        $totalMinutos = 0;
        $meleeTotal = 0;
        $meleeDone = 0;
        $touchTotal = 0;
        $touchDone = 0;
        $lesioanadoTotal = 0;
        $chutadorTotal = 0;

        foreach ($jugadores as $jugador ){

            if($jugador->getPosicion() <= 8){

                if($maxMelee < $jugador->getMeleeTotal()){
                    $maxMelee = $jugador->getMeleeTotal();
                    $melee = $jugador->getId();
                }

                if($maxTouch < $jugador->getTouchTotal()){
                    $maxTouch = $jugador->getTouchTotal();
                    $touch = $jugador->getId();
                }

                $meleeDone += $jugador->getMeleeDone();
                $meleeTotal += $jugador->getMeleeTotal();

                $touchDone += $jugador->getTouchDone();
                $touchTotal += $jugador->getTouchTotal();
                
            }else{

                if($maxchutesPalos < $jugador->getChutePalosDone()){
                    $maxchutesPalos = $jugador->getChutePalosDone();
                    $chutesPalos = $jugador->getId();
                }

                $chutesPalosDone += $jugador->getChutePalosDone();
                $chutesPalosTotal += $jugador->getChutePalosTotal();
            }

            if($maxPlacajes < $jugador->getPlacajesDone()){
                $maxPlacajes = $jugador->getPlacajesDone();
                $placajes = $jugador->getId();
            }

            if($maxTarjetasAmarilla < $jugador->getTarjetaAmarilla()){
                $maxTarjetasAmarilla = $jugador->getTarjetaAmarilla();
                $tarjetasAmarilla = $jugador->getId();
            }

            if($maxTarjetasRoja < $jugador->getTarjetaRoja()){
                $maxPlacajes = $jugador->getTarjetaRoja();
                $tarjetasRoja = $jugador->getId();
            }

            if($maxEnsayo < $jugador->getEnsayo()){
                $maxEnsayo = $jugador->getEnsayo();
                $ensayo = $jugador->getId();
            }
            

            $placajesDone += $jugador->getPlacajesDone();
            $placajesTotal += $jugador->getPlacajesTotal();
            $totalTarjetasAmarilla += $jugador->getTarjetaAmarilla();
            $totalTarjetasRoja += $jugador->getTarjetaRoja();
            $totalEnsayo += $jugador->getEnsayo();
            $totalMinutos += $jugador->getMinutosJugados();

            if($jugador->isLesionado()){
                $lesioanadoTotal ++;
            }

            if($jugador->isEsChutador()){
                $chutadorTotal ++;
            }
        }

        return $this->render('main/estadisticas.html.twig', [
            'placajes' => $jugadorRepository->find($placajes),
            'tarjetasAmarilla' => $jugadorRepository->find($tarjetasAmarilla),
            'tarjetasRoja' => $jugadorRepository->find($tarjetasRoja),
            'ensayo' => $jugadorRepository->find($ensayo),
            'chutesPalos' => $jugadorRepository->find($chutesPalos),
            'minutos' => $jugadorRepository->find($minutos),
            'melee' => $jugadorRepository->find($melee),
            'touch' => $jugadorRepository->find($touch),
            'placajesPorcentaje' => $this->getPorcentaje($placajesTotal, $placajesDone),
            'placajesTotal' => $placajesTotal,
            'chutesPalosPorcentaje' => $this->getPorcentaje($chutesPalosTotal, $chutesPalosDone),
            'chutesPalosTotal' =>$chutesPalosTotal,
            'meleePorcentaje' => $this->getPorcentaje($meleeTotal, $meleeDone),
            'meleeTotal' => $meleeTotal,
            'touchPorcentaje' => $this->getPorcentaje($touchTotal, $touchDone),
            'touchTotal' => $touchTotal,
            'totalTarjetasAmarilla' => $totalTarjetasAmarilla,
            'totalTarjetasRoja' => $totalTarjetasRoja,
            'totalEnsayo' => $totalEnsayo,
            'totalMinutos' => $totalMinutos,
            'lesioanadoTotal' => $lesioanadoTotal,
            'chutadorTotal' => $chutadorTotal
        ]);
    }

    /**
     * Función que calcula el porcentaje
     */
    public function getPorcentaje($total, $done)
    {
        $resultado = 0;

        if($total == $done){
            $resultado = 100;
        }
        else if($total > 0){
            $resultado = (($done * 100) / $total)|number_format(2);
        }else{
            $resultado = 0;
        }

        return $resultado;
    }
}