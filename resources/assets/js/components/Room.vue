<template>
    <div class="container-fluid">
        <activities :version="version" :project="project" :current="current" :rooms="rooms"></activities>
    </div>
</template>

<script>
    import Activities from './Activities';

    export default {
    	components: {
    		Activities
    	},
        props: ['current', 'version', 'project', 'rooms'],
        
    }
</script>
