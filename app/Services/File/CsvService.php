<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;

class CsvService implements FileServiceInterface
{
    public function getRows(UploadedFile $file): LazyCollection
    {
        $filename = sprintf('uploaded_websites_%s.csv', time());
        $path = $file->storeAs('uploads', $filename);

        $reader = SimpleExcelReader::create(Storage::path($path));

        return $reader->getRows();
    }
}
