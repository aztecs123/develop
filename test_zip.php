<?php
$zip = new ZipArchive();
echo class_exists('ZipArchive') ? 'Класс ZipArchive есть<br>' : 'НЕТ КЛАССА<br>';
echo method_exists($zip, 'open') ? 'Метод open работает' : 'Метод open не найден';