

@foreach ($comments as $comment)
@php
    $r1 = $comment->id ;

    $replies_id = "replies_show" . $r1;
    $span_id = "show_span_" . $r1 ;
    $replies_class = "d-block";
    $replies_count = \App\Models\Comment::repliesCount($comment->id);
    $span_title = "Hide " . $replies_count . " replies";

@endphp
    <div
         @if ($comment->parent_id != null)
            style="margin-left:20px"
        @endif>
    <hr>
        <strong>{{$comment->user->name}}</strong>

        @if ($replies_count > 0)
        <span id={{$span_id}} class="replies-control"
            onclick="showHideReplies('{{$replies_id}}' , '{{$span_id}}' , {{$replies_count}})">{{$span_title}}</span>
        @endif

        <p>{{$comment->content}}</p>

        <div id={{$replies_id}} class={{$replies_class}}>
        <form  action="{{route('comments.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="rreplies1" class="d-block">
                <div class="form-group">
                    <textarea type="text" class="form-control" name="content" > </textarea>
                    <input type="hidden" name="post_id" value="{{$post->id}}">
                    <input type="hidden" name="parent_id" value="{{$comment->id}}">
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit">Reply</button>
                </div>
            </div>
        </form>

            @include('posts.comments' , ['comments' => $comment->replies])
        </div>
    </div>

@endforeach


