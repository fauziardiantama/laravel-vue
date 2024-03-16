<template>
  <div>
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
                <CTableRow v-for="(proposal) in proposals.data" :key="proposal.id">
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
                    <CButton color="primary" @click="openDetailModal(proposal)">Detail</CButton>
                  </CTableDataCell>
                </CTableRow>
              </CTableBody>
              <CTableBody v-else>
                <CTableRow>
                  <CTableDataCell class="text-center" colspan="6">
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
      <CModalTitle>{{ activeProposal.judul_proposal }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow>
        <CCol :md="4">
          <CLabel>NIM</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.nim }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>NIM</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.mahasiswa.nama }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Judul Proposal</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.judul_proposal }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Tahun</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.tahun }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Semester</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.semester_id == 1 ? 'Ganjil' : 'Genap' }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Status proposal</CLabel>
        </CCol>
        <CCol :md="8">
          <p><CBadge v-if="activeProposal.status_proposal > 0" color="success">disetujui</CBadge><CBadge v-if="activeProposal.status_proposal < 0" color="danger">Ditolak</CBadge><CBadge v-if="activeProposal.status_proposal == 0" color="warning">Menunggu</CBadge></p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Dokumen proposal</CLabel>
        </CCol>
        <CCol :md="8">
          <a :href="`${app}/mahasiswa/ta/proposal-ta/${activeProposal.token}/${activeProposal.file_proposal}`" target="_blank" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ activeProposal.judul_proposal }}</a>
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="success" @click="approve(activeProposal)">
        Terima
      </CButton>
      <CButton color="danger" @click="reject(activeProposal)">
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
  name: 'Proposal',
  components: {
    pagination
  },
  data() {
    return {
      proposals: {
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
      activeProposal: {
        id: '',
        nim: '',
        judul_proposal: '',
        token: '',
        file_proposal: '',
        tahun: '',
        semester_id: 1,
        status_proposal: 0,
        mahasiswa: {
          nim: '',
          nama: ''
        }
      },
      app: window.location.origin
    }
  },
  async created() {
    this.getProposal();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen('Prop', (e) => {
      this.getProposal();
      console.log({
        event: "Prop",
        data: e,
        activeid: this.activeProposal.id,
        isTrue: e.type == "update" && e.item.id == this.activeProposal.id
      })
      if (e.type == "update" && e.item.id == this.activeProposal.id) {
          console.log("update");
          console.log(e.item);
          this.openDetailModal(e.item);
        } else if (e.type == "destroy" && e.item.id == this.activeProposal.id) {
          this.closeModal();
        }
    });
  },
  methods: {
    getProposal() {
      axios.get(`${this.app}/api/ta/proposal_ta?page=${this.proposals.current_page}`)
      .then(response => {
        this.proposals = response.data.data;
      })
      .catch(error => {
        this.proposals = {
            total: 0,
            per_page: 2,
            from: 1,
            to: 0,
            current_page: 1,
            data: []
      };
        this.itemstatus = error.response.data.message;
        console.log(error);
      });
    },
    openDetailModal(item) {
      this.activeProposal = {
        id: item.id,
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
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeProposal = {
        id: '',
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
    approve(proposal) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${this.app}/api/ta/proposal_ta/${proposal.nim}/approved`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Proposal disetujui!');
        this.closeModal();
        this.openDetailModal(response.data.data);
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menyetujui proposal!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menyetujui proposal!',
            message: error.response.status
          });
        }
      });
    },
    reject(proposal) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${this.app}/api/ta/proposal_ta/${proposal.nim}/rejected`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Proposal ditolak!');
        this.closeModal();
        this.openDetailModal(response.data.data);
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menolak proposal!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menolak proposal!',
            message: error.response.status
          });
        }
      });
    }
  }
}
</script>
