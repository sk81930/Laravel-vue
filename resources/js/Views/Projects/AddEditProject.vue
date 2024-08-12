<template>
  <div>
    <BCard
      style="max-width: 90%;"
      class="mx-20"
    >
    <template #header>

      <div class="d-flex justify-content-between">
        <h2 class="mb-0">{{(this.form.id)?'Edit Project(ID:'+this.form.id+')':'Add Project'}}</h2>
        <router-link to="/manager/projects"> <b-button type="button" variant="secondary">Back</b-button></router-link>
      </div>
      
    </template>

    <BAlert :model-value="true" variant="danger" v-if="errors">
        <ul v-for="(errorD) in errors">
          <li v-for="(error) in errorD">
            <span>{{error}}</span>
          </li>  
        </ul>
    </BAlert>

    <BAlert :model-value="true" variant="success" v-if="successprofile">
         <span>{{successprofile}}</span>
    </BAlert>

        <b-form @submit="onSubmit" @reset="onReset" v-if="show">

          <b-form-group id="input-group-1" label="Title:" label-for="input-1" class="mb-4">
            <b-form-input
              id="input-1"
              v-model="form.title"
              placeholder="Enter project title"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-1" label="Description:" label-for="input-1" class="mb-4">
            <editor
               :api-key="apikey"
               v-model="form.description"
               :init="{
                 height: 200,
                 menubar: false,
                 plugins: [
                   'advlist advcode autolink lists link image charmap print preview anchor',
                   'searchreplace visualblocks code fullscreen',
                   'insertdatetime media table paste code help wordcount'
                 ],
                 toolbar:
                   'undo redo | formatselect | bold italic backcolor | \
                   alignleft aligncenter alignright alignjustify | \
                   bullist numlist outdent indent | removeformat | code | help'
               }"
             />
          </b-form-group>

          <b-form-group id="input-client" label="Client:" label-for="client" class="mb-4">
            <b-form-input
              id="client"
              v-model="form.client"
              placeholder="Enter client name"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-1" label="Access details:" label-for="input-1" class="mb-4">
            <editor
               :api-key="apikey"
               v-model="form.access_details"
               :init="{
                 height: 200,
                 menubar: false,
                 plugins: [
                   'advlist advcode autolink lists link image charmap print preview anchor',
                   'searchreplace visualblocks code fullscreen',
                   'insertdatetime media table paste code help wordcount'
                 ],
                 toolbar:
                   'undo redo | formatselect | bold italic backcolor | \
                   alignleft aligncenter alignright alignjustify | \
                   bullist numlist outdent indent | removeformat | code | help'
               }"
             />
          </b-form-group>

         
          <div class="d-flex justify-content-between">
            <b-button type="submit" variant="primary">Submit</b-button>
          </div>
        </b-form>
    </BCard>    
  </div>
</template>

<script>

  import axios from 'axios';
  import { mapMutations } from "vuex";
  import store from '../../Store';

  import Editor from '@tinymce/tinymce-vue'



  export default {
      name: 'AddEditProject',
      components: {
       'editor': Editor
      },
      data() {
          return {
              apikey: "glo0bi24atqwyfnt8cwl9fubznvu9f6z8fsw29roxhix64r3",
              appload:false,
              errors:null,
              successprofile:null,
              form: {
                id:null,
                title: '',
                description: '',
                client: '',
                access_details: '',
              },
              show: true,
          }
      },
      created() {
        if ('id' in this.$route.params) {
            var id = this.$route.params.id;

            this.getProjectByIdFunc(id);

        }
        this.appload = true;

        // let user = store.getters["auth/user"];
        // this.form.name = user.name;
        // this.form.email = user.email;
         
      },
      methods: {
          async onSubmit(event) {
            event.preventDefault()
            var formData = JSON.stringify(this.form);
            this.errors = null;
            this.successprofile = null;


            await store.dispatch('manager/AddEditProject', formData).then(async response => {


                    this.successprofile = response.data.message;
                    if(!this.form.id){
                      this.form.title = "";
                      this.form.description = "";
                      this.form.client = "";
                      this.form.access_details = "";
                    }


            })
            .catch(error => {
                 
                 let errorsData = Object.values(error.response.data.errors);
                 this.errors = errorsData;
            });;

          //  console.log(returndata)

           // debugger;
          },
          async getProjectByIdFunc(id) {
              await store.dispatch('manager/getProjectById', {id:id}).then(async response => {

                    let resultt = response.data;



                    if(resultt.project && resultt.project.id){

                        this.form.id = resultt.project.id;

                        this.form.title = resultt.project.title;
                        this.form.description = resultt.project.description;
                        this.form.client =resultt.project.client;
                        this.form.access_details =resultt.project.access_details;

                    }
                      


              })
              .catch(error => {
                   
                   let errorsData = Object.values(error.response.data.errors);
                   this.errors = errorsData;
              });
          },
          onReset(event) {
            event.preventDefault()
            // Reset our form values
            this.form.email = ''
            this.form.name = ''
            this.form.food = null
            this.form.checked = []
            // Trick to reset/clear native browser form validation state
            this.show = false
            this.$nextTick(() => {
              this.show = true
            })
          }
      }
  }
</script>