<?php

use Aws\Exception\AwsException;

class FileUploader
{
    private $allowedExtensions = ['pdf'];

    /**
     * @param array $file
     * @param string $fileName
     * @param object $s3
     * @param string $bucket
     * @return string
     * @throws Exception
     */
    public function uploadFile(array $file, string $fileName, object $s3, string $bucket): string
    {
        $this->validate($fileName);

        $pdfFile = $file['tmp_name'];

        try {
            $result = $s3->putObject([
                'Bucket' => $bucket,
                'Key' => $fileName,
                'Body' => fopen($pdfFile, 'rb'),
                'ContentType' => 'application/pdf',
            ]);

            //добавлен специально подписанный URL, чтобы можно было перейти по ссылке и открыть файл (в бесплатной версии wasabi публичный доступ запрещен)
            $command = $s3->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => $fileName
            ]);
            $request = $s3->createPresignedRequest($command, '+1 hour');
            $presignedUrl = (string)$request->getUri();

//            return $result['ObjectURL'];
            return $presignedUrl;

        } catch (AwsException $e) {
            throw new Exception('Ошибка при загрузке файла на Wasabi: ' . $e->getMessage());
        }
    }

    /**
     * @param string $fileName
     * @return void
     * @throws Exception
     */
    public function validate(string $fileName): void
    {
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!in_array($fileExtension, $this->allowedExtensions)) {
            throw new Exception('Недопустимое расширение файла. Разрешены только PDF файлы.');
        }
    }
}

?>