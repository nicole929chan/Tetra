<template>
<div>
    <div class="row fixed-bottom justify-content-center">
	  <div class="col-8 offset-2">
	    <ul id="bottom-tool">
	      <li class="m-0" v-for="version, index in versions" :class="{ 'active' : current.id == version.id }">
		    <div style="padding: 5px 10px;">
		      <a :href="`/rooms/${room.id}/versions/${version.id}`">
		        <div class="float-left mr-2">
		          <div style="font-size:10px;">{{ versionTime(version) }}</div>
		          <div>VERSION</div>
		        </div>
		        <div class="version">{{ index + 1 }}</div>
		      </a>
		    </div>
		  </li>
	      <li class="m-0 active">
	        <div style="padding: 5px 10px;">
	          <div class="float-left mr-3">
	            <div style="font-size:10px;">{{ selectionTime }}</div>
	            <div>View selection</div>
	          </div>
	        </div>
	      </li>
	      <li class="m-0">
	        <div style="padding: 5px 10px;" class="text-center">
	          <a href="#" id="bottom_off"><img src="/images/toggle_on.png" alt="" style="padding:13px;"></a>
	        </div>
	      </li>
	    </ul>
	  </div>
	</div>

	<div id="view_select-open">
	  <a href="#" id="view_select_on"><img src="/images/view_select_on.png" alt=""></a>
	</div>
</div>
</template>

<script>
    import moment from 'moment';

    export default {
    	props: ['room', 'current'],
    	data () {
    		return {
    			selection: {},
    			versions: null
    		}
    	},
    	mounted () {
    		let end_point1 = axios.defaults.baseURL + `/rooms/${this.room.id}/selection`
    		let end_point2 = axios.defaults.baseURL + `/versions/rooms/${this.room.id}`

    		axios.get(end_point1)
    		    .then(response => {
    		    	this.selection = response.data.selection
    		    })
    		    .catch(error => {
    		    	console.log(error)
    		    })

    		axios.get(end_point2)
    		    .then(response => {
    		    	this.versions = response.data.versions
    		    })
    		    .catch(error => {
    		    	console.log(error)
    		    })

    	},
    	computed: {
    		selectionTime () {
    			return moment(this.selection.updated_at).format('YYYY-MM-DD')
    		},
    	},
    	methods: {
    		versionTime (version) {
    			return moment(version.created_at).format('YYYY-MM-DD')	
    		}
    	}
    }
</script>
