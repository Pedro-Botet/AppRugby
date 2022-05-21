<?php

namespace App\Entity;

use App\Repository\JornadasRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=JornadasRepository::class)
 */
class Jornadas
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $jornada;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJornada(): ?string
    {
        return $this->jornada;
    }

    public function setJornada(string $jornada): self
    {
        $this->jornada = $jornada;

        return $this;
    }
}
