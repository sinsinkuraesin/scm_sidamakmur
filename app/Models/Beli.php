<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beli extends Model
{
    use HasFactory;

    protected $table = 'tbl_beli';

    protected $fillable = [
        'kd_beli', 'kd_supplier', 'tgl_beli', 'jenis_ikan', 'harga_beli', 'jml_ikan', 'total_harga', 'bukti_pembayaran'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kd_supplier',);
    }

    public function ikan()
    {
        return $this->belongsTo(Ikan::class, 'jenis_ikan');
    }

}
