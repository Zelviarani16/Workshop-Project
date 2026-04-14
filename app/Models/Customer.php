<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'idcustomer';
protected $fillable = [
    'nama', 'foto_blob', 'foto_path',
    'alamat', 'provinsi', 'kota', 
    'kecamatan', 'kelurahan', 'kode_pos'
];}