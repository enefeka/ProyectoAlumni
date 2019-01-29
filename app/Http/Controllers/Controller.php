<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use \Firebase\JWT\JWT;
use App\Users;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected $key = '73yt247ht9y3hg93g876h38gh4598';


    protected function error($code, $message)
    {
        $json = ['message' => $message];
        $json = json_encode($json);
        return  response($json, $code)->header('Access-Control-Allow-Origin', '*');
    }
    
    protected function success($message, $data = [])
    {
    	$json = ['message' => $message, 'data' => $data];
        $json = json_encode($json);
        return  response($json, 200)->header('Access-Control-Allow-Origin', '*');
    }

    protected function checkLogin($email, $password)
    {
        $userSave = Users::where('email', $email)->first();
        $emailSave = $userSave->email;
        $passwordSave = $userSave->password;
        if($emailSave == $email && $passwordSave == $password)
        {
            return true;
        }
        return false;
    }
    protected function recoverPassword($email)
    {
        $userRecover = Users::where('email', $email)->first();
        $emailRecover = $userRecover->email;
        if($emailRecover == $email)
        {
            return true;
        }
        return false;
    }

}
