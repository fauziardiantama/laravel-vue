<template>
    <div>
      <AppSidebar :user="'mahasiswa'" />
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
  import { useStore } from 'vuex'
  
  export default {
    name: 'TaMahasiswa',
    components: {
      AppFooter,
      AppHeader,
      AppSidebar,
      CContainer,
    },
    data() {
      return {
        user: {
          status: 0,
        }
      }
    },
    async created() {
      console.log('Ta Component created.')
      const response = await axios.get(`${window.location.origin}/api/user`);
      this.user.status = response.data.user.status;
      //store status by commit updateStatus with value response.data.user.status
      this.$store.commit('updateStatus', response.data.user.status)
      //console.log status from store
      console.log('status from store: '+this.$store.state.status)
      console.log(this.user);
    },
    mounted() {
      console.log('Ta Component mounted.')
      Echo.channel('user').listen('UserUpdated', (e) => {
        if (e.user.nim != this.user.nim) return;
          console.log(e);
          this.user.status = e.user.status;
          //store status by commit updateStatus with value e.user.status
          this.$store.commit('updateStatus', e.user.status)
          //console.log status from store
          console.log('status from store: '+this.$store.state.status)
      });
      console.log(this.user);
    }
  }
</script>
  