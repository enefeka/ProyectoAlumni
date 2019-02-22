<?php

namespace App\Http\Controllers;

use App\Groups;
use App\Users;
use App\Roles;
use App\Privacity;
use App\Belong;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class GroupsController extends Controller
{

    public function post_create()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id_user = $userData->id;
        $user = Users::find($id_user);
        if ($user->id !== 1) {
            return $this->createResponse(401, 'No tienes permiso');
        }
        $name = $_POST['name'];

        if (empty($_POST['name'])) {
            return $this->createResponse(400, 'Introduce el nombre del grupo');
        }
        try {

            $groups = Groups::where('name', $name)->get();
            foreach ($groups as $group) {
                if ($group->name == $name) {
                    return $this->createResponse(400, 'El nombre del grupo ya existe');
                }
            }

            $groups = new Groups();
            $groups->name = $name;
            $groups->save();

            
            // $belong = new Belong();
            // $belong->id_user = 1;
            // $belong->id_group = $groups->id;

            // $belong->save();

            return $this->createResponse(200, 'Grupo creado');
            
        } catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());
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
            return $this->createResponse(401, 'No tienes permiso');
        }
        $id = $_POST['id'];
        if (empty($_POST['id'])) {
            return $this->createResponse(400, 'Introduce la id del grupo');
        }
        try {
            $groupBD = Groups::find($id);
            if ($groupBD == null) {
                return $this->createResponse(400, 'El grupo no existe');
            }

            $groupBD->delete();

            return $this->createResponse(200, 'Grupo borrado');
        } catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());
        }
    }
    
   public function post_assign()
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
        if (empty($_POST['id_user']) || empty($_POST['id_group'])) {
            return $this->createResponse(400, 'Falta parametro id_user, id_group');
        }

        $id_user = $_POST['id_user'];
        $id_group = $_POST['id_group'];
        try {
            $groupDB = Groups::find($id_group);
            if ($groupDB == null) 
            {
                return $this->createResponse(400, 'El grupo no existe');
            }
            $userDB = Users::find($id_user);

            if ($userDB == null) 
            {
                return $this->createResponse(400, 'El usuario no existe');
            }
            

            $belongDB = Belong::where('id_user', $id_user)
                              ->where('id_group', $id_group)
                              ->get();

            
            $arrBelongs = (array)$belongDB;
            $isBelongsEmpty = array_filter($arrBelongs);



            if (!empty($isBelongsEmpty)) {
                 return $this->createResponse(400, 'El usuario ya está asignado a ese grupo');
             }


            $belongDB = new Belong();
            $belongDB->id_user = $id_user;
            $belongDB->id_group = $id_group;
            $belongDB->save();
            return $this->createResponse(200, 'Usuario asignado a grupo');

        } catch (Exception $e) 
        {
            return $this->createResponse(500, $e->getMessage());
        }
    }


    

    public function post_unassign()
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
        if (empty($_POST['id_user']) || empty($_POST['id_group'])) {
            return $this->createResponse(400, 'Falta parametro id_user, id_group');
        }   
        $id_user = $_POST['id_user'];
        $id_group = $_POST['id_group'];
        try {
            $groupDB = Groups::find($id_group);
            if ($groupDB == null) 
            {
                return $this->createResponse(400, 'El grupo no existe');
            }
            $userDB = Users::find($id_user);
            if ($userDB == null) 
            {
                return $this->createResponse(400, 'El usuario no existe');
            }
            
            $belongDB = Belong::where('id_user', $id_user)
                              ->where('id_group', $id_group)
                              ->first();

            $arrBelongs = (array)$belongDB;
            $isBelongsEmpty = array_filter($arrBelongs);



            if (empty($isBelongsEmpty)) {
                 return $this->createResponse(400, 'El usuario no pertenece a ese grupo');
             }
            
            $belongDB->delete();
            
            return $this->createResponse(200, 'Usuario desasignado del grupo');

        } catch (Exception $e) 
        {
            return $this->createResponse(500, $e->getMessage());
        }
    }

    public function get_groups()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        if ($token == null) {
            return $this->createResponse(401, 'El token no es válido');
        }
        // $groups = Groups::whereNotNull('id')->get();
        $groups = Groups::all();
        $groupNames = [];
        $groupIds = [];
        foreach ($groups as $group) {
            array_push($groupNames, $group->name);
            array_push($groupIds, $group->id);
        }
        // return response()->json([
        //     'grupos' => $groupNames,
        //     'ids' => $groupIds,
        // ]);
                return $this->createResponse(200, 'Listado de grupos', array('groups' => $groups));
    } 

    public function get_groupsByUser()
    {

    }

    public function get_usersFromGroup()
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

        $id_group = $_GET['id_group'];

        $belongs = Belong::where('id_group', $id_group)
                    ->get();

        $memberIds =[];            
        foreach ($belongs as $belong) {
            $member = Users::findOrFail($belong->id_user);
            array_push($memberIds, $member);
        }


        // foreach ($members as $member){
        //     $userObject = Users::where('id', $member->id)->get();
        //     $userObjects[] = $userObject;
        // }

        return $this->createResponse(200, 'Usuarios pertenecientes al grupo', $memberIds);
    }
    

    public function get_groupsByUserCliente()
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
        
        $id_user = $_GET['id_user'];

        $belongs = Belong::where('id_user', $id_user)
                        ->get();

        $arrBelongs = (array)$belongs;
        $isBelongsEmpty = array_filter($arrBelongs);



        if (empty($isBelongsEmpty)) {
                 return $this->createResponse(400, 'El usuario no pertenece a ningun grupo');
             }

        foreach ($belongs as $key => $belong) {
                $group = Groups::find($belong->id_group);
                $groups[] = $group;
             }
             // var_dump($group);
        foreach ($groups as $key => $group) {
                $belongsGroup = Belong::where('id_group', $group->id)->get();
             } 
             // var_dump($belongsGroup);
        foreach ($belongsGroup as $key => $belongGroup) {
                $userGroup = Users::where('id', $belongGroup->id_user)->first();
        }
            // var_dump($userGroup);

        if ($userGroup != null) {
            $usersGroup[] = $userGroup;
        }

        $group['users'] = $usersGroup;

        $usersGroup = [];

        return $this->createResponse(200, 'Grupos a los que pertenece devueltos', array('groups' => Users::reindex($groups)));
   
    }0
}
