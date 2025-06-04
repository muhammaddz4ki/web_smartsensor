<?php

namespace App\Controllers;

class LandingPage extends BaseController
{
    public function index()
    {
        return view('landing_page'); // ini akan memanggil file View di app/Views/landing_page.php
    }
}
