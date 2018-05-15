<template>

  <div class="container-fluid p-0">
    <div class="row">
      <div id="sidebar" class="col-3 position-fixed">
        <div id="profile" class="row align-item-center">
          <div class="col-10 media">
            <img src="https://dummyimage.com/80/828187/ffffff.jpg&text=MNS" class="rounded-circle align-self-center mr-3 ml-3" alt="">
            <div class="media-body align-self-center">
              75 Greene Avenue,<br>
              Brooklyn<br>
              NY 11201
            </div>
          </div>
          <div class="col-2">
            <a href="#" id="sidebar-arrow"><img src="/images/tools_off.png" alt="" class="mt-5" ></a>
          </div>
        </div>
        <div class="row">
          <div class="col p-0">
            <form action="#">
              <select name="rooms" id="rooms" class="custom-select">
                <option value="">MASTER BATH 5F</option>
              </select>
            </form>
          </div>
        </div>
        <div id="activity" class="row">
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
      	              :key="activity.uniqueId"
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
      	      <template v-if="commentable">
      	        <comment-form
      	          :version="version"
      	          @cancelComment="cancelComment"
      	          @addComment="addComment"
      	        ></comment-form>
      	      </template>
      	      <div class="card">
      	      	<button class="btn btn-sm btn-success" @click="commentable = true">Add Comment</button>
      	      </div>
      	    </div>
      	  </div>
      	</div>
      </div>
      <div class="col p-0">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel" id="menu">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse text-center" id="navbarSupportedContent">
                    <!-- Main Of Navbar -->
                    <ul class="navbar-nav">
                        <!-- Authentication Links -->
                            <li><a class="nav-link" href="#">PROJECTS</a></li>
                            <li><a class="nav-link" href="#">USERS</a></li>
                            <li><a class="nav-link" href="#">HELP</a></li>
                            <li><a  class="nav-link" href="#" id="menu_off"><img src="/images/menu_off.png" alt=""></a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="p-0">
          <leaflet
              :version="version">
          </leaflet>
        </main>
      </div>

    </div>
  </div>
</template>

<script>
	import Leaflet from './Leaflet';
  import Activity from './Activity';
  import CommentForm from './CommentForm';

	export default {
		components: {
			Activity, Leaflet, CommentForm
		},
		props: ['version'],
		data () {
			return {
				activities: null,
				commentable: false,
				currentUniqueId: 0
			}
		},
		created () {
			window.bus.$on('addMark', (mark) => {
				this.currentUniqueId++
				mark.uniqueId = this.currentUniqueId
				this.activities.unshift(mark)

				window.scrollTo(0, 0)
			})

			window.bus.$on('destroyMark', (markId, leafletKey) => {
					this.destroyActivity(markId, 'Mark')
			})

      window.bus.$on('updateMark', (mark) => {
          
      })
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

    		    	this.currentUniqueId = this.activities.length
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
			},
			cancelComment () {
				this.commentable = false
			},
			addComment (comment) {
				this.commentable = false
				this.currentUniqueId++
				comment.uniqueId = this.currentUniqueId
				this.activities.unshift(comment)

				window.scrollTo(0, 0)
			},
		}
	}
</script>
