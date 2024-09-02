<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomeResource;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return new HomeResource(true);
    }
}
