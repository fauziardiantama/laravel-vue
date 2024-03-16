<template>
  <div>
    <CRow>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader>Tambah Sesi</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                <CFormLabel for="createname">No sesi</CFormLabel>
                <CFormInput type="text" id="createname" placeholder="No sesi" v-model="createItem.no_sesi"
                  :invalid="form_validation.no_sesi.invalid"
                  :feedback="form_validation.no_sesi.feedback" />
              </div>
              <div class="mb-3">
                <CFormLabel for="createname">Waktu mulai</CFormLabel>
                <CFormInput type="time" id="createname" placeholder="Waktu mulai" v-model="createItem.waktu_mulai"
                  :invalid="form_validation.waktu_mulai.invalid"
                  :feedback="form_validation.waktu_mulai.feedback" />
              </div>
              <div class="mb-3">
                <CFormLabel for="createname">Waktu selesai</CFormLabel>
                <CFormInput type="time" id="createname" placeholder="Waktu selesai" v-model="createItem.waktu_selesai"
                  :invalid="form_validation.waktu_selesai.invalid"
                  :feedback="form_validation.waktu_selesai.feedback" />
              </div>
              <div class="mb-3 text-center">
                <CButton color="primary" class="w-100" @click="addSesi">Tambah</CButton>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader>Daftar Sesi</CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>No Sesi</CTableHeaderCell>
                  <CTableHeaderCell>Waktu mulai</CTableHeaderCell>
                  <CTableHeaderCell>Waktu selesai</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="sesis.length > 0">
                <CTableRow v-for="(sesi, index) in sesis" :key="sesi.id">
                  <CTableDataCell class="text-center">
                      {{ index + 1 }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ sesi.no_sesi }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ sesi.waktu_mulai }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ sesi.waktu_selesai }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailModal(sesi)">Detail</CButton>
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
              <CTableBody v-else>
                <CTableRow>
                  <CTableDataCell class="text-center" colspan="5">
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
      <CModalTitle>Detail Sesi-{{ activeSesi.no_sesi }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>No Sesi</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="no_sesi" placeholder="No Sesi" v-model="activeSesi.no_sesi"
            :feedback="edit_validation.no_sesi.feedback"
            :invalid="edit_validation.no_sesi.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Waktu mulai</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="time" id="waktu_mulai" placeholder="Waktu mulai" v-model="activeSesi.waktu_mulai"
            :feedback="edit_validation.waktu_mulai.feedback"
            :invalid="edit_validation.waktu_mulai.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Waktu selesai</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="time" id="waktu_selesai" placeholder="Waktu selesai" v-model="activeSesi.waktu_selesai"
            :feedback="edit_validation.waktu_selesai.feedback"
            :invalid="edit_validation.waktu_selesai.invalid" />
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="primary" @click="update(activeSesi)">
        Update
      </CButton>
      <CButton color="danger" @click="deleteSesi(activeSesi)">
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
  name: 'Sesi',
  components: {
    pagination
  },
  data() {
    return {
      sesis: [],
      createItem: {
        no_sesi: '',
        waktu_mulai: '',
        waktu_selesai: ''
      },
      form_validation: {
        no_sesi: {
          invalid: false,
          feedback: ''
        },
        waktu_mulai: {
          invalid: false,
          feedback: ''
        },
        waktu_selesai: {
          invalid: false,
          feedback: ''
        }
      },
      edit_validation: {
        no_sesi: {
          invalid: false,
          feedback: ''
        },
        waktu_mulai: {
          invalid: false,
          feedback: ''
        },
        waktu_selesai: {
          invalid: false,
          feedback: ''
        }
      },
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeSesi: {
        id: '',
        no_sesi: '',
        waktu_mulai: '',
        waktu_selesai: ''
      }
    }
  },
  created() {
    this.getSesis();
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
    getSesis() {
      axios.get(`${window.location.origin}/api/ta/sesi_ujian`)
      .then(response => {
        this.sesis = response.data.data;
      })
      .catch(error => {
        this.sesis = [];
        this.itemstatus = error.response.data.message;
        console.log(error);
      });
    },
    addSesi() {
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/ta/sesi_ujian`, this.createItem)
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
        this.createItem = {
          no_sesi: '',
          waktu_mulai: '',
          waktu_selesai: ''
        };
        this.getSesis();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.form_validation = {
            no_sesi: {
              invalid: !!error.response.data.errors.no_sesi,
              feedback: error.response.data.errors.no_sesi.join(' & ')
            },
            waktu_mulai: {
              invalid: !!error.response.data.errors.waktu_mulai,
              feedback: error.response.data.errors.waktu_mulai.join(' & ')
            },
            waktu_selesai: {
              invalid: !!error.response.data.errors.waktu_selesai,
              feedback: error.response.data.errors.waktu_selesai.join(' & ')
            }
          }
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambah sesi!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambah sesi!',
            message: error.response.status
          });
        }
      });
    },
    update(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/ta/sesi_ujian/${item.id}`, item)
      .then(response => {
        console.log('berhasil update', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getSesis();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.edit_validation = {
            no_sesi: {
              invalid: !!error.response.data.errors.no_sesi,
              feedback: error.response.data.errors.no_sesi.join(' & ')
            },
            waktu_mulai: {
              invalid: !!error.response.data.errors.waktu_mulai,
              feedback: error.response.data.errors.waktu_mulai.join(' & ')
            },
            waktu_selesai: {
              invalid: !!error.response.data.errors.waktu_selesai,
              feedback: error.response.data.errors.waktu_selesai.join(' & ')
            }
          }
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
    deleteSesi(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.delete(`${window.location.origin}/api/ta/sesi_ujian/${item.id}`)
      .then(response => {
        console.log('berhasil delete', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
        this.closeModal();
        this.getSesis();
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
      this.activeSesi = item;
      this.edit_validation = {
        no_sesi: {
          invalid: false,
          feedback: ''
        },
        waktu_mulai: {
          invalid: false,
          feedback: ''
        },
        waktu_selesai: {
          invalid: false,
          feedback: ''
        }
      }
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeSesi = {
        id : 0,
        no_sesi: '',
        waktu_mulai: '',
        waktu_selesai: ''
      }
    }
  }
}
</script>