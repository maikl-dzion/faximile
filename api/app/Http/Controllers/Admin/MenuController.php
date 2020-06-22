<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;

class MenuController extends Controller
{

    public function index() {
        $results = Menu::all();
        return $this->respond($results);
    }

    public function store(Request $request, Menu $model) {
        $data    = $request->all();
        $newItem = $model->create($data);
        return $this->respond($newItem);
    }

    public function show(Menu $model, $id = 0) {
        //$item  = $model->getItem($id);
        $item = $model->findOrFail($id);
        return $this->respond($item);
    }

    public function update(Request $request, Menu $model, $id = 0) {
        $data   = $request->all();
        $status = $model->find($id)->update($data);
        $item   = $model->getItem($id);
        $resp   = array('status' => $status, 'item' => $item);
        return $this->respond($resp);
    }

    public function destroy(Menu $model, $id = 0)
    {
        $object = $model->findOrFail($id);
        $status = $object->delete();
        $resp   = array('status' => $status);
        return $this->respond($resp);
    }

    public function create(){
        //
    }

    public function edit($id = 0){
        //
    }
}
