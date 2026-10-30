<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingGoal extends Model
{
    protected $fillable = [
        'business_id',
        'created_by',
        'name',
        'goal_type',
        'description',
        'status',
        'primary_kpi',
        'target_value',
        'current_value',
        'unit',
        'secondary_kpis',
        'target_audience_ids',
        'target_locations',
        'platforms',
        'budget',
        'currency',
        'start_date',
        'end_date',
        'priority',
        'constraints',
        'success_definition',
    ];




    protected function casts(): array
    {
        return [
            'secondary_kpis' => 'array',
            'target_audience_ids' => 'array',
            'target_locations' => 'array',
            'platforms' => 'array',
            'budget' => 'decimal:2',
            'target_value' => 'decimal:2',
            'current_value' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'priority' => 'integer',
        ];
    }




    public function business()
    {
        return $this->belongsTo(Business::class);
    }




    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }




    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
