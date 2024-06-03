export default [
  {
    component: 'CNavItem',
    name: 'Dashboard',
    to: { name: 'AdminTaDashboard' },
    icon: 'cil-speedometer',
  },
  {
    component: 'CNavItem',
    name: 'Mahasiswa',
    to: { name: 'AdminTaMahasiswa' },
    icon: 'cil-people',
  },
  {
    component: 'CNavGroup',
    name: 'Proposal TA',
    to: { name: 'AdminTaProposal' },
    icon: 'cil-puzzle',
    items: [
      {
        component: 'CNavItem',
        name: 'Daftar Proposal',
        to: { name: 'AdminTaProposal' }
      },
      {
        component: 'CNavItem',
        name: 'Jadwal Proposal',
        to: { name: 'AdminTaJadwalProposal' }
      }
    ],
  },
  {
    component: 'CNavItem',
    name: 'Bimbingan',
    to: { name: 'AdminTaBimbingan' },
    icon: 'cil-people'
  },
  {
    component: 'CNavGroup',
    name: 'Umum',
    to: { name: 'AdminTaRuangan' },
    icon: 'cil-settings',
    items: [
      {
        component: 'CNavItem',
        name: 'Ruangan',
        to: { name: 'AdminTaRuangan' }
      },
      {
        component: 'CNavItem',
        name: 'Sesi',
        to: { name: 'AdminTaSesi' }
      },
      {
        component: 'CNavItem',
        name: 'Tahun Akademik',
        to: { name: 'AdminTaTahunAkademik' }
      }
    ],
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
