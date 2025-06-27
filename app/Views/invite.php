<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Рассылка приглашений</title>
    <style>
        body { font-family: sans-serif; background: #f7f7f7; padding: 20px; }
        .container { background: #ffffff; padding: 20px; border-radius: 8px; max-width: 800px; margin: auto; }
        .preview-box { background: #f1f1f1; padding: 15px; border-radius: 6px; margin-top: 20px; }
        label { display: block; margin-top: 15px; }
        select, textarea, input[type="email"], input[type="text"] { width: 100%; padding: 8px; }
        button { margin-top: 20px; padding: 10px 20px; }
    </style>
</head>
<body>
<div class="container">
    <?php if (session()->getFlashdata('success')): ?>
        <div style="color:green">✅ <?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div style="color:red">❌ <?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="/invite" enctype="multipart/form-data" id="inviteForm">
        <label>Email:</label>
        <input type="email" name="email" value="<?= esc($email) ?>" required>

        <label>Город:</label>
        <select name="city" required>
            <?php foreach ($cities as $c): ?>
                <option <?= $city == $c['name'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Категория:</label>
        <select name="category" required>
            <?php foreach ($categories as $cat): ?>
                <option <?= $category == $cat['name'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <?php if (!empty($templates)): ?>
        <label>Мои шаблоны:</label>
        <select id="savedTemplateSelect">
            <option value="">-- Выберите шаблон --</option>
            <?php foreach ($templates as $name => $text): ?>
                <option value="<?= htmlspecialchars($text) ?>"><?= esc($name) ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>

        <label>Текст письма (Markdown):</label>
        <textarea name="message" id="messageArea" rows="10"><?= $message ?></textarea>

        <label>Название для сохранения шаблона:</label>
        <input type="text" name="template_name" placeholder="например: Вебинар июль">

        <label>Импорт из Word (.docx):</label>
        <input type="file" name="docx_file" accept=".docx">

        <button type="submit" name="submit" value="import">Загрузить из Word</button>
        <button type="submit" name="submit" value="send">Отправить письмо</button>
        <button type="submit" name="submit" value="save_template">Сохранить шаблон</button>
    </form>

    <div class="preview-box">
        <strong>📬 Предпросмотр письма:</strong>
        <div id="preview"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    const textarea = document.getElementById('messageArea');
    const preview = document.getElementById('preview');
    const savedTemplateSelect = document.getElementById('savedTemplateSelect');

    function updatePreview() {
        const text = textarea.value;
        const html = marked.parse(text);
        preview.innerHTML = html;
    }

    textarea.addEventListener('input', updatePreview);
    document.addEventListener('DOMContentLoaded', updatePreview);

    if (savedTemplateSelect) {
        savedTemplateSelect.addEventListener('change', () => {
            const value = savedTemplateSelect.value;
            if (value) {
                textarea.value = value;
                updatePreview();
            }
        });
    }
</script>
</body>
</html>
