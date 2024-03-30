<?php

namespace App\Traits\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;
trait UploadFiles{
    public function uploadImage($files,$url='temporary', $disk='public', $width=null, $height=null)
    {
        $dist = storage_path('app/public/'.$url);
        if ($url != 'images' && !\File::isDirectory(storage_path('app/public/files/'.$url ."/"))){
            \File::makeDirectory(storage_path('app/public'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR), 0777, true);
            $dist = storage_path('app/public'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR);
        }elseif (\File::isDirectory(storage_path('app/public/files/'.$url."/"))) {
            $dist = storage_path('app/public/files/'.$url ."/");
        }
        $file = '';

        if (gettype($files) == 'array') {
          $file = [];
          foreach ($files as $new_file) {
              $file_name = substr(str_replace(' ','_',$file->getClientOriginalName()),1,5).'dalel_'.time().'dalel_@2023.'.$file->extension();
              if ($new_file->move($dist, $file_name)) {
              $file[][$key] = $file_name;
            }
          }
        } else {
          $file = $files;
          $file_name = str_replace(' ','_',$file->getClientOriginalName()).'dalel_'.time().'dalel_@2023.'.$file->extension();
            if ($file->move($dist,$file_name)) {
              $file =  $file_name;
            }
        }
        return $file_name;
    }

    public function uploadFile($files,$url='temporary', $disk='public', $width=null, $height=null)
    {
        $dist = storage_path('app/public/'.$url);
        if ($url != 'images' && !\File::isDirectory(storage_path('app/public/files/'.$url ."/"))){
            \File::makeDirectory(storage_path('app/public'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR), 0777, true);
            $dist = storage_path('app/public'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$url.DIRECTORY_SEPARATOR);
        }elseif (\File::isDirectory(storage_path('app/public/files/'.$url."/"))) {
            $dist = storage_path('app/public/files/'.$url ."/");
        }
        $file = '';

        if (gettype($files) == 'array') {
          $file = [];
          foreach ($files as $new_file) {
              $file_name = substr(str_replace(' ','_',$file->getClientOriginalName()),1,5).'dalel_'.time().'dalel_@2023.'.$file->extension();
              if ($new_file->move($dist, $file_name)) {
              $file[][$key] = $file_name;
            }
          }
        } else {
          $file = $files;
          $file_name = substr(str_replace(' ','_',$file->getClientOriginalName()),1,5).'dalel_'.time().'dalel_@2023.'.$file->extension();

          if ($file->move($dist,$file_name)) {
            $file =  $file_name;
          }
        }
        return $file_name;
    }

}
