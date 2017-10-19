<script>
	import Replies from '../components/Replies.vue';
	import SubscribeButton from '../components/SubscribeButton.vue';

	export default {
		props: ['thread'],

		components: {
			Replies, SubscribeButton
		},

		data() {
			return {
				repliesCount: this.thread.replies_count,
                locked: this.thread.locked
			};
		},
        methods: {
            toggleLock() {
                let httpMethod = this.locked ? 'delete' : 'post';
                axios[httpMethod]('/lock-thread/' + this.thread.slug);

                this.locked = ! this.locked;
            }
        }
	}
</script>