export default [
  {
    component: 'CNavItem',
    name: 'Dashboard',
    to: { name: 'DosenTaDashboard' },
    icon: 'cil-speedometer',
  },
  {
    component: 'CNavItem',
    name: 'Bimbingan',
    to: { name: 'DosenTaBimbingan' },
    icon: 'cil-people',
  },
  {
    component: 'CNavItem',
    name: 'Jadwal Proposal',
    to: { name: 'DosenTaJadwalProposal' },
    icon: 'cil-settings',
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
