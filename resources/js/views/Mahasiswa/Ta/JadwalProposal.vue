<template>
    <div>
      <CRow>
        <CCol :md="12">
          <CCard class="mb-4">
            <CCardHeader class="row">
              <p class="col-6">Daftar Jadwal</p>
              <!--search bar-->
              <div class="col-6">
                <CInputGroup>
                  <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getJadwal"/>
                  <CInputGroupText @click="getJadwal" class="cursor-pointer">
                    <font-awesome-icon :icon="['fas', 'search']" />
                  </CInputGroupText>
                </CInputGroup>
              </div>
            </CCardHeader>
            <CCardBody>
              <table-lite
                  class="table-lite"
                  :is-slot-mode="true"
                  :is-re-search="jadwal.research"
                  :is-loading="jadwal.isLoading"
                  :columns="jadwal.columns"
                  :rows="jadwal.rows"
                  :total="jadwal.totalRecordCount"
                  :sortable="jadwal.sortable"
                  :messages="jadwal.messages"
                  @do-search="jadwalSearch"
              >
                <template v-slot:id="data">
                  {{ data.value.index }}
                </template>
                <template v-slot:semester_id="data">
                  {{ data.value.semester?.semester ?? '-' }}
                </template>
                <template v-slot:no_sesi="data">
                  {{ data.value.sesi_ujian?.no_sesi ?? '-' }}
                </template>
                <template v-slot:nama="data">
                  {{ data.value.ruangan?.nama ?? '-' }}
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
    name: 'JadwalProposal',
    data() {
      return {
        jadwal: {
          isLoading:false,
          columns: [
            {
              label: "#",
              field: "id",
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
              label: "Tanggal",
              field: "tanggal",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "10%",
              sortable: true,
            },
            {
              label: "Tahun",
              field: "tahun",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "10%",
              sortable: true,
            },
            {
              label: "Semester",
              field: "semester_id",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "15%",
              sortable: true,
            },
            {
              label: "Sesi",
              field: "no_sesi",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "10%",
              sortable: true,
            },
            {
              label: "Ruangan",
              field: "nama",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "15%",
              sortable: true,
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
            order: "id",
            sort: "desc"
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
      jadwalSearch(offset, limit, order, sort) {
        this.jadwal.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/ta/jadwal_proposal_ta?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
          this.jadwal.research = false;
          console.log(this.search == '' ? '[kosong]' : this.search);
          this.jadwal.rows = response.data.data.data;
          //add iteration index and push to rows as 'index'
          let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
          this.jadwal.rows.forEach((item, index) => {
            //calculate index based on current page
            item.index = index + 1 + pagination;
          });
          this.jadwal.totalRecordCount = response.data.data.total;
          this.jadwal.page = {
            limit: limit, 
            offset: offset,
          };
          this.jadwal.sortable = {
            order: order,
            sort: sort
          };
          this.jadwal.isLoading = false;
        });
      },
      getJadwal() {
        this.search = document?.getElementById('search')?.value ?? this.search;
        this.jadwal.totalRecordCount = 0;
        this.jadwal.rows = [];
        this.jadwal.page = {
          limit: 10,
          offset: 0
        };
        this.jadwal.research = true;
        this.jadwalSearch(this.jadwal.page.offset, this.jadwal.page.limit, this.jadwal.sortable.order, this.jadwal.sortable.sort);
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
  