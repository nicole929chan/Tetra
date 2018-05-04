<template>
  <div>
  	<div v-if="reply">
    	<a href="#" @click.prevent="toggle">reply</a>
  	</div>
  	<div v-if="show">
  		{{ reply.body }}
  	</div>
  </div>
</template>

<script>
    export default {
    	props: ['reply'],
    	data () {
    		return {
    			show: false
    		}
    	},
    	methods: {
    		toggle () {
    			this.show = !this.show
    		}
    	}
    }
</script>