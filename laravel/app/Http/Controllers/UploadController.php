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

    public function store(Request $request)
    {
        $request->validate([
        'image' => 'required|image|max:2048' // Validation rules for upload
        ]);

        $image = $request->file('image');
        $fileName = uniqid() . '.' . $image->getClientOriginalExtension(); 

        $path = $image->storeAs('uploads', $fileName); // Store the original image

        // (Optional) Using Intervention Image
        $thumbnailPath = 'thumbnails/' . $fileName;
        $intervention = Image::make($image->getRealPath());
        $intervention->fit(200, 200, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/' . $thumbnailPath));

        // (Alternative) Using pure Imagick
        $imagick = new Imagick(storage_path('app/uploads/' . $fileName));
        $imagick->resizeImage(200, 200, Imagick::FILTER_TRIANGLE, 1);
        $imagick->writeImage(storage_path('app/thumbnails/' . $fileName));

        // Update your Image model to store original and thumbnail paths (if ap
        return redirect()->route('gallery.index')->with('success', 'Image uploaded successfully!');
    }
}
