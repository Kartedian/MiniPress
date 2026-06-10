<?php

namespace Dwm\MiniPress\infrastructure;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Article extends Model{
    use HasUuids;

    protected $table = 'article';

    public $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
}