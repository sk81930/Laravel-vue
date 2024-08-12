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
        async getProjects({commit},data) {
                return await agent.Manager.getProjects(data);
        },
        async getProjectById({commit},data) {
                return await agent.Manager.getProjectById(data);
        },
        async AddEditProject({commit},data) {
                return await agent.Manager.AddEditProject(data);
        },
    }
}