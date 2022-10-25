<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $id = Auth::id();

        if ($user->profile == null) {
            $profile = Profile::create([
                'user_id' => $id,
                //'city' => 'City',
                //'gender' => 'Male',
                //'profile_picture' => 'nom',
                //'bio' => ' '
            ]);
        }

        return view('users.profile')->with('user',$user);
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
        $user = Auth::user();
        $id = Auth::id();

        $user->name = $request->name;
        Profile::create([
            'user_id' => $id,
            'city' => ($request->has('city')) ? $request->city:'',
            'gender' => ($request->has('gender')) ? $request->gender:'',
            'bio' => ($request->has('bio')) ? $request->bio:'',
        ]);

        // ($request->has('city')) ? $request->city:'';

        if($request->has('profile_picture'))
        {
            $photo = $request->profile_picture;
            $newPhoto = time().$photo->getClientOriginalName();
            $photo->move('uploads/profiles',$newPhoto);
            $user->profile->profile_picture = 'uploads/profiles/'.$newPhoto;
        }

        $user->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->profile->city = $request->city;
        $user->profile->gender = $request->gender;
        $user->profile->bio = $request->bio;

        if($request->has('profile_picture'))
        {
            $photo = $request->profile_picture;
            $newPhoto = time().$photo->getClientOriginalName();
            $photo->move('uploads/profiles',$newPhoto);
            $user->profile->profile_picture = 'uploads/profiles/'.$newPhoto;
        }

        $user->profile->save();

        $user->save();

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
