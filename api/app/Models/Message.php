<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model {

    protected $table = 'message';

    public function querySelect($query, $where = false) {
        if(!empty($where))
           $data = DB::select($query, $where);
        else
           $data = DB::select($query);
        return $data;
    }

    public function fetchData($tableName = 'message', $where = false) {
        if(!$where)
           $data = DB::table($tableName)->select('*')->get();
        else {
           $data = DB::table($tableName)->where($where)->get();
        }
        return $this->inArray($data);
    }

    private function inArray($data, $one = false)  {
        $data = $data->toArray();
        if(empty($data))
            return array();
        if($one)
            $data = $data[0];
        return (array)$data;
    }

    public function addItem($tableName, $data) {
        $r = DB::table($tableName)->insert($data);
        return array('save_result' => $r);
    }

    public function editItem($tableName, $data, $where) {
        $r = DB::table($tableName)
            ->where($where[0], $where[1])
            ->update($data);
        return array('save_result' => $r);
    }

    public function changeReadState($where, $readState = 1) {
        $r = DB::update("UPDATE message SET read_state = {$readState} 
                              WHERE id = ? AND send_user_id = ?", $where);
        return array('save_result' => $r);
    }

//    public function documentsAll() {
//        $data = DB::table('document')
//            ->select('*',
//                'document.id        AS doc_id',
//                'document.create_at AS doc_create_at',
//                'document.path      AS doc_path',
//                'person.id          AS user_id')
//            ->join('doctype', 'document.doctype_id', '=', 'doctype.id')
//            ->leftJoin('person' , 'document.person_id' , '=', 'person.id')
//            ->leftJoin('company', 'person.company_id', '=', 'company.id')
//            ->get();
//        return $this->inArray($data);
//    }

}

// $users = DB::select('select * from users where active = ?', [1]);
// DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);
// $affected = DB::update('update users set votes = 100 where name = ?', ['John']);
// $deleted = DB::delete('delete from users');
// $users = DB::table('users')->select('name', 'email as user_email')->get();