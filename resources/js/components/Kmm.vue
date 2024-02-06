<template>
    <div>
      <AppSidebar_admin />
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
  import AppSidebar_admin from '@/components/AppSidebar_admin.vue'
  
  export default {
    name: 'Kmm',
    components: {
      AppFooter,
      AppHeader,
      AppSidebar_admin,
      CContainer,
    },
    mounted() {
      console.log('KMM Component mounted.')
    },
  }
</script>
  