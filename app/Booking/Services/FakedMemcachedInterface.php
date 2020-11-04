<?php

namespace App\Booking\Services;

interface FakedMemcachedInterface {


    public function get($key);
    public function set($key,$value);
    public function addServer($host, $port);

}
