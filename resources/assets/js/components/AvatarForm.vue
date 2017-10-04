<template>
    <div>
        <div class="level">
            <img :src="avatar" alt="" width="50" height="50" class="mr-1"/>
            <h1 v-text="user.name"></h1>
        </div>

        <form method="POST" enctype="multipart/form-data" v-if="canUpdate">
            <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
        </form>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';

    export default {
        props: ['user'],

        components: { ImageUpload },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },

            persist(avatar) {
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(
                    `/api/users/${this.user.name}/avatar`,
                    data
                ).then(() => {
                    flash('Flash uploaded!');
                });
            }
        }
    }
</script>