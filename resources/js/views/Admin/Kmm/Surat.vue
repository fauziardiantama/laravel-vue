<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-2">Daftar Surat Jawaban</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" v-model="jawaban_search" @keyup.enter="getJawabanBySearch(true)"/>
                <CInputGroupText @click="getJawabanBySearch(true)" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
            <div class="col-4 mt-2 text-right right">
              <pagination v-if="!jawaban_search_on"
                :pagination="jawabans"
                @paginate="getJawabans()"
                :offset="4">
              </pagination>
              <pagination v-if="jawaban_search_on"
                :pagination="jawabans"
                @paginate="getJawabanBySearch(false)"
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
                  <CTableHeaderCell>Surat Jawaban</CTableHeaderCell>
                  <CTableHeaderCell>Status</CTableHeaderCell>
                  <CTableHeaderCell>Action</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="jawabans.data.length > 0">
                <CTableRow v-for="jawaban in jawabans.data" :key="jawaban.id_surat">
                  <CTableDataCell class="text-center">
                    {{ jawaban.magang?.nim ?? '-' }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ jawaban.magang?.mahasiswa?.nama ?? '-' }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <a href="javascript:void(0)" class="dokumen-link" :id="'jawaban-'+jawaban.id_surat" @click="getDokumenJ(jawaban)">{{ jawaban.file_surat ?? '-' }}</a>
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ jawaban.magang?.status_diterima_instansi == 1 ? 'Approved' : (jawaban.magang?.status_diterima_instansi == -1 ? 'Rejected' : 'Pending') }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="success" @click="approve(jawaban)">Approve</CButton>
                    <CButton color="danger" @click="reject(jawaban)">Tolak</CButton>
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
              <CTableBody v-else>
                <CTableRow>
                  <CTableDataCell class="text-center" colspan="4">
                    {{ jawabanstatus }}
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
            </CTable>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-2">Daftar Surat Magang</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" v-model="surat_search" @keyup.enter="getSuratBySearch(true)"/>
                <CInputGroupText @click="getSuratBySearch(true)" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
            <div class="col-4 mt-2 text-right right">
              <pagination v-if="!surat_search_on"
                :pagination="surats"
                @paginate="getSurats()"
                :offset="4">
              </pagination>
              <pagination v-if="surat_search_on"
                :pagination="surats"
                @paginate="getSuratBySearch(false)"
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
                  <CTableHeaderCell>Jenis</CTableHeaderCell>
                  <CTableHeaderCell>Status pengajuan</CTableHeaderCell>
                  <CTableHeaderCell>Surat</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="surats.data.length > 0">
                <CTableRow v-for="surat in surats.data" :key="surat.id_surat">
                  <CTableDataCell class="text-center">
                    {{ surat.magang?.nim ?? '-' }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ surat.jenis_surat == 1 ? 'Surat Pengantar' : (surat.jenis_surat == 2 ? 'Surat serah terima' : '-') }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ surat.magang?.status_pengajuan_instansi == 1 ? 'Approved' : (surat.magang?.status_pengajuan_instansi == -1 ? 'Rejected' : 'Pending') }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <a href="javascript:void(0)" :id="'magang-'+surat.id_surat" class="dokumen-link" @click="getDokumen(surat)">{{ surat.file_surat ?? '-' }}</a>
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
              <CTableBody v-else>
                <CTableRow>
                  <CTableDataCell class="text-center" colspan="4">
                    {{ suratstatus }}
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
            </CTable>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </div>
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
import axios from 'axios';

export default {
  name: 'Surat',
  components: {
    pagination
  },
  data() {
    return {
      jawaban_search_on: false,
      jawaban_search: '',
      jawabanstatus: 'Mengambil items',
      jawabans: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1,
        data: []
      },
      surat_search_on: false,
      surat_search: '',
      suratstatus: 'Mengambil items',
      surats: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1,
        data: []
      },
      offset: 4
    }
  },
  async created() {
    this.getSurats();
    this.getJawabans();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen("Mgng", (e) => {
      console.log(e);
      this.getMagangs();
      if (e.type == "update" && e.item.id_magang == this.activeMagang?.id_magang) {
          this.openDetailModal(e.item);
        } else if (e.type == "destroy" && e.item.id_magang == this.activeMagang?.id_magang) {
          this.closeModal();
        }
    });
  },
  methods: {
    getSuratBySearch(first = false) {
      if (this.surat_search == '') {
        this.surat_search_on = false;
        this.getSurat();
        return;
      }
      if (first) {
        this.surat_search_on = true;
        this.surats.current_page = 1;
        this.surats.data = [];
      }
      axios.get(`${window.location.origin}/api/kmm/surat_magang/all/search?kueri=${this.surat_search}&page=${this.surats.current_page}`)
      .then(response => {
        this.surats = response.data.data;
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil instansi',
            message: error.response.status
        });
      });
    },
    getSurats() {
      axios.get(`${window.location.origin}/api/kmm/surat_magang/all?page=${this.surats.current_page}`)
      .then(response => {
        this.surats = response.data.data;
      })
      .catch(error => {
        console.log(error);
      });
    },
    getJawabanBySearch(first = false) {
      if (this.jawaban_search == '') {
        this.jawaban_search_on = false;
        this.getJawaban();
        return;
      }
      if (first) {
        this.jawaban_search_on = true;
        this.jawabans.current_page = 1;
        this.jawabans.data = [];
      }
      axios.get(`${window.location.origin}/api/kmm/surat_jawaban/all/search?kueri=${this.jawaban_search}&page=${this.jawabans.current_page}`)
      .then(response => {
        this.jawabans = response.data.data;
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil instansi',
            message: error.response.status
        });
      });
    },
    getJawabans() {
      axios.get(`${window.location.origin}/api/kmm/surat_jawaban/all?page=${this.jawabans.current_page}`)
      .then(response => {
        this.jawabans = response.data.data;
      })
      .catch(error => {
        console.log(error);
      });
    },
    approve(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/kmm/magang/${item.magang?.id_magang}/instansi/approve`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil menyetujui magang');
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
      axios.post(`${window.location.origin}/api/kmm/magang/${item.magang?.id_magang}/instansi/reject`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil menolak magang');
      })
      .catch(e => {
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
        console.log(e);
      });
    },
    getDokumen(surat) {
      this.$store.dispatch('showLoadingAlert');
      axios.get(`${window.location.origin}/api/kmm/surat_magang/all/${surat.id_surat}/dokumen`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil mengambil dokumen')
        surat = response.data.data;
        if (surat != null) {
          const url = `${window.location.origin}/mahasiswa/magang/${ surat.jenis_surat == 1 ? 'surat-pengantar' : (surat.jenis_surat == 2 ? 'surat-serah-terima' : '')}/${surat.token}/${surat.file_surat}`;
          console.log(url);
          //change href to a with id = surat.id_surat and _blank
          document.getElementById('magang-'+surat.id_surat).href = url;
          document.getElementById('magang-'+surat.id_surat).target = '_blank';
          //delete click event
          document.getElementById('magang-'+surat.id_surat).removeAttribute('onclick');
          window.open(url, '_blank');
        } else {
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil dokumen',
            message: 'Dokumen tidak ditemukan'
          });
        }
      })
      .catch(e => {
        console.log(e);
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
      });
    },
    getDokumenJ(jawaban) {
      this.$store.dispatch('showLoadingAlert');
      console.log(jawaban);
      axios.get(`${window.location.origin}/api/kmm/surat_jawaban/all/${jawaban.id_surat}/dokumen`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil mengambil dokumen')
        jawaban = response.data.data;
        if (jawaban != null) {
          console.log(jawaban);
          const url = `${window.location.origin}/mahasiswa/magang/surat-jawaban/${jawaban.token}/${jawaban.file_surat}`;
          //change href to a with id = jawaban.id_surat and _blank
          document.getElementById('jawaban-'+jawaban.id_surat).href = url;
          document.getElementById('jawaban-'+jawaban.id_surat).target = '_blank';
          //delete @click event
          document.getElementById('jawaban-'+jawaban.id_surat).removeAttribute('onclick');
          window.open(url, '_blank');
        } else {
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil dokumen',
            message: 'Dokumen tidak ditemukan'
          });
        }
      })
      .catch(e => {
        console.log(e);
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
      });
    }
  }
}
</script>
