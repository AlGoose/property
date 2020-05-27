import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from './components/HomeComponent'
import Show from './components/ShowComponent'
import Form from './components/FormComponent'
import Projects from './components/ProjectsComponent'
import Managers from './components/ManagersComponent'
import NotFound from './components/NotFoundComponent'
import Report from './components/ReportComponent'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },

        {
            path: '/project/:id/edit',
            name: 'edit',
            component: Form,
        },

        {
            path: '/project/:id',
            name: 'show',
            component: Show,
        },

        {
            path: '/project/create',
            name: 'form',
            component: Form,
        },

        {
            path: '/project',
            name: 'project',
            component: Projects,
        },

        {
            path: '/managers',
            name: 'managers',
            component: Managers,
        },

        {
            path: '/reports',
            name: 'reports',
            component: Report,
        },

        {
            path: '/404',
            name: '404',
            component: NotFound,
        }, {
            path: '*',
            redirect: '/404'
        }
    ],
});

export default router;