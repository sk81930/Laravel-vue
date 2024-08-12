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
        async AddEditTask({commit},data) {

                return await agent.Task.AddEditTask(data);
        },
        async getTasks({commit},data) {

                return await agent.Task.getTasks(data);
        },
        async getTaskById({commit},data) {

                return await agent.Task.getTaskById(data);
        },
        async AddComment({commit},data) {

                return await agent.Task.AddComment(data);
        },
        async getComments({commit},data) {

                return await agent.Task.getComments(data);
        },
    }
}