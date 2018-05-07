<template>
	<div class="topic m-1">
	    <div class="comment" style="border-left: 2px solid red;">
	        <div class="d-flex justify-content-between">
	    		<h4>{{ activity.creator.name }}</h4>
	    		<div v-text="ago"></div>
	        </div>
	    	<p v-if="!editable">
				{{ form.body }}
	    	</p>
	    	<div v-else class="form-group">
	    		<textarea rows="5" v-model="form.body" class="form-control"></textarea>
	    	</div>
	    	<div v-if="errors"><small v-text="errors.body[0]" class="text-danger"></small></div>
		    <div class="d-flex justify-content-end">
		        <div class="d-flex" v-if="canUpdate">
			    	<button class="btn btn-sm btn-outline-info" @click="edit" v-if="!editable">edit</button>
			        <button class="btn btn-sm btn-outline-danger" @click="destroy" v-if="!editable && replies.length==0">del</button>
			        <button class="btn btn-sm btn-outline-info" @click="cancel" v-if="editable">cancel</button>
			        <button class="btn btn-sm btn-outline-success" @click="save" v-if="editable">save</button>
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
    			replies: this.activity.replies,
    			editable: false,
    			form: {
    				body: this.activity.body
    			},
    			currentBody: this.activity.body,
    			errors: null
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

    			if (this.replies.length == 0) {
    				this.$emit('destroyActivity')
    			}

    			return;
    		},
    		addReply (reply) {
    			this.replies.unshift(reply)
    			this.show = false
    		},
    		edit () {
    			this.editable = true
    		},
    		save () {
    			let end_point = axios.defaults.baseURL

    			end_point += (this.activity.type == 'Mark') ? `/marks/${this.activity.id}` : `/comments/${this.activity.id}`

    			axios.patch(end_point, this.form)
    			    .then(response => {
    			    	this.currentBody = this.form.body
    			    	this.editable = false
    			    	this.errors = null
    			    })
    			    .catch(error => {
    			    	this.errors = error.response.data.errors
    			    })
    		},
    		cancel () {
    			this.form.body = this.currentBody
    			this.errors = null
    			this.editable = false
    		},
    	}
    	
    }
</script>