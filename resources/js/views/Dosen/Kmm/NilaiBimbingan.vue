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
              </table-lite>   
          </CCardBody>
        </CCard>
        <CCard class="mb-4">
            <CCardHeader class="row">
                <p class="col-6">Input Nilai Bimbingan</p>
            </CCardHeader>
            <CCardBody>
              <CForm>
                <div class="mb-3">
                  <CFormLabel for="judul">Parameter</CFormLabel>
                  <CFormSelect
                        aria-label="Default select example"
                        :options="parameter_options"
                        v-model="form.parameter"
                        :feedback="form_validation.parameter.feedback"
                        :invalid="form_validation.parameter.invalid"
                        required>
                    </CFormSelect>
                </div>
                <div class="mb-3">
                  <CFormLabel for="judul">Nilai</CFormLabel>
                  <CFormInput type="text" id="nilai" placeholder="Nilai"
                    v-model="form.nilai"
                    :feedback="form.nilai.feedback"
                    :invalid="form.nilai.invalid" />
                </div>
                <div class="mb-3 text-center">
                  <CRow>
                    <CCol :md="6">
                      <CButton color="primary" class="w-100" @click="addNilai()">Tambah</CButton>
                    </CCol>
                  </CRow>
                </div>
              </CForm>
            </CCardBody>
        </CCard>
        <CCard class="mb-4">
          <CCardHeader class="row">
              <p class="col-6">Nilai Bimbingan</p>
              <!--search bar-->
              <div class="col-6">
                  <CInputGroup>
                      <CFormInput type="text" placeholder="Search" id="searchNilai" :value="searchNilai" @keyup.enter="getBimbinganDosen"/>
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
                  :is-re-search="nilai.research"
                  :is-loading="nilai.isLoading"
                  :columns="nilai.columns"
                  :rows="nilai.rows"
                  :total="nilai.totalRecordCount"
                  :sortable="nilai.sortable"
                  :messages="nilai.messages"
                  @do-search="nilaiSearch"
              >
                  <template v-slot:id_nilai_bimbingan="data">
                      {{ data.value.index }}
                  </template>
                  <template v-slot:id_parameter="data">
                      {{ data.value.parameter_nilai_bimbingan.parameter }}
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
    name: 'NilaiBimbingan',
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
              }
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
        nilai: {
          isLoading: false,
          columns: [
              {
                  label: "#",
                  field: "id_nilai_bimbingan",
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
                  label: "Parameter",
                  field: "id_parameter",
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
                  label: "Nilai",
                  field: "nilai",
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
          ],
          rows: [],
          totalRecordCount: 0,
          sortable: {
              order: "id_nilai_bimbingan",
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
        searchNilai: '',
        form: {
          parameter: '',
          nilai: '',
        },
        form_validation: {
          parameter: {
            feedback: '',
            invalid: false
          },
          nilai: {
            feedback: '',
            invalid: false
          }
        },
        parameter_options: [
            { label: 'Pilih parameter', value: '' }
        ],
        search: '',
        showDetailModal: false,
        activeMagang: null
      }
    },
    async created() {
      this.getMagangs();
      this.getParameters();
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
      getParameters() {

          axios.get(`${window.location.origin}/api/kmm/nilai/parameter/bimbingan/year/2023`)
          .then((response) => {
              this.tahun_options = [
                  { label: 'Pilih parameter', value: ''}
              ];
              //map and push to options
              response.data.data.map((item) => {
                  this.parameter_options.push({
                      label: item.parameter,
                      value: item.id_parameter
                  });
              });
          })
          .catch((error) => {
              console.log(error);
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
      nilaiSearch(offset, limit, order, sort) {
        this.nilai.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/nilai/bimbingan/${this.activeMagang.id_magang}?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
          this.nilai.research = false;
          console.log(this.search == '' ? '[kosong]' : this.search);
          this.nilai.rows = response.data.data.data;
          //add iteration index and push to rows as 'index'
          let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
          this.nilai.rows.forEach((item, index) => {
            //calculate index based on current page
            item.index = index + 1 + pagination;
          });
          this.nilai.totalRecordCount = response.data.data.total;
          this.nilai.page = {
            limit: limit, 
            offset: offset,
          };
          this.nilai.sortable = {
            order: order,
            sort: sort
          };
          this.nilai.isLoading = false;
        });
      },
      getNilais() {
        this.search = document?.getElementById('searchNilai')?.value ?? this.search;
        this.nilai.totalRecordCount = 0;
        this.nilai.rows = [];
        this.nilai.page = {
          limit: 10,
          offset: 0
        };
        this.nilai.research = true;
        this.nilaiSearch(this.nilai.page.offset, this.nilai.page.limit, this.nilai.sortable.order, this.nilai.sortable.sort);
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
        this.getNilais();
        this.showBimbinganModal = true;
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
        this.searchBimbinganInstansi = '';
        this.showDetailModal = false;
        this.activeMagang = null;
      },
      addNilai() {
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${window.location.origin}/api/kmm/nilai/bimbingan`, {
          id_magang: this.activeMagang.id_magang,
          id_parameter: this.form.parameter,
          nilai: this.form.nilai
        })
        .then(response => {
          console.log(response);
          this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
          this.form = {
            parameter: '',
            nilai: ''
          };
          //remove file from input
          this.getBimbinganDosen();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.form_validation = {
              parameter: {
                invalid: !!error.response.data.errors.id_parameter,
                feedback: error.response.data.errors.id_parameter.join(' & ')
              },
              nilai: {
                invalid: !!error.response.data.errors.nilai,
                feedback: error.response.data.errors.nilai.join(' & ')
              }
            }
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menambah informasi!',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menambah informasi!',
              message: error.response.status
            });
          }
        });
      }
    }
  }
  </script>
  