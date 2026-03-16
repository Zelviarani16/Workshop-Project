<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model {
    public $timestamps = false;
    protected $table = 'reg_regencies';
    protected $fillable = ['id', 'province_id', 'name'];
}