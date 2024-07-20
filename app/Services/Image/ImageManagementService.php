<?php

namespace App\Services\Image;

use Exception;
use App\Services\Auth\LoginService;
use Illuminate\Support\Facades\Storage;

class ImageManagementService extends LoginService
{
  // Static function to delete image
  public static function deleteImage($filename)
  {

    $path = 'public' . $filename;
    $path =  str_replace("/storage", "/public", $filename);

    if (!Storage::exists($path)) {
      return [
        'status' => false,
        'message' => 'Image not found.',
      ];
    }

    // Delete the file
    Storage::delete($path);
    return [
      'status' => true,
      'message' => 'Image deleted successfully.',
    ];
  }

  public static function processImageField($request, $record, string $path = "profile", string $valueName = 'image'): ?string
  {
    $media = $request->input($valueName);
    // check if image field is set.
    if (!empty($media)) {
      // check if image is valid base 64 encoding.
      if (is_base64_string($media)) {

        $filename = $record->id ?? random();
        // the folder path for the image
        $folderPath = "public/images/{$path}/";
        // store the image in local
        $update = save_base64_file($media, Storage::path("{$folderPath}/{$filename}"));

        $thumbnail = "tbn";

        $file_content = "{$folderPath}{$thumbnail}/{$filename}" . "." . get_mime_type($media);

        // image path
        $publicUrl = Storage::url($file_content);

        if (!empty($update)) {
          // delete the previous image if it exist
          if (!empty($record->{$valueName})) {
            self::deleteImage($record->{$valueName});
          }
          // return the image
          return $publicUrl;
        }
        return $publicUrl;
        // check in if image is valid url
      } else if (is_valid_url($media)) return $media;
    } else return "";
    return '';
  }
}
