<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @Groups({"public", "private"})
     */
    private $id;

    /**
     * @Groups({"public", "private"})
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @Groups({"public", "private"})
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @Groups({"private"})
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Groups({"public", "private"})
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=1)
     */
    private $gender;

    public function __construct($uuid = null)
    {
        if(is_null($uuid)) {
            $this->id = uuid_create(UUID_TYPE_RANDOM);
        }

        $this->id = $uuid;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
}
