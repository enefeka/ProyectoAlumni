<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Events;
use App\Groups;
use App\Users;
use App\Roles;
use App\Types;
use App\Privacity;
use App\Belong;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class CommentsController extends Controller
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
            return $this->error(401, 'No tienes permiso');
        }
        $title = $_POST['title'];
        $description = $_POST['description'];
        $id_event = $_POST['id_event'];

        if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['id_event'])) {
            return $this->error(400, 'Todos los datos son obligatorios');
        }
        try {

            $eventDB = Events::find($id_event);
            if (empty($eventDB)) {
                return $this->error(400, 'No existe el evento');
            }

            $commentDB = new Comments();
            $commentDB->title = $title;
            $commentDB->description = $description;
            $commentDB->id_event = $id_event;

            date_default_timezone_set('CET');
            $commentDB->date = date('Y-m-d H:i:s');
            $commentDB->id_user = $user->id;

            $commentDB->save();

            return $this->error(200, 'Comentario añadido');


            
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
        $id_comment = $_POST['id_comment'];
        if (empty($_POST['id_comment'])) {
            return $this->error(400, 'Introduce la id del comentario');
        }
        try {
            $commentBD = Comments::find($id_comment);
            if ($commentBD == null) {
                return $this->error(400, 'El comentario no existe');
            }
            $eventDB = Events::find($commentBD->id_event);
            if ($commentBD->id_user == $user->id || $user->id == $eventDB->id_user || $user->id_rol == 1) {
                $commentBD->delete();
                return $this->error(200, 'El comentario ha sido borrado');
            }

            return $this->error(401, 'No autorizado');
        } catch (Exception $e) {
            return $this->error(500, $e->getMessage());
        }
    }

}
