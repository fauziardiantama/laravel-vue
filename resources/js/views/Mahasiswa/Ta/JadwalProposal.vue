<template>
    <div>
      <CRow>
        <CCol :md="12">
          <CCard class="mb-4">
            <CCardHeader class="row">
              <p class="col-7">Daftar Jadwal</p>
              <div class="col-5 mt-2 text-right right">
                <pagination  :pagination="jadwals"
                       @paginate="getJadwal()"
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
                    <CTableHeaderCell>Tanggal</CTableHeaderCell>
                    <CTableHeaderCell>Tahun</CTableHeaderCell>
                    <CTableHeaderCell>Semester</CTableHeaderCell>
                    <CTableHeaderCell>Sesi</CTableHeaderCell>
                    <CTableHeaderCell>Ruangan</CTableHeaderCell>
                    <CTableHeaderCell>Actions</CTableHeaderCell>
                  </CTableRow>
                </CTableHead>
                <CTableBody v-if="jadwals.data.length > 0">
                  <CTableRow v-for="(jadwal,index) in jadwals.data" :key="jadwal.id">
                    <CTableDataCell class="text-center">
                        <!-- index calculated with pages -->
                        {{ index + 1 + (jadwals.current_page - 1) * jadwals.per_page }}
                    </CTableDataCell>
                    <CTableDataCell>
                      {{ jadwal.tanggal }}
                    </CTableDataCell>
                    <CTableDataCell>
                      {{ jadwal.tahun }}
                    </CTableDataCell>
                    <CTableDataCell>
                      {{ jadwal.semester?.semester }}
                    </CTableDataCell>
                    <CTableDataCell>
                      {{ jadwal.sesi_ujian?.no_sesi }}
                    </CTableDataCell>
                    <CTableDataCell>
                      {{ jadwal.ruangan?.nama }}
                    </CTableDataCell>
                    <CTableDataCell>
                      <CButton color="primary" @click="openDetailModal(jadwal)">Detail</CButton>
                    </CTableDataCell>
                  </CTableRow>
                </CTableBody>
                <CTableBody v-else>
                  <CTableRow>
                    <CTableDataCell class="text-center" colspan="7">
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
        <CModalTitle>Detail Sesi-{{ activeJadwal?.sesi?.no_sesi }}</CModalTitle>
      </CModalHeader>
      <CModalBody>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Tanggal</CLabel>
          </CCol>
          <CCol :md="8">
            {{ activeJadwal.tanggal }}
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Tahun</CLabel>
          </CCol>
          <CCol :md="8">
            {{ activeJadwal.tahun }}
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Semester</CLabel>
          </CCol>
          <CCol :md="8">
            {{ activeJadwal.semester?.semester }}
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Sesi</CLabel>
          </CCol>
          <CCol :md="8">
            {{ activeJadwal.sesi_ujian?.no_sesi }}
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Waktu mulai</CLabel>
          </CCol>
          <CCol :md="8">
            <p>{{ activeJadwal?.sesi_ujian?.waktu_mulai }}</p>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Waktu selesai</CLabel>
          </CCol>
          <CCol :md="8">
            <p>{{ activeJadwal?.sesi_ujian?.waktu_selesai }}</p>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Ruangan</CLabel>
          </CCol>
          <CCol :md="8">
            {{ activeJadwal.ruangan?.nama }}
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Dosen penguji</CLabel>
          </CCol>
          <CCol v-if="activeJadwal?.dosen.length > 0" :md="8">
            <p v-for="(dosen,index) in activeJadwal?.dosen" :key="index">{{ dosen.nama }}</p>
          </CCol>
            <CCol v-else :md="8">
                <p>-</p>
            </CCol>
        </CRow>
        <CRow>
          <CCol :md="4">
            <CLabel>Proposal Mahasiswa</CLabel>
          </CCol>
          <CCol v-if="activeJadwal?.proposal_ta.length > 0" :md="8">
            <p v-for="(proposal_ta,index) in activeJadwal?.proposal_ta" :key="index">{{ proposal_ta.nim }} - {{ proposal_ta.judul_proposal }}</p>
          </CCol>
            <CCol v-else :md="8">
                <p>-</p>
            </CCol>
        </CRow>
      </CModalBody>
      <CModalFooter>
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
    name: 'JadwalProposal',
    components: {
      pagination
    },
    data() {
      return {
        jadwals: {
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
        activeJadwal: null
      }
    },
    async created() {
      this.getJadwal();
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
      getJadwal() {
        axios.get(`${window.location.origin}/api/ta/jadwal_proposal_ta?page=${this.jadwals.current_page}`)
        .then(response => {
          this.jadwals = response.data.data;
          if (this.jadwals.data.length == 0) {
            this.itemstatus = 'Tidak ada jadwal';
          }
        })
        .catch(error => {
            this.jadwals = {
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
        this.closeModal();
        this.activeJadwal = item;
        this.showDetailModal = true;
      },
      closeModal() {
        this.showDetailModal = false;
        this.activeJadwal = null;
      },
      daftarProposal(item) {
        this.closeModal();
        this.activeJadwal = item;
        this.showProposal = true;
      },
    }
  }
  </script>
  