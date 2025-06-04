<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
       
        // Cek apakah user sudah login
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
        }
       
        $data = [
            'totalDevices' => 3,
            'totalApplications' => 3,
            'activeDevices' => 3,
            'devices' => [
                (object)[ 'name' => 'Device A', 'status' => 'online', 'last_active' => '2025-05-21 10:00:00'],
                (object)[ 'name' => 'Device B', 'status' => 'offline', 'last_active' => '2025-05-20 18:00:00'],
            ]
        ];

        return view('dashboard/index', $data);
    }
}