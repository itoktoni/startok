<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use App\Concerns\DefaultEntity;
use App\Concerns\OptionTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use Filterable, Sortable, DefaultEntity, OptionTrait;

    protected $table   = 'products';
    protected $primaryKey = 'id';

    public $timestamps = true;
    public $incrementing = true;

    public function rules()
    {
        return [
            $this->field_name() => ['required', 'string', 'max:255'],
        ];
    }
}
