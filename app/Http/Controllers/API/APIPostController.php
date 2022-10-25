<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Post as PostResource;


class APIPostController extends BaseController
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $posts = Post::orderBy('created_at' , 'DESC')->get();
        return $this->sendResponse(PostResource::collection($posts),
           'All Posts');
    }

    public function trashedPosts()
    {
        $trashed_posts = Post::onlyTrashed()->where('user_id',Auth::id())->get();
        return $this->sendResponse(PostResource::collection($trashed_posts),
           'All Trashed Posts');
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
        $id = $post->id;
        $post = Post::where('id',$id)->get();
        return $this->sendResponse(PostResource::collection($post),
           'Post Created Successfully');
    }

    public function show($post_id)
    {
        // $post = Post::where('id',$post_id)->first();
        $post = Post::where('id',$post_id)->first();
        if ($post === null) {
            return $this->sendError('No Post found has the given id');
        }
        $post = Post::where('id',$post_id)->get();
        return $this->sendResponse(PostResource::collection($post),
           'Post retrieved Successfully');
    }

    // public function edit($post_id)
    // {
    //    // $post = Post::find($id);
    //     $post = Post::where('id',$post_id)->where('user_id',Auth::id())->first();
    //     if ($post === null) {
    //         return redirect()->back();
    //     }
    //     return view('posts.edit',$post);
    // }

    public function update(Request $request, $post_id)
    {
        $post = Post::where('id',$post_id)->where('user_id',Auth::id())->first();
        if ($post === null) {
            return $this->sendError('You are not Authorized to Edit this Post');
        }

        // $post = Post::find($post_id);
        $this->validate($request,[
            'title' => 'required',
            'content' => 'required',
            //'photo' => 'image'
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
        $post->save();// $post->save;

        $post = Post::where('id',$post_id)->where('user_id',Auth::id())->get();

        return $this->sendResponse(PostResource::collection($post),
           'Post Updated Successfully');
    }

    public function destroy($post_id)
    {
       // $post = Post::find($post_id);
        $post = Post::where('id',$post_id)->where('user_id',Auth::id())->first();
        if ($post === null) {
            return $this->sendError('Post Not found');
        }

        $post->delete();

        $post = Post::where('id',$post_id)->where('user_id',Auth::id())->get();
        return $this->sendResponse(PostResource::collection($post),
           'Post Moved to trash');
    }

    public function hdelete($post_id)
    {
        $post = Post::withTrashed()->where('id',$post_id)->where('user_id',Auth::id())->first();
        if ($post === null) {
            return $this->sendError('Post Not found');
        }

        Post::withTrashed()->where('id' , $post_id)
            ->where('user_id',Auth::id())
                ->first()->forceDelete();

        $post = Post::withTrashed()->where('id' , $post_id)->where('user_id',Auth::id())->get();
        return $this->sendResponse(PostResource::collection($post),
           'Post Deleted Successfully');
    }

    public function restore($post_id)
    {
        $post = Post::withTrashed()->where('id',$post_id)->where('user_id',Auth::id())->first();
        if ($post === null) {
            return $this->sendError('Post Not found');
        }

        Post::withTrashed()->where('id' , $post_id)
            ->where('user_id',Auth::id())
                ->first()->restore();

        $post = Post::withTrashed()->where('id' , $post_id)->where('user_id',Auth::id())->get();

        return $this->sendResponse(PostResource::collection($post),
            'Post Restored Successfully');
    }


    public function isliked($post_id)
    {
        return Post::isliked($post_id);
    }


    public function like($post_id)
    {
        $user_id = Auth::id();

        if(! $this->isliked($post_id) )
        {
            $post = Post::find($post_id);
            $l = $post->likes + 1;
            $post->likes = $l;
            $post->save();

            DB::insert('insert into likes (user_id, post_id) values (?, ?)', [$user_id, $post_id]);

            return "Liked";
        }

       return "Already Liked";
    }

    public function dislike($post_id)
    {
        $user_id = Auth::id();
        if($this->isliked($post_id) ){
            $post = Post::find($post_id);

            $l = $post->likes - 1;
            $post->likes = $l;
            $post->save();
            DB::table('likes')->where('user_id', $user_id)->where('post_id', $post_id)->delete();

            return "Disliked";
        }

        return "Not Liked";
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
