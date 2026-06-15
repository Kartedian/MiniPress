<?php

namespace Dwm\MiniPress\infrastructure;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model{
    protected $table = 'categorie';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_auteur', 'id');
    }
}