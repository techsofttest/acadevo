<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    
    protected $guarded = [];

    protected $appends = ['variant_label'];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function variant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getVariantLabelAttribute() {
        if (!empty($this->variant_name)) {
            return $this->variant_name;
        }
        if ($this->relationLoaded('variant') && $this->variant) {
            return trim($this->variant->value . ' ' . $this->variant->unit);
        }
        if (preg_match('/\(([^)]+)\)$/', $this->title, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
