<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class InstallController extends Controller
{
    public function test()
    {
        Artisan::call('migrate');
        return Artisan::output();
    }
}
