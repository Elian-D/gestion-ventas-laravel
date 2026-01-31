<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandleStorage
{
    /**
     * Gestiona la subida de archivos y limpieza de archivos antiguos.
     */
    public function handleUpload($file, $path, $oldFile = null): ?string
    {
        if (!$file) return $oldFile;

        // 1. Eliminar archivo anterior si existe
        if ($oldFile && Storage::disk('public')->exists($oldFile)) {
            Storage::disk('public')->delete($oldFile);
        }

        // 2. Guardar el nuevo y retornar la ruta
        return $file->store($path, 'public');
    }

    /**
     * Elimina un archivo físicamente.
     */
    public function deleteFile($path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}