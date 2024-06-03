export default [
  {
    component: 'CNavItem',
    name: 'Dashboard',
    to: { name: 'MahasiswaKmmDashboard' },
    icon: 'cil-speedometer',
    needactivation: false
  },
  {
    component: 'CNavItem',
    name: 'Magang',
    to: { name: 'MahasiswaKmmMagang' },
    icon: 'cil-pencil',
    needactivation: true
  },
  {
    component: 'CNavItem',
    name: 'Dokumen',
    to: { name: 'MahasiswaKmmDokumen' },
    icon: 'cil-file',
    needactivation: true
  },
  {
    component: 'CNavItem',
    name: 'Bimbingan',
    to: { name: 'MahasiswaKmmBimbingan' },
    icon: 'cil-pencil',
    needactivation: true
  },
  {
    component: 'CNavItem',
    name: 'Seminar',
    to: { name: 'MahasiswaKmmSeminar' },
    icon: 'cil-pencil',
    needactivation: true
  },
  {
    component: 'CNavItem',
    name: 'Nilai',
    to: { name: 'MahasiswaKmmNilai' },
    icon: 'cil-pencil',
    needactivation: true
  }
  // {
  //   component: 'CNavItem',
  //   name: 'Proposal TA',
  //   to: { name: 'MahasiswaTaProposal' },
  //   icon: 'cil-pencil',
  //   needactivation: true
  // }
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
