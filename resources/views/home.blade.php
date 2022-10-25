@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('My Dear Diary') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        Welcome,
                    @if ((Auth::user() != null))
                                @if (Auth::user()->profile != null)
                                    <a href="{{route('profile')}}"> {{Auth::user()->name}} </a>
                                @endif

                    @endif
                    <hr>
                    <div>
                        <a class="btn btn-primary" role="button" href="{{route('posts')}}">View all posts</a>
                    </div>
<hr>
                    {{-- <div>
                        <p id="pa"> {{Auth::user()->count}} </p>
                        <button type="button" onclick="change1()">Try it</button>
                    </div> --}}


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
