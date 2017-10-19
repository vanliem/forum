@extends('layouts.app')

@section('header')
    <link href="/css/vendor/jquery.atwho.css" rel="stylesheet">
    <script>
        window.thread = <?= json_encode($thread);?>
    </script>
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <img src="{{ $thread->creator->avatar_path }}"
                                     alt="{{ $thread->creator->name }}"
                                     height="25" width="25"
                                     class="mr-1"
                                />

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
                    
                    <replies :data="{{ $thread->replies }}" 
                        @removed="repliesCount--"
                        @added="repliesCount++">
                    </replies>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>
                                This thread was publish {{ $thread->created_at->diffForHumans() }} by
                                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a>, 
                                and currently has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}
                            </p>

                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>

                            <button class="btn btn-default"
                                v-if="authorize('isAdmin')"
                                @click="toggleLock"
                                v-text="locked ? 'Unlock' : 'Lock'">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
