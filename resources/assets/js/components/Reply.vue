<template>
    <div :id="'reply-' + id " class="panel" :class="isBest ? ' panel-success' : ' panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a> said ...
                    <span v-text="ago"></span>
                </h5>

                <div v-if="signedIn">
                    <favourite :reply="data"></favourite>
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
            <div v-if="authorize('updateReply', reply)">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
            </div>

            <button class="btn btn-default btn-xs ml-a" @click="markBestReply" v-if="! isBest">Best Reply</button>
        </div>

    </div>
</template>
<script>
    import Favourite from './Favourite.vue';
    import moment from 'moment';

    export default {
        props: ['data'],

        components: { Favourite },

        data() {
            return {
                id: this.data.id,
                body: this.data.body,
                editing: false,
                thread: window.thread,
                reply: this.data
            };
        },

        computed: {
            ago() {
                return moment(this.data.created_at).fromNow() + '...';
            },

            isBest() {
                return this.thread.best_reply_id == this.id;
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
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
                axios.delete('/replies/' + this.data.id);
                
                this.$emit('deleted', this.data.id);
            },

            markBestReply() {
                axios.post('/replies/' + this.data.id + '/best');

                this.thread.best_reply_id = this.id;
            }
        }        
    }
</script>
