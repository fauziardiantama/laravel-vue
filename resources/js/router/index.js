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
        path: '/mahasiswa',
        name: 'Mahasiswa',
        component: {
            render() {
              return h(resolveComponent('router-view'))
            },
          },
        redirect: { name: 'MahasiswaLogin'},
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
                path: 'ta',
                name: 'MahasiswaTa',
                component: () => import('@/components/TaMahasiswa.vue'),
                redirect: { name: 'MahasiswaTaDashboard'},
                meta: { mahasiswa: true },
                children: [
                    {
                        path: 'dashboard',
                        name: 'MahasiswaTaDashboard',
                        component: () => import('@/views/Mahasiswa/Ta/Dashboard.vue'),
                    },
                    {
                        path: 'proposal',
                        name: 'MahasiswaTaProposal',
                        meta: { actived: true },
                        component: () => import('@/views/Mahasiswa/Ta/Proposal.vue'),
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
        redirect: { name: 'DosenLogin'},
        children: [
            {
                path: 'login',
                name: 'DosenLogin',
                component: () => import('@/components/DosenLogin.vue'),
            },
            {
                path: 'Ta',
                name: 'DosenTa',
                component: () => import('@/components/TaDosen.vue'),
                redirect: { name: 'DosenTaDashboard'},
                meta: { dosen: true },
                children: [
                    {
                        path: 'dashboard',
                        name: 'DosenTaDashboard',
                        component: () => import('@/views/Dosen/Ta/Dashboard.vue'),
                    }
                ]
            }
        ]
    },
    {
        path: '/admin',
        name: 'Admin',
        redirect: { name: 'AdminLogin'},
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
                path: 'Ta',
                name: 'AdminTa',
                component: () => import('@/components/TaAdmin.vue'),
                redirect: { name: 'AdminTaDashboard'},
                meta: { admin: true },
                children: [
                    {
                        path: 'dashboard',
                        name: 'AdminTaDashboard',
                        component: () => import('@/views/Admin/Ta/Dashboard.vue'),
                    },
                    {
                        path: 'mahasiswa',
                        name: 'AdminTaMahasiswa',
                        component: () => import('@/views/Admin/Ta/Mahasiswa.vue'),
                    },
                    {
                        path: 'proposal',
                        name: 'AdminTaProposal',
                        component: () => import('@/views/Admin/Ta/Proposal.vue'),
                    },
                    {
                        path: 'tahunakademik',
                        name: 'AdminTaTahunAkademik',
                        component: () => import('@/views/Admin/Ta/TahunAkademik.vue'),
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
      try {
        console.log('check mahasiswa');
        const response = await axios.get('/api/user');
        if (response.status === 200 && response.data.token.name == "mahasiswa") {
          console.log('berhasil');
          next();
        } else {
          console.log('gagal');
          console.log(response.data);
          next({ name: 'MahasiswaLogin' });
        }
      } catch (e) {
        if (e.response.status === 401) {
            console.log(e.response.data);
            next({ name: 'MahasiswaLogin' });
          } else {
            console.log(e);
          } // Redirect to login
      }
    } else if (to.matched.some(record => record.meta.dosen)) {
      try {
        const response = await axios.get('/api/user');
        if (response.status === 200 && response.data.token.name == "dosen") {
          next();
        } else {
          next({ name: 'DosenLogin' });
        }
      } catch (e) {
        if (e.response.status === 401) {
            console.log(e.response.data);
            next({ name: 'DosenLogin' });
          } else {
            console.log(e);
          } // Redirect to login
      }
    } else if (to.matched.some(record => record.meta.admin)) {
         // Route requires authentication
         try {
          const response = await axios.get('/api/user');
          if (response.status === 200 && response.data.token.name == "admin") {
            next();
          } else {
            next({ name: 'AdminLogin' });
          }
        } catch (e) {
          if (e.response.status === 401) {
              console.log(e.response.data);
              next({ name: 'AdminLogin' });
            } else {
              console.log(e);
            } // Redirect to login
        }
    } else if (to.matched.some(record => record.meta.actived)) {
        try {
          const response = await axios.get('/api/user');
          if (response.status === 200 && response.data.user.status == 1) {
            next();
          } else {
            next({ name: 'MahasiswaTaDashboard' });
          }
        } catch (e) {
          if (e.response.status === 401) {
              console.log(e.response.data);
              next({ name: 'MahasiswaLogin' });
            } else {
              console.log(e);
            }// Redirect to login
        }
    } else {
      // Route doesn't require authentication
      next();
    }
  });
  
export default router;