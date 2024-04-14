<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,
          'website' => $this->domain,
          'commercial_name' => $this->commercial_name,
          'legal_name' => $this->legal_name,
          'all_available_names' => $this->all_available_names,
          'phone_numbers' => $this->phone_numbers,
          'social_media_links' => $this->social_media_links,
          'address' => $this->address === "" ? 'No address found' : $this->address
        ];
    }
}
