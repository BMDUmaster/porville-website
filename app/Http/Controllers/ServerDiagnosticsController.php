<?php

namespace App\Http\Controllers;

use App\Support\ServerDiagnostics;
use Illuminate\Http\Response;

class ServerDiagnosticsController extends Controller
{
    public function __invoke(): Response
    {
        $checks = ServerDiagnostics::run();

        $html = view('server-check', compact('checks'))->render();

        return response($html, 200)->header('Content-Type', 'text/html; charset=utf-8');
    }
}
