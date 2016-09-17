<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * An identity coming from a 3rd party identity provider (Google, Facebook, etc.)
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint(name="identity",columns={"provider", "provider_id"}),
 * },
 * name="identity")
 * @ORM\Entity(repositoryClass="Application\Repository\IdentityRepository")
 */
class Identity
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
     * @var User
     * DB column name must be user_id (_id added automatically)
     * or you can set column name in JoinColumn: name="user", ...
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * This is the identity provider (Facebook, Google, etc.)
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $provider;

    /**
     * This is the ID given by the identity provider
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $providerId;

    /**
     * Set user
     * @param User $user
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set provider
     * @param string $provider
     * @return self
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set provider ID
     * @param string $providerId
     * @return self
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;

        return $this;
    }

    /**
     * Get provider Id
     * @return string
     */
    public function getProviderId()
    {
        return $this->providerId;
    }
}
