<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint(name="user_email",columns={"email"}),
 * },
 * name="user")
 * @ORM\Entity(repositoryClass="Application\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name = '';

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * URL of photo for the user
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set photo URL
     * @param string $photo
     * @return self
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo URL
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}