<?php

namespace App\Traits;
trait uploadFiles {
    public function UploadSingleFile($file ,$Directory)
    {
        $newName=$file->HashName();
        $file->storeAs('public/'.$Directory,$newName);
        return $newName;
    }
}
