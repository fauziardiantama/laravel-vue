<template>
  <div>
    <CRow>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader>Tambah Tahun</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                <CFormLabel for="createname">tahun</CFormLabel>
                <CFormInput type="text" id="createname" placeholder="tahun" v-model="createItem.tahun" :invalid="form_validation.tahun.invalid" />
              </div>
              <div class="mb-3 text-center">
                <CButton color="primary" class="w-100" @click="addItem">Tambah</CButton>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader> Tahun akademik </CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Tahun</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="tahuns.length > 0">
                <CTableRow v-for="(tahun, index) in tahuns" :key="tahun.id">
                  <CTableDataCell class="text-center">
                      {{ index + 1 }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ tahun.tahun }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="danger" @click="deleteItem(tahun)">Delete</CButton>
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
              <CTableBody v-else>
                <CTableRow>
                  <CTableDataCell class="text-center" colspan="4">
                    {{ itemstatus }}
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
            </CTable>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </div>
</template>

<style scoped>
  .right {
    display: flex;
    justify-content: flex-end;
  }
  .dokumen-link {
    color: #000;
    text-decoration: none;
    background-color: rgba(114, 114, 114, 0.484);
    padding: 5px 10px;
    border-radius: 10px;
    margin: 0 2px;
    font-size: 0.8rem;
    font-weight: 1000;
}
</style>

<script>
import pagination from '@/components/Pagination.vue';

export default {
  name: 'TahunAkademik',
  components: {
    pagination
  },
  data() {
    return {
      tahuns: [],
      createItem: {
        tahun: ''
      },
      form_validation: {
        tahun: {
          invalid: false,
          feedback: ''
        }
      },
      itemstatus: 'Mengambil items'
    }
  },
  created() {
    this.getTahun();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen('Thn', (e) => {
      console.log({
        event: "Thn",
        data: e
      })
      this.tahuns = e.item;
    });
  },
  methods: {
    getTahun() {
      axios.get(`${window.location.origin}/api/ta/tahun`)
      .then(response => {
        this.tahuns = response.data.data;
      })
      .catch(error => {
        this.itemstatus = error.response.data.message;
        console.log(error);
      });
    },
    addItem() {
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/ta/tahun`, this.createItem)
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
        this.createItem = {
          tahun: ''
        };
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.form_validation.tahun.invalid = !!error.response.data.errors.tahun;
          this.form_validation.tahun.feedback = error.response.data.errors.tahun.join(' & ');
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambah tahun',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambah tahun',
            message: error.response.status
          });
        }
      });
    },
    deleteItem(item) {
      this.$store.dispatch('showLoadingAlert');   
      axios.delete(`${window.location.origin}/api/ta/tahun/${item.tahun}`)
        .then(response => {
          console.log('berhasil delete', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
        })
        .catch(error => {
          if (error.response.status === 400) {
            console.log(error.response.data);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menghapus item',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menghapus item',
              message: error.response.status
            });
          }
        });
    }
  }
}
</script>
