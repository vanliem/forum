<template>
    <div :id="'reply-' + id " class="panel panel-default">
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

        <div class="panel-footer level" v-if="canDelete">
            <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
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
                editing: false
            };
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            ago() {
                return moment(this.data.created_at).fromNow() + '...';
            },

            canDelete() {
                return this.authorize(user => this.data.user_id == user.id);
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                });

                this.editing = ! this.editing;

                flash('Update successfully.');
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);
                
                this.$emit('deleted', this.data.id);
                
                /*$(this.$el).fadeOut(300, () => {
                    flash('Your reply has been deleted successfully.');
                });*/
            }
        }        
    }
</script>
