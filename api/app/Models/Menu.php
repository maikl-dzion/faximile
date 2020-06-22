<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'title',
        'name',
        'type',
        'parent_id',
        'note',
    ];


    public function getItem($id = 0) {
        $object  = $this->find($id);
        // $item = $object->toJson();
        $item = $object->toArray();
        return $item;
    }
}
