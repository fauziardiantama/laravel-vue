import {createRouter, createWebHistory} from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'Landing',
        component: () => import('@/components/LandingPage.vue'),
    },
    {
        path: '/about',
        name: 'About',
        component: () => import('@/components/About.vue'),
    },
    {
        path: '/home',
        name: 'Home',
        component: () => import('@/components/Home.vue'),
    },
    {
        path: '/kmm',
        name: 'Kmm',
        component: () => import('@/components/Kmm.vue'),
        redirect: '/kmm/dashboard',
        children: [
        {
          path: '/kmm/dashboard',
          name: 'Dashboard',
          // route level code-splitting
          // this generates a separate chunk (about.[hash].js) for this route
          // which is lazy-loaded when the route is visited.
          component: () => import(/* webpackChunkName: "dashboard" */ '@/views/Dashboard.vue'),
        },
        {
          path: '/kmm/crud',
          name: 'Crud',
          // route level code-splitting
          // this generates a separate chunk (about.[hash].js) for this route
          // which is lazy-loaded when the route is visited.
          component: () => import(/* webpackChunkName: "dashboard" */ '@/views/Crud.vue'),
        }
      ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;