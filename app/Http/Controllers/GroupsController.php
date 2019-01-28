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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $name = $_POST['name'];

        if (empty($_POST['name'])) {
            return $this->error(400, 'Introduce el nombre del grupo');
        }
        try {

            $groups = Groups::where('name', $name)->get();
            foreach ($groups as $group) {
                if ($group->name == $name) {
                    return $this->error(400, 'El nombre del grupo ya existe');
                }
            }

            $groups = new Groups();
            $groups->name = $name;
            $groups->save();

            
            // $belong = new Belong();
            // $belong->id_user = 1;
            // $belong->id_group = $groups->id;

            // $belong->save();

            return $this->error(200, 'Grupo creado');
            
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
            return $this->error(400, 'Introduce la id del grupo');
        }
        try {
            $groupBD = Groups::find($id);
            if ($groupBD == null) {
                return $this->error(400, 'El grupo no existe');
            }

            $groupBD->delete();

            return $this->error(200, 'Grupo borrado');
        } catch (Exception $e) {
            return $this->error(500, $e->getMessage());
        }
    }
    public function get_groups()
    {
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        if ($token == null) {
            return $this->error(401, 'El token no es válido');
        }
        $groups = Groups::whereNotNull('id')->get();
        $groupNames = [];
        $groupIds = [];
        foreach ($groups as $group) {
            array_push($groupNames, $group->name);
            array_push($groupIds, $group->id);
        }
        return response()->json([
            'grupos' => $groupNames,
            'ids' => $groupIds,
        ]);
    } 
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function show(Groups $groups)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function edit(Groups $groups)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Groups $groups)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function destroy(Groups $groups)
    {
        //
    }
}
