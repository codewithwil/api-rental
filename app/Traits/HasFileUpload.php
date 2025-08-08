<?php

namespace App\Traits;

use App\{
    Models\Files\Files
};

use Illuminate\{
    Http\UploadedFile,
    Support\Facades\Storage
};

trait HasFileUpload
{
    public function uploadFile(UploadedFile $file, $model, string $disk = 'public', string $path = 'uploads')
    {
        if ($model->file) {
            Storage::disk($disk)->delete($model->file->path);
            $model->file->delete();
        }

        $storedPath = $file->store($path, $disk);

        return Files::create([
            'path'           => $storedPath,
            'original_name'  => $file->getClientOriginalName(),
            'size'           => $file->getSize(),
            'mime_type'      => $file->getMimeType(),
            'fileable_id'    => $model->getKey(),
            'fileable_type'  => get_class($model),
        ]);
    }
}
