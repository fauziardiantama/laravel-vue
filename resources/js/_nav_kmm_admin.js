export default [
  {
    component: 'CNavItem',
    name: 'Dashboard',
    to: { name: 'AdminKmmDashboard' },
    icon: 'cil-speedometer',
  },
  {
    component: 'CNavGroup',
    name: 'Pengguna',
    to: { name: 'AdminKmmMahasiswa' },
    icon: 'cil-people',
    items: [
      {
        component: 'CNavItem',
        name: 'Mahasiswa',
        to: { name: 'AdminKmmMahasiswa' }
      },
      {
        component: 'CNavItem',
        name: 'Instansi',
        to: { name: 'AdminKmmInstansi' }
      },
      {
        component: 'CNavItem',
        name: 'Dosen',
        to: { name: 'AdminKmmDosen' }
      }
    ]
  },
  {
    component: 'CNavItem',
    name: 'Surat',
    to: { name: 'AdminKmmSurat' },
    icon: 'cil-file',
  },
  {
    component: 'CNavItem',
    name: 'Magang',
    to: { name: 'AdminKmmMagang' },
    icon: 'cil-layers',
  },
  {
    component: 'CNavItem',
    name: 'Seminar',
    to: { name: 'AdminKmmSeminar' },
    icon: 'cil-star',
  },
  {
    component: 'CNavItem',
    name: 'Informasi',
    to: { name: 'AdminKmmInformasi' },
    icon: 'cil-notes',
  },
  {
    component: 'CNavGroup',
    name: 'Nilai',
    to: { name: 'AdminKmmBobotNilai' },
    icon: 'cil-calculator',
    items: [
      {
        component: 'CNavItem',
        name: 'Bobot Nilai',
        to: { name: 'AdminKmmBobotNilai' }
      },
      {
        component: 'CNavItem',
        name: 'Parameter Nilai',
        to: { name: 'AdminKmmParameterNilai' }
      }
    ]
  },
  {
    component: 'CNavGroup',
    name: 'Umum',
    to: { name: 'AdminKmmTopik' },
    icon: 'cil-settings',
    items: [
      {
        component: 'CNavItem',
        name: 'Topik',
        to: { name: 'AdminKmmTopik' }
      },
      {
        component: 'CNavItem',
        name: 'Progres',
        to: { name: 'AdminKmmProgres' }
      },
      {
        component: 'CNavItem',
        name: 'Ruangan',
        to: { name: 'AdminKmmRuangan' }
      },
      {
        component: 'CNavItem',
        name: 'Tahun Akademik',
        to: { name: 'AdminKmmTahunAkademik' }
      },
    ]
  }
  // {
  //   component: 'CNavItem',
  //   name: 'Download CoreUI',
  //   href: 'http://coreui.io/vue/',
  //   icon: { name: 'cil-cloud-download', class: 'text-white' },
  //   _class: 'bg-success text-white',
  //   target: '_blank'
  // },
  // {
  //   component: 'CNavItem',
  //   name: 'Try CoreUI PRO',
  //   href: 'http://coreui.io/pro/vue/',
  //   icon: { name: 'cil-layers', class: 'text-white' },
  //   _class: 'bg-danger text-white',
  //   target: '_blank'
  // }
]
