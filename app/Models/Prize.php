<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Prize extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'name',
        'description',
        'segment',
        'weight',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public static function search($query)
    {
        return empty($query) ? static::query(): static::where('name', 'like', '%'.$query.'%');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public static function selectPrizeFromSegment(string $segment, int $campaignId): Prize
    {
        if (App::environment() !== "testing") {
            $orderByRaw = "-LOG(1.0 - RAND()) / weight";
        } else {
            $orderByRaw = "-LOG(1.0 - RANDOM()) / weight";
        }

        return  Prize::select('*')
            ->where('segment', $segment)
            ->where('campaign_id', $campaignId)
            ->orderByRaw($orderByRaw)
            ->first();
    }

    public function move()
    {
        return $this->belongsTo(Moves::class);
    }

    public function getImage()
    {
        if (in_array($this->image_path, [1,2,3,4,5,6,7])) {
            return asset('storage/' . $this->image_path . ".png");
        }

        return asset('storage/' . $this->image_path);
    }
}
