#!/usr/bin/php
<?php

require_once '../vendor/autoload.php';

$credentials = [
    'client_id' => 'testpodio-x4zjpu',
    'client_secret' => '1zjVzHQJBipe4O2AMAKJaXPRo6z74B8YSzGmHUrovUNGSMn5vWt7h48SACJI37eD',
    'scopes' => ['global' => 'all']
];

$auth = (new \Juanparati\Podium\Auths\ServerSideAuth(scopes: $credentials['scopes']))
    ->setClientId($credentials['client_id'])
    ->setClientSecret($credentials['client_secret']);

$auth->performAuthorization();