<template>
	<div class="ml-2">
    <div class="d-flex justify-content-between">
		  <div><strong>{{ reply.owner.name }}</strong></div>
      <div v-text="ago"></div>
    </div>
    <p v-if="!editable">
      {{ form.body }}
    </p>
    <div v-else class="form-group">
      <textarea rows="5" v-model="form.body" class="form-control"></textarea>
    </div>
    <div v-if="errors"><small v-text="errors.body[0]" class="text-danger"></small></div>
    <div class="d-flex justify-content-end">
      <div class="d-flex" v-if="canUpdate">
        <button class="btn btn-sm btn-outline-info" @click="edit" v-if="!editable">edit</button>
        <button class="btn btn-sm btn-outline-danger" @click="destroy" v-if="!editable">del</button>
        <button class="btn btn-sm btn-outline-info" @click="cancel" v-if="editable">cancel</button>
        <button class="btn btn-sm btn-outline-success" @click="save" v-if="editable">save</button>
      </div>
    </div>
	</div>
</template>

<script>
    import moment from 'moment';

    export default {
    	props: ['reply'],
    	data () {
    		return {
          editable: false,
          form: {
            body: this.reply.body
          },
          currentBody: this.reply.body,
          errors: null
    		}
    	},
      computed: {
        ago () {
          return moment(this.reply.updated_at).fromNow()
        },
        canUpdate () {
          return this.reply.owner.id == window.App.user.id
        }
      },
    	methods: {
    		toggle () {
    			this.show = !this.show
    		},
        destroy () {
          this.$emit('destroyReply')
        },
        edit () {
          this.editable = true
        },
        save () {
          let end_point = axios.defaults.baseURL + `/replies/${this.reply.id}`

          axios.patch(end_point, this.form)
            .then(response => {
              // console.log(response)
              this.currentBody = this.form.body
              this.editable = false
              this.errors = null
            })
            .catch(error => {
              this.errors = error.response.data.errors
            })
        },
        cancel() {
          this.form.body = this.currentBody
          this.errors = null
          this.editable = false
        }
    	}
    }
</script>