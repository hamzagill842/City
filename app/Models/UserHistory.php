<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserHistory extends Model
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

    public function city()
    {
        return $this->belongsTo(Province::class,'province_id','id');
    }
}
