<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AppController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('teams.show', ['team' => $request->user()->team]);
    }
}
