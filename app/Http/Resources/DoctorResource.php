<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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

            'id'                    =>$this->id,
            'name'                  => $this->name,
            'specialist'            => $this->specialist,
            'desciption'            => $this->desciption,
            'center_id'             => $this->center_id,

        ];
    }
}
