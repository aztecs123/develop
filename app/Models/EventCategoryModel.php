<?php

namespace App\Models;

use CodeIgniter\Model;

class EventCategoryModel extends Model
{
    protected $table      = 'events_categories';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'profession_id', 'code_nmo', 'name_nmo', 'title_image_filename'];
    public    $returnType     = 'array';
}