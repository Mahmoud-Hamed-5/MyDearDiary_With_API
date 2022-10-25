@extends('layouts.app')

@section('content')
<head>

</head>

<div class="container" >
    <a href="{{route('posts')}}">All posts</a>
    <div class="row">
        <div class="col-md-8">



            @if ($trashed_posts->count() > 0)
                @php
                    $c = 1;
                    $counter_id = "likes_counter" . $c;

                    $n = 1;
                    $show_names_id = "likes_names" . $n;
                @endphp

                @foreach ($trashed_posts as $post)
                    <div class="post-content">
                        <div class="post-container">
                            <div class="post-detail">
                                <div class="user-info">
                                    <h5>
                                        <img src="{{URL::asset($post->user->profile->profile_picture)}}"
                                            alt="d" class="profile-photo-md pull-left">
                                        <a href="timeline.html" class="profile-link">{{$post->user->name}}</a>
                                        <span class="following"
                                            onmouseover="show2({{$post->id}} , {{$n}})"
                                            onmouseout="hide({{$n}})">
                                            show</span>
                                    </h5>
                                    <p class="text-muted">Published: {{$post->created_at->diffForhumans()}}</p>
                                </div>

                                <div class="reaction">
                                    <a class="text-success"
                                        href="{{route('post.restore' , ['id' => $post->id ])}}">
                                        <i class="fa-solid fa-2x fa-arrow-rotate-left"></i></a>
                                    &nbsp;  &nbsp;
                                    <a class="text-danger"
                                        href="{{route('post.hdelete' , ['id'=> $post->id])}}">
                                        <i class="fa-solid fa-2x fa-trash-can"></i> </a>

                                </div>
                                <br>
                                <div  class="names">
                                    <p id={{$show_names_id}}>

                                    </p>

                                </div>

                                <div class="line-divider"></div>
                                <div class="post-title">
                                    <p> {{$post->title}} <i class="em em-anguished"></i>
                                        <i class="em em-anguished"></i> <i class="em em-anguished"></i>
                                    </p>
                                </div>
                                <div class="post-text">
                                    <p> {{$post->content}} <i class="em em-anguished"></i>
                                        <i class="em em-anguished"></i> <i class="em em-anguished"></i>
                                    </p>
                                </div>
                                <div class="line-divider"></div>

                                @if ($post->photo != null)
                                    <div class="post-photo">
                                        <img src="{{URL::asset($post->photo)}}" alt="{{$post->photo}}"
                                            class="img-tumbnail" width="300" height="300">
                                    </div>
                                    <div class="line-divider"></div>
                                @endif

                                {{-- open small modal to show likes --}}
                  <div style="margin-top: 10px" >
                    <a data-toggle="modal" id="smallButton" data-target="#smallModal" title="show likes">
                      <span class="text-info" style="cursor: pointer"
                            onclick="showLikes({{$post->id}})">
                        <i id={{$counter_id}} class="fa fa-thumbs-up"> {{$post->likes}}</i>
                      </span>
                    </a>
                  </div>

               </div>
             </div>
           </div>

                        @php
                            $c = $c+1;
                            $counter_id = 'likes_counter' . $c;

                            $n = $n + 1;
                            $show_names_id = "likes_names" . $n;
                        @endphp
                @endforeach
                    @else
                        <div class="alert alert-danger" role="alert"> No Trashed posts! </div>
                    @endif

            </div>
        </div>
    </div>
</div>

<!-- small modal -->
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Likes</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="smallBody">
            <div>
                <!-- the result to be displayed apply here -->
            </div>
        </div>
    </div>
</div>
</div>


@endsection
