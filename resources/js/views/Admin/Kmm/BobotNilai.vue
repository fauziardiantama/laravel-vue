<template>
    <div>
      <CRow>
        <CCol :md="5">
          <CCard class="mb-4">
            <CCardHeader>Tambah Bobot Nilai</CCardHeader>
            <CCardBody>
              <CForm>
                <div class="mb-3">
                  <CFormLabel for="judul">Tahun</CFormLabel>
                  <CFormSelect
                        aria-label="Default select example"
                        :options="tahun_options"
                        v-model="form.tahun"
                        :feedback="form_validation.tahun.feedback"
                        :invalid="form_validation.tahun.invalid"
                        required>
                    </CFormSelect>
                </div>
                <div class="mb-3">
                  <CFormLabel for="deskripsi">Jenis</CFormLabel>
                  <CFormSelect
                        aria-label="Default select example"
                        :options="jenis_options"
                        v-model="form.jenis_nilai"
                        :feedback="form_validation.jenis_nilai.feedback"
                      :invalid="form_validation.jenis_nilai.invalid"
                        required>
                    </CFormSelect>
                </div>
                <div class="mb-3">
                  <CFormLabel for="judul">Persentase</CFormLabel>
                  <CFormInput type="text" id="persentase" placeholder="Persentase"
                    v-model="form.persentase"
                    :feedback="form_validation.persentase.feedback"
                    :invalid="form_validation.persentase.invalid" />
                </div>
                <div class="mb-3 text-center">
                  <CRow>
                    <CCol :md="6">
                      <CButton color="primary" class="w-100" @click="addItem(false)">Tambah</CButton>
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
              <p class="col-6">Daftar Bobot Nilai</p>
              <!--search bar-->
              <div class="col-6">
                <CInputGroup>
                  <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getBobotnilais"/>
                  <CInputGroupText @click="getBobotnilais" class="cursor-pointer">
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
                  :is-re-search="bobotnilai.research"
                  :is-loading="bobotnilai.isLoading"
                  :columns="bobotnilai.columns"
                  :rows="bobotnilai.rows"
                  :total="bobotnilai.totalRecordCount"
                  :sortable="bobotnilai.sortable"
                  :messages="bobotnilai.messages"
                  @do-search="BobotnilaiSearch"
              >
                <template v-slot:id_bobot="data">
                  {{ data.value.index }}
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
          <CModalTitle>Detail {{ activeBobotnilai?.tahun ?? '-' }}</CModalTitle>
          </CModalHeader>
          <CModalBody>
          <CRow>
              <CCol :md="4">
              <CLabel><b>Tahun</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeBobotnilai?.tahun }}</p>
                <CFormSelect v-else
                  :options="tahun_options"
                  v-model="activeBobotnilai.tahun"
                  :feedback="edit_validation.tahun.feedback"
                  :invalid="edit_validation.tahun.invalid"
                  required>
                </CFormSelect>
              </CCol>
          </CRow>
          <CRow>
              <CCol :md="4">
              <CLabel><b>Jenis</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeBobotnilai?.jenis_nilai }}</p>
                <CFormSelect v-else
                  :options="jenis_options"
                  v-model="activeBobotnilai.jenis_nilai"
                  :feedback="edit_validation.jenis_nilai.feedback"
                  :invalid="edit_validation.jenis_nilai.invalid"
                  required>
                </CFormSelect>
              </CCol>
          </CRow>
          <CRow>
              <CCol :md="4">
                <CLabel><b>Persentase</b></CLabel>
              </CCol>
              <CCol :md="8">
                <p v-if="!edit">{{ activeBobotnilai?.persentase }}</p>
                <CFormInput v-else
                type="text"
                id="persentase"
                placeholder="Persentase"
                v-model="activeBobotnilai.persentase"
                :feedback="edit_validation.persentase.feedback"
                :invalid="edit_validation.persentase.invalid"
                required />
              </CCol>
          </CRow>
          <div class="mb-3"></div>
          </CModalBody>
          <CModalFooter>
            <CButton color="primary" v-if="!edit" @click="edit = !edit">
                Edit
            </CButton>
            <CButton color="success" v-else @click="update(activeBobotnilai)">
                Save
            </CButton>
            <CButton color="danger" @click="deleteBobotnilai(activeBobotnilai)">
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
    name: 'BobotNilai',
    data() {
      return {
        bobotnilai: {
          isLoading: false,
          columns : [
            {
              label: "#",
              field: "id_bobot",
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
              width: "25%",
              sortable: true,
            },
            {
              label: "Jenis",
              field: "jenis_nilai",
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
              label: "%",
              field: "persentase",
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
            order: "id_bobot",
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
        form: {
          tahun: '',
          jenis_nilai: '',
          persentase: '',
        },
        form_validation: {
          tahun: {
            invalid: false,
            feedback: ''
          },
          jenis_nilai: {
            invalid: false,
            feedback: ''
          },
          persentase: {
            invalid: false,
            feedback: ''
          }
        },
        edit_validation: {
          tahun: {
            invalid: false,
            feedback: ''
          },
          jenis_nilai: {
            invalid: false,
            feedback: ''
          },
          persentase: {
            invalid: false,
            feedback: ''
          }
        },
        itemstatus: 'Mengambil items',
        showDetailModal: false,
        activeBobotnilai: null,
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
        jenis_options: [
          { label: 'Pilih jenis nilai', value: ''},
          { label: 'bimbingan', value: 'bimbingan' },
          { label: 'instansi', value: 'instansi' },
          { label: 'seminar', value: 'seminar' }
        ],
        app: window.location.origin
      }
    },
    created() {
      this.getBobotnilais();
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
      BobotnilaiSearch(offset, limit, order, sort) {
        this.bobotnilai.isLoading = true;
        //calculate page based on offset and limit
        let page = offset / limit + 1;
        let url = `${window.location.origin}/api/kmm/nilai/bobot?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
        axios.get(url)
        .then((response) => {
          this.bobotnilai.research = false;
          console.log(this.search == '' ? '[kosong]' : this.search);
          this.bobotnilai.rows = response.data.data.data;
          //add iteration index and push to rows as 'index'
          let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
          this.bobotnilai.rows.forEach((item, index) => {
            //calculate index based on current page
            item.index = index + 1 + pagination;
          });
          this.bobotnilai.totalRecordCount = response.data.data.total;
          this.bobotnilai.page = {
            limit: limit, 
            offset: offset,
          };
          this.bobotnilai.sortable = {
            order: order,
            sort: sort
          };
          this.bobotnilai.isLoading = false;
        });
      },
      getBobotnilais() {
        this.search = document?.getElementById('search')?.value ?? this.search;
        this.bobotnilai.totalRecordCount = 0;
        this.bobotnilai.rows = [];
        this.bobotnilai.page = {
          limit: 10,
          offset: 0
        };
        this.bobotnilai.research = true;
        this.BobotnilaiSearch(this.bobotnilai.page.offset, this.bobotnilai.page.limit, this.bobotnilai.sortable.order, this.bobotnilai.sortable.sort);
      },
      addItem() {        
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${this.app}/api/kmm/nilai/bobot`, this.form)
        .then(response => {
          console.log(response);
          this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
          this.form = {
            tahun: '',
            jenis_nilai: '',
            persentase: ''
          };
          //remove file from input
          this.getBobotnilais();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.form_validation = {
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              jenis_nilai: {
                invalid: !!error.response.data.errors.jenis_nilai,
                feedback: error.response.data.errors.jenis_nilai.join(' & ')
              },
              persentase: {
                invalid: !!error.response.data.errors.persentase,
                feedback: error.response.data.errors.persentase.join(' & ')
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
      update(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.put(`${this.app}/api/kmm/nilai/bobot/${item.id_bobot}`, this.activeBobotnilai)
        .then(response => {
          console.log('berhasil update', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
          this.closeModal();
          this.openDetailModal(response.data.data);
          this.getBobotnilais();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.edit_validation = {
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              jenis_nilai: {
                invalid: !!error.response.data.errors.jenis_nilai,
                feedback: error.response.data.errors.jenis_nilai.join(' & ')
              },
              persentase: {
                invalid: !!error.response.data.errors.persentase,
                feedback: error.response.data.errors.persentase.join(' & ')
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
      deleteBobotnilai(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.delete(`${this.app}/api/kmm/nilai/bobot/${item.id_bobot}`)
        .then(response => {
          console.log('berhasil delete', response.data.data);
          this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
          this.closeModal();
          this.getBobotnilais();
        })
        .catch(error => {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menghapus item!',
            message: error.response.data.message
          });
        });
      },
      openDetailModal(item) {
        this.activeBobotnilai = item;
        this.edit_validation = {
          tahun: {
            invalid: false,
            feedback: ''
          },
          jenis_nilai: {
            invalid: false,
            feedback: ''
          },
          persentase: {
            invalid: false,
            feedback: ''
          }
        }
        this.showDetailModal = true;
      },
      closeModal() {
        this.edit = false;
        this.showDetailModal = false;
        this.activeBobotnilai = null;
      }
    }
  }
  </script>
  