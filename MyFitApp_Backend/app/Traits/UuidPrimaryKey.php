<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

trait UuidPrimaryKey
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
}
