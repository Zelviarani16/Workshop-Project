<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model {
    public $timestamps = false;
    protected $table = 'reg_villages';
    protected $fillable = ['id', 'district_id', 'name'];
}