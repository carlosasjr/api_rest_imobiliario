<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    protected $table = 'real_state';

    protected $appends = ['_links', 'thumb'];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'content',
        'price',
        'slug',
        'bedrooms',
        'bathrooms',
        'property_area',
        'total_property_area'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'real_state_categories');
    }

    public function photos()
    {
        return $this->hasMany(RealStatePhoto::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    //Accessors
    public function getLinksAttribute()
    {
        return [
           'href' => route('real-states.show', $this->id),
           'rel'  => 'real-state'
        ];
    }

    public function getThumbAttribute()
    {
        $thumb = $this->photos()->where('is_thumb', true);

        if (!$thumb->count()) return null;

        return $thumb->first()->photo;
    }



}
