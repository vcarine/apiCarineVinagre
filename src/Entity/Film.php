<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FilmRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FilmRepository::class)
 */
#[ApiResource(normalizationContext: ['groups' => ['film']])]
class Film
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Ce titre est requis")
     * @Groups({"film"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"film"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"film"})
     */
    private $realisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRealisateur(): ?string
    {
        return $this->realisateur;
    }

    public function setRealisateur(string $realisateur): self
    {
        $this->realisateur = $realisateur;

        return $this;
    }
    /**
     * @return Collection|Film[]
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function addFilm(Film $film): self
    {
        if (!$this->films->contains($film)) {
            $this->films[] = $film;
            $film->setTitre($this);
            $film->setDescription($this);
            $film->setRealisateur($this);
        }

        return $this;
    }

    public function removeCar(Film $film): self
    {
        if ($this->films->removeElement($film)) {

            if ($film->getTitre() === $this) {
                $film->setTitre(null);
            }
        }

        return $this;
    }
}
