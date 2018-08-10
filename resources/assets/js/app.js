import Vue from 'vue';
import VueRouter from 'vue-router';
import VueMaterial from 'vue-material';
import App from './components/App.vue';
import routes from './routes';
import filesStore from './store/filesStore';

Vue.use(VueMaterial);
Vue.use(VueRouter);

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes
});

window.events = new Vue();

window.flash = function(message, type = 'success') {
    window.events.$emit('flash', message, type);
};

new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store: filesStore,
});
