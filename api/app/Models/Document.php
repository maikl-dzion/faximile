<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Document extends Model {

    protected $table = 'document';

    public function getList() {

        $data = DB::table('document')
            ->join('person' , 'document.person_id' , '=', 'person.id')
            ->join('company', 'document.company_id', '=', 'company.id')
            ->join('doctype', 'document.doctype_id', '=', 'doctype.id')
            ->select('*')
            ->get();
        return $this->inArray($data);
    }

    public function getUserDocs($userId) {

        $data = DB::table('document')
            ->join('person' , 'document.person_id' , '=', 'person.id')
            ->join('company', 'document.company_id', '=', 'company.id')
            ->join('doctype', 'document.doctype_id', '=', 'doctype.id')
            ->where('person.id', $userId)
            ->where('person.role',  3)
            ->get();

        return $this->inArray($data);
    }

    public function getCompanyDocs($companyId, $userId) {

        $data = DB::table('document')
            ->join('person' , 'document.person_id' , '=', 'person.id')
            ->join('company', 'document.company_id', '=', 'company.id')
            ->join('doctype', 'document.doctype_id', '=', 'doctype.id')
            // ->where('person.id', $userId)
            ->where('document.company_id',  $companyId)
            ->get();

        return $this->inArray($data);
    }

    public function getCompanyList($role = 0){
        $data = DB::select('SELECT 

                                   d.id doc_id
                                   ,c.id company_id
                                   ,*
                                   
                                 FROM company AS c
                                 LEFT JOIN document AS d ON d.company_id = c.id
                                 LEFT JOIN doctype  AS t ON t.id = d.doctype_id');
        return $data;
    }

    public function getPersonList($role = 0){

        $data = DB::select('SELECT 

                                   p.id user_id
                                   ,d.id doc_id
                                   ,c.id company_id
                                   ,*
                                   
                                 FROM person AS p
                                 LEFT JOIN company  AS c ON c.id = p.company_id 
                                 LEFT JOIN document AS d ON d.person_id = p.id
                                 LEFT JOIN doctype  AS t ON t.id = d.doctype_id
                                 WHERE p.role = :role', ['role' => 3]);
        return $data;
    }

    public function getPerson($param, $fieldName = 'id') {
        $data = DB::table('person')
            ->where('person.' .$fieldName, $param)
            ->get();
        return $this->inArray($data, true);
    }

    private function inArray($data, $one = false)  {
        $data = $data->toArray();
        if($one)
            $data = $data[0];
        return (array)$data;
    }

    private function getTableList($tableName = 'persone', $role = 1) {
        $data = DB::table($tableName)->select('*')->get();
        return $this->inArray($data);
    }
}

// $users = DB::select('select * from users where active = ?', [1]);
// DB::insert('insert into users (id, name) values (?, ?)', [1, 'Dayle']);
// $affected = DB::update('update users set votes = 100 where name = ?', ['John']);
// $deleted = DB::delete('delete from users');
// $users = DB::table('users')->select('name', 'email as user_email')->get();