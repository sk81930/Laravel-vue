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
        async getUsers({commit},data) {
                return await agent.Admin.getUsers(data);
        },
        async AddEditUser({commit},data) {
                return await agent.Admin.AddEditUser(data);
        },
        async getUserById({commit},data) {
                return await agent.Admin.getUserById(data);
        },
    }
}