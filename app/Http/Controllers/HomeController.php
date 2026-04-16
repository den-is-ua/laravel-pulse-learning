<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

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

    public function slowRequest(): Response
    {
        sleep(2);

        return response('OK', 200);
    }

    public function slow_outgoing(): Response
    {
        $response = Http::get('http://127.0.0.1/slow-request');

        return response($response->body(), $response->status());
    }
}
