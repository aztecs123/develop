<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TemplateController extends Controller
{
    protected $templatePath = WRITEPATH . 'templates/user_templates.json';

    public function index()
    {
        $templates = $this->loadTemplates();
        return view('templates/index', ['templates' => $templates]);
    }

    public function delete($name)
    {
        $templates = $this->loadTemplates();
        if (isset($templates[$name])) {
            unset($templates[$name]);
            file_put_contents($this->templatePath, json_encode($templates, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return redirect()->to('/templates')->with('success', 'Шаблон удалён.');
        }
        return redirect()->to('/templates')->with('error', 'Шаблон не найден.');
    }

    private function loadTemplates(): array
    {
        if (!is_file($this->templatePath)) {
            return [];
        }
        return json_decode(file_get_contents($this->templatePath), true) ?? [];
    }
}
