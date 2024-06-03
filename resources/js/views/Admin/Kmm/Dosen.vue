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
            <p class="col-6">Daftar Dosen</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getDosens"/>
                <CInputGroupText @click="getDosens" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
          </CCardHeader>
          <CCardBody>
            <table-lite
              class="table-lite"
              :pageOptions="[ { value: 10, text: 10 }, { value: 25, text: 25 }, { value: 50, text: 50 }, { value: 100, text: 100 } ]"
              :is-slot-mode="true"
              :is-re-search="dosen.research"
              :is-loading="dosen.isLoading"
              :columns="dosen.columns"
              :rows="dosen.rows"
              :total="dosen.totalRecordCount"
              :sortable="dosen.sortable"
              :messages="dosen.messages"
              @do-search="dosenSearch"
            >
                <template v-slot:id_dosen="data">
                  {{ data.value.index }}
                </template>
                <template v-slot:none="data">
                  <CButton color="primary" @click="openDetailModal(data.value)">Detail</CButton>
                </template>
            </table-lite>
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
  .table-header {
    background: #f0f2f4;
    color: rgba(44, 56, 74, 0.95);
    border: 1px solid rgba(200, 204, 209, 0.99);
  }

  :deep(table.vtl-table) {
    display: table !important;
  }
</style>

<script>
export default {
  name: 'Dosen',
  data() {
    return {
      dosen: {
        isLoading: false,
        columns: [
          {
            label: "#",
            field: "id_dosen",
            headerClasses: ["table-header","text-center"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "10%",
            sortable: true,
            isKey: true,
          },
          {
            label: "NIK",
            field: "nik",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "35%",
            sortable: true,
          },
          {
            label: "Nama",
            field: "nama",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "40%",
            sortable: true,
          },
          {
            label: "Action",
            field: "none",
            headerClasses: ["table-header", "text-center"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "15%",
            sortable: false,
          }
        ],
        rows: [],
        totalRecordCount: 0,
        sortable: {
          order: "id_dosen",
          sort: "asc",
        },
        messages: {
            pagingInfo: "{0}-{1}/{2}",
            pageSizeChangeLabel: "Per Halaman ",
            gotoPageLabel: " Ke Hal. ",
            noDataAvailable: "Tidak ada data",
        },
        page: {
          limit: 10,
          offset: 0
        },
        research: false
      },
      search: '',
      createItem: {
        nama: '',
        email: '',
        nik: '',
        password: '',
        password_confirmation: ''
      },
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
    dosenSearch(offset, limit, order, sort) {
      this.dosen.isLoading = true;
      //calculate page based on offset and limit
      let page = offset / limit + 1;
      let url = `${window.location.origin}/api/kmm/dosen?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
      axios.get(url)
      .then((response) => {
        this.dosen.research = false;
        console.log(this.search == '' ? '[kosong]' : this.search);
        this.dosen.rows = response.data.data.data;
        //add iteration index and push to rows as 'index'
        let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
        this.dosen.rows.forEach((item, index) => {
          //calculate index based on current page
          item.index = index + 1 + pagination;
        });
        this.dosen.totalRecordCount = response.data.data.total;
        this.dosen.page = {
          limit: limit, 
          offset: offset,
        };
        this.dosen.sortable = {
          order: order,
          sort: sort
        };
        this.dosen.isLoading = false;
      });
    },
    getDosens() {
      this.search = document?.getElementById('search')?.value ?? this.search;
      this.dosen.totalRecordCount = 0;
      this.dosen.rows = [];
      this.dosen.page = {
        limit: 10,
        offset: 0
      };
      this.dosen.research = true;
      this.dosenSearch(this.dosen.page.offset, this.dosen.page.limit, this.dosen.sortable.order, this.dosen.sortable.sort);
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
