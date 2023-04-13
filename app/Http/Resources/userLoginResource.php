<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userLoginResource extends JsonResource
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
            'id'                =>(int)$this->id,
            'firstname'         => $this->firstname,
            'lastname'         => $this->lastname,
            'phone'             => $this->phone,
            'address'           => $this->address,
            'email'             => $this->email,
            'password'          => $this->password,
            'token'             => $this->createToken('user')->plainTextToken,
        ];
    }
}