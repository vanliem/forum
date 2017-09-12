@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="#">{{ $thread->creator->name }}</a> posted:
                    {{ $thread->title }}
                </div>

                <div class="panel-body">
                    <h4>{{ $thread->title }}</h4>
                    <div class="body">
                        {{ $thread->body }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @foreach($thread->replies as $reply)
                @include('threads.reply')
            @endforeach
        </div>
    </div>
    @if(auth()->check())
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form action="{{ $thread->path() . '/replies' }}" method="POST" accept-charset="utf-8">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" placeholder="Have somethong to say ?" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-info">Post</button>
                </form>
            </div>
        </div>
    @else
        <p class="text-center">Please <a href="{{ route('login') }}" title="login">login</a> to participate in this dicussion</p>
    @endif
</div>
@endsection
