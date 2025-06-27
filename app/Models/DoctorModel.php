<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'city', 'job_position',
        'spec_1', 'spec_2', 'spec_3', 'spec_4'
    ];
    protected $returnType = 'array';
}