import axios from 'axios';
import store from './Store';

const API_ROOT = "";

let token = null;
const tokenPlugin = (req) => {
  	// if (token) {
    // 	req.set("Authorization", `Bearer ${token}`);
  	// }
};

const instance = axios.create({});

instance.interceptors.request.use(
  config => {
    if(store.state.auth.token){
    	config.headers["Authorization"] = "Bearer " + store.state.auth.token;
    }
    config.headers["Content-Type"] = "application/json";
    return config;
  },
  error => {
    Promise.reject(error);
  }
);


const requests = {


	del: (url) =>
		instance.del(`${API_ROOT}${url}`),
	get: (url, body = "") =>	{
		if(body != ""){
			  body = { params: body }
		}
		return instance.get(`${API_ROOT}${url}`, body)
	},
	put: (url, body) =>
		instance
			.put(`${API_ROOT}${url}`, body),
	post: (url, body) =>
		instance
			.post(`${API_ROOT}${url}`, body)
}

const Auth = {
	user: () => requests.get("/api/user"),
	logout: () => requests.post("/api/logout",{}),
	login: (formData) => requests.post("/api/login", formData),
	getUserRole: () => requests.get("/api/getUserRole"),
}
const User = {
	updateProfile: (formData) => requests.post("/api/updateProfile",formData),
}
const Admin = {
	getUsers: (data) => requests.get("/api/getUsers",data),
	AddEditUser: (formData) => requests.post("/api/AddEditUser",formData),
	getUserById: (data) => requests.get("/api/getUserById/"+data.userid,""),
}
const Manager = {
	getProjects: (data) => requests.get("/api/getProjects",data),
	AddEditProject: (formData) => requests.post("/api/AddEditProject",formData),
	getProjectById: (data) => requests.get("/api/getProjectById/"+data.id,""),
}
const Project = {
	getAllProject: () => requests.get("/api/getAllProject"),
}
const Task = {
	AddEditTask: (formData) => requests.post("/api/AddEditTask",formData),
	getTasks: (data) => requests.get("/api/getTasks",data),
	getTaskById: (data) => requests.get("/api/getTaskById/"+data.id,""),
	AddComment: (formData) => requests.post("/api/AddComment/",formData),
	getComments: (data) => requests.get("/api/getComments/"+data.id,data),
}
export default {
	Auth,
	User,
	Admin,
	Manager,
	Project,
	Task,
	setToken: (_token) => {
    	token = _token;
  	},
};