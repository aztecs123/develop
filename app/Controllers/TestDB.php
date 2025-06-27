<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use Config\Database;

class TestDB extends Controller
{
    public function index()
    {
        $db = Database::connect();
        $builder = $db->table('users');
        $query = $builder->get(5); // первые 5 пользователей

        return $this->response->setJSON($query->getResult());
    }
}