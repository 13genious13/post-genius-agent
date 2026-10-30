<?php
namespace App\Jobs;
use App\Analytics\MarketingMemoryBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
class RefreshCampaignMemories implements ShouldQueue {
 use Queueable;public int $tries=1;
 public function __construct(public int $businessId,public int $campaignId){$this->onQueue('analytics');}
 public function handle(MarketingMemoryBuilder $builder):void{$builder->rebuild($this->businessId,$this->campaignId);}
}
