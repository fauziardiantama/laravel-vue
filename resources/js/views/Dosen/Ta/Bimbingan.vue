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
                  <template v-slot:judul_proposal="data">
                    {{ data.value.proposal_ta?.judul_proposal ?? '-' }}
                  </template>
                  <template v-slot:none="data">
                    <CButton color="primary" @click="getBimbingan(data.value)">Detail</CButton>
                  </template>
                </table-lite>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
    </div>
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
      </CModalBody>
      <CModalFooter>
        <CButton color="secondary" @click="closeModal">
          Close
        </CButton>
      </CModalFooter>
    </CModal>
  </template>
  
  <style scoped>
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
    name: 'Magang',
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
              label: "Judul TA",
              field: "judul_proposal",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "40%",
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
        search: '',
        activeMagang: null
      }
    },
    async created() {
      this.getMagangs();
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
      magangSearch(offset, limit, order, sort) {
        this.magang.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/ta/bimbingan/all?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
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
        this.showBimbinganModal = true;
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
        this.activeMagang = null;
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
    }
  }
  </script>
  