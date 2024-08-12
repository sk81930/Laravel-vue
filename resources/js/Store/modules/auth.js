import axios from 'axios';
import agent from '../../agent';
import store from '../../Store';
export default {
    namespaced: true,
    state: {
        isLogin: false,
        user: null,
        token: null,
    },
    getters: {
        isLogin(state) {
            return state.isLogin
        },
        user(state) {
            return state.user
        },
        token(state) {
            return state.token
        }
    },
    mutations: {
        setLogin(state, value) {
            state.isLogin = value
        },
        setUser(state, value) {
            state.user = value
        },
        setToken(state, value) {
            state.token = value
            state.isLogin = true
        },
        LogOut(state){
            state.user = null
            state.token = null
            state.isLogin = false
        },
    },
    actions: {
        async getUser({commit}) {
                  await agent.Auth.user().then(response => {

                      if(response.data.user){
                        store.commit('auth/setUser', response.data.user);
                      }
                      return response;
                  })
                  .catch(error => {
                     commit('setUser', null)
                     commit('setToken', null)
                     commit('setLogin', false)
                     //this.$router.push('/')
                     window.location.reload();
                  });
        },
        async getUserRole({commit}) {

            return await agent.Auth.getUserRole();
        },
        async logout({commit}) {
                  await agent.Auth.logout().then(response => {
                      if(response.data.message){
                         commit('setUser', null)
                         commit('setToken', null)
                         commit('setLogin', false)
                         window.location.reload();
                      }
                      return response;
                  })
                  .catch(error => {
                     window.location.reload();
                  });
               //this.$router.push('/')
              // window.location.reload();
        },
    }
}