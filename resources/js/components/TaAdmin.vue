<template>
    <div>
      <AppSidebar :type="'ta'" :user="'admin'" />
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
  import AppSidebar from '@/components/AppSidebar.vue'
  
  export default {
    name: 'Ta',
    components: {
      AppFooter,
      AppHeader,
      AppSidebar,
      CContainer,
    },
    async created() {
      console.log('created')
      await this.$store.dispatch('user').then(() => {
        Echo.private(`Admin`).listen('AdminUpdated', (e) => {
          console.log(e.user);
        });
      }).catch((error) => {
        console.log(error)
      })
    }
  }
</script>
  