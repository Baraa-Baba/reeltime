<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    use HasFactory;

    protected $primaryKey = 'hero_banner_id';

    protected $fillable = [
        'title',
        'subtitle',
        'cta_label',
        'cta_route_name',
        'background_image',
        'position',
        'is_active',
    ];
}
