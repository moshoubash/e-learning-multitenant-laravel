<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class DesignConfig extends Model
{
    protected $fillable = ['colors', 'logo'];

    protected function casts(): array
    {
        return [
            'colors' => 'array',
        ];
    }
}
