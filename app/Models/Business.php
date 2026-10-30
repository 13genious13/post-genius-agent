<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'industry',
        'business_type',
        'website',
        'description',
        'mission',
        'vision',
        'unique_value_proposition',
        'brand_story',
        'country',
        'state',
        'city',
        'timezone',
        'primary_language',
        'target_locations',
        'keywords',
        'marketing_objectives',
        'brand_voice',
        'brand_traits',
        'words_to_use',
        'words_to_avoid',
        'primary_color',
        'secondary_color',
        'accent_color',
        'logo_url',
        'onboarding_completed',
        'profile_completion',
        'intelligence_generated_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'target_locations' => 'array',
            'keywords' => 'array',
            'marketing_objectives' => 'array',
            'brand_traits' => 'array',
            'words_to_use' => 'array',
            'words_to_avoid' => 'array',
            'onboarding_completed' => 'boolean',
            'profile_completion' => 'integer',
            'intelligence_generated_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function productsServices()
    {
        return $this->hasMany(BusinessProductService::class)
            ->orderBy('sort_order');
    }

    public function audiences()
    {
        return $this->hasMany(BusinessAudience::class);
    }

    public function competitors()
    {
        return $this->hasMany(BusinessCompetitor::class);
    }

    public function channels()
    {
        return $this->hasMany(BusinessChannel::class);
    }

    public function intelligences()
    {
        return $this->hasMany(BusinessIntelligence::class)
            ->latest('version');
    }

    public function latestIntelligence()
    {
        return $this->hasOne(BusinessIntelligence::class)
            ->latestOfMany('version');
    }

    public function calculateProfileCompletion(): int
    {
        $checks = [
            filled($this->name),
            filled($this->industry),
            filled($this->description),
            filled($this->website),
            filled($this->unique_value_proposition),
            filled($this->brand_voice),
            !empty($this->target_locations),
            $this->productsServices()->exists(),
            $this->audiences()->exists(),
            $this->competitors()->exists(),
        ];

        $completed=collect($checks)
            ->filter()
            ->count();

        return (int) round(
            ($completed/count($checks)) * 100
        );
    }

    public function refreshProfileCompletion(): void
    {
        $completion = $this->calculateProfileCompletion();

        $this->forceFill([
            'profile_completion' => $completion,
            'onboarding_completed' => $completion >= 70,
        ])->saveQuietly();
    }

    public function marketingGoals()
    {
        return $this->hasMany(MarketingGoal::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function researchRuns()
    {
        return $this->hasMany(ResearchRun::class);
    }

    public function campaignStrategies()
    {
        return $this->hasMany(
            CampaignStrategy::class
        );
    }

    public function agentRuns()
    {
        return $this->hasMany(
            AgentRun::class
        );
    }

    public function agentControl()
    {
        return $this->hasOne(AgentControl::class);
    }

    public function contentAssets() { return $this->hasMany(\App\Models\ContentAsset::class); }

    public function creativeAssets() { return $this->hasMany(\App\Models\CreativeAsset::class); }

    public function socialAccounts(){return $this->hasMany(\App\Models\SocialAccount::class);}
    public function socialPublications(){return $this->hasMany(\App\Models\SocialPublication::class);}

}
