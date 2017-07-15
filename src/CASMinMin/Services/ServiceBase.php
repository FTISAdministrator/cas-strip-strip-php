<?php
namespace Chez14\CASMinMin\Services;

interface ServiceBase {
    public function init_login();
    public function done_login($ticket_url);
    public function get_service();
    public function get_client();
}