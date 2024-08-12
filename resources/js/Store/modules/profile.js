import axios from 'axios';
import agent from '../../agent';
import store from '../../Store';
export default {
    namespaced: true,
    state: {
        errors: null,
    },
    getters: {
        setErrors(state, value) {
            state.errors = value
        },
    },
    mutations: {
    },
    actions: {
        async updateProfile({commit},data) {

                return await agent.User.updateProfile(data);
        },
    }
}