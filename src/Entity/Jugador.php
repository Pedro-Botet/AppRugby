<?php

namespace App\Entity;

use App\Repository\JugadorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JugadorRepository::class)
 */
class Jugador
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telefono;

    /**
     * @ORM\Column(type="integer")
     */
    private $anoNacimiento;

    /**
     * @ORM\Column(type="integer")
     */
    private $altura;

    /**
     * @ORM\Column(type="integer")
     */
    private $peso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tarjetaAmarilla;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tarjetaRoja;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ensayo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $minutosJugados;

    /**
     * @ORM\Column(type="boolean")
     */
    private $lesionado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $capitan;

    /**
     * @ORM\Column(type="integer")
     */
    private $posicion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $esChutador;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placajesTotal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placajesDone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $touchTotal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $touchDone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meleeTotal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meleeDone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $chutePalosTotal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $chutePalosDone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $golpesCastigoTotal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $golpesCastigoDone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getAnoNacimiento(): ?int
    {
        return $this->anoNacimiento;
    }

    public function setAnoNacimiento(int $anoNacimiento): self
    {
        $this->anoNacimiento = $anoNacimiento;

        return $this;
    }

    public function getAltura(): ?int
    {
        return $this->altura;
    }

    public function setAltura(int $altura): self
    {
        $this->altura = $altura;

        return $this;
    }

    public function getPeso(): ?int
    {
        return $this->peso;
    }

    public function setPeso(int $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getTarjetaAmarilla(): ?string
    {
        return $this->tarjetaAmarilla;
    }

    public function setTarjetaAmarilla(string $tarjetaAmarilla): self
    {
        $this->tarjetaAmarilla = $tarjetaAmarilla;

        return $this;
    }

    public function getTarjetaRoja(): ?string
    {
        return $this->tarjetaRoja;
    }

    public function setTarjetaRoja(string $tarjetaRoja): self
    {
        $this->tarjetaRoja = $tarjetaRoja;

        return $this;
    }

    public function getEnsayo(): ?string
    {
        return $this->ensayo;
    }

    public function setEnsayo(string $ensayo): self
    {
        $this->ensayo = $ensayo;

        return $this;
    }

    public function getMinutosJugados(): ?string
    {
        return $this->minutosJugados;
    }

    public function setMinutosJugados(string $minutosJugados): self
    {
        $this->minutosJugados = $minutosJugados;

        return $this;
    }

    public function isLesionado(): ?bool
    {
        return $this->lesionado;
    }

    public function setLesionado(bool $lesionado): self
    {
        $this->lesionado = $lesionado;

        return $this;
    }

    public function isCapitan(): ?bool
    {
        return $this->capitan;
    }

    public function setCapitan(bool $capitan): self
    {
        $this->capitan = $capitan;

        return $this;
    }

    public function getPosicion(): ?int
    {
        return $this->posicion;
    }

    public function setPosicion(int $posicion): self
    {
        $this->posicion = $posicion;

        return $this;
    }

    public function isEsChutador(): ?bool
    {
        return $this->esChutador;
    }

    public function setEsChutador(bool $esChutador): self
    {
        $this->esChutador = $esChutador;

        return $this;
    }

    public function getPlacajesTotal(): ?string
    {
        return $this->placajesTotal;
    }

    public function setPlacajesTotal(?string $placajesTotal): self
    {
        $this->placajesTotal = $placajesTotal;

        return $this;
    }

    public function getPlacajesDone(): ?string
    {
        return $this->placajesDone;
    }

    public function setPlacajesDone(?string $placajesDone): self
    {
        $this->placajesDone = $placajesDone;

        return $this;
    }

    public function getTouchTotal(): ?string
    {
        return $this->touchTotal;
    }

    public function setTouchTotal(?string $touchTotal): self
    {
        $this->touchTotal = $touchTotal;

        return $this;
    }

    public function getTouchDone(): ?string
    {
        return $this->touchDone;
    }

    public function setTouchDone(?string $touchDone): self
    {
        $this->touchDone = $touchDone;

        return $this;
    }

    public function getMeleeTotal(): ?string
    {
        return $this->meleeTotal;
    }

    public function setMeleeTotal(?string $meleeTotal): self
    {
        $this->meleeTotal = $meleeTotal;

        return $this;
    }

    public function getMeleeDone(): ?string
    {
        return $this->meleeDone;
    }

    public function setMeleeDone(?string $meleeDone): self
    {
        $this->meleeDone = $meleeDone;

        return $this;
    }

    public function getChutePalosTotal(): ?string
    {
        return $this->chutePalosTotal;
    }

    public function setChutePalosTotal(?string $chutePalosTotal): self
    {
        $this->chutePalosTotal = $chutePalosTotal;

        return $this;
    }

    public function getChutePalosDone(): ?string
    {
        return $this->chutePalosDone;
    }

    public function setChutePalosDone(?string $chutePalosDone): self
    {
        $this->chutePalosDone = $chutePalosDone;

        return $this;
    }

    public function getGolpesCastigoTotal(): ?string
    {
        return $this->golpesCastigoTotal;
    }

    public function setGolpesCastigoTotal(?string $golpesCastigoTotal): self
    {
        $this->golpesCastigoTotal = $golpesCastigoTotal;

        return $this;
    }

    public function getGolpesCastigoDone(): ?string
    {
        return $this->golpesCastigoDone;
    }

    public function setGolpesCastigoDone(?string $golpesCastigoDone): self
    {
        $this->golpesCastigoDone = $golpesCastigoDone;

        return $this;
    }
}
