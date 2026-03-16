<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model {
    public $timestamps = false;
    protected $table = 'reg_districts';
    protected $fillable = ['id', 'regency_id', 'name'];
}
