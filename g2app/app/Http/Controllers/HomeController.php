<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HomeController
{
    public function home()
    {
        return Inertia::render('Home');
    }
}
