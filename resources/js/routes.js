import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from './components/HomeComponent'
import Show from './components/ShowComponent'
import Form from './components/FormComponent'
import Projects from './components/ProjectsComponent'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/project/create',
            name: 'form',
            component: Form,
        },
        {
            path: '/project/:id',
            name: 'show',
            component: Show,
        },

        {
            path: '/project',
            name: 'project',
            component: Projects,
        },
    ],
});

export default router;