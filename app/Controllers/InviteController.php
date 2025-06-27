<?php

namespace App\Controllers;

use App\Models\EventCategoryModel;
use App\Models\CityModel;
use App\Models\DoctorModel;
use CodeIgniter\Controller;
use Config\Services;
use PhpOffice\PhpWord\IOFactory;
use League\CommonMark\CommonMarkConverter;

class InviteController extends Controller
{
    protected $templatePath = WRITEPATH . 'templates/user_templates.json';

    public function form()
    {
        $categories = model(EventCategoryModel::class)->orderBy('name')->findAll();
        $cities = model(CityModel::class)->orderBy('name')->findAll();
        $templates = $this->loadTemplates();

        $cityId = $this->request->getGet('city');
        $spec = $this->request->getGet('category');

        $doctorModel = new DoctorModel();

        if ($cityId) {
            $doctorModel->where('city', $cityId);
        }

        if ($spec) {
            $doctorModel->groupStart()
                ->orWhere('spec_1', $spec)
                ->orWhere('spec_2', $spec)
                ->orWhere('spec_3', $spec)
                ->orWhere('spec_4', $spec)
                ->groupEnd();
        }

        $doctors = $doctorModel->findAll();
        $emails = array_column($doctors, 'username');

        return view('invite', [
            'categories' => $categories,
            'cities' => $cities,
            'templates' => $templates,
            'email' => implode(", ", $emails),
            'city' => $cityId,
            'category' => $spec,
            'message' => '',
            'doctors' => $doctors
        ]);
    }

    public function send()
    {
        $email = $this->request->getPost('email');
        $city = $this->request->getPost('city');
        $category = $this->request->getPost('category');
        $message = $this->request->getPost('message');
        $templates = $this->loadTemplates();

        if ($this->request->getPost('submit') === 'save_template') {
            $templateName = trim($this->request->getPost('template_name'));
            if ($templateName && $message) {
                $templates[$templateName] = $message;
                file_put_contents($this->templatePath, json_encode($templates, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                return redirect()->back()->with('success', 'Шаблон сохранён!');
            }
            return redirect()->back()->with('error', 'Введите название шаблона и текст.');
        }

        $file = $this->request->getFile('docx_file');
        if ($file && $file->isValid() && $file->getClientExtension() === 'docx') {
            try {
                $tempPath = $file->getTempName();
                $phpWord = IOFactory::load($tempPath);

                $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
                ob_start();
                $htmlWriter->save('php://output');
                $html = ob_get_clean();

                $message = strip_tags($html);
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Ошибка чтения .docx файла: ' . $e->getMessage());
            }
        }

        if ($this->request->getPost('submit') === 'send') {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !str_contains($email, ',')) {
                return redirect()->back()->with('error', 'Неверный email');
            }

            $converter = new CommonMarkConverter();
            $parsedHtml = $converter->convert($message);

            $htmlTemplate = "
            <!DOCTYPE html>
            <html>
            <head>
              <meta charset='UTF-8'>
              <style>
                body { font-family: sans-serif; background: #f7f7f7; padding: 20px; }
                .container { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
                .footer { margin-top: 20px; font-size: 12px; color: #999; }
              </style>
            </head>
            <body>
              <div class='container'>
                $parsedHtml
                <div class='footer'>
                  Это письмо сформировано автоматически. Не отвечайте на него.
                </div>
              </div>
            </body>
            </html>
            ";

            $emailService = Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('Приглашение на мероприятие');
            $emailService->setMailType('html');
            $emailService->setMessage($htmlTemplate);

            if ($emailService->send()) {
                return redirect()->to('/invite')->with('success', 'Письмо отправлено!');
            }

            return redirect()->back()->with('error', 'Ошибка при отправке.');
        }

        $categories = model(EventCategoryModel::class)->orderBy('name')->findAll();
        $cities = model(CityModel::class)->orderBy('name')->findAll();

        return view('invite', [
            'categories' => $categories,
            'cities' => $cities,
            'email' => $email,
            'city' => $city,
            'category' => $category,
            'message' => $message,
            'templates' => $templates
        ]);
    }

    private function loadTemplates(): array
    {
        if (!is_file($this->templatePath)) {
            return [];
        }
        return json_decode(file_get_contents($this->templatePath), true) ?? [];
    }
}
