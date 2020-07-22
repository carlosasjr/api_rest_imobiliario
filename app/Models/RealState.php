<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    protected $table = 'real_state';
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
}
