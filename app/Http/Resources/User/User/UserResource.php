<?php

namespace App\Http\Resources\User\User;

use App\Http\Resources\User\Area\AreaResource;
use App\Http\Resources\User\City\CityResource;
use App\Http\Resources\User\Country\CountryResource;
use App\Http\Resources\User\Education\EducationResource;
use App\Http\Resources\User\Specialty\SpecialtyResource;
use Illuminate\Http\Request;
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
            'email'     => $this->email,
            'phone'     => $this->phone,
            'dailing_code'=> $this->dailing_code,
            'gender'    => $this->gender,
            'image'     => $this->image,
            'points'    => (float)$this->points,
            'token'     => $this->token,
            'country'   => CountryResource::make($this->country),
            'city'      => CityResource::make($this->city),
            'area'      => AreaResource::make($this->area),
            'education' => EducationResource::make($this->education),
            'specialty' => SpecialtyResource::make($this->specialty),
        ];
    }
}
