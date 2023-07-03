<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    public $table = 'states';


    protected $guarded;


    /* -------------------------------------------------------------------------- */
    /*                                Relationship                                */
    /* -------------------------------------------------------------------------- */

    /**
     * zones
     *
     * @return HasMany
     */
    public function zones(): HasMany
    {
        return $this->hasMany(Zone::class, 'state_id');
    }
}
