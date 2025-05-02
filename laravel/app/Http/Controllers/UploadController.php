<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the incoming file
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Store file locally on the 'public' disk (storage/app/public/uploads)
        $path = $request->file('document')->store('uploads', 'public');

        // Generate public URL for the stored file
        $publicUrl = Storage::disk('public')->url($path);

        // Store file in MinIO
        $minioPath = $request->file('document')->store('uploads', 'minio');

        // Build full MinIO access URL
        $minioUrl = env('MINIO_ENDPOINT') . '/' . env('MINIO_BUCKET') . '/' . $minioPath;

        // Return JSON response
        return response()->json([
            'path' => $path,
            'public_url' => $publicUrl,
            'minio_url' => $minioUrl,
        ], 200);
    }
}
