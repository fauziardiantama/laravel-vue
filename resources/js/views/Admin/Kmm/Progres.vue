<template>
  <div>
    <CCard class="mb-4">
          <CCardHeader> Daftar Progres</CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Nama Progres</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="progreses.length > 0">
                <CTableRow v-for="(progres, index) in progreses" :key="progres.id">
                  <CTableDataCell class="text-center">
                      {{ index + 1 }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ progres.progres }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailModal(progres)">Detail</CButton>
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
  </div>
  <CModal size="lg" backdrop="static" :visible="showDetailModal" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Detail {{ activeProgres.progres }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Nama progres</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="progres" placeholder="Nama progres" v-model="activeProgres.progres"
            :feedback="edit_validation.progres.feedback"
            :invalid="edit_validation.progres.invalid" />
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="primary">
        Magang
      </CButton>
      <CButton color="primary" @click="update(activeProgres)">
        Update
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
  name: 'Progres',
  components: {
    pagination
  },
  data() {
    return {
      progreses: [],
      edit_validation: {
        progres : {
          invalid: false,
          feedback: ''
        }
      },
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeProgres: {
        id: '',
        progres: ''
      }
    }
  },
  created() {
    this.getProgres();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen('Prgs', (e) => {
      this.getProgres();
      if (e.item.id_progres === this.activeProgres.id) {
        if (e.type === 'update') {
          this.openDetailModal(e.item);
        }
      }
    });
  },
  methods: {
    getProgres() {
      axios.get(`${window.location.origin}/api/kmm/progres`)
      .then(response => {
        this.progreses = response.data.data;
      })
      .catch(error => {
        this.itemstatus = error.response.data.message;
        console.log(error);
      });
    },
    update(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/progres/${item.id}`, item)
      .then(response => {
        console.log('berhasil update', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getProgres();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.edit_validation.progres.invalid = !!error.response.data.errors.progres;
          this.edit_validation.progres.feedback = error.response.data.errors.progres.join(' & ');
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengubah proses!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengubah proses!',
            message: error.response.data.message
          });
        }
      });
    },
    openDetailModal(item) {
      this.activeProgres = {
        id : item.id_progres,
        progres: item.progres,
      }
      this.edit_validation = {
        progres: {
          invalid: false,
          feedback: ''
        }
      }
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeProgres = {
        id : 0,
        nama_topik: ''
      }
    }
  }
}
</script>
