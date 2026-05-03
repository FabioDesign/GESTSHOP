<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use SoftDeletes;
    
    public $table = 'sub_categories';

    protected $guarded = [];

}
