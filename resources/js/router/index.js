import {createRouter, createWebHistory} from 'vue-router';
import {h, resolveComponent} from 'vue';
import axios from 'axios';
import NProgress from 'nprogress';

const routes = [
    {
      path: '/test',
      name: 'TestingOne',
      meta: {
        title: 'testing one'
      },
      component: () => import('@/components/TestingOne.vue'),
    },
    {
      path: '/test2',
      name: 'TestingTwo',
      meta: {
        title: 'testing two'
      },
      component: () => import('@/components/TestingTwo.vue'),
      children: [
        {
            path: 'test3',
            name: 'TestingThree',
            meta: {
              title: 'testing three'
            },
            component: () => import('@/components/TestingThree.vue'),
        }
      ]
    },
    {
        path: '/',
        name: 'Landing',
        meta: {
          title: 'Dashboard'
        },
        component: () => import('@/components/LandingPage.vue'),
    },
    {
        path: '/about',
        name: 'About',
        meta: {
          title: 'About'
        },
        component: () => import('@/components/About.vue'),
    },
    {
        path: '/home',
        name: 'Home',
        meta: {
          title: 'Home'
        },
        component: () => import('@/components/Home.vue'),
    },
    {
        path: '/mahasiswa',
        name: 'Mahasiswa',
        meta: {
          title: 'Mahasiswa'
        },
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
                meta: {
                  title: 'Login Mahasiswa'
                },
                component: () => import('@/components/MahasiswaLogin.vue'),
            },
            {
                path: 'register',
                name: 'MahasiswaRegister',
                meta: {
                  title: 'Register Mahasiswa'
                },
                component: () => import('@/components/Register.vue'),
            },
            {
                path: 'ta',
                name: 'MahasiswaTa',
                meta: {
                  title: 'Tugas Akhir',
                  mahasiswa: true,
                  redirect: 'MahasiswaTa'
                },
                component: () => import('@/components/TaMahasiswa.vue'),
                redirect: { name: 'MahasiswaTaDashboard'},
                children: [
                    {
                        path: 'dashboard',
                        name: 'MahasiswaTaDashboard',
                        meta: {
                          title: 'Dashboard'
                        },
                        component: () => import('@/views/Mahasiswa/Ta/Dashboard.vue'),
                    },
                    {
                        path: 'proposal',
                        name: 'MahasiswaTaProposal',
                        meta: {
                          title: 'Proposal Tugas Akhir',
                          actived: true,
                          dashboard: 'MahasiswaTaDashboard'
                        },
                        component: () => import('@/views/Mahasiswa/Ta/Proposal.vue'),
                    },
                    {
                        path: 'pembimbing',
                        name: 'MahasiswaTaPembimbing',
                        meta: {
                          title: 'Pembimbing',
                          actived: true,
                          dashboard: 'MahasiswaTaDashboard'
                        },
                        component: () => import('@/views/Mahasiswa/Ta/Pembimbing.vue'),
                    },
                    {
                        path: 'jadwal',
                        name: 'MahasiswaTaJadwal',
                        meta: {
                          title: 'Jadwal',
                          actived: true,
                          dashboard: 'MahasiswaTaDashboard'
                        },
                        component: () => import('@/views/Mahasiswa/Ta/JadwalProposal.vue'),
                    }
                ]
            },
            {
                path: 'kmm',
                name: 'MahasiswaKmm',
                meta: {
                  title: 'KMM',
                  mahasiswa: true,
                  redirect: 'MahasiswaKmm'
                },
                component: () => import('@/components/KmmMahasiswa.vue'),
                redirect: { name: 'MahasiswaKmmDashboard'},
                children: [
                    {
                        path: 'dashboard',
                        name: 'MahasiswaKmmDashboard',
                        meta: {
                          title: 'Dashboard'
                        },
                        component: () => import('@/views/Mahasiswa/Kmm/Dashboard.vue'),
                    },
                    {
                        path: 'magang',
                        name: 'MahasiswaKmmMagang',
                        meta: {
                          title: 'Magang',
                          actived: true,
                          dashboard: 'MahasiswaKmmDashboard'
                        },
                        component: () => import('@/views/Mahasiswa/Kmm/Magang.vue'),
                    },
                    {
                        path: 'dokumen',
                        name: 'MahasiswaKmmDokumen',
                        meta: {
                          title: 'Dokumen',
                          actived: true,
                          dashboard: 'MahasiswaKmmDashboard'
                        },
                        component: () => import('@/views/Mahasiswa/Kmm/Dokumen.vue'),
                    }
                ]
            }
        ]
    },
    {
        path: '/dosen',
        name: 'Dosen',
        meta: {
          title: 'Dosen'
        },
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
                meta: {
                  title: 'Login Dosen'
                },
                component: () => import('@/components/DosenLogin.vue'),
            },
            {
                path: 'ta',
                name: 'DosenTa',
                meta: {
                  title: 'Tugas Akhir',
                  dosen: true
                },
                component: () => import('@/components/TaDosen.vue'),
                redirect: { name: 'DosenTaDashboard'},
                children: [
                    {
                        path: 'dashboard',
                        name: 'DosenTaDashboard',
                        meta: {
                          title: 'Dashboard'
                        },
                        component: () => import('@/views/Dosen/Ta/Dashboard.vue'),
                    },
                    {
                        path: 'jadwal',
                        name: 'DosenTaJadwal',
                        meta: {
                          title: 'Jadwal'
                        },
                        component: () => import('@/views/Dosen/Ta/JadwalProposal.vue'),
                    },
                ]
            },
            {
                path: 'kmm',
                name: 'DosenKmm',
                meta: {
                  title: 'KMM',
                  dosen: true
                },
                component: () => import('@/components/KmmDosen.vue'),
                redirect: { name: 'DosenKmmDashboard'},
                children: [
                    {
                        path: 'dashboard',
                        name: 'DosenKmmDashboard',
                        meta: {
                          title: 'Dashboard'
                        },
                        component: () => import('@/views/Dosen/Kmm/Dashboard.vue'),
                    },
                    {
                        path: 'bimbingan',
                        name: 'DosenKmmBimbingan',
                        meta: {
                          title: 'Bimbingan'
                        },
                        component: () => import('@/views/Dosen/Kmm/Bimbingan.vue'),
                    },
                    {
                        path: 'topik',
                        name: 'DosenKmmTopik',
                        meta: {
                          title: 'Topik'
                        },
                        component: () => import('@/views/Dosen/Kmm/Topik.vue')
                    }
                ]
            }
        ]
    },
    {
        path: '/admin',
        name: 'Admin',
        meta: {
          title: 'Admin'
        },
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
                meta: {
                  title: 'Login Admin'
                },
                component: () => import('@/components/AdminLogin.vue'),
            },
            {
                path: 'ta',
                name: 'AdminTa',
                meta: {
                  title: 'Tugas Akhir',
                  admin: true
                },
                component: () => import('@/components/TaAdmin.vue'),
                redirect: { name: 'AdminTaDashboard'},
                children: [
                    {
                        path: 'dashboard',
                        name: 'AdminTaDashboard',
                        meta: {
                          title: 'Dashboard'
                        },
                        component: () => import('@/views/Admin/Ta/Dashboard.vue'),
                    },
                    {
                        path: 'mahasiswa',
                        name: 'AdminTaMahasiswa',
                        meta: {
                          title: 'Mahasiswa'
                        },
                        component: () => import('@/views/Admin/Ta/Mahasiswa.vue'),
                    },
                    {
                        path: 'proposal',
                        name: 'AdminTaProposal',
                        meta: {
                          title: 'Proposal TA'
                        },
                        component: () => import('@/views/Admin/Ta/Proposal.vue'),
                    },
                    {
                        path: 'jadwal',
                        name: 'AdminTaJadwal',
                        meta: {
                          title: 'Jadwal'
                        },
                        component: () => import('@/views/Admin/Ta/JadwalProposal.vue'),
                    },
                    {
                        path: 'ruangan',
                        name: 'AdminTaRuangan',
                        meta: {
                          title: 'Ruangan'
                        },
                        component: () => import('@/views/Admin/Ta/Ruangan.vue'),
                    },
                    {
                        path: 'sesi',
                        name: 'AdminTaSesi',
                        meta: {
                          title: 'Sesi'
                        },
                        component: () => import('@/views/Admin/Ta/Sesi.vue'),
                    },
                    {
                        path: 'tahunakademik',
                        name: 'AdminTaTahunAkademik',
                        meta: {
                          title: 'Tahun Akademik'
                        },
                        component: () => import('@/views/Admin/Ta/TahunAkademik.vue'),
                    }
                ]
            },
            {
                path: 'kmm',
                name: 'AdminKmm',
                meta: {
                  title: 'KMM',
                  admin: true
                },
                component: () => import('@/components/KmmAdmin.vue'),
                redirect: { name: 'AdminKmmDashboard'},
                children: [
                    {
                        path: 'dashboard',
                        name: 'AdminKmmDashboard',
                        meta: {
                          title: 'Dashboard'
                        },
                        component: () => import('@/views/Admin/Kmm/Dashboard.vue'),
                    },
                    {
                      path: 'mahasiswa',
                      name: 'AdminKmmMahasiswa',
                      meta: {
                        title: 'Mahasiswa'
                      },
                      component: () => import('@/views/Admin/Kmm/Mahasiswa.vue'),
                    },
                    {
                      path: 'tahunakademik',
                      name: 'AdminKmmTahunAkademik',
                      meta: {
                        title: 'Tahun Akademik'
                      },
                      component: () => import('@/views/Admin/Kmm/TahunAkademik.vue'),
                    },
                    {
                      path: 'instansi',
                      name: 'AdminKmmInstansi',
                      meta: {
                        title: 'Instansi'
                      },
                      component: () => import('@/views/Admin/Kmm/Instansi.vue'),
                    },
                    {
                      path: 'surat',
                      name: 'AdminKmmSurat',
                      meta: {
                        title: 'Surat'
                      },
                      component: () => import('@/views/Admin/Kmm/Surat.vue'),
                    },
                    {
                      path: 'dosen',
                      name: 'AdminKmmDosen',
                      meta: {
                        title: 'Dosen'
                      },
                      component: () => import('@/views/Admin/Kmm/Dosen.vue'),
                    },
                    {
                      path: 'topik',
                      name: 'AdminKmmTopik',
                      meta: {
                        title: 'Topik'
                      },
                      component: () => import('@/views/Admin/Kmm/Topik.vue'),
                    },
                    {
                      path: 'progres',
                      name: 'AdminKmmProgres',
                      meta: {
                        title: 'Progres'
                      },
                      component: () => import('@/views/Admin/Kmm/Progres.vue'),
                    },
                    {
                      path: 'magang',
                      name: 'AdminKmmMagang',
                      meta: {
                        title: 'Magang'
                      },
                      component: () => import('@/views/Admin/Kmm/Magang.vue'),
                    },
                    {
                      path: 'informasi',
                      name: 'AdminKmmInformasi',
                      meta: {
                        title: 'Informasi'
                      },
                      component: () => import('@/views/Admin/Kmm/Informasi.vue'),
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
    // If this isn't an initial page load.
    if (to.name) {
      // Start the route progress bar.
      NProgress.start()
    }
    document.title = to.meta.title ? to.meta.title+' | D3TI KMM-TA' : 'D3TI KMM-TA';

    const roles = ['mahasiswa', 'dosen', 'admin'];

    for (let role of roles) {
      if (to.matched.some(record => record.meta[role])) {
        try {
          const response = await axios.get('/api/user');
          if (response.status === 200 && response.data.auth.name == role) {
            console.log('berhasil');
            break;
          } else {
            console.log('gagal');
            console.log(response.data);
            const arr = to.matched.filter(record => record.meta[role]);
            const redirect = arr[0].meta.redirect;
            if (!redirect) {
              next({ name: `${role.charAt(0).toUpperCase() + role.slice(1)}Login` });
            } else {
              next({ name: `${role.charAt(0).toUpperCase() + role.slice(1)}Login`, query: { redirect: redirect } });
            }
          }
        } catch (e) {
          if (e.response.status === 401) {
            console.log(e.response.data);
            const arr = to.matched.filter(record => record.meta[role]);
            const redirect = arr[0].meta.redirect;
            if (!redirect || redirect == '') {
              next({ name: `${role.charAt(0).toUpperCase() + role.slice(1)}Login` });
            } else {
              next({ name: `${role.charAt(0).toUpperCase() + role.slice(1)}Login`, query: { redirect: redirect } });
            }
          } else {
            console.log(e);
          }
        }
      }
    }

    if (to.matched.some(record => record.meta.actived)) {
      try {
        const response = await axios.get('/api/user');
        if (response.status === 200 && response.data.user.status == 1) {
          next();
        } else {
          const arr = to.matched.filter(record => record.meta.actived);
          const dashboard = arr[0].meta.dashboard;
          next({ name: dashboard });
        }
      } catch (e) {
        if (e.response.status === 401) {
          console.log(e.response.data);
          const arr = to.matched.filter(record => record.meta.actived);
          const dashboard = arr[0].meta.dashboard;
          next({ name: dashboard });
        } else {
          console.log(e);
        }
      }
    } else {
      next();
    }
});

router.afterEach((to, from) => {
  // Complete the animation of the route progress bar.
  NProgress.done()
})
  
export default router;