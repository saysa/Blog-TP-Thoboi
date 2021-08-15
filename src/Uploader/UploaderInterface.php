<?php

declare(strict_types=1);

namespace App\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface UploaderInterface.
 */
interface UploaderInterface
{
    public function upload(UploadedFile $file): string;
}
