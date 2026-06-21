<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class DesignConfig extends Model
{
    protected $fillable = ['colors'];

    protected function casts(): array
    {
        return [
            'colors' => 'array',
        ];
    }
}
