<template>
    <div>
      <CRow>
        <CCol :md="12">
            <CCard class="mb-4">
            <CCardHeader>Tambah Jadwal</CCardHeader>
            <CCardBody>
                <CForm>
                    <div class="row mb-3">
                          <div class="col-12">
                          <CFormLabel for="createname">Tanggal</CFormLabel>
                          <CFormInput type="date" id="tanggal" placeholder="nomor telepon" v-model="form.tanggal"
                              :feedback="form_validation.tanggal.feedback"
                              :invalid="form_validation.tanggal.invalid" />
                          </div>
                    </div>  
                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                        <CFormLabel for="createname">Tahun</CFormLabel>
                        <CFormSelect
                          :options="tahunoptions"
                          v-model="form.tahun"
                          :feedback="form_validation.tahun.feedback"
                          :invalid="form_validation.tahun.invalid"
                          required>
                        </CFormSelect>
                        </div>
                        <div class="col-12 col-md-6">
                        <CFormLabel for="createname">Semester</CFormLabel>
                        <CFormSelect
                          :options="semesteroptions"
                          v-model="form.semester_id"
                          :feedback="form_validation.semester_id.feedback"
                          :invalid="form_validation.semester_id.invalid"
                          required>
                        </CFormSelect>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                        <CFormLabel for="createname">Sesi</CFormLabel>
                        <CFormSelect
                          :options="sesioptions"
                          v-model="form.sesi_id"
                          :feedback="form_validation.sesi_id.feedback"
                          :invalid="form_validation.sesi_id.invalid"
                          required>
                        </CFormSelect>
                        </div>
                        <div class="col-12 col-md-6">
                        <CFormLabel for="createname">Ruangan</CFormLabel>
                        <CFormSelect
                          :options="ruanganoptions"
                          v-model="form.ruangan_id"
                          :feedback="form_validation.ruangan_id.feedback"
                          :invalid="form_validation.ruangan_id.invalid"
                          required>
                        </CFormSelect>
                        </div>
                    </div>
                    <div class="mb-3 text-center">
                        <CButton color="primary" class="w-100" @click="addJadwal">Tambah</CButton>
                    </div>
                </CForm>
            </CCardBody>
            </CCard>
        </CCol>
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
            <CFormInput type="date" id="tanggal" placeholder="nomor telepon" v-model="activeJadwal.tanggal"
              :feedback="edit_validation.tanggal.feedback"
              :invalid="edit_validation.tanggal.invalid" />
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Tahun</CLabel>
          </CCol>
          <CCol :md="8">
            <CFormSelect
              :options="tahunoptions"
              v-model="activeJadwal.tahun"
              :feedback="edit_validation.tahun.feedback"
              :invalid="edit_validation.tahun.invalid"
              required>
            </CFormSelect>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Semester</CLabel>
          </CCol>
          <CCol :md="8">
            <CFormSelect
              :options="semesteroptions"
              v-model="activeJadwal.semester_id"
              :feedback="edit_validation.semester_id.feedback"
              :invalid="edit_validation.semester_id.invalid"
              required>
            </CFormSelect>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Sesi</CLabel>
          </CCol>
          <CCol :md="8">
            <CFormSelect
              :options="sesioptions"
              v-model="activeJadwal.sesi_id"
              :feedback="edit_validation.sesi_id.feedback"
              :invalid="edit_validation.sesi_id.invalid"
              required>
            </CFormSelect>
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
            <CFormSelect
              :options="ruanganoptions"
              v-model="activeJadwal.ruangan_id"
              :feedback="edit_validation.ruangan_id.feedback"
              :invalid="edit_validation.ruangan_id.invalid"
              required>
            </CFormSelect>
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
      </CModalBody>
      <CModalFooter>
        <CButton color="success" @click="editJadwal(activeJadwal)">Update</CButton>
        <CButton color="primary" @click="daftarProposal(activeJadwal)">
          Proposal/Mahasiswa
        </CButton>
        <CButton color="primary" @click="daftarDosen(activeJadwal)">
          Tambah dosen penguji
        </CButton>
        <CButton color="secondary" @click="closeModal">
          Close
        </CButton>
      </CModalFooter>
    </CModal>
    <CModal size="lg" backdrop="static" :visible="showDosen" @close="closeModal">
      <CModalHeader>
        <CModalTitle>Detail Sesi-{{ activeJadwal?.sesi?.no_sesi }}</CModalTitle>
      </CModalHeader>
      <CModalBody>
        <CForm>
            <div class="row mb-3">
              <div class="col-12 col-md-12">
                  <CFormSelect
                      :options="dosen_options"
                      v-model="addDosen.id_dosen"
                      label="Dosen Penguji"
                      :feedback="dosen_validation.id_dosen.feedback"
                      :invalid="dosen_validation.id_dosen.invalid"
                      required>
                  </CFormSelect>
              </div>
            </div>
            <div class="mb-3 text-center">
              <CButton color="primary" class="w-100" @click="storeDosen">Tambah</CButton>
            </div>
        </CForm>
        <CRow>
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
      </CModalBody>
      <CModalFooter>
        <CButton color="primary" @click="openDetailModal(activeJadwal)">Kembali</CButton>
        <CButton color="secondary" @click="closeModal">
          Close
        </CButton>
      </CModalFooter>
    </CModal>
    <CModal size="lg" backdrop="static" :visible="showProposal" @close="closeModal">
      <CModalHeader>
        <CModalTitle>Detail Sesi-{{ activeJadwal?.sesi?.no_sesi }}</CModalTitle>
      </CModalHeader>
      <CModalBody>
        <CForm>
            <div class="row mb-3">
              <div class="col-12 col-md-12">
                  <CFormSelect
                      :options="proposal_options"
                      v-model="addProposal.proposal_ta_id"
                      label="Proposal TA Mahasiswa"
                      :feedback="proposal_validation.proposal_ta_id.feedback"
                      :invalid="proposal_validation.proposal_ta_id.invalid"
                      required>
                  </CFormSelect>
              </div>
            </div>
            <div class="mb-3 text-center">
              <CButton color="primary" class="w-100" @click="storeProposal">Tambah</CButton>
            </div>
        </CForm>
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
        <CButton color="primary" @click="openDetailModal(activeJadwal)">Kembali</CButton>
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
        dosen_options: [
          { label: 'Pilih Dosen', value: ''},
          { label: 'Dosen 1', value: 1},
          { label: 'Dosen 2', value: 2},
          { label: 'Dosen 3', value: 3},
          { label: 'Dosen 4', value: 4}
        ],
        dosen_validation: {
          id_dosen: {
            feedback: '',
            invalid: false
          }
        },
        proposal_options: [
          { label: 'Pilih Proposal', value: ''},
          { label: 'Proposal 1', value: 1},
          { label: 'Proposal 2', value: 2},
          { label: 'Proposal 3', value: 3},
          { label: 'Proposal 4', value: 4}
        ],
        proposal_validation: {
          proposal_ta_id: {
            feedback: '',
            invalid: false
          }
        },
        addDosen: {
          id_dosen: ''
        },
        addProposal: {
          proposal_ta_id: ''
        },
        tahunoptions: [
          { label: 'Pilih Tahun', value: ''},
          { label: '2021', value: 2021},
          { label: '2022', value: 2022},
          { label: '2023', value: 2023},
          { label: '2024', value: 2024}
        ],
        semesteroptions: [
          { label: 'Pilih Semester', value: ''},
          { label: 'Ganjil', value: 1},
          { label: 'Genap', value: 2}
        ],
        sesioptions: [
          { label: 'Pilih Sesi', value: ''},
          { label: '1', value: 1},
          { label: '2', value: 2},
          { label: '3', value: 3},
          { label: '4', value: 4}
        ],
        ruanganoptions: [
          { label: 'Pilih Ruangan', value: ''},
          { label: 'Aula', value: 1},
          { label: 'Lab 1', value: 2},
          { label: 'Lab 2', value: 3},
          { label: 'Lab 3', value: 4}
        ],
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
        form: {
          tanggal: '',
          tahun: '',
          semester_id: '',
          sesi_id: '',
          ruangan_id: ''
        },
        form_validation: {
          tanggal: {
            feedback: '',
            invalid: false
          },
          tahun: {
            feedback: '',
            invalid: false
          },
          semester_id: {
            feedback: '',
            invalid: false
          },
          sesi_id: {
            feedback: '',
            invalid: false
          },
          ruangan_id: {
            feedback: '',
            invalid: false
          }
        },
        edit_validation: {
          tanggal: {
            feedback: '',
            invalid: false
          },
          tahun: {
            feedback: '',
            invalid: false
          },
          semester_id: {
            feedback: '',
            invalid: false
          },
          sesi_id: {
            feedback: '',
            invalid: false
          },
          ruangan_id: {
            feedback: '',
            invalid: false
          }
        },
        search: '',
        showDetailModal: false,
        showDosen: false,
        showProposal: false,
        activeJadwal: null
      }
    },
    async created() {
      this.getJadwal();
      this.getTahun();
      this.getSesi();
      this.getRuangan();
      this.getDosens();
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
      storeProposal() {
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${window.location.origin}/api/ta/jadwal_proposal_ta/${this.activeJadwal.id}/add/mahasiswa`, this.addProposal)
        .then(response => {
          this.$store.dispatch('showSuccessAlert', 'Proposal ditambahkan!');
          this.addProposal = {
            proposal_ta_id: ''
          };
          this.proposal_validation = {
            proposal_ta_id: {
              feedback: '',
              invalid: false
            }
          };
          this.daftarProposal(this.activeJadwal);
          this.getJadwal();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.proposal_validation = {
              proposal_ta_id: {
                invalid: !!error.response.data.errors.proposal_ta_id,
                feedback: error.response.data.errors.proposal_ta_id.join(' & ')
              }
            };
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menambah proposal!',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menambah proposal!',
              message: error.response.status
            });
          }
        });
      },
      storeDosen() {
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${window.location.origin}/api/ta/jadwal_proposal_ta/${this.activeJadwal.id}/add/dosen`, this.addDosen)
        .then(response => {
          this.$store.dispatch('showSuccessAlert', 'Dosen ditambahkan!');
          this.addDosen = {
            id_dosen: ''
          };
          this.dosen_validation = {
            id_dosen: {
              feedback: '',
              invalid: false
            }
          };
          this.daftarDosen(this.activeJadwal);
          this.getJadwal();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.dosen_validation = {
              id_dosen: {
                invalid: !!error.response.data.errors.id_dosen,
                feedback: error.response.data.errors.id_dosen.join(' & ')
              }
            };
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menambah dosen!',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menambah dosen!',
              message: error.response.status
            });
          }
        });
      },
      getProposal() {
        axios.get(`${window.location.origin}/api/ta/proposal_ta/all`)
        .then(response => {
          this.proposal_options = [
            { label: 'Pilih Proposal', value: ''},
            ...response.data.data.map(item => {
              return {
                label: item.nim + ' - ' + item.mahasiswa?.nama + ' - ' + item.judul_proposal,
                value: item.id
              }
            })
          ];
        })
        .catch(error => {
          this.proposal_options = [
            { label: 'Pilih Proposal', value: ''},
          ];
          console.log(error);
        });
      },
      getDosens() {
            axios.get(`${window.location.origin}/api/kmm/dosen/all`)
            .then((response) => {
                this.dosen_options = [
                    { label: 'Open this select menu', value: ''}
                ];
                //map and push to options
                response.data.data.map((item) => {
                    this.dosen_options.push({
                        label: item.nama,
                        value: item.id_dosen
                    });
                });
            })
            .catch((error) => {
                console.log(error);
            });
      },
      getTahun() {
        axios.get(`${window.location.origin}/api/ta/tahun`)
        .then(response => {
          this.tahunoptions = [
            { label: 'Pilih Tahun', value: ''},
            ...response.data.data.map(item => {
              return {
                label: item.tahun,
                value: item.tahun
              }
            })
          ];
        })
        .catch(error => {
            this.tahuns = [];
            console.log(error);
        });
      },
      getSesi() {
        axios.get(`${window.location.origin}/api/ta/sesi_ujian`)
        .then(response => {
          this.sesioptions = [
            { label: 'Pilih Sesi', value: ''},
            ...response.data.data.map(item => {
              return {
                label: 'Sesi ke-'+item.no_sesi,
                value: item.id
              }
            })
          ];
        })
        .catch(error => {
            this.sesioptions = [
              { label: 'Pilih Sesi', value: ''},
            ];
            console.log(error);
        });
      },
      getRuangan() {
        axios.get(`${window.location.origin}/api/ta/ruangan`)
        .then(response => {
          this.ruanganoptions = [
            { label: 'Pilih Ruangan', value: ''},
            ...response.data.data.map(item => {
              return {
                label: item.nama,
                value: item.id
              }
            })
          ];
        })
        .catch(error => {
            this.ruanganoptions = [
              { label: 'Pilih Ruangan', value: ''},
            ];
            console.log(error);
        });
      },
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
      addJadwal() {
        this.$store.dispatch('showLoadingAlert');
        axios.post(`${window.location.origin}/api/ta/jadwal_proposal_ta`, this.form)
        .then(response => {
          this.$store.dispatch('showSuccessAlert', 'Proposal disetujui!');
          this.form = {
            tanggal: '',
            tahun: '',
            semester_id: '',
            sesi_id: '',
            ruangan_id: ''
          };
          this.form_validation = {
            tanggal: {
              feedback: '',
              invalid: false
            },
            tahun: {
              feedback: '',
              invalid: false
            },
            semester_id: {
              feedback: '',
              invalid: false
            },
            sesi_id: {
              feedback: '',
              invalid: false
            },
            ruangan_id: {
              feedback: '',
              invalid: false
            }
          };
          this.getJadwal();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.form_validation = {
              tanggal: {
                invalid: !!error.response.data.errors.tanggal,
                feedback: error.response.data.errors.tanggal.join(' & ')
              },
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              semester_id: {
                invalid: !!error.response.data.errors.semester_id,
                feedback: error.response.data.errors.semester_id.join(' & ')
              },
              sesi_id: {
                invalid: !!error.response.data.errors.sesi_id,
                feedback: error.response.data.errors.sesi_id.join(' & ')
              },
              ruangan_id: {
                invalid: !!error.response.data.errors.ruangan_id,
                feedback: error.response.data.errors.ruangan_id.join(' & ')
              }
            };
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menambah jadwal!',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal menambah jadwal!',
              message: error.response.status
            });
          }
          console.log(error);
        });
      },
      editJadwal(item) {
        this.$store.dispatch('showLoadingAlert');
        axios.put(`${window.location.origin}/api/ta/jadwal_proposal_ta/${item.id}`, item)
        .then(response => {
          this.$store.dispatch('showSuccessAlert', 'Jadwal diupdate!');
          this.closeModal();
          this.openDetailModal(response.data.data);
          this.getJadwal();
        })
        .catch(error => {
          if (error.response.status === 422) {
            this.edit_validation = {
              tanggal: {
                invalid: !!error.response.data.errors.tanggal,
                feedback: error.response.data.errors.tanggal.join(' & ')
              },
              tahun: {
                invalid: !!error.response.data.errors.tahun,
                feedback: error.response.data.errors.tahun.join(' & ')
              },
              semester_id: {
                invalid: !!error.response.data.errors.semester_id,
                feedback: error.response.data.errors.semester_id.join(' & ')
              },
              sesi_id: {
                invalid: !!error.response.data.errors.sesi_id,
                feedback: error.response.data.errors.sesi_id.join(' & ')
              },
              ruangan_id: {
                invalid: !!error.response.data.errors.ruangan_id,
                feedback: error.response.data.errors.ruangan_id.join(' & ')
              }
            };
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal mengupdate jadwal!',
              message: error.response.data.message
            });
          } else {
            console.log(error);
            this.$store.dispatch('showErrorAlert', {
              title: 'Gagal mengupdate jadwal!',
              message: error.response.status
            });
          }
        });
      },
      openDetailModal(item) {
        this.closeModal();
        this.activeJadwal = item;
        this.showDetailModal = true;
      },
      closeModal() {
        this.showDetailModal = false;
        this.showDosen = false;
        this.showProposal = false;
        this.activeJadwal = null;
      },
      daftarProposal(item) {
        this.closeModal();
        this.activeJadwal = item;
        this.showProposal = true;
      },
      daftarDosen(item) {
        this.closeModal();
        this.activeJadwal = item;
        this.showDosen = true;
      }
    }
  }
  </script>
  