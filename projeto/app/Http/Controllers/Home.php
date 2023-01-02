<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Home extends Controller
{
    public function index()
    {
        $arquivos = scandir("./assets/images/");
        return view("index");
    }

}
