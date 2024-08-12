<template>
  <div class="row" v-if="task" ref="content" id="content">
      <div class="col-md-8">
          <BCard
            style="max-width: 98%;"
            class="mx-20"
            
          >
              <template #header>

                <div class="d-flex justify-content-between">
                  <h2 class="mb-0">{{task.title}}</h2>
                  <router-link to="/manager/tasks"> <b-button type="button" variant="secondary">Back</b-button></router-link>
                </div>
                
              </template>

              <div class="body-card">
                 <p v-html="task.description"></p>
              </div>

          </BCard>   

          <BCard
              style="max-width: 98%;"
              class="mx-20 mt-5"
            >
            <template #header>

              <div class="d-flex justify-content-between">
                <h2 class="mb-0">Add Comment</h2>
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

                <b-form @submit="onSubmit" v-if="show">

                  <b-form-group id="input-group-1" label="" label-for="input-1" class="mb-0">
                    <editor
                       ref="editor"
                       :api-key="apikey"
                       v-model="form.comment"
                       :init="initEditor"
                     >
                        <span class="mention"></span>
                    </editor>
                  </b-form-group>

                  <fileUploader @filesdata="filedata" :emptyfiles="this.emptyfiles" />

                 
                  <div class="d-flex justify-content-between mt-3">
                    <b-button type="submit" variant="primary">Submit</b-button>
                  </div>
                </b-form>
          </BCard>  
          <Comments @resetcommentparent="resetcommentparentfunc" :resetcomment="this.resetcomment" />
      </div>
      <div class="col-md-4">
        <BCard
            style="max-width: 90%;"
            class="mx-20"
            
          >
          <div class="body-card">
            <div class="inner-dd" v-if="task.assign_to_user">
              <b class="mb-0">Assign to: </b><p>{{task.assign_to_user.name}}</p>
            </div>
            <div class="inner-dd" v-if="task.created_by_user">
              <b class="mb-0">Created By: </b><p>{{task.created_by_user.name}}</p>
            </div>
            <div class="inner-dd" v-if="task.project_user">
              <b class="mb-0">Project : </b><p>{{task.project_user.title}}</p>
            </div>

            <div class="inner-dd" v-if="task.observer_users">
                <b class="mb-0">Observer user: </b>
                <div v-for="(observer_user, indexu) in task.observer_users">
                  <p>{{observer_user.name}}</p>
                </div>
            </div>
             
          </div>
        </BCard>   
      </div>
      <hightlight @selectedData="selectedData" />
  </div>
</template>

<script>

  import { ref } from 'vue'

  import axios from 'axios';
  import { mapMutations } from "vuex";
  import store from '../../Store';

  import Editor from '@tinymce/tinymce-vue'

  import { getTinymce } from '@tinymce/tinymce-vue/lib/cjs/main/ts/TinyMCE';

  import Multiselect from '@vueform/multiselect'
  import "@vueform/multiselect/themes/default.css";

  import fileUploader from "./components/Index.vue";

  import Hightlight from './components/hightlight';

  import Comments from './comments';

  const editorRef = ref(null);




  export default {
      name: 'AddEditTask',
      components: {
       'editor': Editor,
       'multiselect': Multiselect,
       'fileUploader': fileUploader,
       'Hightlight': Hightlight,
       'Comments': Comments,
      },
      data() {
          return {
              resetcomment:null,
              emptyfiles:null,
              apikey: "glo0bi24atqwyfnt8cwl9fubznvu9f6z8fsw29roxhix64r3",
              appload:false,
              files:[],
              form:{
                taskid: null,
                comment: "",
                files: [],
              },
              errors:null,
              successprofile:null,
              task: null,
              show: true,
              projectOptions: [],
              assignToOptions: [],
              observerOptions: [],
              priorityOptions: [{"label": "High",value : "high"},{"label": "Medium",value : "medium"},{"label": "Low",value : "low"}],
              initEditor: {
                   height: 200,
                   menubar: false,
                   plugins: [
                     'advlist advcode autolink lists link image charmap print preview anchor mentions',
                     'searchreplace visualblocks code fullscreen',
                     'insertdatetime media table paste code help wordcount'
                   ],
                    toolbar:
                     'undo redo | formatselect | bold italic backcolor | \
                     alignleft aligncenter alignright alignjustify | \
                     bullist numlist outdent indent | removeformat | code | image | help',
                    mentions_selector: 'span.mymention',
                    mentions_fetch: this.mentionsFetch,
                    mentions_menu_complete: function (editor, userInfo) {
                      var span = editor.getDoc().createElement('span');
                      span.className = 'mymention';
                      // store the user id in the mention so it can be identified later
                      span.setAttribute('data-mention-id', userInfo.id);
                      span.setAttribute('style', "color: #0a99e3;");
                      span.appendChild(editor.getDoc().createTextNode('@' + userInfo.name));
                      return span;
                    },
                    setup: (editor) => {
                      editorRef.value = editor;
                     // editor.on('keydown', this.handleKeyDown);
                    }
              }
          }
      },
      created() {
        if ('id' in this.$route.params) {
            var id = this.$route.params.id;
            this.form.taskid = id;

            this.getTaskByIdFunc(id);

        }

        // let user = store.getters["auth/user"];
        // this.form.name = user.name;
        // this.form.email = user.email;
         
      },
      mounted() {
        this.appload = true;

        // let user = store.getters["auth/user"];
        // this.form.name = user.name;
        // this.form.email = user.email;
         
      },
      methods: {
          selectedData(data) {
             getTinymce().activeEditor.execCommand('mceInsertContent', false, " <b>"+data+"</b> ");
          },
          resetcommentparentfunc() {
             this.resetcomment = null;
          },
          filedata(files) {
            this.form.files = files;
            this.emptyfiles = null;
          },
          async handleKeyDown(e) {
            if (e.key === '@') {
              // Prevent default '@' insertion behavior
              e.preventDefault();
              // Insert a .mention element at the current cursor position
              editorRef.value.insertContent('<span class="mention">@</span>');
            }
          },
          async onSubmit(event) {
              event.preventDefault()
              const formData = new FormData();
              //this.emptyfiles = null;

              if(this.form.files.length > 0){
                 this.form.files.forEach(function(file,index){
                      formData.append('files['+index+']', file);
                 })
              }
              formData.append('comment', this.form.comment);
              formData.append('taskid', this.form.taskid);

              this.errors = null;
              this.successprofile = null;
              

              await store.dispatch('task/AddComment', formData).then(async response => {


                      

                      if(response.data.message){
                          this.successprofile = response.data.message;
                          this.form.files = [];
                          this.form.comment = "";
                          this.emptyfiles = 1;
                          this.resetcomment = 1;
                      }


              })
              .catch(error => {
                   let errorsData = Object.values(error.response.data.errors);
                   this.errors = errorsData;
              });

          },
          async getTaskByIdFunc(id) {
              await store.dispatch('task/getTaskById', {id:id}).then(async response => {

                    let resultt = response.data;



                    if(resultt.task && resultt.task.id){

                        this.task = resultt.task;


                    }
                      


              })
              .catch(error => {
                   
                   let errorsData = Object.values(error.response.data.errors);
                   this.errors = errorsData;
              });
          },
          async mentionsFetch(query, success) {
              await store.dispatch('auth/getUserRole').then(async response => {

                   if(response.data && response.data.userrole && response.data.userrole.length > 0){

                      var dataUser = [];

                      response.data.userrole.forEach(function(user,index){

                        var id = user.id;


                          dataUser.push({id: id.toString(), name: user.name});
                      })
                      const filteredUsers = dataUser.filter(user =>
                          user.name.toLowerCase().includes(query.term.toLowerCase())
                      );
                      success(filteredUsers);
                   }
                      


              }).catch(error => {});
             
          },
      }
  }
</script>