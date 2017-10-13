<template>
    <div :id="'reply-' + id " class="panel" :class="isBest ? ' panel-success' : ' panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a> said ...
                    <span v-text="ago"></span>
                </h5>

                <div v-if="signedIn">
                    <favourite :reply="reply"></favourite>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="body">
                    <div v-if="editing">
                        <form @submit.prevent="update">
                            <div class="form-group">
                                <textarea v-model="body" class="form-control" required="required"></textarea>    
                            </div>
                            
                            <button class="btn btn-xs btn-primary">Update</button>
                            <button class="btn btn-link btn-xs" @click="editing = false" type="button"> Cancel</button>
                        </form>
                    </div>
                    
                    <div v-else v-html="body"></div>
                </div>
            </div>
        </div>

        <div class="panel-footer level">
            <div v-if="authorize('own', reply)">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-default btn-xs ml-a" @click="markBestReply"
                    v-if="authorize('own', reply)">
                Best Reply?
            </button>
        </div>

    </div>
</template>
<script>
    import Favourite from './Favourite.vue';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: { Favourite },

        data() {
            return {
                id: this.id,
                body: this.reply.body,
                editing: false,
                thread: window.thread,
            };
        },

        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow() + '...';
            },

            isBest() {
                return this.thread.best_reply_id == this.id;
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id, {
                    body: this.body
                })
                .catch(error => {
                    flash(
                        error.response.data ? error.response.data : 'Error',
                        'danger'
                    );
                }).then(({}) => {
                    this.editing = !this.editing;

                    flash('Update successfully.');
                });

            },

            destroy() {
                axios.delete('/replies/' + this.id);
                
                this.$emit('deleted', this.id);
            },

            markBestReply() {
                axios.post('/replies/' + this.id + '/best');

                this.thread.best_reply_id = this.id;
            }
        }        
    }
</script>
