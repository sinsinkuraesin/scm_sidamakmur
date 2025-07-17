<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jual extends Model
{
    use HasFactory;

    protected $table = 'tbl_jual';
    protected $primaryKey = 'jual_id'; // Tambahkan ini jika kolom PK bukan 'id'

    protected $fillable = [
        'nama_konsumen', 'tgl_jual', 'nama_pasar',
    ];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'nama_konsumen');
    }


    public function detailJual()
    {
        return $this->hasMany(DetailJual::class, 'jual_id', 'jual_id');
    }


}

