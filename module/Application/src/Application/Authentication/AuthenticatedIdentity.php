<?php

namespace Application\Authentication;

use Application\Model\User;

class AuthenticatedIdentity extends \ZF\MvcAuth\Identity\AuthenticatedIdentity
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct($user->getEmail());
        $this->setName($user->getEmail());
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}