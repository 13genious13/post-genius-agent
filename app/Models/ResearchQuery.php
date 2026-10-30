<?php




namespace App\Models;




use Illuminate\Database\Eloquent\Model;




class ResearchQuery extends Model
{
    protected $fillable = [
        'research_run_id',
        'category',
        'query',
        'purpose',
        'status',
        'sort_order',
        'result_count',
        'latency_ms',
        'error_message',
        'searched_at',
    ];




    protected function casts(): array
    {
        return [
            'searched_at' => 'datetime',
        ];
    }




    public function researchRun()
    {
        return $this->belongsTo(
            ResearchRun::class
        );
    }




    public function sources()
    {
        return $this->hasMany(
            ResearchSource::class
        );
    }
}
