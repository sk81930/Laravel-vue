<template>
    <section class="gradient-form" style="background-color: #eee;">
        <div class="container py-5 h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-6">
              <div class="card rounded-3 text-white main-login">
                <div class="row g-0">
                  <div class="col-lg-12">
                    <div class="card-body p-md-5 mx-md-4">

                      <div class="text-center">
                        <img src="../../Assets/images/logo.png" style="width: 185px;" alt="logo" />
                        <h4 class="mt-4 mb-5 pb-1">Team portal login</h4>
                      </div>

                      <form>
                        <p v-if="Object.keys(validationErrors).length != 0" class='text-center '><small class='text-danger'>Incorrect Email or Password</small></p>
                        <div class="form-outline mb-4">
                                <label 
                                    htmlFor="email"
                                    class="form-label">
                                        Email address
                                </label>
                                <input 
                                    v-model="email"
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                />
                        </div>

                        <div class="form-outline mb-4">
                            <label 
                                htmlFor="password"
                                class="form-label">Password
                            </label>
                            <input 
                                v-model="password"
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                            />
                        </div>

                        <div class="text-center pt-1 mb-5 pb-1">
                          <button 
                                  :disabled="isSubmitting"
                                  @click="loginAction()"
                                  type="button"
                                  class="btn btn-primary btn-block fa-lg  mb-3">Login</button>
                          
                        </div>
                        <!-- <a class="text-muted" href="#!">Forgot password?</a> -->

                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</template>


<script>
  import axios from 'axios';
  import { mapMutations } from "vuex";
  import Store from '../../Store';
  import agent from '../../agent';

  import Logo from "../../Assets/images/logo.png";
  import "../../Assets/scss/login.scss";
  export default {
      name: 'Login',
      data() {
        return {
            email:'',
            password:'',
            validationErrors:{},
            isSubmitting:false,
        };
      },
      created() {
      },
      methods: {
        ...mapMutations(["setUser", "setToken"]),
          async loginAction(){
            this.isSubmitting = true
            let payload = {
                email: this.email,
                password: this.password,
            }
            await agent.Auth.login(payload).then(async response => {
                      if(response.data.access_token){
                        await Store.commit('auth/setToken', response.data.access_token);
                        await Store.commit('auth/setUser', response.data.user);
                        if(response.data.user.role == "admin"){
                            this.$router.push('/admin/dashboard')
                        }else{
                            this.$router.push('/manager/dashboard')
                        }
                        
                      }
                      return response;
                  })
                  .catch(error => {
                      this.isSubmitting = false
                      if (error.response.data.errors != undefined) {
                          this.validationErrors = error.response.data.errors
                      }
                      if (error.response.data.error != undefined) {
                          this.validationErrors = error.response.data.error
                      }
                      return error
                  });
            
          }
      },
  }
</script>