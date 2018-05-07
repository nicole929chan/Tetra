<template>
	<div>
		<div class="form-group">
			<textarea rows="5" class="form-control" v-model="form.body"></textarea>
			<div v-if="errors"><small v-text="errors.body[0]"></small></div>
			<div class="custom-file">
				<input type="file" class="custom-file-input" @change="onChange">
				<label class="custom-file-label">Choose file</label>
			</div>
		</div>
		<div class="d-flex justify-content-end">
			<button class="btn btn-sm btn-outline-info" @click="cancel">cancel</button>
			<button class="btn btn-sm btn-outline-success" @click="submit">save</button>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['activity'],
	    data () {
	    	return {
	    		form: {
	    			body: '',
	    			file_path: {}
	    		},
	    		src: '',
	    		errors: null
	    	}
	    },
	    methods: {
	    	onChange (e) {
	    		if (! e.target.files.length) return

	    		let file = e.target.files[0]
	    	    
	    	    let reader = new FileReader()

	    	    reader.readAsDataURL(file)

	    	    reader.onload = e => {
	    	    	let src = e.target.result

	    	    	this.form.file_path = file

	    	    	this.src = src
	    	    }
	    	},
	    	cancel () {
	    		this.errors = null
	    		this.resetForm()
	    		this.$emit('cancelReply')
	    	},
	    	submit () {
	    		let end_point = axios.defaults.baseURL
	    		if (this.activity.type == 'Mark') {
	    			end_point += `/replies/marks/${this.activity.id}`
	    		} else {
	    			end_point += `/replies/comments/${this.activity.id}`
	    		}

	    		let data = new FormData()
	    		data.append('file_path', this.form.file_path)
	    		data.append('body', this.form.body)

	    		axios.post(end_point, data)
	    		    .then(response => {
	    		    	this.errors = null
	    		    	this.resetForm()
	    		    	this.$emit('addReply', response.data.reply)
	    		    })
	    		    .catch(error => {
	    		    	this.errors = error.response.data.errors
	    		    })
	    	},
	    	resetForm () {
	    		this.form.body = ''
	    		this.form.file = {}
	    		this.src = ''
	    	}
	    }
	}
</script>