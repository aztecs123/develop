<h2>Предпросмотр письма</h2>

<?= session()->getFlashdata('error') ?>

<p><strong>Email получателя:</strong> <?= esc($email) ?></p>
<p><strong>Город:</strong> <?= esc($city) ?></p>
<p><strong>Категория:</strong> <?= esc($category) ?></p>

<hr>

<!-- Редактируемое поле -->
<form method="post" action="/invite" enctype="multipart/form-data">
    <input type="hidden" name="email" value="<?= esc($email) ?>">
    <input type="hidden" name="city" value="<?= esc($city) ?>">
    <input type="hidden" name="category" value="<?= esc($category) ?>">

    <label>Текст письма:</label><br>
    <textarea name="message" rows="10" cols="80"><?= esc($message) ?></textarea><br><br>

    <!-- Загрузка Word -->
    <label>Импортировать текст из Word (.docx):</label>
    <input type="file" name="docx_file" accept=".docx">
    <button type="submit" formaction="/invite/import-docx">Импортировать</button>
    <br><br>

    <button type="submit">Отправить письмо</button>
</form>

<p><a href="/invite">Назад к редактированию</a></p>