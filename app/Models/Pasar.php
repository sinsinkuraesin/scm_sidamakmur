<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasar extends Model
{
    use HasFactory;
    public $table = "tbl_pasar";

    protected $fillable = [
        'kd_pasar', 'nama_pasar','alamat','jam_buka', 'jam_tutup'
    ];
}
