require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import VueToast from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-default.css'
import 'vue-select/dist/vue-select.css';

import axios from 'axios'

import headerLayout from './components/layouts/header.vue'
import navLayout from './components/layouts/nav.vue'
import infiniteScroll from "vue-infinite-scroll";
import vSelect from 'vue-select'

import students from './components/students/list'
import studentsForm from './components/students/form'
import departments from './components/departments/list'
import departmentsForm from './components/departments/form'
import applicants from './components/applicants/list'
import applicantsForm from './components/applicants/form'
import groupsForm from './components/groups/form'

Vue.use(infiniteScroll);

Vue.use(VueProgressBar, {
    color: 'rgb(120, 222, 180)',
    failedColor: 'red',
    height: '7px'
});

Vue.use(VueRouter);

Vue.use(VueToast, {
    position: 'top',
    duration: 3000,
});

Vue.component('v-select', vSelect);

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const router = new VueRouter({
    mode: 'hash',
    routes: [
        { path: '/students', component: students },
        { path: '/students/create', component: studentsForm },
        { path: '/students/:id/edit', component: studentsForm },
        { path: '/applicants', component: applicants },
        { path: '/applicants/create', component: applicantsForm },
        { path: '/applicants/:id/edit', component: applicantsForm },
        { path: '/departments', component: departments },
        { path: '/departments/create', component: departmentsForm },
        { path: '/departments/:id/edit', component: departmentsForm },
        { path: '/groups/create', component: groupsForm },
    ],
    linkActiveClass: 'active-menu-item'
});

window.Vue = new Vue({
    router,

    components: {
        headerLayout,
        navLayout
    },

    template: `<div>

    <vue-progress-bar></vue-progress-bar>

    <header-layout></header-layout>

    <div class="container" style="margin-top: 40px">
        <div class="columns">
            <div class="column is-2">
                <nav-layout></nav-layout>
            </div>
            <div class="column">
                <router-view @dispatchEvent="dispatchEvent" :overlayActive="overlayActive"></router-view>
            </div>
        </div>
    </div>

</div>`,

    data: () => ({
        overlayActive: false,
        observer: {},
        STATUSES: {
            'studying': 'Учится',
            'expelled': 'Отчислен',
            'academic_leave': 'В академическом отпуске',
            'graduated': 'Выпустился'
        },
        ACADEMIC_DEGREES: {
            'bachelor': 'Бакалавр',
            'specialist': 'Специалист',
            'master': 'Магистр',
            'postgraduate': 'Аспирант'
        },
    }),

    created() {
        this.initializePageReload();
        this.lockModalOnScroll();
    },

    watch: {
        $route (to, from) {
            document.getElementsByTagName('body')[0].classList.remove('body-scroll-lock');

            this.lockModalOnScroll();

            window.scroll({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });
        }
    },

    methods: {
        dispatchEvent(event) {
            switch(event.code) {
                case 'showOverlay': this.overlayActive = true; break;
                case 'hideOverlay': this.overlayActive = false; break;

                default: console.warn('From dispatchEvent: cant find realisation for event code "' + event.code + '"');
            }
        },

        lockModalOnScroll() {
            setTimeout(function () {

                let MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
                let target = document.querySelector('.content');

                if(!target) return;

                this.observer = new MutationObserver(function(mutations) {

                    let element = document.querySelector('.modal.is-active');

                    if(element) {
                        document.getElementsByTagName('body')[0].classList.add('body-scroll-lock');
                    } else {
                        document.getElementsByTagName('body')[0].classList.remove('body-scroll-lock');
                    }
                });

                let config = { childList: true, subtree: true };
                this.observer.observe(target, config);

            }, 100);
        },

        initializePageReload() {

            let time = new Date().getTime();

            document.body.addEventListener("mouseover", function(e) {
                time = new Date().getTime();
            });

            document.body.addEventListener("keypress", function(e) {
                time = new Date().getTime();
            });

            function refresh() {
                if(new Date().getTime() - time >= 300000) {
                    window.location.reload(true);
                } else {
                    setTimeout(refresh, 300000);
                }
            }

            setTimeout(refresh, 300000);
        }
    }
}).$mount('#app');
