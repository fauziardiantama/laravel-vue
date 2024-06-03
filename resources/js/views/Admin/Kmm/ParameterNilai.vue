<template>
    <div>
      <CRow>
        <CCol :md="5">
          <CCard class="mb-4">
            <CCardHeader>Tambah Parameter Bimbingan</CCardHeader>
            <CCardBody>
              <CForm>
                <div class="mb-3">
                  <CFormLabel for="judul">Tahun</CFormLabel>
                  <CFormSelect
                        aria-label="Default select example"
                        :options="tahun_options"
                        v-model="form_bimbingan.tahun"
                        :feedback="form_bimbingan_validation.tahun.feedback"
                        :invalid="form_bimbingan_validation.tahun.invalid"
                        required>
                    </CFormSelect>
                </div>
                <div class="mb-3">
                  <CFormLabel for="judul">Parameter</CFormLabel>
                  <CFormInput type="text" id="parameter" placeholder="Parameter"
                    v-model="form_bimbingan.parameter"
                    :feedback="form_bimbingan_validation.parameter.feedback"
                    :invalid="form_bimbingan_validation.parameter.invalid" />
                </div>
                <div class="mb-3 text-center">
                  <CRow>
                    <CCol :md="6">
                      <CButton color="primary" class="w-100" @click="addBimbingan()">Tambah</CButton>
                    </CCol>
                  </CRow>
                </div>
              </CForm>
            </CCardBody>
          </CCard>
        </CCol>
        <CCol :md="7">
          <CCard class="mb-4">
            <CCardHeader class="row">
              <p class="col-6">Daftar Parameter Bimbingan</p>
              <!--search bar-->
              <div class="col-6">
                <CInputGroup>
                  <CFormInput type="text" placeholder="Search" id="searchBimbingan" :value="searchBimbingan" @keyup.enter="getBimbingans"/>
                  <CInputGroupText @click="getBimbingans" class="cursor-pointer">
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
                  :is-re-search="bimbingan.research"
                  :is-loading="bimbingan.isLoading"
                  :columns="bimbingan.columns"
                  :rows="bimbingan.rows"
                  :total="bimbingan.totalRecordCount"
                  :sortable="bimbingan.sortable"
                  :messages="bimbingan.messages"
                  @do-search="BimbinganSearch"
              >
                <template v-slot:id_parameter="data">
                  {{ data.value.index }}
                </template>
                <template v-slot:none="data">
                  <CButton color="primary" @click="openBimbingan(data.value)">Detail</CButton>
                </template>
              </table-lite>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
    </div>
    <div>
      <CRow>
        <CCol :md="5">
          <CCard class="mb-4">
            <CCardHeader>Tambah Parameter Seminar</CCardHeader>
            <CCardBody>
              <CForm>
                <div class="mb-3">
                  <CFormLabel for="judul">Tahun</CFormLabel>
                  <CFormSelect
                        aria-label="Default select example"
                        :options="tahun_options"
                        v-model="form_seminar.tahun"
                        :feedback="form_seminar_validation.tahun.feedback"
                        :invalid="form_seminar_validation.tahun.invalid"
                        required>
                    </CFormSelect>
                </div>
                <div class="mb-3">
                  <CFormLabel for="judul">Parameter</CFormLabel>
                  <CFormInput type="text" id="parameter" placeholder="Parameter"
                    v-model="form_seminar.parameter"
                    :feedback="form_seminar_validation.parameter.feedback"
                    :invalid="form_seminar_validation.parameter.invalid" />
                </div>
                <div class="mb-3 text-center">
                  <CRow>
                    <CCol :md="6">
                      <CButton color="primary" class="w-100" @click="addSeminar()">Tambah</CButton>
                    </CCol>
                  </CRow>
                </div>
              </CForm>
            </CCardBody>
          </CCard>
        </CCol>
        <CCol :md="7">
          <CCard class="mb-4">
            <CCardHeader class="row">
              <p class="col-6">Daftar Parameter Seminar</p>
              <!--search bar-->
              <div class="col-6">
                <CInputGroup>
                  <CFormInput type="text" placeholder="Search" id="searchSeminar" :value="searchSeminar" @keyup.enter="getSeminars"/>
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
                  @do-search="SeminarSearch"
              >
                <template v-slot:id_parameter="data">
                  {{ data.value.index }}
                </template>
                <template v-slot:none="data">
                  <CButton color="primary" @click="openSeminar(data.value)">Detail</CButton>
                </template>
              </table-lite>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
    </div>
    <div>
      <CRow>
        <CCol :md="5">
          <CCard class="mb-4">
            <CCardHeader>Tambah Parameter Instansi</CCardHeader>
            <CCardBody>
              <CForm>
                <div class="mb-3">
                  <CFormLabel for="judul">Tahun</CFormLabel>
                  <CFormSelect
                        aria-label="Default select example"
                        :options="tahun_options"
                        v-model="form_instansi.tahun"
                        :feedback="form_instansi_validation.tahun.feedback"
                        :invalid="form_instansi_validation.tahun.invalid"
                        required>
                    </CFormSelect>
                </div>
                <div class="mb-3">
                  <CFormLabel for="judul">Parameter</CFormLabel>
                  <CFormInput type="text" id="parameter" placeholder="Parameter"
                    v-model="form_instansi.parameter"
                    :feedback="form_instansi_validation.parameter.feedback"
                    :invalid="form_instansi_validation.parameter.invalid" />
                </div>
                <div class="mb-3 text-center">
                  <CRow>
                    <CCol :md="6">
                      <CButton color="primary" class="w-100" @click="addInstansi()">Tambah</CButton>
                    </CCol>
                  </CRow>
                </div>
              </CForm>
            </CCardBody>
          </CCard>
        </CCol>
        <CCol :md="7">
          <CCard class="mb-4">
            <CCardHeader class="row">
              <p class="col-6">Daftar Parameter Instansi</p>
              <!--search bar-->
              <div class="col-6">
                <CInputGroup>
                  <CFormInput type="text" placeholder="Search" id="searchInstansi" :value="searchInstansi" @keyup.enter="getInstansis"/>
                  <CInputGroupText @click="getInstansis" class="cursor-pointer">
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
                  :is-re-search="instansi.research"
                  :is-loading="instansi.isLoading"
                  :columns="instansi.columns"
                  :rows="instansi.rows"
                  :total="instansi.totalRecordCount"
                  :sortable="instansi.sortable"
                  :messages="instansi.messages"
                  @do-search="InstansiSearch"
              >
                <template v-slot:id_parameter="data">
                  {{ data.value.index }}
                </template>
                <template v-slot:none="data">
                  <CButton color="primary" @click="openInstansi(data.value)">Detail</CButton>
                </template>
              </table-lite>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
    </div>
    <CModal size="lg" backdrop="static" :visible="showBimbingan" @close="closeModal">
          <CModalHeader>
          <CModalTitle>Detail {{ activeBimbingan?.tahun ?? '-' }}</CModalTitle>
          </CModalHeader>
          <CModalBody>
          <CRow>
              <CCol :md="4">
              <CLabel><b>Tahun</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeBimbingan?.tahun }}</p>
                <CFormSelect v-else
                  :options="tahun_options"
                  v-model="activeBimbingan.tahun"
                  :feedback="edit_bimbingan_validation.tahun.feedback"
                  :invalid="edit_bimbingan_validation.tahun.invalid"
                  required>
                </CFormSelect>
              </CCol>
          </CRow>
          <CRow>
              <CCol :md="4">
                <CLabel><b>Parameter</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeBimbingan?.parameter }}</p>
                <CFormInput v-else
                type="text"
                id="parameter"
                placeholder="Parameter"
                v-model="activeBimbingan.parameter"
                :feedback="edit_bimbingan_validation.parameter.feedback"
                :invalid="edit_bimbingan_validation.parameter.invalid"
                required />
              </CCol>
          </CRow>
          <div class="mb-3"></div>
          </CModalBody>
          <CModalFooter>
            <CButton color="primary" v-if="!edit" @click="edit = !edit">
                Edit
            </CButton>
            <CButton color="success" v-else @click="updateBimbingan(activeBimbingan)">
                Save
            </CButton>
            <CButton color="danger" @click="deleteBimbingan(activeBimbingan)">
                Delete
            </CButton>
            <CButton color="secondary" @click="closeModal">
                Close
            </CButton>
          </CModalFooter>
    </CModal>
    <CModal size="lg" backdrop="static" :visible="showSeminar" @close="closeModal">
          <CModalHeader>
          <CModalTitle>Detail {{ activeSeminar?.tahun ?? '-' }}</CModalTitle>
          </CModalHeader>
          <CModalBody>
          <CRow>
              <CCol :md="4">
              <CLabel><b>Tahun</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeSeminar?.tahun }}</p>
                <CFormSelect v-else
                  :options="tahun_options"
                  v-model="activeSeminar.tahun"
                  :feedback="edit_seminar_validation.tahun.feedback"
                  :invalid="edit_seminar_validation.tahun.invalid"
                  required>
                </CFormSelect>
              </CCol>
          </CRow>
          <CRow>
              <CCol :md="4">
                <CLabel><b>Parameter</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeSeminar?.parameter }}</p>
                <CFormInput v-else
                type="text"
                id="parameter"
                placeholder="Parameter"
                v-model="activeSeminar.parameter"
                :feedback="edit_seminar_validation.parameter.feedback"
                :invalid="edit_seminar_validation.parameter.invalid"
                required />
              </CCol>
          </CRow>
          <div class="mb-3"></div>
          </CModalBody>
          <CModalFooter>
            <CButton color="primary" v-if="!edit" @click="edit = !edit">
                Edit
            </CButton>
            <CButton color="success" v-else @click="updateSeminar(activeSeminar)">
                Save
            </CButton>
            <CButton color="danger" @click="deleteSeminar(activeSeminar)">
                Delete
            </CButton>
            <CButton color="secondary" @click="closeModal">
                Close
            </CButton>
          </CModalFooter>
    </CModal>
    <CModal size="lg" backdrop="static" :visible="showInstansi" @close="closeModal">
          <CModalHeader>
          <CModalTitle>Detail {{ activeInstansi?.tahun ?? '-' }}</CModalTitle>
          </CModalHeader>
          <CModalBody>
          <CRow>
              <CCol :md="4">
              <CLabel><b>Tahun</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeInstansi?.tahun }}</p>
                <CFormSelect v-else
                  :options="tahun_options"
                  v-model="activeInstansi.tahun"
                  :feedback="edit_instansi_validation.tahun.feedback"
                  :invalid="edit_instansi_validation.tahun.invalid"
                  required>
                </CFormSelect>
              </CCol>
          </CRow>
          <CRow>
              <CCol :md="4">
                <CLabel><b>Parameter</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeInstansi?.parameter }}</p>
                <CFormInput v-else
                type="text"
                id="parameter"
                placeholder="Parameter"
                v-model="activeInstansi.parameter"
                :feedback="edit_instansi_validation.parameter.feedback"
                :invalid="edit_instansi_validation.parameter.invalid"
                required />
              </CCol>
          </CRow>
          <div class="mb-3"></div>
          </CModalBody>
          <CModalFooter>
            <CButton color="primary" v-if="!edit" @click="edit = !edit">
                Edit
            </CButton>
            <CButton color="success" v-else @click="updateInstansi(activeInstansi)">
                Save
            </CButton>
            <CButton color="danger" @click="deleteInstansi(activeInstansi)">
                Delete
            </CButton>
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
    name: 'Parameter',
    data() {
      return {
        bimbingan: {
          isLoading: false,
          columns : [
            {
              label: "#",
              field: "id_parameter",
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
              label: "Tahun",
              field: "tahun",
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
              label: "Parameter",
              field: "parameter",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "35%",
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
            order: "id_parameter",
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
        seminar: {
          isLoading: false,
          columns : [
            {
              label: "#",
              field: "id_parameter",
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
              label: "Tahun",
              field: "tahun",
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
              label: "Parameter",
              field: "parameter",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "35%",
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
            order: "id_parameter",
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
        instansi: {
          isLoading: false,
          columns : [
            {
              label: "#",
              field: "id_parameter",
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
              label: "Tahun",
              field: "tahun",
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
              label: "Parameter",
              field: "parameter",
              headerClasses: ["table-header"],
              headerStyles: 
              {
                background: "#f0f2f4",
                color: "rgba(44, 56, 74, 0.95)",
                border: "1px solid rgba(200, 204, 209, 0.99)",
              },
              width: "35%",
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
            order: "id_parameter",
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
        form_bimbingan: {
          tahun: '',
          parameter: ''
        },
        form_seminar: {
          tahun: '',
          parameter: ''
        },
        form_instansi: {
          tahun: '',
          parameter: ''
        },
        form_bimbingan_validation: {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        },
        form_seminar_validation: {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        },
        form_instansi_validation: {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        },
        edit_bimbingan_validation: {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        },
        edit_seminar_validation: {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        },
        edit_instansi_validation: {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        },
        searchBimbingan: '',
        searchSeminar: '',
        searchInstansi: '',
        showBimbingan: false,
        showSeminar: false,
        showInstansi: false,
        activeBimbingan: null,
        activeSeminar: null,
        activeInstansi: null,
        edit: false,
        tahun_options: [
            { label: 'Pilih Tahun', value: ''},
            { label: '2018', value: 2018 },
            { label: '2019', value: 2019 },
            { label: '2020', value: 2020 },
            { label: '2021', value: 2021 },
            { label: '2022', value: 2022 },
            { label: '2023', value: 2023 },
            { label: '2024', value: 2024 }
        ],
        app: window.location.origin
      }
    },
    created() {
      this.getBimbingans();
        this.getSeminars(); 
        this.getInstansis();
      this.getTahuns();
    },
    mounted() {
      console.log('Dashboard component mounted.');
      Echo.private('Admin')
      .listen('Tpk', (e) => {
        this.getTopiks();
        if (e.item.id_topik === this.activeTopik.id) {
          if (e.type === 'destroy') {
            this.closeModal();
          }
          if (e.type === 'update') {
            this.openDetailModal(e.item);
          }
        }
      });
    },
    methods: {
      getTahuns() {
          axios.get(`${window.location.origin}/api/kmm/tahun`)
          .then((response) => {
              this.tahun_options = [
                  { label: 'Pilih tahun', value: ''}
              ];
              //map and push to options
              response.data.data.map((item) => {
                  this.tahun_options.push({
                      label: item.tahun,
                      value: item.tahun
                  });
              });
          })
          .catch((error) => {
              console.log(error);
          });
      },
      BimbinganSearch(offset, limit, order, sort) {
        this.bimbingan.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/nilai/parameter/bimbingan?kueri=${this.searchBimbingan}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
          this.bimbingan.research = false;
          console.log(this.searchBimbingan == '' ? '[kosong]' : this.searchBimbingan);
          this.bimbingan.rows = response.data.data.data;
          //add iteration index and push to rows as 'index'
          let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
          this.bimbingan.rows.forEach((item, index) => {
            //calculate index based on current page
            item.index = index + 1 + pagination;
          });
          this.bimbingan.totalRecordCount = response.data.data.total;
          this.bimbingan.page = {
            limit: limit, 
            offset: offset,
          };
          this.bimbingan.sortable = {
            order: order,
            sort: sort
          };
          this.bimbingan.isLoading = false;
        });
      },
      SeminarSearch(offset, limit, order, sort) {
        this.seminar.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/nilai/parameter/seminar?kueri=${this.searchSeminar}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
          this.seminar.research = false;
          console.log(this.searchSeminar == '' ? '[kosong]' : this.searchSeminar);
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
      InstansiSearch(offset, limit, order, sort) {
        this.instansi.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/nilai/parameter/instansi?kueri=${this.searchInstansi}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
          this.instansi.research = false;
          console.log(this.searchInstansi == '' ? '[kosong]' : this.searchInstansi);
          this.instansi.rows = response.data.data.data;
          //add iteration index and push to rows as 'index'
          let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
          this.instansi.rows.forEach((item, index) => {
            //calculate index based on current page
            item.index = index + 1 + pagination;
          });
          this.instansi.totalRecordCount = response.data.data.total;
          this.instansi.page = {
            limit: limit, 
            offset: offset,
          };
          this.instansi.sortable = {
            order: order,
            sort: sort
          };
          this.instansi.isLoading = false;
        });
      },
      getBimbingans() {
        this.searchBimbingan = document?.getElementById('searchBimbingan')?.value ?? this.searchBimbingan;
        this.bimbingan.totalRecordCount = 0;
        this.bimbingan.rows = [];
        this.bimbingan.page = {
          limit: 10,
          offset: 0
        };
        this.bimbingan.research = true;
        this.BimbinganSearch(this.bimbingan.page.offset, this.bimbingan.page.limit, this.bimbingan.sortable.order, this.bimbingan.sortable.sort);
      },
      getSeminars() {
        this.searchSeminar = document?.getElementById('searchSeminar')?.value ?? this.searchSeminar;
        this.seminar.totalRecordCount = 0;
        this.seminar.rows = [];
        this.seminar.page = {
          limit: 10,
          offset: 0
        };
        this.seminar.research = true;
        this.SeminarSearch(this.seminar.page.offset, this.seminar.page.limit, this.seminar.sortable.order, this.seminar.sortable.sort);
      },
      getInstansis() {
        this.searchInstansi = document?.getElementById('searchInstansi')?.value ?? this.searchInstansi;
        this.instansi.totalRecordCount = 0;
        this.instansi.rows = [];
        this.instansi.page = {
          limit: 10,
          offset: 0
        };
        this.instansi.research = true;
        this.InstansiSearch(this.instansi.page.offset, this.instansi.page.limit, this.instansi.sortable.order, this.instansi.sortable.sort);
      },
      addBimbingan() {        
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${this.app}/api/kmm/nilai/parameter/bimbingan`, this.form_bimbingan)
        .then(response => {
          console.log(response);
          this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
          this.form = {
            tahun: '',
            parameter: ''
          };
          //remove file from input
          this.getBimbingans();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.form_validation = {
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              parameter: {
                invalid: !!error.response.data.errors.parameter,
                feedback: error.response.data.errors.parameter.join(' & ')
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
      addSeminar() {        
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${this.app}/api/kmm/nilai/parameter/seminar`, this.form_seminar)
        .then(response => {
          console.log(response);
          this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
          this.form = {
            tahun: '',
            parameter: ''
          };
          //remove file from input
          this.getSeminars();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.form_validation = {
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              parameter: {
                invalid: !!error.response.data.errors.parameter,
                feedback: error.response.data.errors.parameter.join(' & ')
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
      addInstansi() {        
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${this.app}/api/kmm/nilai/parameter/instansi`, this.form_instansi)
        .then(response => {
          console.log(response);
          this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
          this.form = {
            tahun: '',
            parameter: ''
          };
          //remove file from input
          this.getInstansis();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.form_validation = {
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              parameter: {
                invalid: !!error.response.data.errors.parameter,
                feedback: error.response.data.errors.parameter.join(' & ')
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
      updateBimbingan(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.put(`${this.app}/api/kmm/nilai/parameter/bimbingan/${item.id_parameter}`, this.activeBimbingan)
        .then(response => {
          console.log('berhasil update', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
          this.closeModal();
          this.openBimbingan(response.data.data);
          this.getBimbingans();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.edit_validation = {
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              parameter: {
                invalid: !!error.response.data.errors.parameter,
                feedback: error.response.data.errors.parameter.join(' & ')
              }
            }
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal mengubah topik!',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal mengubah topik!',
              message: error.response.data.message
            });
          }
        });
      },
      updateSeminar(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.put(`${this.app}/api/kmm/nilai/parameter/seminar/${item.id_parameter}`, this.activeSeminar)
        .then(response => {
          console.log('berhasil update', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
          this.closeModal();
          this.openSeminar(response.data.data);
          this.getSeminars();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.edit_validation = {
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              parameter: {
                invalid: !!error.response.data.errors.parameter,
                feedback: error.response.data.errors.parameter.join(' & ')
              }
            }
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal mengubah topik!',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal mengubah topik!',
              message: error.response.data.message
            });
          }
        });
      },
      updateInstansi(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.put(`${this.app}/api/kmm/nilai/parameter/instansi/${item.id_parameter}`, this.activeInstansi)
        .then(response => {
          console.log('berhasil update', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
          this.closeModal();
          this.openInstansi(response.data.data);
          this.getInstansis();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.edit_validation = {
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              parameter: {
                invalid: !!error.response.data.errors.parameter,
                feedback: error.response.data.errors.parameter.join(' & ')
              }
            }
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal mengubah topik!',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal mengubah topik!',
              message: error.response.data.message
            });
          }
        });
      },
      deleteBimbingan(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.delete(`${this.app}/api/kmm/nilai/parameter/bimbingan/${item.id_parameter}`)
        .then(response => {
          console.log('berhasil delete', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
          this.closeModal();
          this.getBimbingans();
        })
        .catch(error => {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menghapus item!',
            message: error.response.data.message
          });
        });
      },
      deleteSeminar(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.delete(`${this.app}/api/kmm/nilai/parameter/seminar/${item.id_parameter}`)
        .then(response => {
          console.log('berhasil delete', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
          this.closeModal();
          this.getSeminars();
        })
        .catch(error => {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menghapus item!',
            message: error.response.data.message
          });
        });
      },
      deleteInstansi(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.delete(`${this.app}/api/kmm/nilai/parameter/instansi/${item.id_parameter}`)
        .then(response => {
          console.log('berhasil delete', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
          this.closeModal();
          this.getInstansis();
        })
        .catch(error => {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menghapus item!',
            message: error.response.data.message
          });
        });
      },
      openBimbingan(item) {
        this.closeModal();
        this.activeBimbingan = item;
        this.edit_bimbingan_validation = {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        }
        this.showBimbingan = true;
      },
      openSeminar(item) {
        this.closeModal();
        this.activeSeminar = item;
        this.edit_seminar_validation = {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        }
        this.showSeminar = true;
      },
      openInstansi(item) {
        this.closeModal();
        this.activeInstansi = item;
        this.edit_instansi_validation = {
          tahun: {
            invalid: false,
            feedback: ''
          },
          parameter: {
            invalid: false,
            feedback: ''
          }
        }
        this.showInstansi = true;
      },
      closeModal() {
        this.edit = false;
        this.showBimbingan = false;
        this.showSeminar = false;
        this.showInstansi = false;
        this.activeBimbingan = null;
        this.activeSeminar = null;
        this.activeInstansi = null;
      }
    }
  }
  </script>
  