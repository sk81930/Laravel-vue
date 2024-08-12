import { createWebHistory, createRouter } from "vue-router";
import GuestLayout from '../Views/Layout/GuestLayout.vue'
import AuthLayout from '../Views/Layout/AuthLayout.vue'

// Admin

import AdminDashboard from '../Views/Admin/AdminDashboard.vue'
import AdminProfile from '../Views/Admin/Profile'
import Users from '../Views/Admin/Users'
import AddEditUser from '../Views/Admin/Users/AddEditUser'


// Manager
import ManagerDashboard from '../Views/Manager/ManagerDashboard.vue'
import ManagerProfile from '../Views/Manager/Profile'

// Projects
import Projects from '../Views/Projects'
import AddEditProject from '../Views/Projects/AddEditProject'

// Tasks
import Tasks from '../Views/Tasks'
import AddEditTask from '../Views/Tasks/AddEditTask'
import ViewTask from '../Views/Tasks/ViewTask'



import Login from "../Views/Auth/Login.vue";

import store from '../Store';

function requireLogin(to, from, next) {
    let isLogin = false;

    isLogin = !!store.state.auth.isLogin;


    if (!isLogin) {
        next("/")
    } else {
        next()
    }
}
function guest(to, from, next) {
    let isLogin;
    isLogin = !!store.state.auth.isLogin;

    if (isLogin) {
        if(store.state.auth.user.role == "admin"){
            next("/admin/dashboard")
        }else if(store.state.auth.user.role == "manager"){
            next("/manager/dashboard")
        }else{
            next()
        }
       
    } else {
        next()
    }
}

let routes = [];

routes.push({
    path: '/',
    component: GuestLayout,
    children: [
        {
            path: '/',
            name: 'Login',
            component: Login,
            beforeEnter: guest,
            
        },
    ]  
});

routes.push({
    path: '/',
    name: 'Admin',
    component: AuthLayout,
    beforeEnter: requireLogin,
    meta: { authorize: "admin" },
    children: [
        {
            path: '/admin/dashboard',
            name: 'Admin Dashboard',
            component: AdminDashboard,
        },
        {
            path: '/admin/users',
            name: 'Users',
            component: Users,
        },
        {
            path: '/admin/users/add',
            name: 'Add user',
            component: AddEditUser,
        },
        {
            path: '/admin/users/edit/:id',
            name: 'Edit user',
            component: AddEditUser,
        },
        {
            path: '/admin/profile',
            name: 'Admin Profile',
            component: AdminProfile,
        },
    ]  
});


routes.push({
    path: '/',
    name: 'Manager',
    component: AuthLayout,
    beforeEnter: requireLogin,
    meta: { authorize: "manager" },
    children: [
        {
            path: '/manager/dashboard',
            name: 'Manager Dashboard',
            component: ManagerDashboard,
        },
        {
            path: '/manager/profile',
            name: 'Manager Profile',
            component: ManagerProfile,
        },
        {
            path: '/manager/projects',
            name: 'Projects',
            component: Projects,
        },
        {
            path: '/manager/projects/add',
            name: 'Add Project',
            component: AddEditProject,
        },
        {
            path: '/manager/projects/edit/:id',
            name: 'Edit Project',
            component: AddEditProject,
        },
        {
            path: '/manager/tasks',
            name: 'Tasks',
            component: Tasks,
        },
        {
            path: '/manager/tasks/add',
            name: 'Add Task',
            component: AddEditTask,
        },
        {
            path: '/manager/tasks/edit/:id',
            name: 'Edit Task',
            component: AddEditTask,
        },
        {
            path: '/manager/tasks/view/:id',
            name: 'View Task',
            component: ViewTask,
        },
    ]  
});


const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const { authorize } = to.meta;
    if (authorize) {
        if(!store.state.auth.user){
           return next({ path: '/' });
        }

        // check if route is restricted by role
        if (authorize.length && authorize != store.state.auth.user.role) {
            // role not authorised so redirect to home page
            return next({ path: '/' });
        }
    }

    next();
})



export default router;