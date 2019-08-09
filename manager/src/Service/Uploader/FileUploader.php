<?php

declare(strict_types=1);

namespace App\Service\Uploader;

use Exception;
use League\Flysystem\FileExistsException;
use League\Flysystem\FilesystemInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /** @var FilesystemInterface */
    private $storage;
    /** @var string */
    private $baseUrl;

    public function __construct(FilesystemInterface $storage, string $baseUrl)
    {
        $this->storage = $storage;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param UploadedFile $file
     * @return File
     * @throws FileExistsException
     * @throws Exception
     */
    public function upload(UploadedFile $file): File
    {
        $path = date('Y/m/d');
        $name = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();

        $this->storage->createDir($path);
        $stream = fopen($file->getRealPath(), 'rb+');
        $this->storage->writeStream($path . '/' . $name, $stream);
        fclose($stream);

        return new File($path, $name, $file->getSize());
    }

    public function generateUrl(string $path): string
    {
        return $this->baseUrl . '/' . $path;
    }
}
