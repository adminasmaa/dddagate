<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shop extends Model
{
    use HasFactory;

    public $table = 'shops';
    protected $appends = ['image_path','name'];


    protected $guarded = [];

    protected $hidden=['updated_at','image_idt_front','image_idt_back'];

    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'longitude' => 'double',
        'latitude' => 'double',
        'status'   => 'boolean'
    ];

    /* -------------------------------------------------------------------------- */
    /*                                Relationship                                */
    /* -------------------------------------------------------------------------- */

    /**
     * zone
     *
     * @return BelongsTo
     */

    public function getNameAttribute()
    {
        return (app()->getLocale() === 'ar') ? $this->name_ar : $this->name_en;
    }

    public function getImagePathAttribute()
    {
        return asset('images/shops/' . $this->image_profile);

    }//end of get image path
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }


    /**
     * tracks
     *
     * @return HasOne
     */
    public function tracks(): HasOne
    {
        return $this->hasOne(Track::class, 'shop_id');
    }

    /**
     * delegate
     *
     * @return BelongsTo
     */
    public function delegate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
