<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatToko extends Model
{
    use HasFactory;
    protected $table = 'alamat_toko';
    protected $fillable = ['cities_id','detail'];


}
