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
        jadwals: {
              total: 0,
              per_page: 2,
              from: 1,
              to: 0,
              current_page: 1,
              data: []
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
        offset: 4,
        itemstatus: 'Mengambil items',
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
  