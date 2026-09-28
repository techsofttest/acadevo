<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{

    protected $guarded = [];

    protected $casts = [
        'additional_images' => 'array',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('selling_price', 'asc');
    }
   

    public function getFirstAdditionalImageAttribute()
    {
        // Ensure the JSON column is an array
        $images = $this->additional_images ?? [];

        // Return the first image or null if empty
        return count($images) > 0 ? $images[0] : null;
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->ofMany([
            'is_default' => 'max',
            'id' => 'min',
        ]);
    }

    public function getOfferPriceAttribute()
    {
        if ($this->relationLoaded('defaultVariant') && $this->defaultVariant) {
            return $this->defaultVariant->selling_price;
        }
        $default = $this->variants()->where('is_default', true)->first() ?? $this->variants()->first();
        return $default ? $default->selling_price : 0;
    }

    public function getOriginalPriceAttribute()
    {
        if ($this->relationLoaded('defaultVariant') && $this->defaultVariant) {
            return $this->defaultVariant->strike_price;
        }
        $default = $this->variants()->where('is_default', true)->first() ?? $this->variants()->first();
        return $default ? $default->strike_price : 0;
    }

    /* =====================
     | VARIANT HELPERS
     ===================== */

    
    public function wishlists()
    {
        return $this->hasMany(\App\Models\Wishlist::class);
    }

    public function isWishlistedByCustomer()
    {
        if (!auth('customer')->check()) {
            return false;
        }

        return $this->wishlists()
            ->where('customer_id', auth('customer')->id())
            ->exists();
    }



/* ======================
 | REVIEWS
 ====================== */
 

public function reviews()
{
    return $this->hasMany(ProductReview::class);
}

public function approvedReviews()
{
    return $this->reviews()->where('is_approved', true);
}

public function getAverageRatingAttribute()
{
    return round($this->approvedReviews()->avg('rating'), 1);
}

public function getReviewsCountAttribute()
{
    return $this->approvedReviews()->count();
}




}
