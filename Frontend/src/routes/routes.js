import login from '../views/principals/login.vue';


const routes = [
    {
        path: '/login',
        name: 'login',
        component: login,
        
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;