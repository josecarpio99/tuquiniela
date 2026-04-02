<?php

namespace App\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Team extends Model implements HasMedia
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'short_name'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(QuinielaMatch::class, 'team_1_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(QuinielaMatch::class, 'team_2_id');
    }
}
