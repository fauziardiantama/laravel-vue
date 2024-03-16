<template>
  <div>
    <CRow>
      <CCol :md="5">
        <CCard class="mb-4">
          <CCardHeader>Tambah Topik</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                <CFormLabel for="nama_topik">Nama topik</CFormLabel>
                <CFormInput type="text" id="nama_topik" placeholder="Nama topik"
                  v-model="createItem.nama_topik"
                  :feedback="form_validation.nama_topik.feedback"
                  :invalid="form_validation.nama_topik.invalid" />
              </div>
              <div class="mb-3 text-center">
                <CButton color="primary" class="w-100" @click="addItem">Tambah</CButton>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="7">
        <CCard class="mb-4">
          <CCardHeader> Daftar Topik</CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Nama Topik</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="topiks.length > 0">
                <CTableRow v-for="(topik, index) in topiks" :key="topik.id">
                  <CTableDataCell class="text-center">
                      {{ index + 1 }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ topik.nama_topik }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailModal(topik)">Detail</CButton>
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
  <CModal size="lg" backdrop="static" :visible="showDetailModal" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Detail {{ activeTopik.nama_topik }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Nama topik</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="nama_topik" placeholder="Nama topik" v-model="activeTopik.nama_topik"
            :feedback="edit_validation.nama_topik.feedback"
            :invalid="edit_validation.nama_topik.invalid" />
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="primary">
        Magang
      </CButton>
      <CButton color="primary">
        Dosen
      </CButton>
      <CButton color="success" @click="update(activeTopik)">
        Update
      </CButton>
      <CButton color="danger" @click="deleteTopik(activeTopik)">
        Delete
      </CButton>
      <CButton color="secondary" @click="closeModal">
        Close
      </CButton>
    </CModalFooter>
  </CModal>
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
  name: 'Topik',
  components: {
    pagination
  },
  data() {
    return {
      topiks: [],
      createItem: {
        nama_topik: ''
      },
      form_validation: {
        nama_topik: {
          invalid: false,
          feedback: ''
        }
      },
      edit_validation: {
        nama_topik: {
          invalid: false,
          feedback: ''
        }
      },
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeTopik: {
        id: '',
        nama_topik: ''
      }
    }
  },
  created() {
    this.getTopiks();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen('Tpk', (e) => {
      this.getTopiks();
      if (e.item.id_topik === this.activeTopik.id) {
        if (e.type === 'destroy') {
          this.closeModal();
        }
        if (e.type === 'update') {
          this.openDetailModal(e.item);
        }
      }
    });
  },
  methods: {
    getTopiks() {
      axios.get(`${window.location.origin}/api/kmm/topik`)
      .then(response => {
        this.topiks = response.data.data;
      })
      .catch(error => {
        this.itemstatus = error.response.data.message;
        console.log(error);
      });
    },
    addItem() {
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/kmm/topik`, this.createItem)
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
        this.createItem = {
          nama_topik: ''
        };
        this.getTopiks();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.form_validation.nama_topik.invalid = !!error.response.data.errors.nama_topik;
          this.form_validation.nama_topik.feedback = error.response.data.errors.nama_topik.join(' & ');
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambah topik!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambah topik!',
            message: error.response.status
          });
        }
      });
    },
    update(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/topik/${item.id}`, item)
      .then(response => {
        console.log('berhasil update', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getTopiks();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.edit_validation.nama_topik.invalid = !!error.response.data.errors.nama_topik;
          this.edit_validation.nama_topik.feedback = error.response.data.errors.nama_topik.join(' & ');
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengubah topik!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengubah topik!',
            message: error.response.data.message
          });
        }
      });
    },
    deleteTopik(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.delete(`${window.location.origin}/api/kmm/topik/${item.id}`)
      .then(response => {
        console.log('berhasil delete', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
        this.closeModal();
        this.getTopiks();
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
          title: 'Gagal menghapus item!',
          message: error.response.data.message
        });
      });
    },
    openDetailModal(item) {
      this.activeTopik = {
        id : item.id_topik,
        nama_topik: item.nama_topik,
      }
      this.edit_validation = {
        nama_topik: {
          invalid: false,
          feedback: ''
        }
      }
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeTopik = {
        id : 0,
        nama_topik: ''
      }
    }
  }
}
</script>
