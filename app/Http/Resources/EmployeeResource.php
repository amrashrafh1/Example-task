<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'national_id'        => $this->national_id,
            'video_introduction' => Storage::url('employees/videos/'.$this->video_introduction),
            'personal_pics'      => Storage::url('employees/pictures/'.$this->personal_pics),
        ];
    }

}
