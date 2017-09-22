@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <span class="flex">
                                    <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> posted:
                                    {{ $thread->title }}
                                </span>
                                @can('update', $thread)
                                    <form action="{{ $thread->path() }}" method="POST" accept-charset="utf-8">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-link">Delete Thread</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    
                        <div class="panel-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                    
                    <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>

                    @if(auth()->check())
                        <form action="{{ $thread->path() . '/replies' }}" method="POST" accept-charset="utf-8">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <textarea name="body" id="body" class="form-control" placeholder="Have somethong to say ?" rows="5"></textarea>
                            </div>
                            <button type="submit" class="btn btn-info">Post</button>
                        </form>
                    @else
                        <p class="text-center">Please <a href="{{ route('login') }}" title="login">login</a> to participate in this dicussion</p>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>
                                This thread was publish {{ $thread->created_at->diffForHumans() }} by
                                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a>, 
                                and currently has <span v-text="repliesCount"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
