<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader>Tambah Instansi</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="row mb-3">
                <div class="col-12 col-md-6">
                  <CFormLabel for="createname">Nama</CFormLabel>
                  <CFormInput type="text" id="nama" placeholder="nama" v-model="createItem.nama"
                    :feedback="form_validation.nama.feedback"
                    :invalid="form_validation.nama.invalid" />
                </div>
                <div class="col-12 col-md-6">
                  <CFormLabel for="createname">E-mail</CFormLabel>
                  <CFormInput type="email" id="email" placeholder="email" v-model="createItem.email"
                    :feedback="form_validation.email.feedback"
                    :invalid="form_validation.email.invalid" />
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-6">
                  <CFormLabel for="createname">Nomor telepon</CFormLabel>
                  <CFormInput type="text" id="no_telp" placeholder="nomor telepon" v-model="createItem.no_telp"
                    :feedback="form_validation.no_telp.feedback"
                    :invalid="form_validation.no_telp.invalid" />
                </div>
                <div class="col-12 col-md-6">
                  <CFormLabel for="createname">Website</CFormLabel>
                  <CFormInput type="text" id="web" placeholder="website" v-model="createItem.web"
                    :feedback="form_validation.web.feedback"
                    :invalid="form_validation.web.invalid" />
                </div>
              </div>
              <div class="mb-3">
                <CFormTextarea
                  id="alamat"
                  label="Alamat"
                  rows="3"
                  placeholder="alamat"
                  v-model="createItem.alamat"
                  :feedback="form_validation.alamat.feedback"
                    :invalid="form_validation.alamat.invalid"
                ></CFormTextarea>
              </div>
              <div class="mb-3 text-center">
                <CButton color="primary" class="w-100" @click="addInstansi">Tambah</CButton>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-2">Daftar Instansi</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" v-model="search" @keyup.enter="getInstansiBySearch(true)"/>
                <CInputGroupText @click="getInstansiBySearch(true)" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
            <div class="col-4 mt-2 text-right right">
              <pagination v-if="!search_on"
                :pagination="instansis"
                @paginate="getInstansis()"
                :offset="4">
              </pagination>
              <pagination v-if="search_on"
                :pagination="instansis"
                @paginate="getInstansiBySearch(false)"
                :offset="4">
              </pagination>
            </div>
          </CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Nama</CTableHeaderCell>
                  <CTableHeaderCell>Status</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="instansis.data.length > 0">
                <CTableRow v-for="(instansi, index) in instansis.data" :key="instansi.id_instansi">
                  <CTableDataCell class="text-center">
                    {{ index + 1 + (instansis.current_page - 1) * instansis.per_page }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ instansi.nama }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CBadge v-if="instansi.status_instansi == 1" color="success">disetujui</CBadge><CBadge v-if="instansi.status_instansi == 2" color="danger">Ditolak</CBadge><CBadge v-if="instansi.status_instansi == 0" color="warning">Menunggu</CBadge>
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailModal(instansi)">Detail</CButton>
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
      <CModalTitle>Detail {{ activeInstansi.nama }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Nama</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="nama" placeholder="nama" v-model="activeInstansi.nama"
            :feedback="edit_validation.nama.feedback"
            :invalid="edit_validation.nama.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Email</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="email" id="email" placeholder="email" v-model="activeInstansi.email"
            :feedback="edit_validation.email.feedback"
            :invalid="edit_validation.email.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>No. Telp</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="no_telp" placeholder="nomor telepon" v-model="activeInstansi.no_telp"
            :feedback="edit_validation.no_telp.feedback"
            :invalid="edit_validation.no_telp.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Web</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="web" placeholder="website" v-model="activeInstansi.web"
            :feedback="edit_validation.web.feedback"
            :invalid="edit_validation.web.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Alamat</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormTextarea
            id="alamat"
            rows="3"
            placeholder="alamat"
            v-model="activeInstansi.alamat"
            :feedback="edit_validation.alamat.feedback"
            :invalid="edit_validation.alamat.invalid"
          ></CFormTextarea>
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Status</CLabel>
        </CCol>
        <CCol :md="8">
          <p><CBadge v-if="activeInstansi.status_instansi == 1" color="success">disetujui</CBadge><CBadge v-if="activeInstansi.status_instansi == 2" color="danger">Ditolak</CBadge><CBadge v-if="activeInstansi.status_instansi == 0" color="warning">Menunggu</CBadge></p>
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="primary">
        Magang
      </CButton>
      <CButton color="primary" @click="update(activeInstansi)">
        Update
      </CButton>
      <CButton color="success" @click="approve(activeInstansi)">
        Terima
      </CButton>
      <CButton color="danger" @click="reject(activeInstansi)">
        Tolak
      </CButton>
      <CButton color="danger" @click="deleteInstansi(activeInstansi)">
        Hapus
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
  .cursor-pointer {
    cursor: pointer;
  }
</style>

<script>
import pagination from '@/components/Pagination.vue';

export default {
  name: 'Instansi',
  components: {
    pagination
  },
  data() {
    return {
      search_on: false,
      search: '',
      instansis: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1,
        data: []
      },
      offset: 4,
      createItem: {
        nama: '',
        email: '',
        alamat: '',
        no_telp: '',
        web: ''
      },
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeInstansi: {
        id : 0,
        nama: '',
        email: '',
        alamat: '',
        no_telp: '',
        web: '',
        status_instansi: 0,
      },
      form_validation : {
        nama: {
          invalid: false,
          feedback: ''
        },
        email: {
          invalid: false,
          feedback: ''
        },
        no_telp: {
          invalid: false,
          feedback: ''
        },
        web: {
          invalid: false,
          feedback: ''
        },
        alamat: {
          invalid: false,
          feedback: ''
        }
      },
      edit_validation : {
        nama: {
          invalid: false,
          feedback: ''
        },
        email: {
          invalid: false,
          feedback: ''
        },
        no_telp: {
          invalid: false,
          feedback: ''
        },
        web: {
          invalid: false,
          feedback: ''
        },
        alamat: {
          invalid: false,
          feedback: ''
        }
      }
    }
  },
  async created() {
    this.getInstansis();
  },
  mounted() {
    console.log('Dashboard component mounted.');
  },
  methods: {
    getInstansiBySearch(first = false) {
      if (this.search == '') {
        this.search_on = false;
        this.getInstansis();
        return;
      }
      if (first) {
        this.search_on = true;
        this.instansis.current_page = 1;
        this.instansis.data = [];
      }
      axios.get(`${window.location.origin}/api/kmm/instansi/search?kueri=${this.search}&page=${this.instansis.current_page}`)
      .then(response => {
        this.instansis = response.data.data;
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil instansi',
            message: error.response.status
        });
      });
    },
    getInstansis() {
      axios.get(`${window.location.origin}/api/kmm/instansi?page=${this.instansis.current_page}`)
      .then(response => {
        this.instansis = response.data.data;
        Echo.private('Admin')
        .listen("Inst", (e) => {
          console.log(e);
          if (this.activeInstansi.id == e.item.id_instansi) {
            if (e.type == "update") {
              this.openDetailModal(e.item);
            }
            if (e.type == "destroy") {
              this.closeModal();
            }
            console.log(this.activeInstansi);
          }
          this.getInstansis();
        });
      })
      .catch(error => {
        console.log(error);
      });
    },
    openDetailModal(item) {
      this.activeInstansi = {
        id : item.id_instansi,
        nama: item.nama,
        email: item.email,
        alamat: item.alamat,
        no_telp: item.no_telp,
        web: item.web,
        status_instansi: item.status_instansi
      }
      this.edit_validation = {
        nama: {
          invalid: false,
          feedback: ''
        },
        email: {
          invalid: false,
          feedback: ''
        },
        no_telp: {
          invalid: false,
          feedback: ''
        },
        web: {
          invalid: false,
          feedback: ''
        },
        alamat: {
          invalid: false,
          feedback: ''
        }
      }
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeInstansi = {
        id : 0,
        nama: '',
        email: '',
        alamat: '',
        no_telp: '',
        web: '',
        status_instansi: 0,
      }
    },
    approve(instansi) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/instansi/${instansi.id}/approve`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Instansi disetujui!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getUsers();
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menyetujui instansi!', 
            message: error.response.data
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menyetujui instansi!',
            message: error.response.status
          });
        }
      });
    },
    reject(instansi) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/instansi/${instansi.id}/reject`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Instansi ditolak!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getUsers();
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.$store.dispatch('showErrorAlert',{
            title: 'Gagal menolak instansi!',
            message: error.response.data
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menolak instansi!',
            message: error.response.status
          });
        }
      });
    },
    addInstansi() {
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/kmm/instansi`, this.createItem)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Instansi berhasil ditambahkan!');
        this.createItem = {
          nama: '',
          email: '',
          alamat: '',
          no_telp: '',
          web: ''
        }
        this.form_validation = {
          nama: {
            invalid: false,
            feedback: ''
          },
          email: {
            invalid: false,
            feedback: ''
          },
          no_telp: {
            invalid: false,
            feedback: ''
          },
          web: {
            invalid: false,
            feedback: ''
          },
          alamat: {
            invalid: false,
            feedback: ''
          }
        }
        this.getInstansis();
      })
      .catch(error => {
        if (error.response.status === 422) {
          console.log('Error: ', error.response.data);
          this.form_validation = {
            nama: {
              invalid: !!error.response.data.errors.nama,
              feedback: error.response.data.errors.nama ? error.response.data.errors.nama.join(' & ') : ""
            },
            email: {
              invalid: !!error.response.data.errors.email,
              feedback: error.response.data.errors.email ? error.response.data.errors.email.join(' & ') : ""
            },
            no_telp: {
              invalid: !!error.response.data.errors.no_telp,
              feedback: error.response.data.errors.no_telp ? error.response.data.errors.no_telp.join(' & ') : ""
            },
            web: {
              invalid: !!error.response.data.errors.web,
              feedback: error.response.data.errors.web ? error.response.data.errors.web.join(' & ') : ""
            },
            alamat: {
              invalid: !!error.response.data.errors.alamat,
              feedback: error.response.data.errors.alamat ? error.response.data.errors.alamat.join(' & ') : ""
            }
          }
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengupload file!', 
            message: error.response.data.message
          });
        } else {
          console.log('Error: ', error);
          this.$store.dispatch('showErrorAlert', {
            title: `Error ${error.response.status}`,
            message: error.response.data.message ?? error.response.data
          });
        }
      });
    },
    update(instansi) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/instansi/${instansi.id}`, instansi)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Instansi berhasil diupdate!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getInstansis();
      })
      .catch(error => {
        if (error.response.status === 422) {
          console.log('Error: ', error.response.data);
          this.edit_validation = {
            nama: {
              invalid: !!error.response.data.errors.nama,
              feedback: error.response.data.errors.nama ? error.response.data.errors.nama.join(' & ') : ""
            },
            email: {
              invalid: !!error.response.data.errors.email,
              feedback: error.response.data.errors.email ? error.response.data.errors.email.join(' & ') : ""
            },
            no_telp: {
              invalid: !!error.response.data.errors.no_telp,
              feedback: error.response.data.errors.no_telp ? error.response.data.errors.no_telp.join(' & ') : ""
            },
            web: {
              invalid: !!error.response.data.errors.web,
              feedback: error.response.data.errors.web ? error.response.data.errors.web.join(' & ') : ""
            },
            alamat: {
              invalid: !!error.response.data.errors.alamat,
              feedback: error.response.data.errors.alamat ? error.response.data.errors.alamat.join(' & ') : ""
            }
          }
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengupload file!',
            message: error.response.data.message
          });
        } else {
          console.log('Error: ', error);
          this.$store.dispatch('showErrorAlert', {
            title: `Error ${error.response.status}`,
            message: error.response.data.message ?? error.response.data
          });
        }
      });
    },
    deleteInstansi(instansi) {
      this.$store.dispatch('showLoadingAlert');
      axios.delete(`${window.location.origin}/api/kmm/instansi/${instansi.id}`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Instansi berhasil dihapus!');
        this.closeModal();
        this.getInstansis();
      })
      .catch(error => {
        console.log('Error: ', error);
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${error.response.status}`,
          message: error.response.data.message ?? error.response.data
        });
      });
    }
  }
}
</script>
