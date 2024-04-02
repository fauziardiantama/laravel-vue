export default [
  {
    component: 'CNavItem',
    name: 'Dashboard',
    to: { name: 'MahasiswaTaDashboard' },
    icon: 'cil-speedometer',
    needactivation: false
  },
  {
    component: 'CNavItem',
    name: 'Proposal TA',
    to: { name: 'MahasiswaTaProposal' },
    icon: 'cil-pencil',
    needactivation: true
  },
  {
    component: 'CNavItem',
    name: 'Pembimbing',
    to: { name: 'MahasiswaTaPembimbing' },
    icon: 'cil-people',
    needactivation: true
  },
  {
    component: 'CNavItem',
    name: 'Jadwal Proposal',
    to: { name: 'MahasiswaTaJadwalProposal' },
    icon: 'cil-settings',
    needactivation: true
  },
  {
    component: 'CNavItem',
    name: 'Bimbingan',
    to: { name: 'MahasiswaTaBimbingan' },
    icon: 'cil-people',
    needactivation: true
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
