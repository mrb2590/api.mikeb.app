// import CreateNote from './components/CreateNote.vue'
import Login from './components/auth/Login.vue';
import FileList from './components/files/List.vue';

const routes = [
    {
        name: 'home',
        path: '/',
        component: Login
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: FileList
    },
    {
        name: 'files',
        path: '/files',
        component: FileList
    },
    {
        name: 'all',
        path: '/*',
        redirect: '/'
    },
];

export default routes;
