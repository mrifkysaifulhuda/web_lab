<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class MyAuth
{
    public static function saveUser($data)
    {
        Session::put('is-login', true);
        foreach ($data as $key => $value) {
            Session::put($key, $value);
        }

        return true;
    }
}