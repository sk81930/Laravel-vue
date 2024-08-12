<template>
  <div>
    <BCard
      header="Profile"
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
              readonly
            ></b-form-input>
          </b-form-group>
          <b-form-group id="password" label="Your password:" label-for="input-password" class="mb-4">
            <b-form-input
                id="input-password"
                v-model="form.password"
                type="password"
                placeholder="Enter password"
                autocomplete="new-password"
              >
            </b-form-input>
          </b-form-group>
          <b-form-group id="confirm-password" label="Your confirm password:" label-for="input-confirm-password" class="mb-4">
            <b-form-input
                id="input-confirm-password"
                v-model="form.confirm_password"
                type="password"
                placeholder="Enter confirm-password"
                :required="form.password!=''"
                autocomplete="new-password"
              >
            </b-form-input>
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
                name: '',
                email: '',
                password: '',
                confirm_password: '',
              },
              show: true
          }
      },
      mounted() {

        let user = store.getters["auth/user"];
        this.form.name = user.name;
        this.form.email = user.email;
         
      },
      methods: {
          async onSubmit(event) {
            event.preventDefault()
            var formData = JSON.stringify(this.form);
            this.errors = null;

            var returndata =  await store.dispatch('profile/updateProfile', formData).then(async response => {
                    
                             await store.dispatch('auth/getUser');

                             this.successprofile = response.data.message;

                             console.log(store)


                })
                .catch(error => {
                     
                     let errorsData = Object.values(error.response.data.errors);
                     this.errors = errorsData;
                });;

            console.log(returndata)

           // debugger;
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