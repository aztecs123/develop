<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Шаблоны писем</title>
    <style>
        body { font-family: sans-serif; background: #f9f9f9; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        a.button { padding: 6px 12px; background: #d9534f; color: white; text-decoration: none; border-radius: 4px; }
        .status { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Мои шаблоны</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="status" style="color:green;">✅ <?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="status" style="color:red;">❌ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!empty($templates)): ?>
        <table>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Текст (Markdown)</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($templates as $name => $text): ?>
                    <tr>
                        <td><?= esc($name) ?></td>
                        <td><pre><?= esc($text) ?></pre></td>
                        <td><a class="button" href="/templates/delete/<?= urlencode($name) ?>" onclick="return confirm('Удалить шаблон?');">Удалить</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Нет сохранённых шаблонов.</p>
    <?php endif; ?>
</body>
</html>
