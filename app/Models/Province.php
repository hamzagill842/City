<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Province extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $keyType = 'string'; // This specifies that the primary key is a string (UUID).

    public $incrementing = false; // Set this to false to indicate that the primary key is not auto-incrementing.


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Accessor method to get the full image path.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getImageAttribute($value)
    {
        if ($value) {
            // If the image path is not null, prepend the base URL to generate the full URL.
            // You can customize the base URL according to your application's configuration.
            $baseUrl = config('app.url');
            return $baseUrl . '/uploads/' . $value;
        }

        return null;
    }



}
