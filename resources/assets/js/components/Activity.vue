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
		    	<button class="btn btn-sm btn-outline-danger" @click="destroy">del</button>
		    </div>
		    <reply
	    	    v-for="reply, index in activity.replies"
	    	    :key="reply.id"
	    	    :reply="reply"
	    	    @destroyReply="destroyReply(reply.id)"
	    	></reply>
	    </div>
	</div>
</template>


<script>
    import Reply from './Reply';
    import moment from 'moment';

    export default {
    	components: {
    		Reply
    	},
    	props: ['activity'],
    	computed:{
    		ago () {
    			return moment(this.activity.updated_at).fromNow()
    		}
    	},
    	methods: {
    		destroyReply (replyId) {
    			let end_point = axios.defaults.baseURL + `/replies/${replyId}`

    			axios.delete(end_point)
    			    .then(response => {
    			    	this.activity.replies = this.activity.replies.filter((reply) => {
    			    		return reply.id != replyId
    			    	})
    			    })
    			    .catch(error => {
    			    	console.log(error)
    			    })
    		},
    		destroy () {
    			this.$emit('destroyActivity')
    		}
    	}
    	
    }
</script>