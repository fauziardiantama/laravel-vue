<template>
    <div>
      <CRow>
        <CCol :md="12">
          <CCard class="mb-4">
            <CCardHeader class="row">
              <p class="col-6">Daftar Seminar</p>
              <!--search bar-->
              <div class="col-6">
                <CInputGroup>
                  <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getSeminars"/>
                  <CInputGroupText @click="getSeminars" class="cursor-pointer">
                    <font-awesome-icon :icon="['fas', 'search']" />
                  </CInputGroupText>
                </CInputGroup>
              </div>
            </CCardHeader>
            <CCardBody>
                <table-lite
                    class="table-lite"
                    :pageOptions="[ { value: 10, text: 10 }, { value: 25, text: 25 }, { value: 50, text: 50 }, { value: 100, text: 100 } ]"
                    :is-slot-mode="true"
                    :is-re-search="seminar.research"
                    :is-loading="seminar.isLoading"
                    :columns="seminar.columns"
                    :rows="seminar.rows"
                    :total="seminar.totalRecordCount"
                    :sortable="seminar.sortable"
                    :messages="seminar.messages"
                    @do-search="seminarSearch"
                >
                  <template v-slot:id_seminar="data">
                    {{ data.value.index }}
                  </template>
                  <template v-slot:nim="data">
                    {{ data.value.magang?.mahasiswa?.nim ?? '-' }}
                  </template>
                  <template v-slot:nama="data">
                    {{ data.value.magang?.mahasiswa?.nama ?? '-' }}
                  </template>
                  <template v-slot:status="data">
                    {{ data.value.status == 1 ? 'Disetujui' : (data.value.status == -1 ? 'Ditolak' : 'Menunggu') }}
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
        <CModalTitle>Detail {{ activeSeminar.judul_kmm ?? '-' }}</CModalTitle>
      </CModalHeader>
      <CModalBody>
        <CRow>
          <CCol :md="4">
            <CLabel>NIM</CLabel>
          </CCol>
          <CCol :md="8">
            <p>{{ activeSeminar.magang?.mahasiswa?.nim ?? '-'}}</p>
          </CCol>
        </CRow>
        <CRow>
          <CCol :md="4">
            <CLabel>Nama</CLabel>
          </CCol>
          <CCol :md="8">
            <p>{{ activeSeminar.magang?.mahasiswa?.nama ?? '-'}}</p>
          </CCol>
        </CRow>
        <CRow>
          <CCol :md="4">
            <CLabel>Judul</CLabel>
          </CCol>
          <CCol :md="8">
            <p>{{ activeSeminar.judul_kmm ?? '-' }}</p>
          </CCol>
        </CRow>
        <!-- Draft KMM, foto, krs, lembar_revisi, daftar_hadir, selesai_kmm -->
        <CRow class="mb-3">
            <CCol :md="4">
              <CLabel>Draft KMM</CLabel>
            </CCol>
            <CCol :md="8">
              <a :href="`${app}/mahasiswa/magang/seminar/draft-kmm/${activeSeminar?.draft_kmm}`" target="_blank" class="dokumen-link">{{ activeSeminar.draft_kmm ?? '-' }}</a>
            </CCol>
          </CRow>
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
              <p class="col-6">Nilai Seminar</p>
              <!--search bar-->
              <div class="col-6">
                  <CInputGroup>
                      <CFormInput type="text" placeholder="Search" id="searchNilai" :value="searchNilai" @keyup.enter="getSeminars"/>
                      <CInputGroupText @click="getSeminars" class="cursor-pointer">
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
                  <template v-slot:id_nilai_seminar="data">
                      {{ data.value.index }}
                  </template>
                  <template v-slot:id_parameter="data">
                      {{ data.value.parameter_nilai_seminar?.parameter }}
                  </template>
                  <template v-slot:id_dosen="data">
                      {{ data.value.dosen?.nama ?? '-' }}
                  </template>
                  <template v-slot:none="data">
                      <CButton color="danger" @click="deleteNilai(data.value.id_nilai_seminar)">Hapus</CButton>
                  </template>
              </table-lite> 
          </CCardBody>
        </CCard>
        </CModalBody>
      <CModalFooter>
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
    name: 'NilaiSeminar',
    data() {
      return {
        seminar: {
          isLoading: false,
          columns: [
            {
              label: "#",
              field: "id_seminar",
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
              label: "Judul KMM",
              field: "judul_kmm",
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
              label: "Status",
              field: "status",
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
            order: "id_seminar",
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
        nilai: {
          isLoading: false,
          columns: [
              {
                  label: "#",
                  field: "id_nilai_seminar",
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
                  width: "20%",
                  sortable: true
              },
              {
                label: "Dosen",
                  field: "id_dosen",
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
              order: "id_nilai_seminar",
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
        search: '',
        showDetailModal: false,
        activeSeminar: null,
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
        app: window.location.origin
      }
    },
    async created() {
      this.getSeminars();
      this.getParameters();
    },
    mounted() {
      console.log('Dashboard component mounted.');
      Echo.private('Admin')
      .listen("Mgng", (e) => {
        console.log(e);
        this.getSeminars();
        if (e.type == "update" && e.item.id_magang == this.activeMagang?.id_magang) {
            this.openDetailModal(e.item);
          } else if (e.type == "destroy" && e.item.id_magang == this.activeMagang?.id_magang) {
            this.closeModal();
          }
      });
    },
    methods: {
      getParameters() {
        axios.get(`${window.location.origin}/api/kmm/nilai/parameter/seminar/year/2023`)
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
      seminarSearch(offset, limit, order, sort) {
        this.seminar.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/seminar/penguji?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
          this.seminar.research = false;
          console.log(this.search == '' ? '[kosong]' : this.search);
          this.seminar.rows = response.data.data.data;
          //add iteration index and push to rows as 'index'
          let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
          this.seminar.rows.forEach((item, index) => {
            //calculate index based on current page
            item.index = index + 1 + pagination;
          });
          this.seminar.totalRecordCount = response.data.data.total;
          this.seminar.page = {
            limit: limit, 
            offset: offset,
          };
          this.seminar.sortable = {
            order: order,
            sort: sort
          };
          this.seminar.isLoading = false;
        });
      },
      getSeminars() {
        this.search = document?.getElementById('search')?.value ?? this.search;
        this.seminar.totalRecordCount = 0;
        this.seminar.rows = [];
        this.seminar.page = {
          limit: 10,
          offset: 0
        };
        this.seminar.research = true;
        this.seminarSearch(this.seminar.page.offset, this.seminar.page.limit, this.seminar.sortable.order, this.seminar.sortable.sort);
      },
      nilaiSearch(offset, limit, order, sort) {
        this.nilai.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/nilai/seminar/${this.activeSeminar.id_magang}?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
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
      openDetailModal(item) {
        this.activeSeminar = item;
        this.showDetailModal = true;
        this.getNilais();
      },
      closeModal() {
        this.showDetailModal = false;
        this.activeSeminar = null;
      },
      addNilai() {
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${window.location.origin}/api/kmm/nilai/seminar`, {
          id_magang: this.activeSeminar.id_magang,
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
          this.getNilais();
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
      },
      deleteNilai(id_nilai_seminar) {
        this.$store.dispatch('showLoadingAlert');
        axios.delete(`${window.location.origin}/api/kmm/nilai/seminar/change/${id_nilai_seminar}`)
        .then(response => {
          console.log(response);
          this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
          this.getNilais();
        })
        .catch(error => {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menghapus informasi!',
            message: error.response.status
          });
        });
      }
    }
  }
  </script>
  