<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'birthdate' => Carbon::parse($this->birthdate)->format('d.m.Y'),
            'age' => $this->age . ' ' . $this->age_type
        ];
    }
}
