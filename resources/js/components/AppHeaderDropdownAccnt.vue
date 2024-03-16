<template>
  <CDropdown variant="nav-item">
    <CDropdownToggle placement="bottom-end" class="py-0" :caret="false" href="javascript:void(0)">
       {{ name }}
       <!-- <CAvatar :src="avatar" size="lg"/> -->
    </CDropdownToggle>
    <CDropdownMenu class="pt-0">
      <CDropdownHeader component="h6" class="bg-light fw-semibold py-2">
        Account
      </CDropdownHeader>
      <!-- <CDropdownItem> <CIcon icon="cil-user" /> Profile </CDropdownItem>
      <CDropdownItem> <CIcon icon="cil-settings" /> Settings </CDropdownItem>
      <CDropdownDivider />
      <CDropdownItem>
        <CIcon icon="cil-shield-alt" /> Change Password
      </CDropdownItem> -->
      <CDropdownItem @click="logout"> <CIcon icon="cil-lock-locked" /> Logout </CDropdownItem>
    </CDropdownMenu>
  </CDropdown>
</template>

<script>
import avatar from '@/assets/images/avatars/8.jpg'
export default {
  name: 'AppHeaderDropdownAccnt',
  setup() {
    return {
      avatar: avatar,
      itemsCount: 42,
    }
  },
  props: {
    name: {
      type: String,
      default: 'User'
    }
  },
  methods: {
    logout() {
      this.$store.dispatch('showLoadingAlert');
      this.$store.dispatch('logout').then(() => {
        this.$store.dispatch('showSuccessAlert', 'Logout Berhasil')
        this.$router.push({ name: 'Landing' })
      }).catch(e => {
        this.$store.dispatch('showErrorAlert', {
          title: 'Logout Gagal',
          message: e.response.data.message || e.response.data
        })
      })
    }
  }
}
</script>
