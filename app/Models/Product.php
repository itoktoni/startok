<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable, Sortable;

    protected $fillable = ['name', 'price', 'description'];
}
