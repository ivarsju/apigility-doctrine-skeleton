<?php

namespace HybridAuth\V1\Rpc\HybridAuth;

class HybridAuthControllerFactory
{
    public function __invoke($controllers)
    {
        $config = $controllers->getServiceLocator()->get('config');
        $entityManager = $controllers->getServiceLocator()->get(\Doctrine\ORM\EntityManager::class);

        return new HybridAuthController($entityManager, $config['hybridauth'], $config['cryptoKey'], $config['publicUrl']);
    }
}