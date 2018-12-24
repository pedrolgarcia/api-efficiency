<?php

namespace App\Http\Controllers;
use App\User;
use App\Http\Requests;
use App\Http\Resources\User as UserResource;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Contracts\JWTSubject;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
