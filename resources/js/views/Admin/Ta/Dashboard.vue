<template>
  <div>
    <CRow class="mb-4">
      <CCol :xs="4">
        <CWidgetStatsF color="primary" title="Dosen" :value="dosenCount">
          <template #icon>
            <CIcon icon="cil-settings" size="xl"/>
          </template>
        </CWidgetStatsF>
      </CCol>
      <CCol :xs="4">
        <CWidgetStatsF color="info" title="Mahasiswa terdaftar" :value="mahasiswaCount">
          <template #icon>
            <CIcon icon="cil-settings" size="xl"/>
          </template>
        </CWidgetStatsF>
      </CCol>
    </CRow>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-7">Daftar Mahasiswa</p>
            <!-- Make pagination -->
            <div class="col-5 mt-2 text-right right">
              <pagination
                :pagination="mahasiswas"
                @paginate="getMahasiswa()"
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
                <CTableRow v-for="(mahasiswa, index) in mahasiswas.data" :key="mahasiswa.id">
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
                    <CButton color="primary" @click="openDetailMahasiswa(mahasiswa)">Detail</CButton>
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
              <CTableBody v-else>
                <CTableRow>
                  <CTableDataCell class="text-center" colspan="4">
                    {{ mahasiswastatus }}
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
            </CTable>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-7">Daftar Proposal</p>
            <div class="col-5 mt-2 text-right right">
              <pagination  :pagination="proposals"
                     @paginate="getProposal()"
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
                  <CTableHeaderCell>Nama mahasiswa</CTableHeaderCell>
                  <CTableHeaderCell>Judul proposal</CTableHeaderCell>
                  <CTableHeaderCell>Tahun</CTableHeaderCell>
                  <CTableHeaderCell>Semester</CTableHeaderCell>
                  <CTableHeaderCell>Status</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="proposals.data.length > 0">
                <CTableRow v-for="(proposal, index) in proposals.data" :key="proposal.id">
                  <CTableDataCell class="text-center">
                    {{ proposal.nim }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ proposal.mahasiswa.nama }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ proposal.judul_proposal }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ proposal.tahun }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ proposal.semester == 1 ? 'Ganjil' : 'Genap' }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CBadge v-if="proposal.status_proposal > 0" color="success">disetujui</CBadge><CBadge v-if="proposal.status_proposal < 0" color="danger">Ditolak</CBadge><CBadge v-if="proposal.status_proposal == 0" color="warning">Menunggu</CBadge>
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openDetailProposal(proposal)">Detail</CButton>
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
              <CTableBody v-else>
                <CTableRow>
                  <CTableDataCell class="text-center" colspan="6">
                    {{ proposalstatus }}
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
            </CTable>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </div>
  <CModal size="lg" backdrop="static" :visible="showDetailProposal" @close="closeProposal">
    <CModalHeader>
      <CModalTitle>{{ activeProposal.judul_proposal }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow>
        <CCol :md="4">
          <label>NIM</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.nim }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>NIM</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.mahasiswa.nama }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Judul Proposal</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.judul_proposal }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Tahun</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.tahun }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Semester</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.semester == 1 ? 'Ganjil' : 'Genap' }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Status proposal</label>
        </CCol>
        <CCol :md="8">
          <p><CBadge v-if="activeProposal.status_proposal > 0" color="success">disetujui</CBadge><CBadge v-if="activeProposal.status_proposal < 0" color="danger">Ditolak</CBadge><CBadge v-if="activeProposal.status_proposal == 0" color="warning">Menunggu</CBadge></p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Dokumen registrasi</label>
        </CCol>
        <CCol :md="8">
          <a :href="`${window.location.origin}/mahasiswa/ta/proposal-ta/${activeProposal.token}/${activeProposal.file_proposal}`" target="_blank" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ activeProposal.judul_proposal }}</a>
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="secondary" @click="closeProposal">
        Close
      </CButton>
    </CModalFooter>
  </CModal>
  <CModal size="lg" backdrop="static" :visible="showDetailMahasiswa" @close="closeMahasiswa">
    <CModalHeader>
      <CModalTitle>Detail {{ activeMahasiswa.nim }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow>
        <CCol :md="4">
          <label>NIM</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMahasiswa.nim }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Nama</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMahasiswa.nama }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Email</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMahasiswa.email }} <CBadge v-if="activeMahasiswa.konfirmasi < 1" color="danger">Belum dikonfirmasi</CBadge><CBadge v-if="activeMahasiswa.konfirmasi > 0" color="success">Sudah dikonfirmasi</CBadge></p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>No. Telp</label>
        </CCol>
        <CCol :md="8">
          <p>{{ activeMahasiswa.no_telp }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Status</label>
        </CCol>
        <CCol :md="8">
          <p><CBadge v-if="activeMahasiswa.status > 0" color="success">disetujui</CBadge><CBadge v-if="activeMahasiswa.status < 0" color="danger">Ditolak</CBadge><CBadge v-if="activeMahasiswa.status == 0" color="warning">Menunggu</CBadge></p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <label>Dokumen registrasi</label>
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
      <CButton color="secondary" @click="closeMahasiswa">
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
  name: 'Dashboard',
  components: {
    pagination
  },
  data() {
    return {
      dosenCount: 0,
      mahasiswaCount: 0,
      mahasiswas: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1,
        data: []
      },
      offset: 4,
      mahasiswastatus : 'Mengambil items',
      showDetailMahasiswa: false,
      activeMahasiswa: {
        id : 0,
        nim: '',
        nama: '',
        email: '',
        no_telp: '',
        status: '',
        konfirmasi: '',
        dokumens: []
      },
      proposals: {
        total: 0,
        per_page: 2,
        from: 1,
        to: 0,
        current_page: 1,
        data: []
      },
      proposalstatus : 'Mengambil items',
      showDetailProposal: false,
      activeProposal: {
        id: 0,
        nim: '',
        judul_proposal: '',
        token: '',
        file_proposal: '',
        tahun: '',
        semester_id: null,
        status_proposal: 0,
        mahasiswa: {
          nim: '',
          nama: ''
        }
      }
    }
  },
  async created() {
    this.getMahasiswa();
    this.getProposal();
    try {
      const response = await axios.get(`${window.location.origin}/api/ta/dosen`);
      this.dosenCount = response.data.data.total;
      const response2 = await axios.get(`${window.location.origin}/api/ta/mahasiswa`);
      this.mahasiswaCount = response2.data.data.total;
    } catch (error) {
      console.log(error);
    }
  },
  mounted() {
    Echo.private('Admin')
    .listen('Mhs', (e) => {
      this.getMahasiswa();
    }).listen('DosenUpdated', (e) => {
      console.log(e);
    }).listen('Prop', (e) => {
      this.getProposal();
    });
  },
  methods: {
    getMahasiswa() {
      axios.get(`${window.location.origin}/api/ta/mahasiswa?page=${this.mahasiswas.current_page}`)
      .then(response => {
        this.mahasiswas = response.data.data;
      })
      .catch(error => {
        this.mahasiswastatus = error.response.data.message;
        console.log(error);
      });
    },
    openDetailMahasiswa(item) {
      this.fillActiveMahasiswa(item);
      axios.get(`${window.location.origin}/api/ta/dokumen_registrasi/${item.nim}`)
      .then(response => {
          if (response.data.data.krs != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'KRS',
              link:  `${window.location.origin}/mahasiswa/dokumen-registrasi/krs/${response.data.data.token}/${response.data.data.krs}`
            });
          }
          if (response.data.data.kartu_mahasiswa != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Kartu Mahasiswa',
              link: `${window.location.origin}/mahasiswa/dokumen-registrasi/kartu-mahasiswa/${response.data.data.token}/${response.data.data.kartu_mahasiswa}`
            });
          }
          if (response.data.data.transkrip != null) {
            this.activeMahasiswa.dokumens.push({
              nama: 'Transkrip',
              link: `${window.location.origin}/mahasiswa/dokeumen-registrasi/transkrip/${response.data.data.token}/${response.data.data.transkrip}`
            });
          }
          if (response.data.data.bukti_seminar != null) {
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
      this.showDetailMahasiswa = true;
    },
    closeMahasiswa() {
      this.showDetailMahasiswa = false;
      this.emptyActiveMahasiswa();
    },
    getProposal() {
      axios.get(`${window.location.origin}/api/ta/proposal_ta?page=${this.proposals.current_page}`)
      .then(response => {
        this.proposals = response.data.data;
      })
      .catch(error => {
        this.proposalstatus = error.response.data.message;
        console.log(error);
      });
    },
    openDetailProposal(item) {
      this.activeProposal = {
        nim: item.nim,
        judul_proposal: item.judul_proposal,
        tahun: item.tahun,
        semester_id: item.semester_id,
        token: item.token,
        file_proposal: item.file_proposal,
        status_proposal: item.status_proposal,
        mahasiswa: {
          nim: item.mahasiswa.nim,
          nama: item.mahasiswa.nama
        }
      }
      this.showDetailProposal = true;
    },
    closeProposal() {
      this.showDetailProposal = false;
      this.activeProposal = {
        nim: '',
        judul_proposal: '',
        token: '',
        file_proposal: '',
        tahun: '',
        semester_id: null,
        status_proposal: 0,
        tahun: '',
        mahasiswa: {
          nim: '',
          nama: ''
        }
      }
    },
    fillActiveMahasiswa(item){
      this.activeMahasiswa = {
        nim: item.nim,
        nama: item.nama,
        email: item.email,
        no_telp: item.no_telp,
        status: item.status,
        konfirmasi: item.konfirmasi,
        dokumens: []
      }
    },
    emptyActiveMahasiswa(){
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
    }
  }
}
</script>
