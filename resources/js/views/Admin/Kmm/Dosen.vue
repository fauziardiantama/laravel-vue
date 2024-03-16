<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader>Tambah Dosen</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                  <CFormLabel for="nama">Nama</CFormLabel>
                  <CFormInput type="text" id="nama" placeholder="nama" v-model="createItem.nama"
                    :feedback="form_validation.nama.feedback"
                    :invalid="form_validation.nama.invalid" />
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-6">
                  <CFormLabel for="nik">NIK</CFormLabel>
                  <CFormInput type="text" id="nik" placeholder="NIK" v-model="createItem.nik"
                    :feedback="form_validation.nik.feedback"
                    :invalid="form_validation.nik.invalid" />
                </div>
                <div class="col-12 col-md-6">
                  <CFormLabel for="email">E-mail</CFormLabel>
                  <CFormInput type="email" id="email" placeholder="email" v-model="createItem.email"
                    :feedback="form_validation.email.feedback"
                    :invalid="form_validation.email.invalid" />
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-6">
                  <CFormLabel for="password">Password</CFormLabel>
                  <CFormInput type="password" id="password" placeholder="password" v-model="createItem.password"
                    :feedback="form_validation.password.feedback"
                    :invalid="form_validation.password.invalid" />
                </div>
                <div class="col-12 col-md-6">
                  <CFormLabel for="password_confirmation">Konfirmasi Password</CFormLabel>
                  <CFormInput type="password" id="password_confirmation" placeholder="konfirmasi password"
                    v-model="createItem.password_confirmation"
                    :invalid="form_validation.password.invalid" />
                </div>
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
            <p class="col-2">Daftar Dosen</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" v-model="search" @keyup.enter="getDosenBySearch(true)"/>
                <CInputGroupText @click="getDosenBySearch(true)" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
            <div class="col-4 mt-2 text-right right">
              <pagination v-if="!search_on"
                :pagination="getDosens"
                @paginate="getDosens()"
                :offset="4">
              </pagination>
              <pagination v-if="search_on"
                :pagination="dosens"
                @paginate="getDosensBySearch(false)"
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
                  <CTableHeaderCell>NIK</CTableHeaderCell>
                  <CTableHeaderCell>Nama</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="dosens.data.length > 0">
                <CTableRow v-for="(dosen, index) in dosens.data" :key="dosen.id_dosen">
                  <CTableDataCell class="text-center">
                    {{ index + 1 + (dosens.current_page - 1) * dosens.per_page }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ dosen.nik }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ dosen.nama }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailModal(dosen)">Detail</CButton>
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
      <CModalTitle>Detail {{ activeDosen.nama }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>NIK</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="nik" placeholder="nik" v-model="activeDosen.nik"
            :feedback="edit_validation.nik.feedback"
            :invalid="edit_validation.nik.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Nama</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="text" id="nama" placeholder="nama" v-model="activeDosen.nama"
            :feedback="edit_validation.nama.feedback"
            :invalid="edit_validation.nama.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Email</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="email" id="email" placeholder="email" v-model="activeDosen.email"
            :feedback="edit_validation.email.feedback"
            :invalid="edit_validation.email.invalid" />
        </CCol>
      </CRow>
      <Crow>
        <p>Kosongi bila tidak ingin mengganti password :</p>
      </Crow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Password</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="password" id="password"
              placeholder="konfirmasi password"
              v-model="activeDosen.password"  
              :feedback="edit_validation.password.feedback"
              :invalid="edit_validation.password.invalid" />
        </CCol>
      </CRow>
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Konfirmasi Password</CLabel>
        </CCol>
        <CCol :md="8">
          <CFormInput type="password" id="password_confirmation"
              placeholder="konfirmasi password"
              v-model="activeDosen.password_confirmation"
              :invalid="edit_validation.password.invalid" />
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="primary">
        Magang
      </CButton>
      <CButton color="primary" @click="update(activeDosen)">
        Update
      </CButton>
      <CButton color="danger" @click="deleteDosen(activeDosen)">
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
  name: 'Dosen',
  components: {
    pagination
  },
  data() {
    return {
      search_on: false,
      search: '',
      dosens: {
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
        nik: '',
        password: '',
        password_confirmation: ''
      },
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeDosen: {
        nik: '',
        nama: '',
        email: '',
        password: '',
        password_confirmation: ''
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
        nik: {
          invalid: false,
          feedback: ''
        },
        password: {
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
        nik: {
          invalid: false,
          feedback: ''
        },
        password: {
          invalid: false,
          feedback: ''
        }
      }
    }
  },
  async created() {
    this.getDosens();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen('Dsn', (e) => {
      this.getDosens();
      if (e.type == "update" && e.item.nik == this.activeDosen.nik) {
          this.openDetailModal(e.item);
        } else if (e.type == "destroy" && e.item.nik == this.activeDosen.nik) {
          this.closeModal();
        }
    })
    .listen("Ins", (e) => {
      if (e.type == "store" && e.item.nim == this.activeMahasiswa.nim) {
          this.activeMahasiswa.dokumens = [];
          if (e.item.krs != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'KRS',
              link:  `${window.location.origin}/mahasiswa/dokumen-registrasi/krs/${e.item.token}/${e.item.krs}`
            });
          }
          if (e.item.kartu_mahasiswa != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Kartu Mahasiswa',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/kartu-mahasiswa/${e.item.token}/${e.item.kartu_mahasiswa}`
            });
          }
          if (e.item.transkrip != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Transkrip',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/transkrip/${e.item.token}/${e.item.transkrip}`
            });
          }
          if (e.item.bukti_seminar != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Bukti Seminar',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/bukti-seminar/${e.item.token}/${e.item.bukti_seminar}`
            });
          }
      } else if (e.type == "destroy" && e.item.nim == this.activeMahasiswa.nim) {
        this.activeMahasiswa.dokumens = [];
        if (e.isEmpty == false) {
          if (e.item.krs != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'KRS',
              link:  `${window.location.origin}/mahasiswa/dokumen-registrasi/krs/${data.token}/${e.item.krs}`
            });
          }
          if (e.item.kartu_mahasiswa != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Kartu Mahasiswa',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/kartu-mahasiswa/${e.item.token}/${e.item.kartu_mahasiswa}`
            });
          }
          if (e.item.transkrip != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Transkrip',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/transkrip/${e.item.token}/${e.item.transkrip}`
            });
          }
          if (e.item.bukti_seminar != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Bukti Seminar',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/bukti-seminar/${e.item.token}/${e.item.bukti_seminar}`
            });
          }
        }
      }
    });
  },
  methods: {
    getDosenBySearch(first = false) {
      if (this.search == '') {
        this.search_on = false;
        this.getDosens();
        return;
      }
      if (first) {
        this.search_on = true;
        this.dosens.current_page = 1;
        this.dosens.data = [];
      }
      axios.get(`${window.location.origin}/api/kmm/dosen?kueri=${this.search}&page=${this.dosens.current_page}`)
      .then(response => {
        this.dosens = response.data.data;
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil instansi',
            message: error.response.status
        });
      });
    },
    getDosens() {
      axios.get(`${window.location.origin}/api/kmm/dosen?page=${this.dosens.current_page}`)
      .then(response => {
        this.dosens = response.data.data;
      })
      .catch(error => {
        console.log(error);
      });
    },
    openDetailModal(item) {
      this.activeDosen = {
        id : item.id_dosen,
        nama: item.nama,
        email: item.email,
        nik: item.nik,
        password: '',
        password_confirmation: ''
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
        nik: {
          invalid: false,
          feedback: ''
        },
        password: {
          invalid: false,
          feedback: ''
        }
      }
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeInstansi = {
        nik: '',
        nama: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    },
    addInstansi() {
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/kmm/dosen`, this.createItem)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Instansi berhasil ditambahkan!');
        this.createItem = {
          nama: '',
          email: '',
          nik: '',
          password: '',
          password_confirmation: ''
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
          nik: {
            invalid: false,
            feedback: ''
          },
          password: {
            invalid: false,
            feedback: ''
          }
        }
        this.getDosens();
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
            nik: {
              invalid: !!error.response.data.errors.nik,
              feedback: error.response.data.errors.nik ? error.response.data.errors.nik.join(' & ') : ""
            },
            password: {
              invalid: !!error.response.data.errors.password,
              feedback: error.response.data.errors.password ? error.response.data.errors.password.join(' & ') : ""
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
    update(dosen) {
      this.$store.dispatch('showLoadingAlert');
      //if password is empty, remove it from the request
      if (dosen.password == '') {
        delete dosen.password;
        delete dosen.password_confirmation;
      }
      axios.put(`${window.location.origin}/api/kmm/dosen/${dosen.nik}`, dosen)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Instansi berhasil diupdate!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getDosens();
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
            nik: {
              invalid: !!error.response.data.errors.nik,
              feedback: error.response.data.errors.nik ? error.response.data.errors.nik.join(' & ') : ""
            },
            password: {
              invalid: !!error.response.data.errors.password,
              feedback: error.response.data.errors.password ? error.response.data.errors.password.join(' & ') : ""
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
    deleteDosen(dosen) {
      this.$store.dispatch('showLoadingAlert');
      axios.delete(`${window.location.origin}/api/kmm/dosen/${dosen.nik}`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Instansi berhasil dihapus!');
        this.closeModal();
        this.getDosens();
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
