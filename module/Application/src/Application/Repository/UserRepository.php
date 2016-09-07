<?php

namespace Application\Repository;

use Application\Model\Identity;
use Application\Model\User;
use Doctrine\ORM\EntityRepository;
use Hybridauth\User\Profile;

class UserRepository extends EntityRepository
{
    /**
     * Create or update a user according to its social identity (coming from Facebook, Google, etc.)
     * @param string $provider
     * @param Profile $profile
     * @return User
     */
    public function createOrUpdate($provider, Profile $profile)
    {
        // First, look for pre-existing identity
        $identityRepository = $this->getEntityManager()->getRepository(Identity::class);
        $identity = $identityRepository->findOneBy([
            'provider' => $provider,
            'providerId' => $profile->identifier,
        ]);

        if ($identity) {
            $user = $identity->getUser();
        }
        // Second, look for pre-existing user (with another identity)
        elseif ($profile->email) {
            $user = $this->findOneByEmail($profile->email);
        } else {
            $user = null;
        }

        // If we still couldn't find a user yet, create a brand new one
        if (!$user) {
            $user = new User();
            $this->getEntityManager()->persist($user);
        }

        // Also create an identity if we couldn't find one at the beginning
        if (!$identity) {
            $identity = new Identity();
            $identity->setUser($user);
            $identity->setProvider($provider);
            $identity->setProviderId($profile->identifier);
            $this->getEntityManager()->persist($identity);
        }

        // Finally update all user properties, but never destroy existing data

        if ($profile->displayName) {
            $user->setName($profile->displayName);
        }

        if ($profile->email) {
            $user->setEmail($profile->email);
        }

        if ($profile->photoURL) {
            $user->setPhoto($profile->photoURL);
        }

        // and other properties ...

        return $user;
    }
}
