<template>
	<div>
		<div v-if="signedIn">
			<div class="form-group">
		        <textarea name="body" id="body" 
		        	class="form-control" required="required" 
		        	placeholder="Have something to say ?"
		        	rows="5" v-model="body">
	        	</textarea>
		    </div>
		    <button type="submit" class="btn btn-default" @click="addReply">Post</button>	
		</div>
	    
    	<p class="text-center" v-else>Please 
    		<a href="/login" title="login">login</a>
    		to participate in this dicussion
    	</p>
	</div>
</template>

<script>
	export default {
		computed: {
			signedIn() {
				return window.App.signedIn;
			}
		},

		data() {
			return {
				body: ''
			};
		},

		methods: {
			addReply() {
				axios.post(location.pathname + '/replies', {
					body: this.body
				})
				.then(({data}) => {
					this.body = '';

					flash('Your reply has been posted.');

					this.$emit('created', data);
				});
			}
		}
	}
</script>
