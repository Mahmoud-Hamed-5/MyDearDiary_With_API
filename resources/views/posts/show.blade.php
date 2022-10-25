@extends('layouts.app')

@section('content')
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="js\myjs.js"></script>
</head>

<div class="container" >
    <a href="{{route('posts')}}">All posts</a>
    <div class="row">
        <div class="col-md-8">

              @php
                $c = 1;
                $counter_id = "likes_counter" . $c;

                $b = 1;
                $button_id = "like_button" . $b;

                $n = 1;
                $show_names_id = "likes_names" . $n;
              @endphp

            <div class="post-content">
              <div class="post-container">
                <div class="post-detail">
                  <div class="user-info">
                    <h5>
                        @if(($post->user->profile != null) && ($post->user->profile->profile_picture != null))
                            <img src="{{URL::asset($post->user->profile->profile_picture)}}" alt="img"
                                class="profile-photo-md pull-left">
                        @endif

                        <a href="timeline.html" class="profile-link">{{$post->user->name}}</a>

                        {{-- Span Just for testing --}}
                         {{-- <span class="following"
                         onmouseover="show2({{$post->id}} , {{$n}})"
                         onmouseout="hide({{$n}})">
                         show</span> --}}

                    </h5>
                    <p class="text-muted">Published: {{$post->created_at->diffForhumans()}}</p>
                  </div>

                  <div class="reaction">
                    @if ($post->user_id == Auth::id())
                      <a href="{{route('post.edit' , ['id' => $post->id ])}}">
                            <i class="fa-solid fa-2x fa-pen-to-square"></i>
                      </a>
                        &nbsp;  &nbsp;
                        <a class="text-danger" href="{{route('post.destroy' , ['id'=> $post->id])}}">
                            <i class="fa-solid fa-2x fa-trash-can"></i>
                        </a>
                    @endif
                  </div>

                    <br>
                    <div  class="names">
                        <p id={{$show_names_id}}></p>
                    </div>

                  <div class="line-divider"></div>
                    <div class="post-title">
                        <p> {{$post->title}} <i class="em em-anguished"></i>
                            <i class="em em-anguished"></i> <i class="em em-anguished"></i></p>
                    </div>
                    <div class="post-text">
                        <p> {{$post->content}} <i class="em em-anguished"></i>
                            <i class="em em-anguished"></i> <i class="em em-anguished"></i></p>
                    </div>
                  <div class="line-divider"></div>

                  @if ($post->photo != null)
                    <div class="post-photo">
                        <img src="{{URL::asset($post->photo)}}" alt="{{$post->photo}}"
                            class="img-tumbnail" width="300" height="300">
                    </div>
                    <div class="line-divider"></div>
                  @endif

                  <div style="margin-top: 10px" >
                      <span class="text-info" style="cursor: pointer"
                         {{-- onclick="show2({{$post->id}} , {{$n}})" --}}
                         onclick="showLikes()"
                         onmouseout="hide({{$n}})">
                        <i id={{$counter_id}} class="fa fa-thumbs-up"> {{$post->likes}}</i>
                      </span>
                  </div>


<div style="margin-top: 10px">
                  <button type="button"
                            onclick="like({{$post->id}} , {{$c}} , {{$b}})">
                            @if ($post->isliked($post->id))
                                <span class="text-warning">
                                <i id={{$button_id}} class="fa-regular fa-2x fa-thumbs-down"></i>
                                </span>
                            @else
                                <span class="text-success">
                                <i id={{$button_id}} class="fa-regular fa-2x fa-thumbs-up"></i>
                                </span>
                            @endif
                    </button>
</div>

<hr>

<h4>Comments</h4>

@include('posts.comments' , ['comments' => $post->comments , 'post_id' => $post->id])



<form action="{{route('comments.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <textarea type="text" class="form-control" name="content" > </textarea>
      <input type="hidden" name="post_id" value="{{$post->id}}">

    </div>
    <div class="form-group">
        <button class="btn btn-success" type="submit">Add comment</button>
    </div>
</form>


</div>


                </div>
              </div>

            </div>
        </div>
    </div>
</div>


@endsection
