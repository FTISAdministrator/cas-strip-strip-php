<?php
include '../vendor/autoload.php';
use \Chez14\CASMinMin;

$service = new CASMinMin\Services\StudentPortal();
$identity = new CASMinMin\Identity\NPM('NPMAndaDiSini', 'PasswordMu123');
$cas = new CASMinMin\CASMinMin($service, $identity);
$cas->set_cookie_file('cookiejar.json');
$cas->login();

$service = new CASMinMin\Services\StudentPortal();
$cas = new CASMinMin\CASMinMin($service);
$cas->set_cookie_file('cookiejar.json');
$cas->login_service();


$client = $service->get_client();
$resp = $client->get('includes/profile.php');
echo $resp->getBody();