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
          <a :href="`${window.location.origin}/mahasiswa/ta/proposal-ta/${activeProposal.file_proposal}`" target="_blank" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ activeProposal.judul_proposal }}</a>
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
import Swal from 'sweetalert2';
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
        nim: '',
        judul_proposal: '',
        file_proposal: '',
        tahun: '',
        semester_id: 1,
        status_proposal: 0,
        mahasiswa: {
          nim: '',
          nama: ''
        }
      }
    }
  },
  async created() {
    //like constructor
    this.getProposal();
  },
  mounted() {
    //like update()
    console.log('Dashboard component mounted.');
    // Echo.channel('items').listen('ItemAdded', (e) => {
    //   console.log(e);
    //   this.items.push(e.item);
    // }).listen('ItemUpdated', (e) => {
    //   console.log(e);
    //   this.items = this.items.map(i => i.id === e.item.id ? e.item : i);
    // }).listen('ItemDeleted', (e) => {
    //   console.log(e);
    //   this.items = this.items.filter(i => i.id !== e.id);
    // });
  },
  methods: {
    getProposal() {
      axios.get(`${window.location.origin}/api/ta/proposal_ta?page=${this.proposals.current_page}`)
      .then(response => {
        this.proposals = response.data.data;
      })
      .catch(error => {
        this.itemstatus = error.response.data.message;
        console.log(error);
      });
    },
    openDetailModal(item) {
      this.activeProposal = {
        nim: item.nim,
        judul_proposal: item.judul_proposal,
        tahun: item.tahun,
        semester_id: item.semester_id,
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
        nim: '',
        judul_proposal: '',
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
      this.showLoadingAlert();
      axios.put(`${window.location.origin}/api/ta/proposal_ta/${proposal.nim}/approved`)
      .then(response => {
        this.showSuccessAlert('Proposal disetujui!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getProposal();
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.showErrorAlert('Gagal menyetujui proposal!', error.response.data);
        } else {
          console.log(error);
          this.showErrorAlert('Gagal menyetujui proposal!', error.response.status);
        }
      });
    },
    reject(proposal) {
      this.showLoadingAlert();
      axios.put(`${window.location.origin}/api/ta/proposal_ta/${proposal.nim}/rejected`)
      .then(response => {
        this.showSuccessAlert('Proposal ditolak!');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getProposal();
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.showErrorAlert('Gagal menolak proposal!', error.response.data);
        } else {
          console.log(error);
          this.showErrorAlert('Gagal menolak proposal!', error.response.status);
        }
      });
    },
    showLoadingAlert() {
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
      });
    },
    showSuccessAlert(message) {
      Swal.fire({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        icon: "success",
        title: message
      });
    },
    showErrorAlert(message, error) {
      Swal.fire({
        title: `Error ${error.status}`,
        text: message,
        icon: 'error',
        details: error.message || error // Display detailed error message if available
      });
    },
  }
}
</script>
