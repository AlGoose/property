require('./bootstrap');
import Vuetify from 'vuetify';
import Routes from './routes';

window.Vue = require('vue');
window.Vue.use(Vuetify);

window.vueApp = new Vue({
    vuetify: new Vuetify,
    router: Routes,
    el: '#app',
});

