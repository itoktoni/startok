<?php

namespace App\Models;

use App\Models\BaseModel;



class Category extends BaseModel
{
    protected $perPage = 20;
    protected $table = 'category';
    protected $primaryKey = 'category_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['category_id', 'category_nama', 'category_keterangan'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function has_ecm.penamaan()
    {
        return $this->hasMany(\App\Facades\Model\Ecm.penamaanModel::getModel(), 'category_id', 'penamaan_id_category');
    }
    
}
