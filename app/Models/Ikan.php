<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ikan extends Model
{
    use HasFactory;
    public $table = "tbl_ikan";

    protected $fillable = [
        'kd_ikan', 'jenis_ikan','harga_beli','harga_jual', 'stok'
    ];


public function supplier()
{
    return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
}

}
