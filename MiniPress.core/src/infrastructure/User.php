<?php

namespace Dwm\MiniPress\infrastructure;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class User extends Model{
    use HasUuids;

    protected $table = 'user';

    public $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
}