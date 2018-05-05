<template>
	<div class="topic m-1">
	    <div class="comment" style="border-left: 2px solid red;">
	        <div class="d-flex justify-content-between">
	    		<h4>{{ activity.creator.name }}</h4>
	    		<div>{{ activity.updated_at }}</div>
	        </div>
	    	<p>
				{{ activity.body }}
	    	</p>
	    	<reply
	    	  v-for="reply, index in activity.replies"
	    	  :key="reply.id"
	    	  :reply="reply"
	    	></reply>
	    </div>
	</div>
</template>


<script>
    import Reply from './Reply';

    export default {
    	components: {
    		Reply
    	},
    	props: ['activity']
    	
    }
</script>