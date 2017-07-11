<?php

class Api {

    private $Method;
    private $RequestBody;
    private $QueryString;
    private $GetHeaders;

    private $Data;


    function __construct(){
        header('Access-Control-Allow-Origin: *');  
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS, PATCH');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
        $this->Method = $_SERVER['REQUEST_METHOD'];// Tipo de verbo http
        $this->RequestBody = file_get_contents('php://input');// Dados enviados via Body da Requisição
        $this->QueryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;// Dados enviados via Url
        $this->GetHeaders = getallheaders();// Todos os headers da requisição

        // chamando função que vai checar o tip de requisição
        $this->checkRequestMethod();
    }

    public function getData(){
        return $this->Data;
    }

    private function checkRequestMethod(){
        switch (strtolower($this->Method)) {
            case 'get':
                parse_str($this->QueryString, $this->Data);
                $this->Data = json_encode($this->Data);
                break;

            case 'post':
                if($this->checkContentType($this->Data) == 'json'){
                    $this->Data = json_decode($this->RequestBody, true);;
                }elseif($this->checkContentType($this->Data) == 'form'){
                    $this->Data = urldecode($this->RequestBody);
                }elseif($this->checkContentType($this->Data) == 'multipart'){
                    $this->Data = $_FILE;
                }
                break;
            
            case 'delete':
                parse_str($_SERVER['QUERY_STRING'], $data);
                if($this->checkContentType($this->Data) == 'json'){
                    $this->Data = json_decode($this->RequestBody, true);;
                }elseif($this->checkContentType($this->Data) == 'form'){
                    $this->Data = urldecode($this->RequestBody);
                }elseif($this->checkContentType($this->Data) == 'multipart'){
                    $this->Data = $_FILE;
                }
                break;
            case 'patch':
                if($this->checkContentType($this->Data) == 'json'){
                    $this->Data = json_decode($this->RequestBody, true);;
                }elseif($this->checkContentType($this->Data) == 'form'){
                    $this->Data = urldecode($this->RequestBody);
                }elseif($this->checkContentType($this->Data) == 'multipart'){
                    $this->Data = $_FILE;
                }
                break;
            case 'put':
                if($this->checkContentType($this->Data) == 'json'){
                    $this->Data = json_decode($this->RequestBody, true);;
                }elseif($this->checkContentType($this->Data) == 'form'){
                    $this->Data = urldecode($this->RequestBody);
                }elseif($this->checkContentType($this->Data) == 'multipart'){
                    $this->Data = $_FILE;
                }
                break;
        }
    }

    // função que verifica o tipo de conteúdo da requisição
    // Se é um json, ou conteúdo de um formulário html, ou um arquivo.
    public function checkContentType($content){
        if($this->GetHeaders['Content-Type'] == 'application/json'){
            
            return 'json';

        }elseif($this->GetHeaders['Content-Type'] == 'application/x-www-form-urlencoded'){

            return 'form';

        }elseif($this->GetHeaders['Content-Type'] == 'multipart/form-data'){
            
            return 'multipart';

        }else{

            echo 'Error, content-type invalid!';die();
        }
    }

}
