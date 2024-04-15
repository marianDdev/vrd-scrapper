<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * @property array $phone_numbers
 * @property array $social_media_links
 * @property string $address
 */
class Company extends Model
{
    use HasFactory, Searchable;

    protected $casts = [
        'phone_numbers'      => 'array',
        'social_media_links' => 'array',
    ];

    protected $fillable = [
        'domain',
        'commercial_name',
        'legal_name',
        'all_available_names',
        'phone_numbers',
        'social_media_links',
        'address',
    ];

    public function searchableAs(): string
    {
        return 'companies_index';
    }

    public function toSearchableArray(): array
    {
        $array = $this->toArray();
        $array['phone_numbers'] = $this->phone_numbers;
        $array['social_media_links'] = $this->social_media_links;

        return $array;
    }
}
