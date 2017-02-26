<?php
include '../vendor/autoload.php';
use \Onlyongunz\CASMinMin;

$service = new CASMinMin\Services\StudentPortal();
$identity = new CASMinMin\Identity\NPM('NPMAndaDiSini', 'passwordmu123');
$cas = new CASMinMin\CASMinMin($service, $identity);
$cas->login();