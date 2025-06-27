<?php

namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
    protected $table      = 'cities';
    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'name', 'region', 'okrug', 'for_user'];
    public    $returnType     = 'array';
}