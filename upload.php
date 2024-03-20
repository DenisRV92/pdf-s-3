<?php
session_start();

require 'vendor/autoload.php';
require 'FileUploader.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Конфигурация AWS SDK для работы с Wasabi
$bucket = 'pdf123';
$region = 'us-east-1'; // регион Wasabi


$key = 'ZO2YV7Q175UAM0HFV8BX';
$secret = 'FgulJhHLJPb1zb50pIuWZR4sFRzjJniQOuBA04tf';

$s3 = new S3Client([
//    'profile' => 'wasabi',
    'endpoint' => 'https://s3.wasabisys.com',
    'version' => 'latest',
    'use_path_style_endpoint' => true,
    'region' => $region,
    'credentials' => [
        'key' => $key,
        'secret' => $secret
    ]
]);

$fileUploader = new FileUploader();

if ($_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
    $pdfFileName = $_FILES['pdfFile']['name'];

    try {
        unset($_SESSION['uploadedUrl']);
        $uploadedUrl = $fileUploader->uploadFile($_FILES['pdfFile'], $pdfFileName, $s3, $bucket);
        $_SESSION['uploadedUrl'] = $uploadedUrl;
        header('Location: index.php');
    } catch (Exception $e) {
        echo "Ошибка при загрузке файла: " . $e->getMessage();
    }
} else {
    echo "Произошла ошибка при загрузке файла.";
}

