<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-6">Daftar Mahasiswa</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getUsers"/>
                <CInputGroupText @click="getUsers" class="cursor-pointer">
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
                :is-loading="mahasiswa.isLoading"
                :is-re-search="mahasiswa.research"
                :columns="mahasiswa.columns"
                :rows="mahasiswa.rows"
                :total="mahasiswa.totalRecordCount"
                :sortable="mahasiswa.sortable"
                :messages="mahasiswa.messages"
                @do-search="mahasiswaSearch"
            >
            <template v-slot:status="data">
              <CBadge v-if="data.value.status == 1" color="success">disetujui</CBadge>
              <CBadge v-if="data.value.status == 2" color="danger">Ditolak</CBadge>
              <CBadge v-if="data.value.status == 0" color="warning">Menunggu</CBadge>
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
  name: 'Mahasiswa',
  data() {
    return {
      mahasiswa: {
        isLoading: false,
        columns: [
          {
            label: 'NIM',
            field: 'nim',
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: '20%',
            sortable: true,
            isKey: true
          },
          {
            label: 'Nama',
            field: 'nama',
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "40%",
            sortable: true,
            sortable: true
          },
          {
            label: "Status",
            field: "status",
            headerClasses: ["table-header", "text-center"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "25%",
            sortable: true
          },
          {
            label: "Action",
            field: "none",
            width: "15%",
            sortable: false,
            headerClasses: ["table-header", "text-center"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            }
          },
        ],
        rows: [],
        totalRecordCount: 0,
        sortable: {
          order: "nim",
          sort: "desc",
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
    mahasiswaSearch(offset, limit, order, sort) {
      this.mahasiswa.isLoading = true;
      let page = offset / limit + 1;
      let url = `${window.location.origin}/api/kmm/mahasiswa?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
      axios.get(url)
      .then(response => {
        this.mahasiswa.research = false;
        this.mahasiswa.rows = response.data.data.data;
        this.mahasiswa.totalRecordCount = response.data.data.total;
        this.mahasiswa.page = {
          limit: limit,
          offset: offset
        };
        this.mahasiswa.sortable = {
          order: order,
          sort: sort
        };
        this.mahasiswa.isLoading = false;
      })
    },
    getUsers() {
      this.search = document?.getElementById('search')?.value ?? this.search;
      this.mahasiswa.totalRecordCount = 0;
      this.mahasiswa.rows = [];
      this.mahasiswa.page = {
        limit: 10,
        offset: 0
      };
      this.mahasiswa.research = true;
      this.mahasiswaSearch(this.mahasiswa.page.offset, this.mahasiswa.page.limit, this.mahasiswa.sortable.order, this.mahasiswa.sortable.sort);
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
