
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./app/custom');

window.Vue = require('vue');

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);

import VueTimeago from 'vue-timeago';

Vue.use(VueTimeago, {
    name: 'timeago', // component name, `timeago` by default
    locale: 'en-US',
    locales: {
        // you will need json-loader in webpack 1
        'en-US': require('vue-timeago/locales/en-US.json')
    }
});

var VueScrollTo = require('vue-scrollto');
Vue.use(VueScrollTo)

import VCalendar from 'v-calendar';

// Use v-calendar, v-date-picker & v-popover components
Vue.use(VCalendar)

import flatPickr from 'vue-flatpickr-component';
Vue.component('flatPickr', flatPickr);

import JobsIndex from './components/jobs/Index.vue';
import TenantJobsIndex from './components/tenant/jobs/Index.vue'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('jobs-index', JobsIndex);
Vue.component('tenant-job-index', TenantJobsIndex);

const routes = [
    {
        path: '/jobs/listings',
        name: 'jobs.index',
        component: JobsIndex
    },
    {
        path: '/company/manage/jobs/index',
        name: 'tenant.jobs.index',
        component: TenantJobsIndex
    }
];

const router = new VueRouter({
    mode: 'history',
    routes
});

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

Vue.component(
    'user-education-index',
    require('./components/account/education/Index.vue')
);

Vue.component(
    'user-skills-index',
    require('./components/account/skills/Index.vue')
);

Vue.component(
    'user-languages-index',
    require('./components/account/languages/Index.vue')
);

Vue.component(
    'user-portfolios-index',
    require('./components/account/portfolios/Index.vue')
);

Vue.component(
    'avatar-upload',
    require('./components/account/avatar/AvatarUpload.vue')
);

const app = new Vue({
    el: '#app',
    router
});
