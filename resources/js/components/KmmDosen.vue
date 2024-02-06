<template>
    <div>
      <AppSidebar_dosen />
      <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <AppHeader />
        <div class="body flex-grow-1 px-3">
          <CContainer lg>
            <router-view />
          </CContainer>
        </div>
        <AppFooter />
      </div>
    </div>
</template>

<style type="scss">
  @import '../styles/style.scss';
</style>


<script>
  import { CContainer } from '@coreui/vue'
  import AppFooter from '@/components/AppFooter.vue'
  import AppHeader from '@/components/AppHeader.vue'
  import AppSidebar_dosen from '@/components/AppSidebar_dosen.vue'
  
  export default {
    name: 'KmmDosen',
    components: {
      AppFooter,
      AppHeader,
      AppSidebar_dosen,
      CContainer,
    },
    mounted() {
      console.log('KMM Component mounted.')
    },
  }
</script>
  