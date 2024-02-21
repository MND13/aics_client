<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AicsAssistance;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::check() &&  Auth::user()->hasRole(['admin', 'social-worker', 'super-admin'])) {

            $users =  User::with([
                'roles',
                'office',
                'profile_docs'
            ]);


            if (Auth::user()->hasRole(['social-worker'])) {
                $roles = ['user'];

                $users->role($roles);
            }


            return ['users' => $users->get()];
        }
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
    public function store(UserRequest $request)
    {
        if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {

            $user = User::create($request->all());
            $user->assignRole($request->role);

            return $user;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //allow admin
        if (Auth::check()  &&  Auth::user()->hasRole(['super-admin'])) {
            return User::with('profile_docs', 'psgc', 'office', 'roles')->findOrFail($id);
        }

        //allow self
        else if (Auth::check() &&  Auth::id() == $id) {

            return User::with('profile_docs', 'psgc', 'office', 'roles')->findOrFail(Auth::id());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {   

        if (Auth::check()) {
            $user = User::findOrFail($id);
            $user->update($request->all());

            if (Auth::user()->hasRole(['super-admin']) && isset($request->role)) 
            {   $user->syncRoles([]);
                $user->assignRole($request->role);
            }

            return $user;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function photos(Request $request)
    {
        $asst =  AicsAssistance::with("aics_client")
            ->where("uuid", "=", $request->assistance)
            ->firstOrFail();


        if ($asst->aics_client && $asst->aics_client->profile_docs) {

            foreach ($asst->aics_client->profile_docs as $key => $value) {
                $value->file_directory =  User::s3Url($value->file_directory);
            }

            return $asst->aics_client->profile_docs;
        }
    }
}
