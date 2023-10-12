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
            $url = $user->profile_pic->file_directory;
            $url_res = User::s3Url($url);
            $user->profile_pic->file_directory = $url_res;
        }


        $data = [
            'user' => $user,
        ];
        return view('home', $data);
    }

    
}
