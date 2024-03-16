<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-2">Daftar Mahasiswa</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" v-model="search" @keyup.enter="getMahasiswaBySearch(true)"/>
                <CInputGroupText @click="getMahasiswaBySearch(true)" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
            <div class="col-4 mt-2 text-right right">
              <pagination v-if="!search_on"
                :pagination="mahasiswas"
                @paginate="getUsers()"
                :offset="4">
              </pagination>
              <pagination v-if="search_on"
                :pagination="mahasiswas"
                @paginate="getMahasiswaBySearch(false)"
                :offset="4">
              </pagination>
            </div>
          </CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    NIM
                  </CTableHeaderCell>
                  <CTableHeaderCell>Nama</CTableHeaderCell>
                  <CTableHeaderCell>Status</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="mahasiswas.data.length > 0">
                <CTableRow v-for="(mahasiswa) in mahasiswas.data" :key="mahasiswa.id">
                  <CTableDataCell class="text-center">
                    {{ mahasiswa.nim }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ mahasiswa.nama }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CBadge v-if="mahasiswa.status > 0" color="success">disetujui</CBadge><CBadge v-if="mahasiswa.status < 0" color="danger">Ditolak</CBadge><CBadge v-if="mahasiswa.status == 0" color="warning">Menunggu</CBadge>
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailModal(mahasiswa)">Detail</CButton>
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
      <CModalTitle>Detail {{ activeMahasiswa.nim }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow>
        <CCol :md="4">
          <CLabel>NIM</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMahasiswa.nim }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Nama</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMahasiswa.nama }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Email</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMahasiswa.email }} <CBadge v-if="activeMahasiswa.konfirmasi < 1" color="danger">Belum dikonfirmasi</CBadge><CBadge v-if="activeMahasiswa.konfirmasi > 0" color="success">Sudah dikonfirmasi</CBadge></p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>No. Telp</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMahasiswa.no_telp }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Status</CLabel>
        </CCol>
        <CCol :md="8">
          <p><CBadge v-if="activeMahasiswa.status > 0" color="success">disetujui</CBadge><CBadge v-if="activeMahasiswa.status < 0" color="danger">Ditolak</CBadge><CBadge v-if="activeMahasiswa.status == 0" color="warning">Menunggu</CBadge></p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Dokumen registrasi</CLabel>
        </CCol>
        <CCol v-if="activeMahasiswa.dokumens.length > 0" :md="8">
          <a :href="dokumen.link" target="_blank" v-for="dokumen in activeMahasiswa.dokumens" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ dokumen.nama }}</a>
        </CCol>
        <CCol v-else :md="8">
          <p>Tidak ada dokumen</p>
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="primary">
        Magang
      </CButton>
      <CButton color="success" @click="approve(activeMahasiswa)">
        Terima
      </CButton>
      <CButton color="danger" @click="reject(activeMahasiswa)">
        Tolak
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
  name: 'Mahasiswa',
  components: {
    pagination
  },
  data() {
    return {
      search_on: false,
      search: '',
      mahasiswas: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1,
        data: []
      },
      offset: 4,
      createItem: {
        name: '',
        description: ''
      },
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeMahasiswa: {
        id : 0,
        nim: '',
        nama: '',
        email: '',
        no_telp: '',
        status: '',
        konfirmasi: '',
        dokumens: []
      }
    }
  },
  async created() {
    this.getUsers();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen('Mhs', (e) => {
      this.getUsers();
      if (e.type == "update" && e.item.nim == this.activeMahasiswa.nim) {
          this.openDetailModal(e.item);
        } else if (e.type == "destroy" && e.item.nim == this.activeMahasiswa.nim) {
          this.closeModal();
        }
    })
    .listen("DokReg", (e) => {
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
    getMahasiswaBySearch(first = false) {
      if (this.search == '') {
        this.search_on = false;
        this.getUsers();
        return;
      }
      if (first) {
        this.search_on = true;
        this.mahasiswas.current_page = 1;
        this.mahasiswas.data = [];
      }
      axios.get(`${window.location.origin}/api/kmm/mahasiswa/search?kueri=${this.search}&page=${this.mahasiswas.current_page}`)
      .then(response => {
        this.mahasiswas = response.data.data;
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil instansi',
            message: error.response.status
        });
      });
    },
    getUsers() {
      axios.get(`${window.location.origin}/api/kmm/mahasiswa?page=${this.mahasiswas.current_page}`)
      .then(response => {
        this.mahasiswas = response.data.data;
      })
      .catch(error => {
        console.log(error);
      });
    },
    openDetailModal(item) {
      this.activeMahasiswa = {
        nim: item.nim,
        nama: item.nama,
        email: item.email,
        no_telp: item.no_telp,
        status: item.status,
        konfirmasi: item.konfirmasi,
        dokumens: []
      }
      axios.get(`${window.location.origin}/api/kmm/dokumen_registrasi/${item.nim}`)
      .then(response => {
          if(response.data.data.krs != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'KRS',
              link:  `${window.location.origin}/mahasiswa/dokumen-registrasi/krs/${response.data.data.token}/${response.data.data.krs}`
            });
          }
          if(response.data.data.kartu_mahasiswa != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Kartu Mahasiswa',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/kartu-mahasiswa/${response.data.data.token}/${response.data.data.kartu_mahasiswa}`
            });
          }
          if(response.data.data.transkrip != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Transkrip',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/transkrip/${response.data.data.token}/${response.data.data.transkrip}`
            });
          }
          if(response.data.data.bukti_seminar != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Bukti Seminar',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/bukti-seminar/${response.data.data.token}/${response.data.data.bukti_seminar}`
            });
          }
      })
      .catch(error => {
        if (error.response.status == 404) {
          this.activeMahasiswa.dokumens = [];
        }
        console.log('Error: '+error);
      });
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeMahasiswa = {
        id : 0,
        nim: '',
        nama: '',
        email: '',
        no_telp: '',
        status: '',
        konfirmasi: '',
        dokumen_registrasi: []
      }
    },
    approve(mahasiswa) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/mahasiswa/${mahasiswa.nim}/aktif`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Mahasiswa disetujui!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getUsers();
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menyetujui mahasiswa!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menyetujui mahasiswa!',
            message: error.response.status
          });
        }
      });
    },
    reject(mahasiswa) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/mahasiswa/${mahasiswa.nim}/reject`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Mahasiswa ditolak!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getUsers();
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menolak mahasiswa!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menolak mahasiswa!',
            message: error.response.status
          });
        }
      });
    }
  }
}
</script>
