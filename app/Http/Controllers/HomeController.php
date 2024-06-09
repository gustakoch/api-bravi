<?php

namespace App\Http\Controllers;

use App\Http\Response;

class HomeController extends Controller
{
    public function index()
    {
        $response = Response::array(false, 'Api Running ...');

        return response()->json($response);
    }
}
