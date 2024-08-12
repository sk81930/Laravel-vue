<template>
    <BCard
        style="max-width: 98%;"
        class="mx-20 mt-5 comments"
      >
      <template #header>

        <div class="d-flex justify-content-between">
          <h2 class="mb-0">Comments</h2>
        </div>
        
      </template>
      <div class="container-data">
        <div class="col-md-12" id="fbcomment">
          <div class="header_comment">
            <div class="row">
              <div class="col-md-6 text-left">
                <span class="count_comment" v-if="totalComment">{{totalComment}} Comments</span>
              </div>
            </div>
          </div>

          <div class="body_comment">
            <div class="row">
              <ul id="list_comment" class="col-md-12">
                <!-- Start List Comment 1 -->
                <li class="box_result row" v-for="(comment, indexC) in comments">
                  <div class="avatar_comment col-md-1">
                    <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar"/>
                  </div>
                  <div class="result_comment col-md-11">
                    <h4>{{comment.created_by_user.name}}</h4>
                    <p v-html="comment.comment"></p>
                    <div class="tools_comment">
                      <span>{{time_ago(comment.updated_at)}}</span>
                    </div>
                  </div>
                  <div class="attachments">
                    <ul class="list_attachments col-md-12">
                      <!-- Start List Comment 1 -->
                      <li class="attachment_result row" v-for="(attachment, indexA) in comment.attachments_data">
                         <a :href="attachment.path" download>{{attachment.name}}</a>
                      </li>
                    </ul>
                  </div>
                </li>
              </ul>
              <button class="show_more" @click="loadMore" v-if="currentPage < maxpage" type="button">
                <span v-if="loadmoreloader"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...</span>
                <span v-else>Load more comments</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </BCard>
</template>

<script>

  import { ref,watchEffect } from 'vue'

  import axios from 'axios';

  import { mapMutations } from "vuex";
  import store from '../../Store';

  import "./comments.scss";





  export default {
      name: 'Comments',
      props:["resetcomment"],
      components: {
      },
      data() {
          return {
            comments: [],
            currentPage: 1,
            maxpage: null,
            totalComment: null,
            id: null,
            loadmoreloader:false
          }
      },
      created() {
         
        if ('id' in this.$route.params) {
            var id = this.$route.params.id;
            this.id = id;

            this.getCommentsFunc({id:id});

        }
         
      },
      mounted() {
        this.appload = true;
        watchEffect(() => {
          if(this.resetcomment){
             this.resetpage();
             this.$emit('resetcommentparent');
          }
        });

        // let user = store.getters["auth/user"];
        // this.form.name = user.name;
        // this.form.email = user.email;
         
      },
      methods: {
          resetpage() {
            this.comments = [];
            this.currentPage = 1;
            this.getCommentsFunc({id:this.id});
          },
          loadMore() {
            this.currentPage += 1;
            var parmas = {};
            parmas.id = this.id;
            parmas.page = this.currentPage;
            this.getCommentsFunc(parmas)
          },
          async getCommentsFunc(data) {

              this.loadmoreloader = true;

              await store.dispatch('task/getComments',data).then(async response => {

                   this.loadmoreloader = false;

                   if(response.data && response.data.comments){

                      if(response.data.comments.last_page){
                         this.maxpage = response.data.comments.last_page;
                      }
                      if(response.data.comments.total){
                         this.totalComment = response.data.comments.total;
                      }
                       
                   }


                   if(response.data && response.data.comments && response.data.comments.data.length > 0){


                      let comments_data =  response.data.comments.data;

                      let dataC = this.comments;

                      comments_data.forEach(function(cm,index){
                        
                         dataC.push(cm);
                      })
                      this.comments = dataC;

                      
 
                   }

              }).catch(error => {
                this.loadmoreloader = false;
              });
             
          },
          time_ago(time) {

              switch (typeof time) {
                case 'number':
                  break;
                case 'string':
                  time = +new Date(time);
                  break;
                case 'object':
                  if (time.constructor === Date) time = time.getTime();
                  break;
                default:
                  time = +new Date();
              }
              var time_formats = [
                [60, 'seconds', 1], // 60
                [120, '1 minute ago', '1 minute from now'], // 60*2
                [3600, 'minutes', 60], // 60*60, 60
                [7200, '1 hour ago', '1 hour from now'], // 60*60*2
                [86400, 'hours', 3600], // 60*60*24, 60*60
                [172800, 'Yesterday', 'Tomorrow'], // 60*60*24*2
                [604800, 'days', 86400], // 60*60*24*7, 60*60*24
                [1209600, 'Last week', 'Next week'], // 60*60*24*7*4*2
                [2419200, 'weeks', 604800], // 60*60*24*7*4, 60*60*24*7
                [4838400, 'Last month', 'Next month'], // 60*60*24*7*4*2
                [29030400, 'months', 2419200], // 60*60*24*7*4*12, 60*60*24*7*4
                [58060800, 'Last year', 'Next year'], // 60*60*24*7*4*12*2
                [2903040000, 'years', 29030400], // 60*60*24*7*4*12*100, 60*60*24*7*4*12
                [5806080000, 'Last century', 'Next century'], // 60*60*24*7*4*12*100*2
                [58060800000, 'centuries', 2903040000] // 60*60*24*7*4*12*100*20, 60*60*24*7*4*12*100
              ];
              var seconds = (+new Date() - time) / 1000,
                token = 'ago',
                list_choice = 1;

              if (seconds == 0) {
                return 'Just now'
              }
              if (seconds < 0) {
                seconds = Math.abs(seconds);
                token = 'from now';
                list_choice = 2;
              }
              var i = 0,
                format;
              while (format = time_formats[i++])
                if (seconds < format[0]) {
                  if (typeof format[2] == 'string')
                    return format[list_choice];
                  else
                    return Math.floor(seconds / format[2]) + ' ' + format[1] + ' ' + token;
                }
              return time;
          }
      }
  }
</script>