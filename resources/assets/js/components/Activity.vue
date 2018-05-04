<template>
	<div class="topic m-1">
	    <div class="comment" style="border-left: 2px solid red;">
	    	<h4>{{ activity.creator.name }}</h4>
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