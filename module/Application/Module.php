<?php

namespace Application;

use Zend\Mvc\MvcEvent;
use Application\Model\User;
use Application\Authentication\AuthenticatedIdentity;
use ZF\MvcAuth\Identity\GuestIdentity;

class Module
{
    private $serviceManager;

    public function onBootstrap(MvcEvent $e)
    {
        $this->serviceManager = $e->getApplication()->getServiceManager();
        $app = $e->getTarget();
        $events = $app->getEventManager();
        $events->attach('authentication', [$this, 'onAuthentication'], 100);
        $events->attach('authorization', [$this, 'onAuthorization'], 100);

        // other things ...
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * If the AUTHORIZATION HTTP header is found, validate and return the user, otherwise default to 'guest'
     * @param \ZF\MvcAuth\MvcAuthEvent $e
     * @return AuthenticatedIdentity|GuestIdentity
     */
    public function onAuthentication(\ZF\MvcAuth\MvcAuthEvent $e)
    {
        $guest = new GuestIdentity();
        $header = $e->getMvcEvent()->getRequest()->getHeader('AUTHORIZATION');
        if (!$header) {
            return $guest;
        }

        $token = $header->getFieldValue();
        $jwt = new \OAuth2\Encryption\Jwt();
        $key = $this->serviceManager->get('config')['cryptoKey'];
        $tokenData = $jwt->decode($token, $key);

        // If the token is invalid, give up
        if (!$tokenData) {
            return $guest;
        }

        $entityManager = $this->serviceManager->get(\Doctrine\ORM\EntityManager::class);
        $user = $entityManager->getRepository(User::class)->findOneById($tokenData['id']);

        $identity = new AuthenticatedIdentity($user);

        return $identity;
    }

    public function onAuthorization(\ZF\MvcAuth\MvcAuthEvent $e)
    {
        /* @var $authorization \ZF\MvcAuth\Authorization\AclAuthorization */
        $authorization = $e->getAuthorizationService();
        $identity = $e->getIdentity();
        $resource = $e->getResource();

        // now set up additional ACLs...
    }
}
