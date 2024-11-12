<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'phone'     => $this->phone,
            'sex'       => $this->sex,
            'birthday'  => $this->birthday,
            'height'    => $this->height,
            'weight'    => $this->weight,
            'city'      => $this->city ? new CityResource(City::find($this->city)) : null
        ];
    }
}
