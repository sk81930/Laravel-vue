<template>
    <div>
        <div class="d-flex items-center justify-content-between mb-5">
            <h2 class="text-3xl">Projects</h2>
            <router-link to="/manager/projects/add">
              <button type="button" class="btn btn-success" >Add Project</button>
            </router-link>
        </div>

        <vue3-datatable
            :rows="rows"
            :columns="cols"
            :loading="loading"
            :totalRows="total_rows"
            :isServerMode="true"
            :pageSize="params.pagesize"
            firstArrow="First"
            lastArrow="Last"
            previousArrow="Prev"
            nextArrow="Next"
            :showNumbersCount="3"
            class="alt-pagination"
            @change="changeServer"
            v-if="hasData"
            ref="datatable"
        >
            <template #created_by="data">
                <strong>{{ capitalizeFirstLetter(data.value.created_by.name)+"(ID:"+data.value.created_by.id+")" }}</strong>
            </template>
            <template #actions="data">
                <div class="d-flex gap-4">
                    <router-link :to="{ path: '/manager/projects/edit/'+data.value.id}">
                      <button type="button" class="btn btn-success !py-1">Edit</button>
                    </router-link>
                    <!-- <button type="button" class="btn btn-danger !py-1" @click="deleteUser(data.value)">Delete</button> -->
                </div>
            </template>
        </vue3-datatable>
    </div>
</template>
<script>
  import { ref } from 'vue';
  import Vue3Datatable from '@bhplugin/vue3-datatable';
  import '@bhplugin/vue3-datatable/dist/style.css';
 
  import axios from 'axios';
  import { mapMutations } from "vuex";
  import store from '../../Store';

  let datatable = ref(null);


  const cols = ref([
      { field: "id", title: "ID", width: "90px", filter: false },
      { field: "title", title: "Title" },
      { field: "client", title: "Client" },
      { field: "created_by", title: "Created by" },
      { field: 'actions', title: 'Actions' },
    ]);


  export default {
      name: 'Projects',
      components: {
          'vue3-datatable': Vue3Datatable
      },
      data() {
          return {
              hasData:false,
              errors:null,
              params:{ pagesize: 10, page:1 },
              loading: false,
              rows: null,
              total_rows: ref(0),
              cols:cols
          }
      },
      created() {

        this.getProjectdata({});



       
        // let user = store.getters["auth/user"];
        // this.form.name = user.name;
        // this.form.email = user.email;
         
      },
      methods: {
          capitalizeFirstLetter(string) {
            //debugger;
              return string.charAt(0).toUpperCase() + string.slice(1);
          },
          async getProjectdata(data) {
               var returndata =  await store.dispatch('manager/getProjects', data).then(response => {

                    let result = response.data.projects;

                      if(result.data.length > 0){

                          this.rows = result.data;
                          this.total_rows = result.total;
                          this.params.pagesize = result.per_page;
                          this.hasData = true;


                      }


                })
                .catch(error => {
                });;

          },
          async changeServer(data) {
              //this.hasData = false;
              this.params.page = data.current_page;
              this.params.current_page = data.current_page;
              this.params.pagesize = data.pagesize;
              this.getProjectdata(this.params);
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
<style>
    /* alt-pagination */
    .alt-pagination .bh-pagination .bh-page-item {
        height: 3rem;
        width: 3rem;
    }
</style>