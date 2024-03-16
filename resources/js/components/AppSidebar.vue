<template>
  <CSidebar
    position="fixed"
    :unfoldable="sidebarUnfoldable"
    :visible="sidebarVisible"
    @visible-change="
      (event) =>
        $store.commit({
          type: 'updateSidebarVisible',
          value: event,
        })
    "
  >
    <CSidebarBrand>
      <!-- <CIcon
        custom-class-name="sidebar-brand-full"
        :icon="logoNegative"
        :height="35"
      /> -->
      <div class="sidebar-brand-full text-white m-3 mt-4 mb-4">
        <h5>D3 Teknik Informatika</h5>
      </div>
    </CSidebarBrand>
    <AppSidebarNav v-if="type=='ta'" :user="user" />
    <AppSidebarNavKmm v-if="type=='kmm'" :user="user" />
    <CSidebarToggler
      class="d-none d-lg-flex"
      @click="$store.commit('toggleUnfoldable')"
    />
  </CSidebar>
</template>

<script>
  import { computed } from 'vue'
  import { useStore } from 'vuex'

  import { AppSidebarNav } from './AppSidebarNav'
  import { AppSidebarNavKmm } from './AppSidebarNavKmm'
  import { logoNegative } from '@/assets/brand/logo-negative'
  import { sygnet } from '@/assets/brand/sygnet'

  export default {
    name: 'AppSidebar',
    components: {
      AppSidebarNav,
      AppSidebarNavKmm,
    },
    props: {
      user: String,
      type: String,
    },
    setup() {
      const store = useStore()
      return {
        logoNegative,
        sygnet,
        sidebarUnfoldable: computed(() => store.state.sidebarUnfoldable),
        sidebarVisible: computed(() => store.state.sidebarVisible),
      }
    },
    mounted() {
      console.log('AppSidebar Component mounted.')
    }
  }
</script>
