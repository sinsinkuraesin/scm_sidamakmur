<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'tbl_supplier';
    protected $fillable = [
        'kd_supplier',
        'jenis_ikan',
        'alamat',
    ];

    public function ikan()
    {
        return $this->belongsTo(Ikan::class, 'jenis_ikan');
    }
}
