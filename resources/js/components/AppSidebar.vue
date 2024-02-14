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
      <CIcon
        custom-class-name="sidebar-brand-full"
        :icon="logoNegative"
        :height="35"
      />
    </CSidebarBrand>
    <AppSidebarNav :user="user" />
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
  import { logoNegative } from '@/assets/brand/logo-negative'
  import { sygnet } from '@/assets/brand/sygnet'

  export default {
    name: 'AppSidebar',
    components: {
      AppSidebarNav,
    },
    props: {
      user: String
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
