<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'short_name', 'logo_url', 'client_bearer_token', 'properties', 'poc_email', 'poc_phone', 'credentials_last_updated_at','oem_metadata'];

    protected $hidden = ['pin', 'id', 'client_id', 'client_secret', 'client_bearer_token', 'created_at', 'properties', 'credentials_last_updated_at', 'poc_phone', 'poc_email'];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->generateKeys();
        });
    }

    private function generateKeys()
    {
        $this->attributes['client_id'] = bin2hex(random_bytes(18));
        $this->attributes['client_secret'] =  bin2hex(random_bytes(18));

        $this->attributes['pin'] = (int) random_int(1000, 9999);
    }

    public function setPocEmailAttribute($value)
    {
        $this->attributes['poc_email'] = strtolower($value);
    }

    public function webhook()
    {
        return $this->hasOne(OemWebhook::class);
    }

    public function webhooks()
    {
        return $this->hasMany(OemWebhook::class);
    }

    public function branches()
    {
        return $this->hasMany(OemBranch::class);
    }

    public function branch()
    {
        return $this->hasOne(OemBranch::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function ips()
    {
        return $this->hasMany(OemWhiteListedIp::class);
    }

    public function ip()
    {
        return $this->hasOne(OemWhiteListedIp::class);
    }

    public function dealer()
    {
        return $this->belongsToMany(Dealer::class, 'dealer_oem');
    }

    public function application()
    {
        return $this->hasOne(Application::class);
    }
}
