<?php

namespace App\Http\Controllers;

use App\Events;
use App\Groups;
use App\Comments;
use App\Users;
use App\Roles;
use App\Types;
use App\Privacity;
use App\Belong;
use App\Asign;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;

class EventsController extends Controller
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
        $title = $_POST['title'];
        $description = $_POST['description'];
        $array_id_group = $_POST['id_group'];
        $id_type = $_POST['id_type'];

        if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['id_group']) || empty($_POST['id_type'])) {
            return $this->createResponse(400, 'Todos los datos son obligatorios');
        }
        try {

            $eventDB = new Events();
            $eventDB->title = $title;
            $eventDB->description = $description;

            $typeDB = Types::find($id_type);
            if (empty($typeDB)) {
                return $this->createResponse(400, 'No existe el tipo de evento indicado');
            }

            $eventDB->id_type = $id_type;

            if (!empty($_POST['lat'])) {
                $eventDB->lat = $_POST['lat'];
            }
            if (!empty($_POST['lon'])) {
                $eventDB->lon = $_POST['lon'];
            }
            if (!empty($_POST['url'])) {
                $eventDB->url = $_POST['url'];
            }

            date_default_timezone_set('CET');
            $eventDB->date = date('Y-m-d H:i:s');
            $eventDB->image = 2;




            $eventDB->id_user = $user->id;
            $eventDB->save();
            foreach ($array_id_group as $key => $idGroup) {

                $groupDB = Groups::find($idGroup);
                if (empty($groupDB)) {
                    $eventDB->delete();
                    return $this->createResponse(400, 'No existe el tipo de grupo indicado');
                }
                $asignDB = new Asign();
                $asignDB->id_event = $eventDB->id;
                $asignDB->id_group = $idGroup;
                $asignDB->save();
            }


            return $this->createResponse(200, 'Evento creado');


            
        } catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());
        }
    }
    
    public function post_update()
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
            return $this->createResponse(400, 'Falta el parámetro id');
        }
        try {
            $eventBD = Events::find($id);
            if ($eventBD == null) {
                return $this->createResponse(400, 'El evento no existe');
            }
            if (!empty($_POST['title']) ) {
                $eventBD->title = $_POST['title'];
            }
            if (!empty($_POST['description']) ) {
                $eventBD->description = $_POST['description'];
            }
            if (!empty($_POST['lat']) ) {
                $eventBD->lat = $_POST['lat'];
            }
            if (!empty($_POST['lon']) ) {
                $eventBD->lon = $_POST['lon'];
            }
            if (!empty($_POST['url']) ) {
                $eventBD->url = $_POST['url'];
            }


            $eventBD->save();
            return $this->createResponse(200, 'Evento actualizado');

            
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
            return $this->createResponse(400, 'Introduce la id del evento');
        }
        try {
            $eventBD = Events::find($id);
            if ($eventBD == null) {
                return $this->createResponse(400, 'El evento no existe');
            }
            if ($eventBD->id_user == $user->id || $user->id_rol == 1) {
                $eventBD->delete();
                return $this->createResponse(200, 'El evento ha sido borrado');
            }
            return $this->createResponse(401, 'No autorizado');
        
        } catch (Exception $e) {
            return $this->createResponse(500, $e->getMessage());
        }
    }


    public function get_events()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;

        if (!isset($_GET['type'])) {

           // return $this->createResponse(400, 'El parámetro type es obligatorio (0 -> todos, 1 -> eventos, 2 -> ofertas trabajo, 3 -> notificaciones, 4 -> noticias)');

        return $this->createResponse(400, 'El parámetro type es obligatorio (0 -> todos, 1 -> eventos, 2 -> ofertas trabajo, 3 -> notificaciones, 4 -> noticias)');

        }
        $type = $_GET['type'];
        if ($type == 0) {
            // $events = Events::whereNotNull('id')->get();
            $events = Events::all();
            $eventTitles = [];
            $eventDescriptions = [];
            foreach ($events as $event) {
                array_push($eventTitles, $event->title);
                array_push($eventDescriptions, $event->description);
        }

        //     return response()->json([
        //         'eventos' => $eventTitles,9
        //         'hola' => $eventDescriptions,
        // ]);
        return $this->createResponse(200, 'Listado de eventos', $events);

        }
        if ($type == 1) {
            $events = Events::where('id_type', 1)->get();
            $eventTitles = [];
            $eventDescriptions = [];
            foreach ($events as $event) {
                array_push($eventTitles, $event->title);
                array_push($eventDescriptions, $event->description);
        }
        //     return response()->json([
        //         'eventos' => $eventTitles,
        //         'descripción' => $eventDescriptions,
        // ]);
        return $this->createResponse(200, 'Eventos', $events);
        }
        if ($type == 2) {
            $events = Events::where('id_type', 2)->get();
            $eventTitles = [];
            $eventDescriptions = [];
            foreach ($events as $event) {
                array_push($eventTitles, $event->title);
                array_push($eventDescriptions, $event->description);
        }
        //     return response()->json([
        //         'eventos' => $eventTitles,
        //         'descripción' => $eventDescriptions,
        // ]);
        return $this->createResponse(200, 'Ofertas de trabajo', $events);
        }
        if ($type == 3) {
            $events = Events::where('id_type', 3)->get();
            $eventTitles = [];
            $eventDescriptions = [];
            foreach ($events as $event) {
                array_push($eventTitles, $event->title);
                array_push($eventDescriptions, $event->description);
        }
        //     return response()->json([
        //         'eventos' => $eventTitles,
        //         'descripción' => $eventDescriptions,
        // ]);
        return $this->createResponse(200, 'Notificaciones', $events);
        }
        if ($type == 4) {
            $events = Events::where('id_type', 4)->get();
            $eventTitles = [];
            $eventDescriptions = [];
            foreach ($events as $event) {
                array_push($eventTitles, $event->title);
                array_push($eventDescriptions, $event->description);
        }
        //     return response()->json([
        //         'eventos' => $eventTitles,
        //         'descripción' => $eventDescriptions,
        // ]);
                    return $this->createResponse(200, 'Eventos', $events);
        }
    } 


        public function get_eventsPanel()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        if ($token == null) {
            return $this->createResponse(401, 'El token no es válido');
        }

        $events = Events::all();
        return $this->createResponse(200, 'Eventos', array('eventos' => $events));
    }
    public function get_eventsAndroid()
        {
            $headers = getallheaders();
            $token = $headers['Authorization'];
            $key = $this->key;

            if (!isset($_GET['type'])) {


            return $this->createResponse(400, 'El parámetro type es obligatorio (0 -> todos, 1 -> eventos, 2 -> ofertas trabajo, 3 -> notificaciones, 4 -> noticias)');

            }
            $type = $_GET['type'];
            if ($type == 0) {
                $events = Events::all();
                $eventObject = [];
                    foreach ($events as $event) {
                    array_push($eventObject, $event);
            }


            return $this->createResponse(200, 'Listado de eventos', ['event' => $eventObject]);

            }

        } 


    public function get_event()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;

        if (!isset($_GET['id'])) {


             return $this->createResponse(400, 'el Parametro id es obligatorio');

            //return $this->createResponse(400, 'El parámetro id es obligatorio');
        }
        $id = $_GET['id'];
        try {
            $event = Events::find($id);
            if ($event == null) {
                return $this->createResponse(400, 'No existe el evento');
                //return $this->createResponse(400, 'No existe el evento');
            }
            $commentsBD = Comments::where('id_event', $id)->get()->all();




            foreach ($commentsBD as $key => $comment) {
                $userBD = Users::find($comment->id_user);
                $comment['username'] = $userBD->username;
                $comment['id_user'] = $userBD->id;
                $comment['photo'] = $userBD->photo;
            }

        //     return response()->json([
        //         'eventos' => $event,
        //         'comentarios' => $commentsBD,
        // ]);
        // $commentsReverse = array_reverse($arrayComments);

        return $this->createResponse(200, 'Evento y comentarios', array('event' => $event, 'comments' => Controller::reindex(array_reverse($commentsBD))));
            
        } catch (Exception $e) {
            
            return $this->createResponse(500, $e->getMessage());
        }
    }


    
    public function  get_find()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));
        $id = Users::where('email', $userData->email)->first()->id;
        if (empty($_GET['search'])) 
        {
          return $this->createResponse(400, 'Falta parámetro obligatorio (search)');
        }
        $search = $_GET['search'];
        if (!isset($_GET['type']) )
        {
          return $this->createResponse(400, 'Faltan parámetros obligatorios (type, 0 -> todos, 1-> eventos, 2-> ofertas trabajo, 3 -> notificaciones, 4 -> noticias)');
        }

        $type = $_GET['type'];

        if ($type == 0) {
            $events = Events::where('title', 'like', $search)->get();
            if (count($events) == 0)
            {
                return $this->createResponse(400, 'No existen eventos');
            }
            $eventTitles = [];
            $eventDescriptions = [];
            foreach ($events as $event) {
                array_push($eventTitles, $event->title);
                array_push($eventDescriptions, $event->description);
            }
        //     return response()->json([
        //         'eventos' => $eventTitles,
        //         'descripción' => $eventDescriptions,
        // ]);
            return $this->createResponse(200, 'Evento', $events);
        }
        else
        {
            $typeDB = Types::find($type);
                if ($typeDB == null) {
                    return $this->createResponse(400, 'Parametro type no valido');
                }
                $events = Events::where('title', 'like', $search)
                                ->where('id_type', $type)
                                ->get();
                if (count($events) == 0)
                {
                    return $this->createResponse(400, 'No existen eventos');
                }
                $eventTitles = [];
                $eventDescriptions = [];
                foreach ($events as $event) {
                    array_push($eventTitles, $event->title);
                    array_push($eventDescriptions, $event->description);
            }
                // return response()->json([
                //     'eventos' => $eventTitles,
                //     'descripción' => $eventDescriptions,
                // ]);
                return $this->createResponse(200, 'Evento', $events);
        }
    }

    public function get_types()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        try {

            $typesBD = Types::all();

            if ($typesBD == null) 
            {
                return $this->createResponse(400, 'No existe ningun tipo');
            }
            $typeNames = [];
            foreach ($typesBD as $type) {
                array_push($typeNames, $type->name);
            }
                // return response()->json([
                //     'tipos' => $typeNames,
                // ]);
            return $this->createResponse(200, 'Listado de tipos', $typesBD);

        } catch (Exception $e) {

            return $this->createResponse(500, $e->getMessage());
            
        }
    }

    public function get_comments()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        $userData = JWT::decode($token, $key, array('HS256'));

        if (empty($_GET['id_event'])) 
        {
          return $this->createResponse(400, 'Falta parámetros obligatorios (id_event) ');
        }
        $id_event= $_GET['id_event'];

        try {
            $eventDB = Events::find($id_event);
            if (empty($eventDB)) {
                return $this->createResponse(400, "No existe el evento");
            }

            $commentsDB = Comments::where('id_event', $id_event)
                                    ->get();
            if (count($commentsDB) == 0) {
                return $this->createResponse(400, "No existen comentarios");
            }

            // $users = [];
            // $userNames = [];
            // $photos = [];
            // foreach ($commentsDB as $key => $comment) {
            //     $userBD = Users::find($comment->id_user);
            //     $comment['username'] = $userBD->username;
            //     $comment['id_user'] = $userBD->id;
            //     $comment['photo'] = $userBD->photo;
            //     array_push($users, $comment->id_user);
            //     array_push($userNames, $userBD->username);
            //     array_push($photos, $userBD->photo);
            // }
            //     return response()->json([
            //         'ids' => $users,
            //         'usernames' => $userNames,
            //         'photos' => $photos
            //         ]);

            return $this->createResponse(200, "Listado de comentarios", $commentsDB);
        } catch (Exception $e) {
            
        }
    }


public function curl_calendar()
    {
        $data = "{}";
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://script.google.com/macros/s/AKfycbw5ZFBZPJTSfldVIICOdY_K5YhsKEhPSN-H_Hm8x8M8JzARYkbm/exec",
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $decode = json_decode($response, true);
        

        return $this->createResponse(200, 'Calendario', $decode['calendario']);
    } 

}





   



    //         $commentsBD = Model_Comments::find('all', array(
    //         'where' => array(
    //             array('id_event',$id_event)
    //             )
    //         )); 
    //         if (empty($commentsBD)) {
    //             return $this->createResponse(400, "No existen comentarios");
    //         }
    //         foreach ($commentsBD as $key => $comment) {
    //             $userBD = Model_Users::find($comment->id_user);
    //             $comment['username'] = $userBD->username;
    //             $comment['id_user'] = $userBD->id;
    //             $comment['photo'] = $userBD->photo;
    //         }
    //         return $this->createResponse(200, "Listado de comentarios", Arr::reindex($commentsBD));

    //     } 
    //     catch (Exception $e) 
    //     {
    //         return $this->createResponse(500, $e->getMessage());
    //     }
    // }
