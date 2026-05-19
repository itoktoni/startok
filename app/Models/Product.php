<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use App\Concerns\DefaultEntity;
use App\Concerns\OptionTrait;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable, Sortable, DefaultEntity, OptionTrait;

    protected $table   = 'products';
    protected $primaryKey = 'id';

    public $timestamps = true;
    public $incrementing = true;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

    protected $fillable = ['name', 'price', 'description'];

    public function toArray()
    {
        $data = parent::toArray();

        if (auth()->check()) {
            unset($data['price']);
        }

        return $data;
    }
}
