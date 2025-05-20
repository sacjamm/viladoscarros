<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller {

    public function uploadImage(Request $request) { 
    $request->validate([
        'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $path = $file->store('uploads', 'public'); // Armazena na pasta 'uploads' no disco 'public'

        // Retorna a URL acessível
        return response()->json(['link' => asset('storage/' . $path)]);
    }

    return response()->json(['error' => 'Nenhum arquivo foi enviado'], 400);
}

    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->file('file')->isValid()) {
            $path = $request->file('file')->store('images', 'public/');
            return response()->json(['link' => Storage::url($path)]);
        }

        return response()->json(['error' => 'Invalid image upload'], 400);
    }
}
