<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Загрузка PDF файла на Wasabi</title>
</head>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="pdfFile">Выберите PDF файл для загрузки:</label><br>
    <input type="file" name="pdfFile" id="pdfFile" accept=".pdf"><br>
    <input type="submit" value="Загрузить">
</form>
<?php if (isset($_SESSION['uploadedUrl'])) {
    echo 'Ссылка на загруженный файл: <a href="' . $_SESSION['uploadedUrl'] . '">' . $_SESSION['uploadedUrl'] . '</a>';
} ?>
</body>
</html>