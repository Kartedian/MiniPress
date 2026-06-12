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

    protected $fillable = [
        'titre', 
        'resumer', 
        'contenue', 
        'categorie', 
        'url_image', 
        'id_auteur',
        'published'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_auteur', 'id');
    }

    public function categorieRelation()
    {
        return $this->belongsTo(Categorie::class, 'categorie', 'id');
    }
}