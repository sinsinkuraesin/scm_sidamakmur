<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    use HasFactory;
    protected $table = 'tbl_konsumen';
    protected $fillable = [
        'nama_konsumen',
        'nama_pasar',
        'alamat',
        'jam_buka',
        'jam_tutup',
    ];

     public function pasar()
    {
        return $this->belongsTo(Pasar::class, 'nama_pasar');
}
}
