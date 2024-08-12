// resources/app.js

require('./bootstrap');
// import './Assets/css/bootstrap.min.css';
// import './Assets/js/bootstrap.bundle.min.js';



import { createApp } from 'vue';
import router from './Routes/index.js';
import MyApp from './components/MyApp.vue';

import Store from './Store';

import {createBootstrap} from 'bootstrap-vue-next'


import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'

import './Assets/scss/common.scss';



createApp({
    components: {
        MyApp,
    }
}).use(router)
.use(createBootstrap({components: true, directives: true}))
.use(Store)
.mount('#app');