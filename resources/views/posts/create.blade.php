@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
      <div class="col">
        <div class="jumbotron">
            <h1 class="display-4">Create post</h1>
            <hr class="my-4">
            <a class="btn btn-primary btn-lg" href="{{route('posts')}}" role="button">View all posts</a>
        </div>
      </div>
    </div>
    <div class="row">

        @if (count($errors) > 0)
        <ul>
            @foreach ($errors->all() as $item)
                <li>
                    {{$item}}
                </li>
            @endforeach
        </ul>
        @endif
    </div>
    <div class="row">
      <div class="col">
        <form action="{{route('post.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="exampleFormControlInput1">Title</label>
              <input type="text" class="form-control" name="title" >
            </div>
            <div class="form-group">
              <label for="exampleFormControlTextarea1">Content</label>
              <textarea class="form-control" name="content" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Photo</label><br>
                <input type="file"  name="photo">
              </div>
<br>
            <div class="form-group">
                <button class="btn btn-success" type="submit">Save</button>
              </div>

          </form>
      </div>
    </div>
  </div>

@endsection
