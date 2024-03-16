<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-2">Daftar Bimbingan</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" v-model="search" @keyup.enter="getMagangBySearch(true)"/>
                <CInputGroupText @click="getMagangBySearch(true)" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
            <div class="col-4 mt-2 text-right right">
              <pagination v-if="!search_on"
                :pagination="magangs"
                @paginate="getMagangs()"
                :offset="4">
              </pagination>
              <pagination v-if="search_on"
                :pagination="magangs"
                @paginate="getMagangBySearch(false)"
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
                  <CTableHeaderCell>Instansi</CTableHeaderCell>
                  <CTableHeaderCell>Progres</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="magangs.data.length > 0">
                <CTableRow v-for="(magang) in magangs.data" :key="magang.id">
                  <CTableDataCell class="text-center">
                    {{ magang.nim != null ? magang.mahasiswa.nim : '-' }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ magang.id_instansi != null ? magang.instansi.nama : '-' }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ magang.id_progres != null ? magang.progres.nama_progres : '-' }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailModal(magang)">Detail</CButton>
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
      <CModalTitle>Detail {{ activeMagang.mahasiswa.nim }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow>
        <CCol :md="4">
          <CLabel>NIM</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMagang.mahasiswa.nim }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Nama</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMagang.mahasiswa.nama }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Instansi</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMagang.id_instansi != null ? activeMagang.instansi.nama : '-' }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Topik</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMagang.id_topik != null ? activeMagang.topik.nama_topik : '-' }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Status dosen</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMagang.status_dosen == 1 ? 'Disetujui' : (activeMagang.status_dosen == -1 ? 'Ditolak' : 'Menunggu') }}</p>
        </CCol>
      </CRow>
      <p>Rencana :</p>
      <CTable v-if="activeMagang.rencana_magang != null" class="mb-3">
                  <CTableHead>
                    <CTableRow>
                      <CTableHeaderCell scope="col">Minggu ke-</CTableHeaderCell>
                      <CTableHeaderCell scope="col">Kegiatan</CTableHeaderCell>
                    </CTableRow>
                  </CTableHead>
                  <CTableBody>
                    <CTableRow v-for="rencana in activeMagang.rencana_magang" :key="rencana.id_rencana">
                      <CTableDataCell>{{ rencana.minggu }}</CTableDataCell>
                      <CTableDataCell>{{ rencana.kegiatan }}</CTableDataCell>
                    </CTableRow>
                  </CTableBody>
        </CTable>
        <p v-else>Rencana belum diisi</p>
    </CModalBody>
    <CModalFooter>
      <CButton color="primary" @click="approve(activeMagang)">
        Approve
      </CButton>
      <CButton color="danger" @click="reject(activeMagang)">
        Reject
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
  name: 'Bimbingan',
  components: {
    pagination
  },
  data() {
    return {
      search_on: false,
      search: '',
      user: null,
      magangs: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1,
        data: []
      },
      offset: 4,
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeMagang: null
    }
  },
  async created() {
    this.getMagangs();
  },
  mounted() {
    console.log('Dashboard component mounted.');
  },
  watch : {
    '$store.state.user': {
      handler(user) {
        if (user != null) {
          this.user = user;
          this.echo();
        }
      }
    }
  },
  methods: {
    echo() {
      Echo.private(`Duser.${this.user.nik}`)
      .listen('Mgng', (e) => {
        this.getMagang();
        if (e.type == "update" && e.item.id_magang == this.activeMagang.id_magang) {
          this.openDetailModal(e.item);
        } else if (e.type == "destroy" && e.item.id_magang == this.activeMagang.id_magang) {
          this.closeModal();
        }
      }).listen('RncMgng', (e) => {
        if (e.item.id_magang == this.activeMagang.id_magang) {
          //search for the rencana magang with the same id as e.item.id_rencana then update it/create it or delete it
          if (e.type == "update" || e.type == "store") {
            let index = this.activeMagang.rencana_magang.findIndex(rencana => rencana.id_rencana == e.item.id_rencana);
            if (index != -1) {
              this.activeMagang.rencana_magang[index] = e.item;
            } else {
              this.activeMagang.rencana_magang.push(e.item);
            }
          } else if (e.type == "destroy") {
            let index = this.activeMagang.rencana_magang.findIndex(rencana => rencana.id_rencana == e.item.id_rencana);
            if (index != -1) {
              this.activeMagang.rencana_magang.splice(index, 1);
            }
          }
        }
      });
    },
    getMagangBySearch(first = false) {
      if (this.search == '') {
        this.search_on = false;
        this.getMagangs();
        return;
      }
      if (first) {
        this.search_on = true;
        this.magangs.current_page = 1;
        this.magangs.data = [];
      }
      axios.get(`${window.location.origin}/api/kmm/magang/search?kueri=${this.search}&page=${this.magangs.current_page}`)
      .then(response => {
        this.magangs = response.data.data;
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil magang',
            message: error.response.status
        });
      });
    },
    getMagangs() {
      axios.get(`${window.location.origin}/api/kmm/magang?page=${this.magangs.current_page}`)
      .then(response => {
        this.magangs = response.data.data;
      })
      .catch(error => {
        console.log(error);
      });
    },
    approve(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/magang/${item.id_magang}/approve`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil menyetujui magang');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getMagangs();
      })
      .catch(e => {
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
        console.log(e);
      });
    },
    reject(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/magang/${item.id_magang}/reject`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil menolak magang');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getMagangs();
      })
      .catch(e => {
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
        console.log(e);
      });
    },
    openDetailModal(item) {
      this.activeMagang = item;
      console.log(this.activeMagang);
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeMagang = null;
    }
  }
}
</script>
