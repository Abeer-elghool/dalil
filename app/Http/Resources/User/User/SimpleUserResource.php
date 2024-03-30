<?php

namespace App\Http\Resources\User\User;

use App\Http\Resources\User\Area\AreaResource;
use App\Http\Resources\User\City\CityResource;
use App\Http\Resources\User\Country\CountryResource;
use App\Http\Resources\User\Education\EducationResource;
use App\Http\Resources\User\Specialty\SpecialtyResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleUserResource extends JsonResource
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
            'email'     => $this->email,
            'phone'     => $this->phone,
            'dailing_code'=> $this->dailing_code,
            'gender'    => $this->gender,
            'image'     => $this->image
        ];
    }
}
