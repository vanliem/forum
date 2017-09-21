<script>
    import Favourite from './Favourite.vue';

    export default {
        props: ['attributes'],

        components: { Favourite },

        data() {
            return {
                body: this.attributes.body,
                editing: false
            };
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });

                this.editing = ! this.editing;

                flash('Update successfully.');
            },

            destroy() {
                axios.delete('/replies/' + this.attributes.id);
                
                $(this.$el).fadeOut(300, () => {
                    flash('Your reply has been deleted successfully.');
                });
            }
        }        
    }
</script>
