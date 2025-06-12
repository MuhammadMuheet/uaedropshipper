<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait imageUploadTrait
{

    public function uploadFile($request,$fileName,$folderName)
    {
        $image = $request->file($fileName);
        $name = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();
        $slug = uniqid() . '-' . Str::slug($name);
        $image_name = $slug . '-' . Carbon::now()->toDateString() .'.'.$extension;
        $image->storeAs('public/'.$folderName,$image_name);
        return $image_name;
    }

    public function uploadFilepond($request, $fileName, $folderName)
    {
        $uploadedFileNames = []; // Initialize as an array to store all file names

        if ($request->hasFile($fileName)) {
            $images = $request->file($fileName);

            foreach ($images as $image) {
                $name = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();

                // Generate a unique file name
                $slug = uniqid() . '-' . Str::slug(pathinfo($name, PATHINFO_FILENAME));
                $image_name = $slug . '-' . Carbon::now()->toDateString() . '.' . $extension;

                // Store the image and save the file name in the array
                $image->storeAs('public/' . $folderName, $image_name);

                // Append the file name to the array
                $uploadedFileNames[] = $image_name;
            }

            return $uploadedFileNames; // Return all file names as an array
        }

        return null; // Return null if no files were uploaded
    }


    public function upload_file_array_instance($request,$fileName,$folderName)
    {
        $image = $request[$fileName];
        $name = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();
        $slug = uniqid() . '-' . Str::slug($name);
        $image_name = $slug . '-' . Carbon::now()->toDateString() .'.'.$extension;
        $image->storeAs('public/'.$folderName,$image_name);
        return $image_name;
    }

}
