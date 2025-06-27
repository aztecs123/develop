<?php

namespace App\Controllers;

use App\Models\CityModel;
use CodeIgniter\Controller;

class CityController extends Controller
{
    public function index()
    {
        $model = new CityModel();

        // Получим только те города, которые отмечены как "показать пользователю"
        $cities = $model->where('for_user', 1)->orderBy('name', 'asc')->findAll();

        return $this->response->setJSON($cities);
    }
}