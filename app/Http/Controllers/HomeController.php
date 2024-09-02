<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomeIndexResource;

class HomeController extends Controller
{
    public function index()
    {
        return new HomeIndexResource(true);
    }
}
