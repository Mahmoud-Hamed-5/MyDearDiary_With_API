@extends('layouts.app')

@php
    $genderArray = ['Male' , 'Female' , 'Other'];
@endphp

@section('content')
<div class="container" style="padding-top: 3%">
    @if (count($errors) > 0)
    @foreach ($errors as $item)
    <div class="alert alert-danger" role="alert">
        {{$item}}
      </div>
    @endforeach

    @endif
<form method="POST"
    @if ($user->profile == null)
        action="{{route('profile.store')}}"
    @else
        action="{{route('profile.update')}}"
    @endif
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="exampleFormControlInput1">Name</label>
        <input type="text" class="form-control" name="name" placeholder="enter your city"
            value="{{$user->name}}">
      </div>

    <div class="form-group">
      <label for="exampleFormControlInput1">City</label>
      <input type="text" class="form-control" name="city" placeholder="enter your city"
      @if ($user->profile != null)
        value="{{$user->profile->city}}"
      @endif>

    </div>
    <div class="form-group">
      <label for="exampleFormControlSelect1">Gender</label>
      <select class="form-control" name="gender">
          @foreach ($genderArray as $item)
          <option value="{{$item}}"
          @if ($user->profile != null)
          {{($user->profile->gender == $item) ? 'selected':''}}
          @endif
          >

          {{$item}}</option>
          @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="exampleFormControlTextarea1">Bio</label>
      <textarea class="form-control" name="bio" rows="3"
      @if ($user->profile != null)
          value="{{$user->profile->bio}}"
      @endif

       placeholder="tell us something about you"></textarea>
    </div>

    <div class="form-group">
        <label for="exampleFormControlInput1">Profile picture</label>
        <input type="file" class="form-control"  name="profile_picture">
      </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
  </form>
</div>

@endsection
