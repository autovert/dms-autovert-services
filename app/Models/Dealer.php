<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['dealer_name', 'dealer_gstin', 'dealer_domain', 'dealer_city', 'dealer_email', 'dealer_phone', 'dealer_address', 'dealer_bank_details', 'dealer_image', 'dealer_oem_code', 'dealer_metadata'];

    protected $hidden = ['is_active', 'id', 'created_at', 'pivot', 'dealer_domain', 'dealer_bank_details', 'dealer_address', 'dealer_gstin'];

    protected $casts = [
        'dealer_metadata' => 'array',
        'dealer_address' => 'array'
    ];

    public function setDealerDomainAttribute($value)
    {
        $this->attributes['dealer_domain'] = strtoupper($value);
    }

    public function setDealerCityAttribute($value)
    {
        $this->attributes['dealer_city'] = strtoupper($value);
    }

    public function setDealerEmailAttribute($value)
    {
        $this->attributes['dealer_email'] = strtolower($value);
    }

    public function setDealerNameAttribute($value)
    {
        $this->attributes['dealer_name'] = ucwords(strtolower($value));
    }

    public function setDealerGstinAttribute($value)
    {
        $this->attributes['dealer_gstin'] = strtoupper($value);
    }

    public function oems()
    {
        return $this->belongsToMany(Oem::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Upload::class, 'dealer_upload');
    }
}
