<template>
    <div class="container">
        <activities :activities="activities"></activities>
    </div>
</template>

<script>
    import Activities from './Activities';

    export default {
    	components: {
    		Activities
    	},
        props: ['room', 'version', 'project'],
        data () {
        	return {
        		activities: null
        	}
        },
        mounted() {
            this.initData()
        },
        methods: {
        	initData() {
        		let end_point = '/activities/versions/' + this.version.id

        		axios({
        			url: end_point,
        			method: 'get',
        			baseURL: axios.defaults.baseURL
        		}).then(response => {
    		    	this.activities = response.data
    		    })
        	}
        }
    }
</script>
