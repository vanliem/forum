@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @forelse($threads as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <article>
                            <div class="level">
                                <h4 class="flex">
                                    <a href="{{ $thread->path() }}">
                                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                            <strong>{{ $thread->title }}</strong>
                                        @else
                                            {{ $thread->title }}
                                        @endif
                                    </a>
                                </h4>
                                <a href="{{ $thread->path() }}">
                                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                                </a>
                            </div>
                        </article>
                    </div>
                    <div class="panel-body">
                        <div class="body">
                            {{ $thread->body }}
                        </div>
                        <hr>
                    </div>
                </div>
                @empty
                    <p>
                        No Thread
                    </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
