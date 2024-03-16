<template>
  <div>
    <CRow>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader>Tambah Ruangan</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                <CFormLabel for="createname">Nama ruangan</CFormLabel>
                <CFormInput type="text" id="createname" placeholder="nama"
                  v-model="createItem.nama"
                  :invalid="form_validation.nama.invalid"
                  :feedback="form_validation.nama.feedback"
                />
              </div>
              <div class="mb-3 text-center">
                <CButton color="primary" class="w-100" @click="addRuang">Tambah</CButton>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader>Daftar Ruangan</CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Nama</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="ruangans?.length > 0">
                <CTableRow v-for="(ruangan, index) in ruangans" :key="ruangan.id">
                  <CTableDataCell class="text-center">
                      {{ index + 1 }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ ruangan.nama }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailModal(ruangan)">Detail</CButton>
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
      <CModalTitle>Detail {{ activeRuang.nama }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Nama</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="nama" placeholder="Nama ruangan" v-model="activeRuang.nama"
            :feedback="edit_validation.nama.feedback"
            :invalid="edit_validation.nama.invalid" />
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="primary" @click="update(activeRuang)">
        Update
      </CButton>
      <CButton color="danger" @click="deleteRuang(activeRuang)">
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
  name: 'Ruangan',
  components: {
    pagination
  },
  data() {
    return {
      ruangans: [],
      createItem: {
        nama: ''
      },
      form_validation: {
        nama: {
          invalid: false,
          feedback: ''
        }
      },
      edit_validation: {
        nama: {
          invalid: false,
          feedback: ''
        }
      },
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeRuang: {
        id : 0,
        nama: ''
      }
    }
  },
  created() {
    this.getRuangans();
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
    getRuangans() {
      axios.get(`${window.location.origin}/api/ta/ruangan`)
      .then(response => {
        this.ruangans = response.data.data;
      })
      .catch(error => {
        this.ruangans = [];
        this.itemstatus = error.response.data.message;
        console.log(error);
      });
    },
    addRuang() {
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/ta/ruangan`, this.createItem)
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
        this.createItem = {
          nama: ''
        };
        this.getRuangans();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.form_validation.nama.invalid = !!error.response.data.errors.nama;
          this.form_validation.nama.feedback = error.response.data.errors.nama.join(' & ');
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
      axios.put(`${window.location.origin}/api/ta/ruangan/${item.id}`, item)
      .then(response => {
        console.log('berhasil update', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getRuangans();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.edit_validation.nama.invalid = !!error.response.data.errors.nama;
          this.edit_validation.nama.feedback = error.response.data.errors.nama.join(' & ');
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
    deleteRuang(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.delete(`${window.location.origin}/api/ta/ruangan/${item.id}`)
      .then(response => {
        console.log('berhasil delete', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
        this.closeModal();
        this.getRuangans();
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
      this.activeRuang = {
        id : item.id,
        nama: item.nama,
      }
      this.edit_validation = {
        nama: {
          invalid: false,
          feedback: ''
        }
      }
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeRuang = {
        id : 0,
        nama: ''
      }
    }
  }
}
</script>