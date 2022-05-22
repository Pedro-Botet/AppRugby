<?php

namespace App\Command;

use App\Entity\Jugador;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImportarDatosCommand extends Command
{
    // protected static $defaultName = 'app:ImportarDatos';
    // protected static $defaultDescription = 'Añadir datos partido';
    private $entityManager;

    public function __construct($projectDir, EntityManagerInterface $entityManager)
    {
        $this->projectDir = $projectDir;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:ImportarDatos')
            ->setDescription('Añadir datos jornada en formato .csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $fechaPartido = $input->getArgument('fecha_partido');

        $datosPartido = $this->getCsvRowsAsArrays();

        $jugadoresRepository = $this->entityManager->getRepository(Jugador::class);
        
        $actualizados = 0;

        foreach ($datosPartido as $dato) {

            if ($jugadorExiste = $jugadoresRepository->findOneBy(['nombre' => $dato['jugador']])){
                
                $this->updateJugador($jugadorExiste, $dato);
                
                $actualizados++;
            }

        }

        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);

        $io->success('Datos Guardados');

        return Command::SUCCESS;
    }

    public function getCsvRowsAsArrays()
    {
        $inputFile = $this->projectDir . '/public/DatosPartidos/18-05-2022.csv';

        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        return $decoder->decode(file_get_contents($inputFile), 'csv');
    }


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