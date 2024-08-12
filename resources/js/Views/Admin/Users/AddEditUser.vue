<template>
  <div>
    <BCard
      :header="(this.form.id)?'Edit user(ID:'+this.form.id+')':'Add user'"
      header-tag="h2"
      style="max-width: 90%;"
      class="mx-20"
    >
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

          <b-form-group id="input-group-1" label="Your Name:" label-for="input-1" class="mb-4">
            <b-form-input
              id="input-1"
              v-model="form.name"
              placeholder="Enter name"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group
            id="input-group-2"
            label="Email address:"
            label-for="input-2"
            class="mb-4"
          >
            <b-form-input
                id="input-2"
                v-model="form.email"
                type="email"
                placeholder="Enter email"
                required
                :readonly="(this.form.id)?true:false"
              ></b-form-input>
          </b-form-group>
          <b-form-group id="password" label="Your password:" label-for="input-password" class="mb-4">
            <b-form-input
                id="input-password"
                v-model="form.password"
                type="password"
                placeholder="Enter password"
                autocomplete="new-password"
                :required="!this.form.id"
              >
            </b-form-input>
          </b-form-group>
          <b-form-group id="confirm-password" label="Your confirm password:" label-for="input-confirm-password" class="mb-4">
            <b-form-input
                id="input-confirm-password"
                v-model="form.confirm_password"
                type="password"
                placeholder="Enter confirm-password"
                autocomplete="new-password"
                :required="(form.password!='')?true:false"
              >
            </b-form-input>
          </b-form-group>
          <b-form-group id="role" label="Role:" label-for="select-role" class="mb-4">
             <b-form-select id="select-role" v-model="form.role" :options="roleOptions" required></b-form-select>
          </b-form-group>

          <b-button type="submit" variant="primary">Submit</b-button>
        </b-form>
    </BCard>    
  </div>
</template>

<script>

  import axios from 'axios';
  import { mapMutations } from "vuex";
  import store from '../../../Store';
  export default {
      name: 'Profile',
      data() {
          return {
              
              errors:null,
              successprofile:null,
              form: {
                id:null,
                name: '',
                email: '',
                password: '',
                confirm_password: '',
                role: 'user',
              },
              show: true,
              roleOptions: [
                { value: 'user', text: 'User' , selected : true},
                { value: 'manager', text: 'Manager' },
              ]
          }
      },
      async mounted() {
        if ('id' in this.$route.params) {
            var userid = this.$route.params.id;

            this.getUserByIdFunc(userid);

        }

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

            var returndata =  await store.dispatch('admin/AddEditUser', formData).then(async response => {


                        this.successprofile = response.data.message;
                        if(!this.form.id){
                          this.form.name = "";
                          this.form.email = "";
                          this.form.password = "";
                          this.form.confirm_password = "";
                          this.form.role = "user";
                        }


                })
                .catch(error => {
                     
                     let errorsData = Object.values(error.response.data.errors);
                     this.errors = errorsData;
                });;

            console.log(returndata)

           // debugger;
          },
          async getUserByIdFunc(userid) {
              await store.dispatch('admin/getUserById', {userid:userid}).then(async response => {

                    let resultt = response.data;

                    if(resultt.user && resultt.user.id){

                        this.form.id = resultt.user.id;

                        this.form.name = resultt.user.name;
                        this.form.email = resultt.user.email;
                        this.form.role =resultt.user.role;

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