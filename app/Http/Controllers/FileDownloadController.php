<?php

namespace App\Http\Controllers;

use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    public function download($resource, $id)
    {
        // Dynamically resolve the model and file path
        $model = "App\\Models\\" . ucfirst($resource);
        $record = $model::findOrFail($id);

        // Get all the file paths associated with the 'images' field (or change to your relevant field)
        $files = $record->images; // Assuming it's a collection or array of file paths

        // Initialize a ZIP archive
        $zip = new ZipArchive;
        $zipFileName = storage_path("app/public/{$resource}_{$id}.zip");

        // Open the archive to write
        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            abort(500, 'Failed to create ZIP file.');
        }

        // Add each file to the ZIP archive
        foreach ($files as $file) {
            $filePath = storage_path("app/public/{$file}");

            // Check if file exists before adding it
            if (Storage::disk('public')->exists($file)) {
                $zip->addFile($filePath, basename($file)); // Add the file with its original name
            }
        }

        // Close the ZIP archive
        $zip->close();

        // Check if the ZIP file was created successfully
        if (!file_exists($zipFileName)) {
            abort(500, 'Error creating ZIP file.');
        }

        // Return the ZIP file for download
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
