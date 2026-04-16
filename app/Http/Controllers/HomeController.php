<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function fast(): Response
    {
        return response('OK', 200);
    }

    public function slow(): Response
    {
        sleep(2);

        return response('OK', 200);
    }
}
