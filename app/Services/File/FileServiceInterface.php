<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\LazyCollection;

interface FileServiceInterface
{
    public function getRows(UploadedFile $file): LazyCollection;
}
