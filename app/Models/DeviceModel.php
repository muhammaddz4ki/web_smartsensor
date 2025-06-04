<?php

namespace App\Models;

use CodeIgniter\Model;

class DeviceModel extends Model
{
    protected $table = 'devices';
    protected $primaryKey = 'device_id';
    protected $allowedFields = [
        'device_name', 'user_id', 'application_id', 'created_at', 'update_at'
    ];
}
