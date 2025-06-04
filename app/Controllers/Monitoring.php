<?php

namespace App\Controllers;

class Monitoring extends BaseController
{
    public function ultrasonik()
{
    $deviceModel = new \App\Models\DeviceModel();

    $devices = $deviceModel->findAll();

    // Contoh data sensor ultrasonik dari LoRa
    $sensorLabels = ['08:00', '09:00', '10:00', '11:00', '12:00'];
    $sensorData = [120, 118, 121, 117, 119]; // Misalnya dalam cm

    return view('dashboard/ultrasonik', [
        'devices' => $devices,
        'sensorLabels' => $sensorLabels,
        'sensorData' => $sensorData,
    ]);
}


    public function dht11()
    {
        $deviceModel = new \App\Models\DeviceModel();
        $devices = $deviceModel->findAll();

        return view('dashboard/dht11', [
            'devices' => $devices,
            // Tambahkan data lain jika perlu
        ]);
    }

    public function ldr()
    {
        $deviceModel = new \App\Models\DeviceModel();
        $devices = $deviceModel->findAll();

        return view('dashboard/ldr', [
            'devices' => $devices,
            // Tambahkan data lain jika perlu
        ]);
    }
    public function allsensor()
    {
        $deviceModel = new \App\Models\DeviceModel();
        $devices = $deviceModel->findAll();

        return view('dashboard/allsensor', [
            'devices' => $devices,
            // Tambahkan data lain jika perlu
        ]);
    }
}
