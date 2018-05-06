<template>
  <div>
  	<div v-if="reply">
    	<a href="#" @click.prevent="toggle">reply</a>
  	</div>
  	<div v-if="show">
      <div class="d-flex justify-content-between">
  		  <div><strong>{{ reply.owner.name }}</strong></div>
        <div v-text="ago"></div>
      </div>
      <div class="d-flex justify-content-between">
        <p v-if="!editable">
          {{ form.body }}
        </p>
        <p v-else>
          <textarea rows="5" v-model="form.body"></textarea>
        </p>
        <div v-if="errors"><small v-text="errors.body[0]"></small></div>
        <div class="d-flex">
          <button class="btn btn-sm btn-outline-info" @click="edit" v-if="!editable">edit</button>
          <button class="btn btn-sm btn-outline-danger" @click="destroy" v-if="!editable">del</button>
          <button class="btn btn-sm btn-outline-info" @click="cancel" v-if="editable">cancel</button>
          <button class="btn btn-sm btn-outline-success" @click="save" v-if="editable">save</button>
        </div>
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
    			show: false,
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
            })
            .catch(error => {
              this.errors = error.response.data.errors
            })

            
        },
        cancel() {
          this.form.body = this.currentBody
          this.editable = false
        }
    	}
    }
</script>