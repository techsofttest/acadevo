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
   

    public function getFirstAdditionalImageAttribute()
    {
        // Ensure the JSON column is an array
        $images = $this->additional_images ?? [];

        // Return the first image or null if empty
        return count($images) > 0 ? $images[0] : null;
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
