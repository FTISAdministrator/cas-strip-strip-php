<?php
include '../vendor/autoload.php';
use \Onlyongunz\CASMinMin;

$service = new CASMinMin\Services\StudentPortal();
$identity = new CASMinMin\Identity\NPM('NPMAndaDiSini', 'PasswordMu123');
$cas = new CASMinMin\CASMinMin($service, $identity);
$cas->login();

$client = $service->get_client();
$resp = $client->get('includes/profile.php');
echo $resp->getBody();