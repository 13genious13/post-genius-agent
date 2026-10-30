<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProductService extends Model
{
    protected $fillable = [
        'business_id',
        'type',
        'name',
        'description',
        'category',
        'price_from',
        'price_to',
        'currency',
        'url',
        'unique_selling_point',
        'benefits',
        'target_audiences',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_from' => 'decimal:2',
            'price_to' => 'decimal:2',
            'benefits' => 'array',
            'target_audiences' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
