<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]
#[ORM\Index(columns: ['age'], name: 'users_age_index')]
#[ORM\Index(columns: ['country'], name: 'users_country_index')]
class User
{
    const USER_NORMALIZATION_GROUP = 'user';

    #[ORM\Id]
    #[ORM\Column (name: 'id', type: "integer")]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $id;

    #[ORM\Column(name: 'name', length: 50)]
    #[Groups(['user'])]
    #[Assert\Length(
        max: 50,
        maxMessage: 'Users name cannot be longer than {{ limit }} characters',
    )]
    private string $name;

    #[ORM\Column(name: 'age', type: "integer")]
    #[Groups(['user'])]
    #[Assert\LessThan(
        value: 128,
        message: 'Users age cannot be more than {{ compared_value }}'
    )]
    private int $age;

    #[ORM\Column(name: 'country', length: 30)]
    #[Groups(['user'])]
    #[Assert\Length(
        max: 30,
        maxMessage: 'Country cannot be longer than {{ limit }} characters',
    )]
    private string $country;

    #[ORM\Column(name: 'email', length: 50)]
    #[Groups(['user'])]
    #[Assert\Length(
        max: 50,
        maxMessage: 'Users email cannot be longer than {{ limit }} characters',
    )]
    private string $email;

    #[ORM\Column(name: 'profile_pic', length: 150)]
    #[Groups(['user'])]
    #[Assert\Length(
        max: 150,
        maxMessage: 'Users profile picture cannot be longer than {{ limit }} characters',
    )]
    private string $profilePic;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {

        $this->age = $age;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProfilePic(): string
    {
        return $this->profilePic;
    }

    public function setProfilePic(string $profilePic): self
    {
        $this->profilePic = $profilePic;

        return $this;
    }
}
