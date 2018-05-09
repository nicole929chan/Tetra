<template>
    <div class="container-fluid">
        <activities :version="version"></activities>
    </div>
</template>

<script>
    import Activities from './Activities';

    export default {
    	components: {
    		Activities
    	},
        props: ['room', 'version', 'project'],
        
    }
</script>
