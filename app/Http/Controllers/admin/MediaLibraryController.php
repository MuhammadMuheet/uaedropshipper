<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MediaLibrary;
use App\Traits\imageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaLibraryController extends Controller
{
    use imageUploadTrait;
    public function index()
    {
        $media = MediaLibrary::all();
        return view('admin.pages.media.index', compact('media'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'filepond' => 'required|mimes:jpg,jpeg,png,gif,svg,pdf,mp4|max:20480',
        ]);
        $media = new MediaLibrary();
        $imagePath = $request->file('filepond')->store('media', 'public');
        $media->name = $request->filepond->getClientOriginalName();
        $media->mime_type = $request->filepond->getMimeType();
        $media->file_path = $imagePath;
        $media->save();
        return response()->json(['message' => 'File uploaded successfully']);
    }
    public function destroy($id)
    {
        $media = \DB::table('media_libraries')->where('id', $id)->first();
        if ($media) {
            \Storage::delete('public/storage/' . $media->file_path);
            \DB::table('media_libraries')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Media deleted successfully.');
        }
        return redirect()->back()->with('error', 'Media not found.');
    }
}
