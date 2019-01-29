<?php

namespace App\Http\Controllers;

use App\Users;
use App\Roles;
use App\Privacity;
use App\Friend;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

    public function post_create()
    {
        try {

            if (empty($_POST['email']) || empty($_POST['password']) ) 
            {

              return $this->error(401, 'Debes rellenar todos los campos');
            }

            if(strlen($_POST['password']) < 5 || strlen($_POST['password']) > 12){
                return $this->error(400, 'La contraseña ha de tener entre 5 y 12 caracteres');
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            if($this->userNotRegistered($email))
            { 

                $newPrivacity = new Privacity(array('phone' => 0,'localization' => 0));
                $newPrivacity->save();
                $props = array('password' => $password, 'id_privacity' => $newPrivacity->id, 'is_registered' => 1);


                $newUser = Users::find('first', array(
                   'where' => array(
                       array('email', $email)

                       ),
                   ));

                $newUser->set($props);
                $newUser->save();

                return $this->error(200, 'Usuario creado', ['user' => $newUser]);

            }
            else
            { //Si el email no es valido ( no esta en la bbdd o ya esta registrado )

                return $this->error(400, 'E-mail no valido o ya esta registrado');
            } 

        }
        catch (Exception $e) 
        {
            return $this->error(500, $e->getMessage());

        }      
    } 
    public function post_changepassword()
    {
        
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $password = $_POST['password'];
        $lastPassword = $_POST['lastPassword'];
        if ($lastPassword !== $userData->password) {
            return $this->error(400, 'La contraseña antigua no es correcta');
        }
        $id = $userData->id;
        $user = Users::find($id);
        $user->password = $password;
        $user->save();
        return $this->error(200, 'Contraseña cambiada');
    }
    

    public function post_login()
    {
        try {

            if (empty($_POST['email']) || empty($_POST['password']) )
            {
                return $this->error(400, 'Introduzca todos los campos');
            }
            $email = $_POST['email'];
            $password = $_POST['password'];
            $key = $this->key;
            $users = Users::where('email', $email)->get();
            if ($users->isEmpty()) { 
                return $this->error(400, "Ese usuario no existe");
            }


            if(self::checkLogin($email, $password)){ 


                $userSave = Users::where('email', $email)->first();
                
                if (!empty($_POST['lon']) && !empty($_POST['lat']) ) {
                    $lon = $_POST['lon'];
                    $lat = $_POST['lat'];
                    $userSave->lon = $lon;
                    $userSave->lat = $lat;
                    $userSave->save();
                }
                

                $array = $arrayName = array
                (
                'id' => $userSave->id,
                'email' => $email,
                'username' => $userSave->username,
                'password' => $password,
                'id_rol' => $userSave->id_rol,
                'id_privacity' => $userSave->id_privacity,
                'group' => $userSave->group

                );
                

                $token = JWT::encode($array, $key);

                $privacity = Privacity::find($userSave->id_privacity);

                return response($token);

            }
            else
            {

              return $this->error(400, 'Usuario o contraseña incorrectas');

            }
        } 
        catch (Exception $e) 
        {
            return $this->error(500, $e->getMessage());
        }
    }

    public function post_update()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id_user = $userData->id;
        $id = $_POST['id'];
        $user = Users::find($id_user);

        if ($user->id_rol != 1 && $userData->id != $id) {
            return $this->error(401, 'No tienes permiso');
        }


        if (empty($_POST['id'])) {
            return $this->error(400, 'Introduce la id del usuario');
        }
        try {
            $userBD = Users::find($id);
            if ($userBD == null) {
                return $this->error(400, 'El usuario no existe');
            }
            if (!empty($_POST['email']) ) {
                $userBD->email = $_POST['email'];
            }
            if (!empty($_POST['phone']) ) {
                $userBD->phone = $_POST['phone'];
            }
            if (!empty($_POST['birthday']) ) {
                $userBD->birthday = $_POST['birthday'];
            }
            if (!empty($_POST['description']) ) {
                $userBD->description = $_POST['description'];
            }

            if (isset($_POST['phoneprivacity']) && isset($_POST['localizationprivacity'])) {

                if ($_POST['phoneprivacity'] != 0 && $_POST['phoneprivacity'] != 1){
                    return $this->error(400, 'Valor de phoneprivacity no válido, debe ser 0 ó 1');
                }

                if ($_POST['localizationprivacity'] != 0 && $_POST['localizationprivacity'] != 1){
                    return $this->error(400, 'Valor de localizationprivacity no válido, debe ser 0 ó 1');
                }

                $privacity = Privacity::find($userBD->id_privacity);
                $privacity->phone = $_POST['phoneprivacity'];
                $privacity->localization = $_POST['localizationprivacity'];
                $privacity->save();

            }
            if (!empty($_POST['id_rol']) ) {
                $rolDB = Roles::find($_POST['id_rol']);
                if ($rolDB == null) {
                    return $this->error(400, 'Rol no valido');
                }
                $userBD->id_rol = $_POST['id_rol'];
            }
            $userBD->save();
            return $this->error(200, 'Usuario actualizado');

            
        } catch (Exception $e) {
           
           return $this->error(500, $e->getMessage());

        }
    }

    public function post_delete()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id_user = $userData->id;
        $user = Users::find($id_user);
        if ($user->id !== 1) {
            return $this->error(401, 'No tienes permiso');
        }
        $id = $_POST['id'];
        if (empty($_POST['id'])) {
            return $this->error(400, 'Introduce la id del usuario');
        }
        try {
            $userBD = Users::find($id);
            if ($userBD == null) {
                return $this->error(400, 'El usuario no existe');
            }

            $userBD->delete();

            return $this->error(200, 'Usuario borrado');
        } catch (Exception $e) {
            return $this->error(500, $e->getMessage());
        }
    }
    public function get_allusers()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id_user = $userData->id;
        $user = Users::find($id_user);
        if ($user->id !== 1) {
            return $this->error(401, 'No tienes permiso');
        }
        $users = Users::where('is_registered', 1)->get();
        $userNames = [];
        $userRoles = [];
        foreach ($users as $user) {
            array_push($userNames, $user->username);
            array_push($userRoles, $user->id_rol);
        }
        return response()->json([
            'users' => $userNames,
            'roles' => $userRoles,
        ]);
    } 

    public function post_recover(Request $request)
    {
        if (!isset($_POST['email'])) 
        {
            return $this->error(401, 'Introduzca su email');
        }    
        $email = $_POST['email'];
        if (self::recoverPassword($email)) {
            $userRecover = Users::where('email', $email)->first();
            $id = $userRecover->id;
            $pwdSent = Users::where('email', $userRecover->email)->first()->password;
            $dataEmail = array(
                'pwd' => $pwdSent,
            );
            Mail::send('emails.welcome', $dataEmail, function($message){
                $emailRecipient = $_POST['email'];
                $message->from('proyectogpass@gmail.com', 'Recuperación contraseña');
                $message->to($emailRecipient)->subject('Recuperación contraseña');
            });
            return "Contraseña Enviada";


        }
        else
        {
            return response("Los datos no son correctos", 403)->header('Access-Control-Allow-Origin', '*');
        }

    }


    public function post_sendRequest()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        if (empty($_POST['id_user'])) 
        {
          return $this->error(400, 'Introduzca la ID del usuario');
        }
        $id_user = $_POST['id_user'];

        try {

            $userBD = Users::where('id', $id_user)->first();
            if ($userBD == null) 
            {
                return $this->error(400, 'No existe el usuario');
            }

            
        $friend = Friend::where('state' , 1)
                    ->where('id_user_send', $userData->id)
                    ->where('id_user_receive', $id_user)
                    ->orWhere('id_user_send', $id_user)
                    ->orWhere('id_user_receive', $userData->id)
                    ->get();
        $arrFriend = (array)$friend;
        $isFriendEmpty = array_filter($arrFriend);           
            
        if (!empty($isFriendEmpty)) 
        {
            return $this->error(400, 'Ya existe una petición existente entre ambos usuarios o ya sois amigos');
            }
            else{
                return $this->error(400, 'holi');
        }
            
        } catch (Exception $e) {
            
        }
    }


    public function userNotRegistered($email)
    {
        $users = Users::where('email', $email)->get();
        foreach ($users as $user) {
            if ($user->email == $email) {
                return false;
            }
            else{
                return true;
            }
        }
    }
}
