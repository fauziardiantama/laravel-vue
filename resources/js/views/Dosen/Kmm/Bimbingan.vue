<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-6">Daftar Bimbingan</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getMagangs"/>
                <CInputGroupText @click="getMagangs" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
          </CCardHeader>
          <CCardBody>
            <table-lite
                class="table-lite"
                :is-slot-mode="true"
                :is-re-search="magang.research"
                :is-loading="magang.isLoading"
                :columns="magang.columns"
                :rows="magang.rows"
                :total="magang.totalRecordCount"
                :sortable="magang.sortable"
                :messages="magang.messages"
                @do-search="magangSearch"
            >
              <template v-slot:id_magang="data">
                {{ data.value.index }}
              </template>
              <template v-slot:nama="data">
                {{ data.value.mahasiswa?.nama ?? '-' }}
              </template>
              <template v-slot:instansi="data">
                {{ data.value.instansi?.nama ?? '-' }}
              </template>
              <template v-slot:progres="data">
                {{ data.value.progres?.progres ?? '-' }}
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
      <CModalTitle>Detail {{ activeMagang.mahasiswa?.nim ?? '-' }}</CModalTitle>
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
      <CButton color="primary" @click="getBimbingan(activeMagang)">
        Bimbingan
      </CButton>
      <CButton v-if="activeMagang.status_dosen != 1" color="primary" @click="approve(activeMagang)">
        Approve
      </CButton>
      <CButton v-if="activeMagang.status_dosen != -1" color="danger" @click="reject(activeMagang)">
        Reject
      </CButton>
      <CButton color="secondary" @click="closeModal">
        Close
      </CButton>
    </CModalFooter>
  </CModal>
  <CModal size="lg" backdrop="static" :visible="showBimbinganModal" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Detail {{ activeMagang.mahasiswa?.nim }} - {{ activeMagang.mahasiswa?.nama }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CCard class="mb-4">
        <CCardHeader class="row">
            <p class="col-6">Daftar Bimbingan dosen</p>
            <!--search bar-->
            <div class="col-6">
                <CInputGroup>
                    <CFormInput type="text" placeholder="Search" id="searchBimbinganDosen" :value="searchBimbinganDosen" @keyup.enter="getBimbinganDosen"/>
                    <CInputGroupText @click="getBimbinganDosen" class="cursor-pointer">
                    <font-awesome-icon :icon="['fas', 'search']" />
                    </CInputGroupText>
                </CInputGroup>
            </div>
        </CCardHeader>
        <CCardBody>
            <table-lite
                class="table-lite"
                :is-slot-mode="true"
                :is-re-search="bimbinganDosen.research"
                :is-loading="bimbinganDosen.isLoading"
                :columns="bimbinganDosen.columns"
                :rows="bimbinganDosen.rows"
                :total="bimbinganDosen.totalRecordCount"
                :sortable="bimbinganDosen.sortable"
                :messages="bimbinganDosen.messages"
                @do-search="bimbinganDosenSearch"
            >
                <template v-slot:id_bimbingan_dosen="data">
                    {{ data.value.index }}
                </template>
                <template v-slot:status="data">
                    <CBadge v-if="data.value.status == 1" color="success">disetujui</CBadge>
                    <CBadge v-if="data.value.status == 0" color="warning">menunggu</CBadge>
                    <CBadge v-if="data.value.status == -1" color="danger">ditolak</CBadge>
                </template>
                <template v-slot:none="data">
                    <CButton color="success" v-if="data.value.status != 1" @click="approveBimbinganDosen(data.value)">Terima</CButton>
                    <CButton color="danger" v-if="data.value.status != -1" @click="rejectBimbinganDosen(data.value)">Tolak</CButton>
                </template>
            </table-lite>   
        </CCardBody>
      </CCard>
      <CCard class="mb-4">
        <CCardHeader class="row">
            <p class="col-6">Daftar Bimbingan instansi</p>
            <div class="col-6">
                <CInputGroup>
                    <CFormInput type="text" placeholder="Search" id="searchBimbinganInstansi" :value="searchBimbinganInstansi" @keyup.enter="getBimbinganInstansi"/>
                    <CInputGroupText @click="getBimbinganInstansi" class="cursor-pointer">
                    <font-awesome-icon :icon="['fas', 'search']" />
                    </CInputGroupText>
                </CInputGroup>
            </div>
        </CCardHeader>
        <CCardBody>
            <table-lite
                class="table-lite"
                :is-slot-mode="true"
                :is-re-search="bimbinganInstansi.research"
                :is-loading="bimbinganInstansi.isLoading"
                :columns="bimbinganInstansi.columns"
                :rows="bimbinganInstansi.rows"
                :total="bimbinganInstansi.totalRecordCount"
                :sortable="bimbinganInstansi.sortable"
                :messages="bimbinganInstansi.messages"
                @do-search="bimbinganInstansiSearch"
            >
                <template v-slot:id_bimbingan_instansi="data">
                    {{ data.value.index }}
                </template>
                <template v-slot:status="data">
                    <CBadge v-if="data.value.status == 1" color="success">disetujui</CBadge>
                    <CBadge v-if="data.value.status == 0" color="warning">menunggu</CBadge>
                    <CBadge v-if="data.value.status == -1" color="danger">ditolak</CBadge>
                </template>
                <template v-slot:none="data">
                    <CButton color="success" v-if="data.value.status != 1" @click="approveBimbinganInstansi(data.value)">Terima</CButton>
                    <CButton color="danger" v-if="data.value.status != -1" @click="rejectBimbinganInstansi(data.value)">Tolak</CButton>
                </template>
            </table-lite>   
        </CCardBody>
      </CCard>
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
  name: 'Bimbingan',
  data() {
    return {
      magang: {
        isLoading: false,
        columns: [
          {
            label: "#",
            field: "id_magang",
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
            label: "NIM",
            field: "nim",
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
            label: "Nama",
            field: "nama",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "20%",
            sortable: true,
          },
          {
            label: "Instansi",
            field: "instansi",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "25%",
            sortable: true,
          },
          {
            label: "Progres",
            field: "progres",
            headerClasses: ["table-header", "text-center"],
            columnClasses: ["text-center"],
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
          order: "id_magang",
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
      showBimbinganModal: false,
      bimbinganDosen: {
        isLoading: false,
        columns: [
            {
                label: "#",
                field: "id_bimbingan_dosen",
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
                width: "20%",
                sortable: true
            },
            {
                label: "Bimbingan",
                field: "data_bimbingan",
                headerClasses: ["table-header"],
                headerStyles: 
                {
                    background: "#f0f2f4",
                    color: "rgba(44, 56, 74, 0.95)",
                    border: "1px solid rgba(200, 204, 209, 0.99)",
                },
                width: "40%",
                sortable: true
            },
            {
                label: "Status",
                field: "status",
                headerClasses: ["table-header"],
                headerStyles: 
                {
                    background: "#f0f2f4",
                    color: "rgba(44, 56, 74, 0.95)",
                    border: "1px solid rgba(200, 204, 209, 0.99)",
                },
                width: "15%",
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
            order: "id_bimbingan_dosen",
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
      searchBimbinganDosen: '',
      bimbinganInstansi: {
        isLoading: false,
        columns: [
            {
                label: "#",
                field: "id_bimbingan_instansi",
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
                width: "20%",
                sortable: true
            },
            {
                label: "Bimbingan",
                field: "data_bimbingan",
                headerClasses: ["table-header"],
                headerStyles: 
                {
                    background: "#f0f2f4",
                    color: "rgba(44, 56, 74, 0.95)",
                    border: "1px solid rgba(200, 204, 209, 0.99)",
                },
                width: "40%",
                sortable: true
            },
            {
                label: "Status",
                field: "status",
                headerClasses: ["table-header"],
                headerStyles: 
                {
                    background: "#f0f2f4",
                    color: "rgba(44, 56, 74, 0.95)",
                    border: "1px solid rgba(200, 204, 209, 0.99)",
                },
                width: "15%",
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
            order: "id_bimbingan_instansi",
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
      searchBimbinganInstansi: '',
      search: '',
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
    magangSearch(offset, limit, order, sort) {
      this.magang.isLoading = true;
      //calculate page based on offset and limit
      let page = offset / limit + 1;
      let url = `${window.location.origin}/api/kmm/magang?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
      axios.get(url)
      .then((response) => {
        this.magang.research = false;
        console.log(this.search == '' ? '[kosong]' : this.search);
        this.magang.rows = response.data.data.data;
        //add iteration index and push to rows as 'index'
        let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
        this.magang.rows.forEach((item, index) => {
          //calculate index based on current page
          item.index = index + 1 + pagination;
        });
        this.magang.totalRecordCount = response.data.data.total;
        this.magang.page = {
          limit: limit, 
          offset: offset,
        };
        this.magang.sortable = {
          order: order,
          sort: sort
        };
        this.magang.isLoading = false;
      });
    },
    getMagangs() {
      this.search = document?.getElementById('search')?.value ?? this.search;
      this.magang.totalRecordCount = 0;
      this.magang.rows = [];
      this.magang.page = {
        limit: 10,
        offset: 0
      };
      this.magang.research = true;
      this.magangSearch(this.magang.page.offset, this.magang.page.limit, this.magang.sortable.order, this.magang.sortable.sort);
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
    bimbinganDosenSearch(offset, limit, order, sort) {
        this.bimbinganDosen.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/magang/${this.activeMagang.id_magang}/bimbingan/dosen?kueri=${this.searchBimbinganDosen}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
            this.bimbinganDosen.research = false;
            this.bimbinganDosen.rows = response.data.data.data;
            //add iteration index and push to rows as 'index'
            let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
            this.bimbinganDosen.rows.forEach((item, index) => {
                //calculate index based on current page
                item.index = index + 1 + pagination;
            });
            this.bimbinganDosen.totalRecordCount = response.data.data.total;
            this.bimbinganDosen.page = {
                limit: limit, 
                offset: offset,
            };
            this.bimbinganDosen.sortable = {
                order: order,
                sort: sort
            };
            this.bimbinganDosen.isLoading = false;
        });
    },
    bimbinganInstansiSearch(offset, limit, order, sort) {
        this.bimbinganInstansi.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/magang/${this.activeMagang.id_magang}/bimbingan/instansi?kueri=${this.searchBimbinganInstansi}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
            this.bimbinganInstansi.research = false;
            this.bimbinganInstansi.rows = response.data.data.data;
            //add iteration index and push to rows as 'index'
            let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
            this.bimbinganInstansi.rows.forEach((item, index) => {
                //calculate index based on current page
                item.index = index + 1 + pagination;
            });
            this.bimbinganInstansi.totalRecordCount = response.data.data.total;
            this.bimbinganInstansi.page = {
                limit: limit, 
                offset: offset,
            };
            this.bimbinganInstansi.sortable = {
                order: order,
                sort: sort
            };
            this.bimbinganInstansi.isLoading = false;
        });
    },
    getBimbinganInstansi() {
       this.searchBimbinganInstansi = document?.getElementById('searchBimbinganInstansi')?.value ?? this.searchBimbinganInstansi;
       this.bimbinganInstansi.totalRecordCount = 0;
        this.bimbinganInstansi.rows = [];
        this.bimbinganInstansi.page = {
            limit: 10,
            offset: 0
        };
        this.bimbinganInstansi.research = true;
        this.bimbinganInstansiSearch(this.bimbinganInstansi.page.offset, this.bimbinganInstansi.page.limit, this.bimbinganInstansi.sortable.order, this.bimbinganInstansi.sortable.sort);
    },
    getBimbinganDosen() {
        this.searchBimbinganDosen = document?.getElementById('searchBimbinganDosen')?.value ?? this.searchBimbinganDosen;
        this.bimbinganDosen.totalRecordCount = 0;
        this.bimbinganDosen.rows = [];
        this.bimbinganDosen.page = {
            limit: 10,
            offset: 0
        };
        this.bimbinganDosen.research = true;
        this.bimbinganDosenSearch(this.bimbinganDosen.page.offset, this.bimbinganDosen.page.limit, this.bimbinganDosen.sortable.order, this.bimbinganDosen.sortable.sort);
    },
    getBimbingan(item) {
      this.closeModal();
      this.activeMagang = item;
      this.getBimbinganDosen();
      this.getBimbinganInstansi();
      this.showBimbinganModal = true;
    },
    approveBimbinganDosen(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.put(`${window.location.origin}/api/kmm/magang/${this.activeMagang.id_magang}/bimbingan/dosen/${item.id_bimbingan_dosen}/approve`)
        .then(response => {
            this.$store.dispatch('showSuccessAlert', 'Berhasil menyetujui bimbingan');
            this.getBimbinganDosen();
        })
        .catch(e => {
            this.$store.dispatch('showErrorAlert', {
                title: `Error ${e.response.status}`,
                message: e.response.data.message ?? e.response.data
            });
            console.log(e);
        });
    },
    rejectBimbinganDosen(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.put(`${window.location.origin}/api/kmm/magang/${this.activeMagang.id_magang}/bimbingan/dosen/${item.id_bimbingan_dosen}/reject`)
        .then(response => {
            this.$store.dispatch('showSuccessAlert', 'Berhasil menolak bimbingan');
            this.getBimbinganDosen();
        })
        .catch(e => {
            this.$store.dispatch('showErrorAlert', {
                title: `Error ${e.response.status}`,
                message: e.response.data.message ?? e.response.data
            });
            console.log(e);
        });
    },
    approveBimbinganInstansi(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.put(`${window.location.origin}/api/kmm/magang/${this.activeMagang.id_magang}/bimbingan/instansi/${item.id_bimbingan_instansi}/approve`)
        .then(response => {
            this.$store.dispatch('showSuccessAlert', 'Berhasil menyetujui bimbingan');
            this.getBimbinganInstansi();
        })
        .catch(e => {
            this.$store.dispatch('showErrorAlert', {
                title: `Error ${e.response.status}`,
                message: e.response.data.message ?? e.response.data
            });
            console.log(e);
        });
    },
    rejectBimbinganInstansi(item) {
        this.$store.dispatch('showLoadingAlert');
        console.log(item.id_bimbingan_instansi);
        axios.put(`${window.location.origin}/api/kmm/magang/${this.activeMagang.id_magang}/bimbingan/instansi/${item.id_bimbingan_instansi}/reject`)
        .then(response => {
          console.log(response.data.data.id_bimbingan_instansi);
            this.$store.dispatch('showSuccessAlert', 'Berhasil menolak bimbingan');
            this.getBimbinganInstansi();
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
      this.showDetailModal = true;
    },
    closeModal() {
      this.showBimbinganModal = false;
      this.bimbinganDosen.rows = [];
      this.bimbinganDosen.totalRecordCount = 0;
      this.bimbinganDosen.page = {
        limit: 10,
        offset: 0
      };
      this.bimbinganDosen.sortable = {
        order: "id_bimbingan_dosen",
        sort: "desc"
      };
      this.searchBimbinganDosen = '';
      this.bimbinganInstansi.rows = [];
      this.bimbinganInstansi.totalRecordCount = 0;
      this.bimbinganInstansi.page = {
        limit: 10,
        offset: 0
      };
      this.bimbinganInstansi.sortable = {
        order: "id_bimbingan_instansi",
        sort: "desc"
      };
      this.searchBimbinganInstansi = '';
      this.showDetailModal = false;
      this.activeMagang = null;
    },
  }
}
</script>
