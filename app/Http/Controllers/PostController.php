<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Services\SlugService;


class PostController extends Controller
{
    public $pp = 0;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::orderBy('created_at' , 'DESC')->get();
        return view('posts.index')->with('posts',$posts);
    }

    public function trashedPosts()
    {
        $trashed_posts = Post::onlyTrashed()->where('user_id',Auth::id())->get();
        return view('posts.trashed')->with('trashed_posts',$trashed_posts);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
            //'photo' => 'required|image'
        ]);

       // $photo = $request->photo;
       // $newPhoto = time().$photo->getClientOriginalName();
       // $photo->move('uploads/posts',$newPhoto);

        $newPhoto = null;
        if($request->has('photo'))
        {
            $photo = $request->photo;
            $newPhoto = time().$photo->getClientOriginalName();
            $photo->move('uploads/posts',$newPhoto);
            //$post->photo = 'uploads/posts/'.$newPhoto;
        }

        $photoStore = $newPhoto == null ? null : 'uploads/posts/'.$newPhoto;

        $post = Post::create([
            'user_id' => Auth::id(),

            'title' => $request->title,
            'content' => $request->content,
            'photo' => $photoStore,
            //'slug' => Str::slug($request->title,'-','en')
             'slug' => SlugService::createSlug('App\Models\Post', 'slug', $request->title)

        ]);
        return redirect()->back();
    }

    public function show($slug)
    {
        $post = Post::where('slug',$slug)->first();
        return view('posts.show')->with('post', $post);
    }

    public function edit($post_id)
    {
       // $post = Post::find($id);
        $post = Post::where('id',$post_id)->where('user_id',Auth::id())->first();
        if ($post === null) {
            return redirect()->back();
        }
        return view('posts.edit',$post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
            'photo' => 'required|image'
        ]);

        if($request->has('photo'))
        {
            $photo = $request->photo;
            $newPhoto = time().$photo->getClientOriginalName();
            $photo->move('uploads/posts',$newPhoto);
            $post->photo = 'uploads/posts/'.$newPhoto;
        }

        $post->title = $request->title;
        $post->title = Str::slug($request->title);
        $post->content = $request->content;
        $post->save;

        return redirect()->back();
    }

    public function destroy($post_id)
    {
       // $post = Post::find($post_id);
        $post = Post::where('id',$post_id)->where('user_id',Auth::id())->first();
        if ($post === null) {
            return redirect()->back();
        }

        $post->delete();

        return redirect()->back();
    }

    public function hdelete($post_id)
    {
        Post::withTrashed()->where('id' , $post_id)
            ->where('user_id',Auth::id())
                ->first()->forceDelete();

        return redirect()->back();
    }

    public function restore($post_id)
    {
        Post::withTrashed()->where('id' , $post_id)
            ->where('user_id',Auth::id())
                ->first()->restore();

        return redirect()->back();
    }


    public function isliked($post_id)
    {
    //     $user_id = Auth::id();
    //     $p = DB::table('likes')
    //         ->select('user_id', 'post_id')
    //         ->where('user_id', '=', $user_id)
    //         ->where('post_id', '=', $post_id)
    //         ->first();

    //    return ($p != null);
        return Post::isliked($post_id);
    }


    public function like($post_id)
    {
        $user_id = Auth::id();

        $post = Post::find($post_id);
        $l = $post->likes + 1;
        $post->likes = $l;
        $post->save();
        //$post->user()->attach();
       // DB::table('likes')->insert();
        DB::insert('insert into likes (user_id, post_id) values (?, ?)', [$user_id, $post_id]);
        //$a = "ddd";
       // return $post->likes;
       //$s = 'number of likes is: ' . $l;

       return $l;
    }

    public function dislike($post_id)
    {
        $user_id = Auth::id();
        $post = Post::find($post_id);

        $l = $post->likes - 1;
        $post->likes = $l;
        $post->save();
        DB::table('likes')->where('user_id', $user_id)->where('post_id', $post_id)->delete();
        //$a = "ddd";
       // return $post->likes;
       //$s = 'number of likes is: ' . $l;
       return $l;
    }

    public function likenames($post_id)
    {

        $names = array();
        $users = DB::table('likes')->where('post_id', $post_id)->pluck('user_id');
        foreach ($users as $user_id ) {
            $user_name = DB::table('users')->where('id', $user_id)->value('name');
            array_push($names,$user_name);
        }
        return $names ;
    }
}
