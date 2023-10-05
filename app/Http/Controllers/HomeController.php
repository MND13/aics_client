<?php

namespace App\Http\Controllers;

use App\Models\ProfileDocuments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::with([
            'roles',
        ])->findOrFail(Auth::user()->id);


        $user->profile_pic = ProfileDocuments::select("file_directory")
            ->where("user_id", "=", Auth::user()->id)->where("name", "=", "client_photo")->first();
        $user->valid_id = ProfileDocuments::select("file_directory")
            ->where("user_id", "=", Auth::user()->id)->where("name", "=", "valid_id")->first();


        if ($user->valid_id) {

            # $url =  str_replace("/storage/", "public/", $user->profile_pic->file_directory);
            $url = $user->profile_pic->file_directory;
            $url_res = User::s3Url($url);
            $user->profile_pic->file_directory = $url_res;
        }

        # $mimtype  = Storage::disk("s3")->mimeType("public/uploads/2023/10/10db9e53-2fe4-4b53-8f97-67ceaed08ab5/jnlr19J80yptYAWw51Miwr3LmirAbLMQ827mlO63.png");
        # $foo  = Storage::disk("s3")->get("public/uploads/2023/10/10db9e53-2fe4-4b53-8f97-67ceaed08ab5/jnlr19J80yptYAWw51Miwr3LmirAbLMQ827mlO63.png");
        #  $user->foo = s3Url:: "data:".$mimtype.";base64,".base64_encode($foo);


        # $user->foo  = base64_encode( $user->foo);
        #dd($user->foo);

        $data = [
            'user' => $user,
        ];
        return view('home', $data);
    }

    
}
