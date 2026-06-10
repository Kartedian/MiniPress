<?php

namespace Dwm\MiniPress\infrastructure;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model{
    protected $table = 'categorie';

    protected $primaryKey = 'id';

    public $timestamps = false;
}