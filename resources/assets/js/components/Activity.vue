<template>
	<div class="topic m-1">
	    <div class="comment" style="border-left: 2px solid red;">
	        <div class="d-flex justify-content-between">
	    		<h4>{{ activity.creator.name }}</h4>
	    		<div v-text="ago"></div>
	        </div>
	        <div class="d-flex justify-content-between">
		    	<p>
					{{ activity.body }}
		    	</p>
		    </div>
		    <div class="d-flex justify-content-end">
		        <div class="d-flex" v-if="canUpdate">
			    	<button class="btn btn-sm btn-outline-info">edit</button>
			    	<button class="btn btn-sm btn-outline-danger" @click="destroy">del</button>
			    	<button class="btn btn-sm btn-outline-info">cancel</button>
			    	<button class="btn btn-sm btn-outline-success">save</button>
			    </div>
		    </div>
		    <p>
		        <a href="#" @click.prevent="show = true">leave a reply</a>
		    </p>
		    <reply-form 
		    	v-if="show" 
		    	:activity="activity"
		    	@addReply="addReply"
		    	@cancelReply="show = false"
		    ></reply-form>
		    <reply
	    	    v-for="reply, index in replies"
	    	    :key="reply.id"
	    	    :reply="reply"
	    	    @destroyReply="destroyReply(reply.id)"
	    	></reply>
	    </div>
	</div>
</template>


<script>
    import Reply from './Reply';
    import ReplyForm from './ReplyForm';
    import moment from 'moment';

    export default {
    	components: {
    		Reply, ReplyForm
    	},
    	props: ['activity'],
    	data () {
    		return {
    			show: false,
    			replies: this.activity.replies
    		}
    	},
    	computed:{
    		ago () {
    			return moment(this.activity.updated_at).fromNow()
    		},
    		canUpdate () {
    			return this.activity.creator.id == window.App.user.id
    		}
    	},
    	methods: {
    		destroyReply (replyId) {
    			let end_point = axios.defaults.baseURL + `/replies/${replyId}`

    			axios.delete(end_point)
    			    .then(response => {
    			    	this.replies = this.replies.filter((reply) => {
    			    		return reply.id != replyId
    			    	})
    			    })
    			    .catch(error => {
    			    	console.log(error)
    			    })
    		},
    		destroy () {
    			this.$emit('destroyActivity')
    		},
    		addReply (reply) {
    			this.replies.unshift(reply)
    			this.show = false
    		}
    	}
    	
    }
</script>