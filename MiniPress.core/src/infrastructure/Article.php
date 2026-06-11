<?php

namespace Dwm\MiniPress\infrastructure;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Article extends Model{
    use HasUuids;

    protected $table = 'Article';

    public $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'id_auteur', 'id');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie', 'id');
    }
}