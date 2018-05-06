<template>
  <div>

    <leaflet :version="version"></leaflet>

	<div id="activity" class="row" style="position: absolute; top: 44px; left: 0; z-index: 999;">
	  <div class="col p-0">
	    <div id="accordion">
	      <div class="card">
	        <div class="card-header" id="headingOne">
	          <h5 class="mb-0">
	            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
	              Activity
	            </button>
	          </h5>
	        </div>
	        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
	          <div class="card-body">
	          	<activity
	              v-for="activity, index in activities"
	              :key="activity.id"
	              :activity="activity"
	              @destroyActivity="destroyActivity(activity.id, activity.type)"
	            ></activity>
	          </div>
	        </div>
	      </div>
	      <div class="card">
	        <div class="card-header" id="headingTwo">
	          <h5 class="mb-0">
	            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
	              Files
	            </button>
	          </h5>
	        </div>
	        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
	          <div class="card-body">
	            ajax 讀取 files (ActivitiesController)
	          </div>
	        </div>
	      </div>
	      <div class="card">
	      	<button class="btn btn-sm btn-success">Add Comment</button>
	      </div>
	    </div>
	  </div>
	</div>

  </div>
</template>

<script>
	import Leaflet from './Leaflet';
    import Activity from './Activity';

	export default {
		components: {
			Activity, Leaflet
		},
		props: ['version'],
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
        	},

			destroyActivity (activityId, activityType) {
				let end_point = axios.defaults.baseURL

				end_point += (activityType == 'Mark') ? `/marks/${activityId}` : `/comments/${activityId}`
				
				axios.delete(end_point)
				    .then(response => {
				    	this.activities = this.activities.filter((activity) => {
				    		if (activity.id == activityId && activity.type == activityType) {
				    			return false
				    		} else {
				    			return true
				    		}
				    	})
				    })
				    .catch(error => {
				    	console.log(error)
				    })
			}
		}
	}
</script>