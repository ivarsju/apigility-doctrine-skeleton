<?php

namespace HybridAuth\V1\Rpc\HybridAuth;

use Application\Model\User;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

class HybridAuthController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array Hybridauth configuration
     */
    private $hybridauthConfiguration;

    /**
     * @var string
     */
    private $cryptoKey;

    /**
     * @var string
     */
    private $publicUrl;

    /**
     * Constructor
     * @param EntityManager $entityManager
     * @param array $hybridauthConfiguration
     * @param string $cryptoKey
     * @param string $publicUrl
     */
    public function __construct(EntityManager $entityManager, array $hybridauthConfiguration, $cryptoKey, $publicUrl)
    {
        $this->entityManager  = $entityManager;
        $this->hybridauthConfiguration = $hybridauthConfiguration;
        $this->cryptoKey = $cryptoKey;
        $this->publicUrl = $publicUrl;
    }

    /**
     * Returns the select authentification provider
     * @return \Hybridauth\Adapter\AdapterInterface
     */
    private function getProvider()
    {

        $provider = $this->getRequest()->getQuery('provider');
        $config = $this->hybridauthConfiguration;

        // CAUTION: Be sure to change the route name according to your need !
        $routeName = 'hybrid-auth.rpc.hybrid-auth';
        $config['callback'] = $this->publicUrl . $this->url()->fromRoute($routeName, ['action' => 'callback']) . '?provider=' . $provider;

        $hybridauth = new \Hybridauth\Hybridauth($config);
        $adapter = $hybridauth->getAdapter($provider);

        return $adapter;
    }

    public function hybridAuthAction()
    {
        $provider = $this->getProvider();
        $provider->disconnect();
        $provider->authenticate();

        // If we reach this point, that means we were already authenticated by
        // the provider. So we finish as a normal subscribe/login
        return $this->callbackAction();
    }

    public function callbackAction()
    {
        $provider = $this->getProvider();
        $provider->authenticate();
        $profile = $provider->getUserProfile();
        $providerName = strtolower($this->getRequest()->getQuery('provider'));

        $user = $this->entityManager->getRepository(User::class)->createOrUpdate($providerName, $profile);
        $this->entityManager->flush();

        $message = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'photo' => $user->getPhoto(),
        ];

        $jwt = new \OAuth2\Encryption\Jwt();
        $token = $jwt->encode($message, $this->cryptoKey);

        $url = $this->publicUrl . 'receive.html?token=' . $token;

        return $this->redirect()->toUrl($url);
    }
}