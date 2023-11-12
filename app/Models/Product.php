<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'name',
        'price',
        'description',
        'user_id',
        'status',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (auth()->check() && auth()->user() instanceof User) {
                $builder->where('user_id', auth()->user()->id);
            }
        });
    }
}
