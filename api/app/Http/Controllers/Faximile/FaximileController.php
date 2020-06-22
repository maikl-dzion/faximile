<?php

namespace App\Http\Controllers\Faximile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Auth\JwtAuth;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Faximile;

class FaximileController extends Controller
{
    private $model;

    public function __construct(){
        $this->getHttpHeaders();
        $this->userAccess();
        $this->model = new Faximile();
    }

    public function index(){
        echo 'index';
    }

    public function uploadFiles(Request $request) {
        //$value = $request->file('file');
        //$value2 = $request->input('user_name');
        $path = $request->file('file')->store('documents', 'public');
        lg($path);
    }

    public function changePassword(Request $request) {
        $userId      = $request->input('id');
        $newPassword = $request->input('password');
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $result      = $this->model->editItem('person',
                        array('password' => $newPassword),
                        array('id', $userId));
        return $this->respond($result, 200);
    }

    public function editPerson(Request $request) {
        $userId      = $request->input('id');
        $fields = array('username',
                        'phone',
                        'email');
        $data = $this->dataFormatted($request, $fields);
        $result = $this->model->editItem('person', $data, array('id', $userId));
        return $this->respond($result, 200);
    }

    public function getPerson($fieldName, $value) {
        $result = $this->model->findPerson($value, $fieldName);
        return $this->respond($result, 200);
    }

    public function sendSmsTest($userId, $message) {

        $sms = new SMSRU('CBB553A9-4DB0-0B73-3206-F13194F05516');
        $data = new \stdClass();
        $data->to   = '89500362758';
        $data->text = 'Hello World'; // Текст сообщения

        // $data->from = ''; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
        // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
        // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
        // $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
        // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему

        $resp = $sms->send_one($data); // Отправка сообщения и возврат данных в переменную

//        if ($resp->status == "OK") { // Запрос выполнен успешно
//            echo "Сообщение отправлено успешно. ";
//            echo "ID сообщения: $resp->sms_id. ";
//            echo "Ваш новый баланс: $sms->balance";
//        } else {
//            echo "Сообщение не отправлено. ";
//            echo "Код ошибки: $sms->status_code. ";
//            echo "Текст ошибки: $sms->status_text.";
//        }

        lg($resp);
        // return $this->respond($result, 200);
    }

    public function checkCode($type, $userId, $prop) {

        $result = array('result' => false);
        $user = $this->model->findPerson($userId);

        switch ($type) {
            case 'phone' :
                $ident = $user['email'];
                $phone = $user['phone'];
                $code  = rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9);
                $r1 = $this->sendMessageSevice($ident, $code, 'mail', 'Подпись документа');
                if(self::SMS_SEND_STATE) {
                    $r0 = $this->sendMessageSevice($phone, $code, 'phone', 'Подпись документа');
                }

                $r2 = $this->model->editItem('person',
                                            array('code' => $code),
                                            array('id', $userId));
                $result = array('send' => $r1);
                break;

            case 'code' :
                $code = $prop;
                if($user['code'] == $code)
                    $result['check'] = true;
                else
                    $result['check'] = false;
                break;
        }

        return $this->respond($result, 200);
    }

    public function getUserInfoDocList($userId) {
        $result = $this->model->userInfoDocList($userId);
        return $this->respond($result, 200);
    }

    public function getUserInfoDocType($userType) {
        $result = $this->userInfoDocType($userType);
        return $this->respond($result, 200);
    }

    protected function userInfoDocType($userType) {
        $where = '';
        $query = 'SELECT * FROM userinfo_doctype ';
        switch($userType) {
            case 1 :
                $where = ' WHERE id IN(1, 2, 3, 4)';
                break;

            case 2 :
                $where = ' WHERE id IN(1, 2, 3, 4, 5, 6)';
                break;

            case 3 :
                $where = ' WHERE id IN(1, 2, 3, 4, 5, 6, 7, 8, 9)';
                break;
        }
        $result = $this->model->querySelect($query . $where . ' ORDER BY id');
        return $result;
    }

    public function signDocumentUser($userId, $documentId, $signType = false) {
        $result = array('sign_result' => false,
                        'message'     => '');
        $message = 'Не удалось подписать документ';
        $signState = false;

        $sign = new SigningDocsController();
        $user = $this->model->findPerson($userId);
        $document = $this->model->findDocument($documentId);
        $userPrivateKey = $user['private_key'];
        $documentPath   = $document['path'];
        $signName    = $this->getSignName($document);
        $singFileName = $signName . '.sign';
        $fileBody = Storage::disk('public')->get($documentPath);

        // подписываем файл
        $signature = $sign->signFile($fileBody, $userPrivateKey);
        if(!$signType)
           $saveFlag = Storage::disk('public')->put('sign_user/' . $singFileName, $signature);
        else
           $saveFlag = Storage::disk('public')->put('users/sign/' . $singFileName, $signature);

        if($saveFlag) {
            $document['sign_user']  = $singFileName;
            $record = $this->model->editItem('document',
                                         array('sign_user' => $singFileName),
                                         array('id', $documentId));

            if(!empty($record['save_result'])) {
                $signState = true;
                $message   = 'Документ успешно подписан';
            }  else {
                $message = 'Не удалось сохранить результат в базу';
            }
        }  else {
            $message = 'Не удалось загрузить файл подписи';
        }

        return $this->respond(array('sign_result' => $signState,
                                    'message'     => $message), 200);
    }

    public function verifyDocumentUser($documentId) {

        $message = 'Подпись не соответствует документу';
        $verify = false;

        $sign = new SigningDocsController();
        $document = $this->model->findDocument($documentId);

        if(!empty($document)) {

            $signUserDir = 'sign_user';

            $docFileName     = $document['path'];
            $signFileName     = $this->getSignName($document);
            $userSignFileName = $signUserDir . '/' . $signFileName;
            $userId  = $document['person_id'];
            $user    = $this->model->findPerson($userId);
            $userPublicKey = $user['public_key'];

            $existsDoc  = Storage::disk('public')->has($docFileName);
            $existsSign = Storage::disk('public')->exists($userSignFileName);

            if($existsDoc && $existsSign) {
                $documentBody  = Storage::disk('public')->get($docFileName);
                $signBody      = Storage::disk('public')->get($userSignFileName);
                $verify    = $sign->verifyFile($documentBody , $signBody, $userPublicKey);
                $message = 'Подпись документа заверена';
            } else {
                $message = 'Не найден документ или подпись';
            }
        }

        return $this->respond(array('verify_result' => $verify,
                                    'message'       => $message), 200);
    }

    public function loadDocInfoFile(Request $request) {

        $fieldName = $request->input('field_name');
        $login     = $request->input('login');
        $userId    = $request->input('user_id');
        $typeId    = $request->input('user_infotype_id');
        $dirname   = 'users';
        $tableName = 'document';

        $newFileName = $userId . '_' .$login . '_' . $fieldName;

        $file = $request->file($fieldName);
        if(!empty($file)) {

            $extension = $file->extension();
            $newFileName  = $userId . '_' . $login . '_' . $fieldName . '.' . $extension;
            $path  = $file->storeAs($dirname, $newFileName, 'public');

            $data = array('user_infotype_id' => $typeId,
                          'person_id'  => $userId,
                          'create_at'  => $this->getCurrentDate(),
                          'path'       => $path);
            $result = $this->model->addItem($tableName, $data);

        } else {
            $error = 'Не удалось загрузить файл';
            lg($error);
        }

        return $this->respond($result, 200);
    }

    public function addItem(Request $request, $tableName = ''){
        $result = array();
        $sign   = new SigningDocsController();
        switch ($tableName) {

            case 'document' :
                  $fields = array('doctype_id',
                                  'person_id',
                                  'document_state');
                  $data = $this->dataFormatted($request, $fields);
                  $resFile = $this->saveFile($request, 'file');
                  if(empty($resFile['path'])) {
                      lg($resFile['error']);
                      return false;
                  }
                  $data['path']       = $resFile['path'];
                  $data['create_at']  = $this->getCurrentDate();

                  // подписываем документ от лица компании
                  $user = $this->model->findPerson($data['person_id']);
                  $company  = $this->model->findCompany($user['company_id']);

                  $fileContent = Storage::disk('public')->get($data['path']);
                  // подписываем файл
                  $signature = $sign->signFile($fileContent, $company['com_private_key']);
                  // и сразу проверяем
                  $verify  = $sign->verifyFile($fileContent , $signature, $company['com_public_key']);

                  $newSignName = $this->getSignName($data);
                  $r = Storage::disk('public')->put('sign_company/' . $newSignName, $signature);
                  $data['sign_company']  = $newSignName;
                  $result = $this->model->addItem($tableName, $data);

                  break;

            case 'person'  :
                  $pkeys = $sign->createKeyPair();

                  $fields = array('username',
                                  'login',
                                  'password',
                                  'role',
                                  'phone',
                                  'email',
                                  'post',
                                  'u_type',
                                  'company_id');
                  $data = $this->dataFormatted($request, $fields);

                  $data['password']    = password_hash($data['password'], PASSWORD_DEFAULT);
                  $data['private_key'] = $pkeys['private_key'];
                  $data['public_key']  = $pkeys['public_key'];
                  $data['user_state']  = 0;
                  $data['create_at']  = $this->getCurrentDate();
                  // lg($data);
                  $result = $this->model->addItem($tableName, $data);
                  break;

            case 'company' :
                 $pkeys = $sign->createKeyPair();
                 $fields = array('company_name',
                                 'inn',
                                 'phone',
                                 'email',
                                 'com_state',
                                 'com_type');
                 $data = $this->dataFormatted($request, $fields);
                 $data['com_private_key'] = $pkeys['private_key'];
                 $data['com_public_key']  = $pkeys['public_key'];
                 $data['create_at']  = $this->getCurrentDate();
                 // lg($data);
                 $result = $this->model->addItem($tableName, $data);
                 break;
        }

        return $this->respond($result, 200);
    }

    public function personActivation(Request $request){
        $userId = $request->input('id');
        $registerPage = $request->input('register_page');
        $user   = $this->model->findPerson($userId);
        $hash   = md5($userId . $user['login']. $user['email']. rand(4, 5000));
        $registerPage = $registerPage . $hash;
        $r = $this->model->editItem('person',
                                     array('link_hash' => $hash, 'user_state' => 1),
                                     array('id', $userId));

        // $email = 'dzion67@mail.ru';
        $email = $user['email'];
        $phone = $user['phone'];

        $ident = $email;
        $html  = '<html><head></head><body>
                    <a href="' . $registerPage .'">' .$registerPage. '</a>      
                  </body></html>';

        $message = $html;
        $result  = $this->sendMessageSevice($ident, $message, 'mail');
        // if(self::SMS_SEND_STATE)
           // $respPhone  = $this->sendMessageSevice($phone, $message, 'phone');

        return $this->respond(array('save_result' =>$result), 200);
    }


    public function personRegister(Request $request){

        $userId  = $request->input('id');
        $login   = $request->input('login');
        $userType   = $request->input('u_type');
        $infoDocType = $this->userInfoDocType($userType);

        $documents = $paths = $notListDocs = array();

        foreach ($infoDocType as $key => $values) {
            $item = (array)$values;
            $id    = $item['id'];
            $alias = $item['alias'];
            $file = $request->file($alias);
            $r = array('alias' => $alias,
                       'file' => $file);
            $documents[$id] = $r;
        }

        // lg($documents);

        $usersDir = 'users';
        $disk     = 'public';
        foreach ($documents as $docTypeId => $values) {
            $file  = $values['file'];
            $fname = $values['alias'];
            if(!empty($file)) {

                $extension = $file->extension();
                $fileName  = $userId . '_' . $login . '_' . $fname . '.' . $extension;
                $filePath  = $file->storeAs(
                    $usersDir, $fileName, $disk
                );

                if($filePath) {

                    $data = array();
                    $data['person_id']  = $userId;
                    $data['path']       = $filePath;
                    $data['create_at']  = $this->getCurrentDate();
                    $data['user_infotype_id'] = $docTypeId;
                    $r = $this->model->addItem('document', $data);
                }

                $paths[] = $filePath;
            } else {
                // если не все документы
                $notListDocs[$docTypeId] = $values;
            }
        }

        $fields = array('username',
                        'login',
                        // 'password',
                        'role',
                        'phone',
                        'email',
                        'post',
                        'u_type',
                        'sign_true',
                        'user_info_true',
                        'company_id');
        $data = $this->dataFormatted($request, $fields);

        $p = 0;
        if(isset($data['user_info_true'])) {
            $v = $data['user_info_true'];
            if($v == 'true' || $v == 1) $p = 1;
        }
        $data['user_info_true'] = $p;

        $p = 0;
        if(isset($data['sign_true'])) {
            $v = $data['sign_true'];
            if($v == 'true' || $v == 1) $p = 1;
        }

        $data['sign_true'] = $p;
        $data['user_state'] = 2;
        // $data['link_hash']  = md5($userId . $login . rand(4, 5000) . rand(4, 5000) . rand(4, 5000));

        $result = $this->model->editItem('person', $data, array('id', $userId));
        return $this->respond(array('save_result' => $result), 200);
    }

    public function getTableData($tableName){
        $where = false;
        switch ($tableName) {
            case 'person' :
                 $where = array(
                     array('role', '=', 3)
                 );
                 break;
        }
        $result = $this->model->oneTableData($tableName, $where);
        return $this->respond($result, 200);
    }

    public function getDocuments($entity = '', $listName = ''){
        $result = array();

        // $entity = 'executor';
        // $this->userRole = 3;
        // lg($this->userInfo);

        $companyId = $this->isValue($this->userInfo, 'company_id');
        $userId    = $this->isValue($this->userInfo, 'id');

        switch ($this->userRole) {
            case 1 : break; // admin

            case 2 : // менеджер  // executor
                if($listName) {
                    $result = $this->getDataList($listName);
                } else {
                    $result = $this->documentsExecutor();
                }
                break;

            case 3 : // работник - мигрант // person
                // $userId = 2;
                $result = $this->documentsPerson($userId);
                break;

            case 4 : // компания  // company
                // $companyId = 3;
                $result = $this->documentsCompany($companyId);
                break;
        }

        return $this->respond($result, 200);
    }

    public function sendMessageSevice($ident, $message, $service = 'mail', $subject = 'Ссылка для регистрации') {
        $result = '';
        switch ($service) {
            case 'mail' :
                $result = $this->sendMail($ident, $message, $subject);
                break;

            case 'phone' :
                $result = $this->sendSms($ident, $message, $subject);
                break;
        }
        return $result;
    }

    ///////////////////////////////
    //////////////////////////////
    // ---- ЗАКРЫТЫЕ МЕТОДЫ ------

    protected function sendMail($ident, $message, $subject = 'Ссылка для регистрации') {
        $to      = $ident;
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        // $headers .= "From: birthday@example.com\r\n";
        $r = mail($to, $subject, $message, $headers);
        return $r;
    }

    protected function sendSms($phone, $message, $subject = '') {

        $sms = new SMSRU('CBB553A9-4DB0-0B73-3206-F13194F05516');
        $data = new \stdClass();
        $data->to   = $phone;
        $data->text = $message; // Текст сообщения

        // $data->from = ''; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
        // $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
        // $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
        // $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
        // $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему

        $resp = $sms->send_one($data); // Отправка сообщения и возврат данных в переменную

        if ($resp->status == "OK") {  // Запрос выполнен успешно
            return $resp;
        } else {
            $errorMessage = 'не удалось отправить сообщение на телефон,попробуйте еще раз';
            $resp['error_message'] = $errorMessage;
            lg($resp);
        }
    }

    protected function dataFormatted($request, $fields) {
        $result = array();
        foreach($fields as $key =>$field) {
            $value = $request->input($field);
            if($value) {
                $result[$field] = $value;
            }
        }
        return $result;
    }

    protected function getSignName($data) {
        $docFileName  = explode('.', $data['path']);
        $signName     = explode('/', $docFileName[0]);
        $fileName     = $signName[1];
        return $fileName;
    }

    protected function saveFile($request, $fname, $newFileName = '', $dirname = 'documents', $disk = 'public') {
        $file = $request->file($fname);
        $error = $data = $path = '';
        if(empty($file)) {
            $error = 'Не удалось загрузить файл';
        } else {
            if(!$newFileName)
               $path = $file->store($dirname, $disk);
        }
        return array(
            'error' => $error,
            'path'  => $path,
            'data'  => $data,
        );
    }

    protected function documentsExecutor(){
        return $this->model->documentsAll();
    }

    protected function documentsCompany($companyId = 0){
        // lg($companyId);
        return $this->model->documentsCompany($companyId);
    }

    protected function documentsPerson($userId = 0){
        // lg($userId);
        return $this->model->documentsPerson($userId);
    }

    public function getDataList($listName = 'company_list'){

        $data = $result = $resp = array();
        $fname = '';
        switch ($listName) {
            case 'company_list' :
                $fname = 'company_uid';
                $data = $this->model->getCompanyList();
                break;

            case 'person_list' :
                $fname = 'user_id';
                $data = $this->model->getPersonList();
                break;
        }

        // lg($data);

        foreach ($data as $object) {

            if(!empty($result[$object->$fname])) {
                if(!empty($object->doc_id)) {
                    $result[$object->$fname]['doc_count']++;
                    if($object->sign_user) {
                        if(!empty($result[$object->$fname]['sign_count']))
                          $result[$object->$fname]['sign_count']++;
                        else
                          $result[$object->$fname]['sign_count'] = 1;
                    }
                }
            }
            else  {
                $result[$object->$fname] = (array)$object;
                $result[$object->$fname]['doc_count']  = 0;
                $result[$object->$fname]['sign_count'] = 0;
                if(!empty($object->doc_id)) {
                    $result[$object->$fname]['doc_count'] = 1;
                    if($object->sign_user) {
                        $result[$object->$fname]['sign_count'] = 1;
                    }
                }
            }
        }

        foreach ($result as $values) {
            $resp[] = $values;
        }

        // lg($result);

        return $resp;
    }

}


// $tokenUrl = 'http://lite.ru/faximile/public/faximile/person-list/
              //eyJhbGciOiJIUzI1NiIsInR5cGUiOiJKV1QifQ==.eyJpZCI6MiwidXNlcm5hbWUiOiJcdTA0MjVcdTA0NDNcdTA0MzRcdTA0MzBcdTA0MzlcdTA0MzFcdTA0MzVcdTA0NDBcdTA0MzNcdTA0MzVcdTA0M2RcdTA0M2VcdTA0MzIgXHUwNDE4XHUwNDQxXHUwNDNjXHUwNDMwXHUwNDM4XHUwNDNiIiwibG9naW4iOiJpc21hIiwicGFzc3dvcmQiOiIxMjM0Iiwicm9sZSI6MywiY3JlYXRlX2F0IjpudWxsLCJ1cGRhdGVfYXQiOm51bGx9.7lvgqQCxr18yvl0DSnpwM2dtbUDhpSWA2+hWxk4B7l0=';