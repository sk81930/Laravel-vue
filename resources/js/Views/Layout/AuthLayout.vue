<template>
    <nav id="navigator" class="navbar" :class="{navbaropen: opened}">
        <span class="open-slide">
          <a href="#"  @click="opened = !opened">
            <div>
              <div class="bar" :class="{topopen: opened}"></div>
              <div class="bar" :class="{midopen: opened}"></div>
              <div class="bar" :class="{botopen: opened}"></div>
            </div>
          </a>
        </span>
        <div class="right-div d-flex align-items-center mx-5">
          <div class="px-3">{{user.name}}</div>
          <button class="btn btn-secondary" v-on="{ click: logout }">Logout</button>
        </div>
        
      </nav>
    <div id="side-menu" class="side-nav" :class="{sidenavopen: opened}">
        <ul class="flex justify-end navbar-nav">
          <li v-for="(menu, index) in menuItem" :key="index">
            <router-link :to="menu.href">
              {{ menu.title }}
            </router-link>
            <!-- <a :href="menu.href" @click.stop="showMenu()" @class="(menu.function)? menu.function:''">{{ menu.title }}</a> -->
            <ul v-if="menu.children" :style="{ display: active ? 'block' : 'none' }" @click.stop>
              <li  v-for="(child, childIndex) in menu.children" :key="childIndex">
                <router-link :to="child.href">
                  {{ child.title }}
                </router-link>
              </li>
            </ul>
          </li>
        </ul>
    </div>
    <div id="main" :class="{mainopen: opened}">
      <router-view></router-view>
    </div>
    <BModal v-model="lagoutModal"  hideHeader="true" @ok="logoutModal">
      <p class="my-2 text-center">Are you sure you want to logout?</p>
    </BModal>
  <!-- <content-footer /> -->
</template>


<script>
  import { mapGetters } from "vuex";
  import store from '../../Store';
  
  import {managerMenus,adminMenus} from "../../Routes/menus.js";


  let menus = [];

  import "../../Assets/scss/navbar.scss";
  export default {
      name: 'GuestLayout',
      data() {
          return {
              authname:null,
              lagoutModal:false
          }
      },
      computed: {
        ...mapGetters({
          user: "auth/user"
        })
      },
      created() {
            this.getUser();
            this.authname = store.getters["auth/user"].name;;
            if(store.state.auth.user.role == "admin"){
               this.menuItem = adminMenus;
            }else if(store.state.auth.user.role == "manager"){
               this.menuItem = managerMenus;
            }
            
            this.opened = true
      },
      methods: {
        async logout() {
          this.lagoutModal = true;
         // await store.dispatch('auth/logout');
        },
        async logoutModal() {
          //this.lagoutModal = false;
          await store.dispatch('auth/logout');
        },
        async getUser(){
          await store.dispatch('auth/getUser')
        }
      },
      components: {
      }
  }
</script>

