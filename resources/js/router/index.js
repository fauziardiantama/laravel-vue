import {createRouter, createWebHistory} from 'vue-router';
import {h, resolveComponent} from 'vue';
import axios from 'axios';

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
                path: 'dashboard',
                name: 'Dashboard',
                component: () => import(/* webpackChunkName: "dashboard" */ '@/views/Dashboard.vue'),
            },
            {
                path: 'crud',
                name: 'Crud',
                component: () => import(/* webpackChunkName: "dashboard" */ '@/views/Crud.vue'),
            },
        ],
    },
    {
        path: '/mahasiswa',
        name: 'Mahasiswa',
        component: {
            render() {
              return h(resolveComponent('router-view'))
            },
          },
        redirect: 'mahasiswa/login',
        children: [
            {
                path: 'login',
                name: 'MahasiswaLogin',
                component: () => import('@/components/MahasiswaLogin.vue'),
            },
            {
                path: 'register',
                name: 'MahasiswaRegister',
                component: () => import('@/components/Register.vue'),
            },
            {
                path: 'kmm',
                name: 'MahasiswaKmm',
                component: () => import('@/components/KmmMahasiswa.vue'),
                redirect: 'kmm/dashboard',
                meta: { mahasiswa: true },
                children: [
                    {
                        path: 'dashboard',
                        name: 'MahsiswaKmmDashboard',
                        component: () => import('@/views/Dashboard.vue'),
                    }
                ]
            }
        ]
    },
    {
        path: '/dosen',
        name: 'Dosen',
        component: {
            render() {
              return h(resolveComponent('router-view'))
            },
          },
        redirect: '/dosen/login',
        children: [
            {
                path: 'login',
                name: 'DosenLogin',
                component: () => import('@/components/DosenLogin.vue'),
            },
            {
                path: 'kmm',
                name: 'DosenKmm',
                component: () => import('@/components/KmmDosen.vue'),
                redirect: 'kmm/dashboard',
                children: [
                    {
                        path: 'dashboard',
                        name: 'DosenKmmDashboard',
                        component: () => import('@/views/Dashboard.vue'),
                    }
                ]
            }
        ]
    },
    {
        path: '/admin',
        name: 'Admin',
        redirect: '/admin/login',
        component: {
            render() {
              return h(resolveComponent('router-view'))
            },
        },
        children: [
            {
                path: 'login',
                name: 'AdminLogin',
                component: () => import('@/components/AdminLogin.vue'),
            },
            {
                path: 'kmm',
                name: 'AdminKmm',
                component: () => import('@/components/Kmm.vue'),
                redirect: 'kmm/dashboard',
                children: [
                    {
                        path: 'dashboard',
                        name: 'AdminKmmDashboard',
                        component: () => import('@/views/Dashboard.vue'),
                    },
                    {
                        path: 'crud',
                        name: 'AdminKmmCrud',
                        component: () => import('@/views/Crud.vue'),
                    }
                ]
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.mahasiswa)) {
      // Route requires authentication
      try {
        const response = await axios.get('/api/mahasiswa');
        if (response.status === 200 && response.data) {
          // User is authenticated
          console.log('User is authenticated');
          next();
        } else {
          // Handle potential errors with authentication response
          console.error('Authentication error:', response.data);
          next({ name: 'login' }); // Redirect to login
        }
      } catch (e) {
        if (e.response.status === 401) {
            console.log(e.response.data);
            next({ name: 'MahasiswaLogin' });
          } else {
            console.log(e);
          }
        ; // Redirect to login
      }
    } else if (to.matched.some(record => record.meta.dosen)) {
         // Route requires authentication
      try {
        const response = await axios.get('/api/dosen');
        if (response.status === 200 && response.data) {
          // User is authenticated
          console.log('User is authenticated');
          next();
        } else {
          // Handle potential errors with authentication response
          console.error('Authentication error:', response.data);
          next({ name: 'DosenLogin' }); // Redirect to login
        }
      } catch (e) {
        if (e.response.status === 401) {
            console.log(e.response.data);
            next({ name: 'DosenLogin' });
          } else {
            console.log(e);
          }
        ; // Redirect to login
      }
    } else if (to.matched.some(record => record.meta.admin)) {
         // Route requires authentication
      try {
        const response = await axios.get('/api/admin');
        if (response.status === 200 && response.data) {
          // User is authenticated
          console.log('User is authenticated');
          next();
        } else {
          // Handle potential errors with authentication response
          console.error('Authentication error:', response.data);
          next({ name: 'AdminLogin' }); // Redirect to login
        }
      } catch (e) {
        if (e.response.status === 401) {
            console.log(e.response.data);
            next({ name: 'AdminLogin' });
          } else {
            console.log(e);
          }
        ; // Redirect to login
      }
    } else {
      // Route doesn't require authentication
      next();
    }
  });
  
export default router;