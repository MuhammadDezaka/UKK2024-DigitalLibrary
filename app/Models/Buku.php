<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = "buku";

    protected $guarded =['id'];

    public $timestamps = false;

     /**
     * Get the kategori associated with the buku.
     */
    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'kategori_buku_relasi', 'buku_id', 'kategori_id');
    }
    public function ulasan()
    {
        return $this->hasMany(UlasanBuku::class, 'buku_id');
    }
    public function koleksi()
    {
        return $this->hasMany(KoleksiBuku::class, 'buku_id');
    }
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

 
}
