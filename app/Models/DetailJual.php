<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJual extends Model
{
    protected $table = 'tbl_detail_jual';

    protected $fillable = [
        'jual_id', 'jenis_ikan', 'harga_jual', 'jml_ikan', 'total'
    ];

    public function ikan()
    {
        return $this->belongsTo(Ikan::class, 'jenis_ikan');
    }

    public function jual()
    {
        return $this->belongsTo(Jual::class, 'jual_id');
    }
}

