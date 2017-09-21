<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="/profiles/{{ $reply->owner->name }}">
                        {{ $reply->owner->name }}
                    </a> said ...
                    {{ $reply->created_at->diffForHumans() }}
                </h5>

                <div>
                    <favourite :reply="{{ $reply }}"></favourite>
                    <!-- <form action="/replies/{{ $reply->id }}/favourites" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default" {{ $reply->isFavourited() ? 'disabled' : '' }}>
                            {{ $reply->favourites_count }}
                            {{ str_plural('Favourite', $reply->favourites_count) }}
                        </button>
                    </form> -->
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="body">
                    <div v-if="editing">
                        <div class="form-group">
                            <textarea v-model="body" class="form-control"></textarea>    
                        </div>
                        
                        <button class="btn btn-xs btn-primary" @click="update()">Update</button>
                        <button class="btn btn-link btn-xs" @click="editing = false"> Cancel</button>
                    </div>
                    
                    <div v-else v-text="body"></div>
                </div>
            </div>
        </div>
        @can('delete', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
            </div>
        @endif
    </div>
</reply>

