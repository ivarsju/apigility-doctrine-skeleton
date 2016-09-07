<?php
return array(
    'controllers' => array(
        'factories' => array(
            'HybridAuth\\V1\\Rpc\\HybridAuth\\Controller' => 'HybridAuth\\V1\\Rpc\\HybridAuth\\HybridAuthControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'hybrid-auth.rpc.hybrid-auth' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/hybridauth[/:action]',
                    'defaults' => array(
                        'controller' => 'HybridAuth\\V1\\Rpc\\HybridAuth\\Controller',
                        'action' => 'hybridAuth',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'hybrid-auth.rpc.hybrid-auth',
        ),
    ),
    'zf-rpc' => array(
        'HybridAuth\\V1\\Rpc\\HybridAuth\\Controller' => array(
            'service_name' => 'HybridAuth',
            'http_methods' => array(
                0 => 'GET',
            ),
            'route_name' => 'hybrid-auth.rpc.hybrid-auth',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'HybridAuth\\V1\\Rpc\\HybridAuth\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'HybridAuth\\V1\\Rpc\\HybridAuth\\Controller' => array(
                0 => 'application/vnd.hybrid-auth.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ),
        ),
        'content_type_whitelist' => array(
            'HybridAuth\\V1\\Rpc\\HybridAuth\\Controller' => array(
                0 => 'application/vnd.hybrid-auth.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
);
