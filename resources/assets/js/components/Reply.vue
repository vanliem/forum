<script>
    export default {
        props: ['attributes'],

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
            }
        }        
    }
</script>
