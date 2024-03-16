<template>
    <div>
      <CRow>
        <CCol :md="12">
            <CCard class="mb-4">
            <CCardHeader component="h5">Pendaftaran Magang</CCardHeader>
            <CCardBody v-if="doneStepOne">
              <CCardText>Magang telah didaftarkan, data topik bisa diubah sebelum menambahkan instansi</CCardText>
              <CForm v-if="!doneStepTwo" class="row g-3" novalidate>
                <CCol md="4">
                    <CFormSelect
                        aria-label="Default select example"
                        :options="tahun_options"
                        v-model="editMagang.tahun"
                        label="Tahun"
                        :feedback="editmagang_validation.tahun.feedback"
                        :invalid="editmagang_validation.tahun.invalid"
                        required>
                    </CFormSelect>
                </CCol>
                <CCol md="8">
                    <CFormSelect
                        aria-label="Default select example"
                        :options="topik_options"
                        v-model="editMagang.id_topik"
                        label="Tipe"
                        :feedback="editmagang_validation.id_topik.feedback"
                        :invalid="editmagang_validation.id_topik.invalid"
                        required>
                    </CFormSelect>
                </CCol>
                <div>
                    <CButton color="primary" class="m-2" @click="updateMagang">Update</CButton>
                    <CButton color="danger" class="m-2" @click="deleteMagang">Hapus</CButton>
                </div>
              </CForm>
              <div v-else>
                <CRow class="mb-3">
                    <CCol :md="2">
                        <CLabel>Tahun</CLabel>
                    </CCol>
                    <CCol :md="10">
                        <p>{{ magang.tahun.tahun }}</p>
                    </CCol>
                </CRow>
                <CRow class="mb-3">
                    <CCol :md="2">
                        <CLabel>Topik</CLabel>
                    </CCol>
                    <CCol :md="10">
                        <p>{{ magang.topik.nama_topik }}</p>
                    </CCol>
                </CRow>
              </div>
            </CCardBody>
            <CCardBody v-if="!doneStepOne">
              <CCardText>Untuk melanjutkan, silahkan masukkan tahun dan topik magang.</CCardText>
              <CForm class="row g-3" novalidate>
                <CCol md="4">
                    <CFormSelect
                        aria-label="Default select example"
                        :options="tahun_options"
                        v-model="addMagang.tahun"
                        label="Tahun"
                        :feedback="magang_validation.tahun.feedback"
                        :invalid="magang_validation.tahun.invalid"
                        required>
                    </CFormSelect>
                </CCol>
                <CCol md="8">
                    <CFormSelect
                        aria-label="Default select example"
                        :options="topik_options"
                        v-model="addMagang.id_topik"
                        label="Tipe"
                        :feedback="magang_validation.id_topik.feedback"
                        :invalid="magang_validation.id_topik.invalid"
                        required>
                    </CFormSelect>
                </CCol>
                <CCol xs="12">
                    <CButton color="primary" @click="storeMagang">Tambah</CButton>
                </CCol>
              </CForm>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="12">
            <CCard class="mb-4">
                <CCardHeader component="h5">Pemilihan Instansi</CCardHeader>
                <CCardBody v-if="doneStepTwo">
                    <CCardText>Instansi telah dipilih, silahkan tunggu admin memverifikasi instansi atau segera menambah rencana magang bila instansi sudah diverifikasi.</CCardText>
                    <CRow>
                        <CCol :md="4">
                            <CLabel>Nama instansi</CLabel>
                        </CCol>
                        <CCol :md="8">
                            <p>{{ magang.instansi.nama }}</p>
                        </CCol>
                    </CRow>
                    <CRow>
                        <CCol :md="4">
                            <CLabel>E-mail</CLabel>
                        </CCol>
                        <CCol :md="8">
                            <p>{{ magang.instansi.email }}</p>
                        </CCol>
                    </CRow>
                    <CRow>
                        <CCol :md="4">
                            <CLabel>No. telp</CLabel>
                        </CCol>
                        <CCol :md="8">
                            <p>{{ magang.instansi.no_telp }}</p>
                        </CCol>
                    </CRow>
                    <CRow>
                        <CCol :md="4">
                            <CLabel>Website</CLabel>
                        </CCol>
                        <CCol :md="8">
                            <p>{{ magang.instansi.web }}</p>
                        </CCol>
                    </CRow>
                    <CRow>
                        <CCol :md="4">
                            <CLabel>Alamat</CLabel>
                        </CCol>
                        <CCol :md="8">
                            <p>{{ magang.instansi.alamat }}</p>
                        </CCol>
                    </CRow>
                    <CRow>
                        <CCol :md="4">
                            <CLabel>Status</CLabel>
                        </CCol>
                        <CCol :md="8">
                            <p>{{ magang.instansi.status_instansi == 1 ? 'Disetujui' : (magang.instansi.status_instansi == 0 ? 'Menunggu verifikasi' : 'Ditolak') }}</p>
                        </CCol>
                    </CRow>
                    <div v-if="!doneAddRencana">
                        <CButton color="danger" class="m-2" @click="removeInstansi">Ganti Instansi</CButton>
                    </div>
                </CCardBody>
                <CCardBody v-else-if="stepTwo">
                    <CCardText>Silahkan pilih instansi atau daftarkan instansi baru bila tidak ada.</CCardText>
                    <CNav variant="tabs" class="mb-3">
                        <CNavItem>
                            <CNavLink href="javascript:void(0)" @click="toggleInstansi = true" :active="toggleInstansi">
                            <h6>Pilih Instansi</h6>
                            </CNavLink>
                        </CNavItem>
                        <CNavItem>
                            <CNavLink href="javascript:void(0)" @click="toggleInstansi = false" :active="!toggleInstansi">
                            <h6>Daftarkan Instansi</h6>
                            </CNavLink>
                        </CNavItem>
                    </CNav>
                    <CForm v-if="toggleInstansi">
                        <div class="mb-3">
                            <CFormSelect
                            aria-label="Default select example"
                            :options="instansi_options"
                            v-model="chooseInstansi.id_instansi"
                            label="Pilih Instansi"
                            :feedback="chooseInstansi_validation.id_instansi.feedback"
                            :invalid="chooseInstansi_validation.id_instansi.invalid"
                            required>
                        </CFormSelect>
                        </div>
                         <div class="mb-3 text-center">
                            <CButton color="primary" class="w-100" @click="askInstansi">Pilih</CButton>
                        </div>
                    </CForm>
                    <CForm v-if="!toggleInstansi">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                            <CFormLabel for="createname">Nama</CFormLabel>
                            <CFormInput type="text" id="nama" placeholder="nama" v-model="addInstansi.nama"
                                :feedback="instansi_validation.nama.feedback"
                                :invalid="instansi_validation.nama.invalid" />
                            </div>
                            <div class="col-12 col-md-6">
                            <CFormLabel for="createname">E-mail</CFormLabel>
                            <CFormInput type="email" id="email" placeholder="email" v-model="addInstansi.email"
                                :feedback="instansi_validation.email.feedback"
                                :invalid="instansi_validation.email.invalid" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                            <CFormLabel for="createname">Nomor telepon</CFormLabel>
                            <CFormInput type="text" id="no_telp" placeholder="nomor telepon" v-model="addInstansi.no_telp"
                                :feedback="instansi_validation.no_telp.feedback"
                                :invalid="instansi_validation.no_telp.invalid" />
                            </div>
                            <div class="col-12 col-md-6">
                            <CFormLabel for="createname">Website</CFormLabel>
                            <CFormInput type="text" id="web" placeholder="website" v-model="addInstansi.web"
                                :feedback="instansi_validation.web.feedback"
                                :invalid="instansi_validation.web.invalid" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <CFormTextarea
                            id="alamat"
                            label="Alamat"
                            rows="3"
                            placeholder="alamat"
                            v-model="addInstansi.alamat"
                                :feedback="instansi_validation.alamat.feedback"
                                :invalid="instansi_validation.alamat.invalid"
                            ></CFormTextarea>
                        </div>
                        <div class="mb-3 text-center">
                            <CButton color="primary" class="w-100" @click="storeInstansi">Tambah</CButton>
                        </div>
                    </CForm>
                </CCardBody>
                <CCardBody v-else>
                    <CCardText>Selesaikan pendaftaran tahun dan topik</CCardText>
                </CCardBody>
            </CCard>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="12">
            <CCard class="mb-4">
            <CCardHeader component="h5">Rencana magang</CCardHeader>
            <CCardBody v-if="stepThree">
              <CCardText>Tambahkan rencana magang anda minimal 5</CCardText>
              <CTable v-if="doneAddRencana" class="mb-3">
                <CTableHead>
                  <CTableRow>
                    <CTableHeaderCell scope="col">Minggu ke-</CTableHeaderCell>
                    <CTableHeaderCell scope="col">Kegiatan</CTableHeaderCell>
                    <CTableHeaderCell scope="col" v-if="!doneStepFour">Action</CTableHeaderCell>
                  </CTableRow>
                </CTableHead>
                <CTableBody>
                  <CTableRow v-for="rencana in rencanas" :key="rencana.id_rencana">
                    <CTableDataCell>{{ rencana.minggu }}</CTableDataCell>
                    <CTableDataCell>{{ rencana.kegiatan }}</CTableDataCell>
                    <CTableDataCell v-if="!doneStepFour">
                        <CButton color="primary" class="m-2" @click="modalRencana(rencana.id_rencana)">Update</CButton>
                    </CTableDataCell>
                  </CTableRow>
                </CTableBody>
              </CTable>
              <CForm v-if="!doneStepFour" class="row g-3" novalidate>
                <CCol md="12">
                    <CFormInput type="text" id="nama" placeholder="nama" v-model="addRencana.kegiatan"
                            label="Kegiatan"
                            :feedback="rencana_validation.kegiatan.feedback"
                            :invalid="rencana_validation.kegiatan.invalid" />
                </CCol>
                <CCol xs="12">
                    <CButton color="primary" class="m-2" @click="storeRencana">Tambah</CButton>
                    <CButton v-if="doneAddRencana" class="m-2" color="danger" @click="removeRencana">Hapus kegiatan paling akhir</CButton>
                </CCol>
              </CForm>
            </CCardBody>
            <CCardBody v-else>
              <CCardText>Pilih instansi terlebih dahulu</CCardText>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="12">
            <CCard class="mb-4">
            <CCardHeader component="h5">Pilih Dosen Pembimbing</CCardHeader>
            <CCardBody v-if="stepFour">
              <CCardText v-if="doneStepFour||approvedDosen">Dosen tidak dapat diganti bila sudah disetujui</CCardText>
              <CCardText v-else>Berikut adalah dosen pembimbing sesuai topik yang anda pilih ({{ magang?.topik?.nama_topik }}) </CCardText>
              <div v-if="doneStepFour">
                <CRow class="mb-3">
                    <CCol :md="2">
                        <CLabel>Nama dosen</CLabel>
                    </CCol>
                    <CCol :md="10">
                        <p>{{ magang.dosen.nama }}</p>
                    </CCol>
                </CRow>
                <CRow class="mb-3">
                    <CCol :md="2">
                        <CLabel>Status</CLabel>
                    </CCol>
                    <CCol :md="10">
                        <p>{{ magang.status_dosen == 1 ? 'Disetujui' : (magang.status_dosen == 0 ? 'Menunggu verifikasi' : 'Ditolak') }}</p>
                    </CCol>
                </CRow>
              </div>
              <CForm class="row g-3" v-if="doneStepFour&&!approvedDosen" novalidate>
                <CCol md="12">
                    <CFormSelect
                        aria-label="Default select example"
                        :options="dosen_options"
                        v-model="editDosen.id_dosen"
                        label="Ganti dosen pembimbing"
                        :feedback="editdosen_validation.id_dosen.feedback"
                        :invalid="editdosen_validation.id_dosen.invalid"
                        required>
                    </CFormSelect>
                </CCol>
                <CCol xs="12">
                    <CButton color="primary" @click="updateDosen">Ubah</CButton>
                    <CButton v-if="doneStepFour" class="m-2" color="danger" @click="removeDosen">Hapus</CButton>
                </CCol>
              </CForm>
              <CForm class="row g-3" v-if="stepFour&&!doneStepFour" novalidate>
                <CCol md="12">
                    <CFormSelect
                        aria-label="Default select example"
                        :options="dosen_options"
                        v-model="addDosen.id_dosen"
                        label="Dosen Pembimbing"
                        :feedback="dosen_validation.id_dosen.feedback"
                        :invalid="dosen_validation.id_dosen.invalid"
                        required>
                    </CFormSelect>
                </CCol>
                <CCol xs="12">
                    <CButton color="primary" @click="storeDosen">Tambah</CButton>
                </CCol>
              </CForm>
            </CCardBody>
            <CCardBody v-if="!stepFour">
              <CCardText>Tambahkan rencana magang minimal 5 minggu terlebih dahulu</CCardText>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
    </div>
    <CModal v-if="doneAddRencana" backdrop="static" :visible="showEditModal" @close="closeModal">
        <CModalHeader>
        <CModalTitle>Edit kegiatan minggu ke-{{ editrencana.minggu }}</CModalTitle>
        </CModalHeader>
        <CModalBody>
            <CForm class="row g-3" novalidate>
                <CCol md="12">
                    <CFormInput type="text" id="nama" placeholder="nama" v-model="editrencana.kegiatan"
                            label="Kegiatan"
                            :feedback="editrencana_validation.kegiatan.feedback"
                            :invalid="editrencana_validation.kegiatan.invalid" />
                </CCol>
              </CForm>
        </CModalBody>
        <CModalFooter>
            <CButton color="secondary" @click="closeModal">
                Close
            </CButton>
            <CButton color="primary" class="m-2" @click="updateRencana">Update</CButton>
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
  </style>
  
  
  <script>

  export default {
    name: 'Dashboard',
    components: {},
    data() {
      return {
        user: null,
        tahun_options: [
            { label: 'Open this select menu', value: ''},
            { label: '2018', value: 2018 },
            { label: '2019', value: 2019 },
            { label: '2020', value: 2020 },
            { label: '2021', value: 2021 },
            { label: '2022', value: 2022 },
            { label: '2023', value: 2023 },
            { label: '2024', value: 2024 }
        ],
        topik_options: [
            { label: 'Open this select menu', value: ''},
            { label: 'Topik 1', value: 1},
            { label: 'Topik 2', value: 2},
            { label: 'Topik 3', value: 3},
            { label: 'Topik 4', value: 4}
        ],
        magang: null,
        addMagang: {
            tahun: '',
            id_topik: '',
        },
        magang_validation: {
            tahun: {
                invalid: false,
                feedback: ''
            },
            id_topik: {
                invalid: false,
                feedback: ''
            }
        },
        editMagang: {
            tahun: '',
            id_topik: '',
        },
        editmagang_validation: {
            tahun: {
                invalid: false,
                feedback: ''
            },
            id_topik: {
                invalid: false,
                feedback: ''
            }
        },
        toggleInstansi: true,
        instansi_options: [
            { label: 'Open this select menu', value: ''},
            { label: 'Instansi 1', value: 1},
            { label: 'Instansi 2', value: 2},
            { label: 'Instansi 3', value: 3},
            { label: 'Instansi 4', value: 4}
        ],
        chooseInstansi: {
            id_instansi: ''
        },
        chooseInstansi_validation: {
            id_instansi: {
                invalid: false,
                feedback: ''
            }
        },
        addInstansi: {
            nama: '',
            email: '',
            no_telp: '',
            web: '',
            alamat: ''
        },
        instansi_validation: {
            nama: {
                invalid: false,
                feedback: ''
            },
            email: {
                invalid: false,
                feedback: ''
            },
            no_telp: {
                invalid: false,
                feedback: ''
            },
            web: {
                invalid: false,
                feedback: ''
            },
            alamat: {
                invalid: false,
                feedback: ''
            }
        },
        rencanas: [],
        addRencana: {
            kegiatan: ''
        },
        editrencana: {
            minggu: 0,
            kegiatan: ''
        },
        editrencana_validation: {
            kegiatan: {
                invalid: false,
                feedback: ''
            }
        },
        rencana_validation: {
            kegiatan: {
                invalid: false,
                feedback: ''
            }
        },
        dosen_options: [
            { label: 'Open this select menu', value: ''},
            { label: 'Dosen 1', value: 1},
            { label: 'Dosen 2', value: 2},
            { label: 'Dosen 3', value: 3},
            { label: 'Dosen 4', value: 4}
        ],
        addDosen: {
            id_dosen: ''
        },
        dosen_validation: {
            id_dosen: {
                invalid: false,
                feedback: ''
            }
        },
        editDosen: {
            id_dosen: ''
        },
        editdosen_validation: {
            id_dosen: {
                invalid: false,
                feedback: ''
            }
        },
        showEditModal: false,
      }
    },
    computed: {
        doneStepOne() {
            return this.magang != null;
        },
        stepTwo() {
            return this.magang != null;
        },
        doneStepTwo() {
            return this.magang != null && this.magang.instansi != null;
        },
        stepThree() {
            return this.magang != null && this.magang.instansi != null && this.magang.instansi.status_instansi == 1;
        },
        doneAddRencana() {
            return this.rencanas.length > 0;
        },
        stepFour() {
            return this.rencanas.length > 4;
        },
        doneStepFour() {
            return this.magang != null && this.magang.dosen != null;
        },
        approvedDosen() {
            return this.magang != null && this.magang.dosen != null && this.magang.status_dosen == 1;
        }
    },
    watch: {
        '$store.state.user': {
            immediate: true,
            handler(user) {
                if (user != null) {
                    this.user = user;
                    this.getTahuns();
                    this.getTopiks();
                    this.getMagang();
                    this.echo();
                }
            }
        },
        'rencanas': {
            immediate: true,
            handler(rencanas) {
                if (rencanas != null) {
                    if (rencanas.length > 4 && this.magang != null) {
                        this.getDosens(this.magang.id_topik);
                    }
                }
            }
        },
        'magang': {
            immediate: true,
            handler(magang) {
                if (magang != null) {
                    this.getInstansis();
                    this.editMagang = {
                        tahun: magang.tahun.tahun,
                        id_topik: magang.id_topik
                    }
                    this.rencanas = magang.rencana_magang;
                    if (magang.id_dosen != null) {
                        this.editDosen = {
                            id_dosen: magang.id_dosen
                        }
                    }
                } else {
                    this.editMagang = {
                        tahun: '',
                        id_topik: ''
                    }
                }
            }
        }
    },
    methods: {
        echo() {
            console.log('Echoing...');
            Echo.private(`User.${this.user.nim}`)
            .listen('Mgng', (e) => {
                this.getMagang();
            }).listen('RncMgng', (e) => {
                this.getRencana();
                if (e.item?.id_rencana == this.editrencana?.id_rencana) {
                    this.closeModal();
                    if (e.type == 'update') {
                        this.modalRencana(e.item.id_rencana);
                    }
                }
            });
            Echo.private(`Mahasiswa`)
            .listen('Inst', (e) => {
                this.getInstansis();
            }).listen('Dsn', (e) => {
                this.getDosens(this.magang.id_topik);
            }).listen('Tpk', (e) => {
                this.getTopiks();
            });
        },
        getMagang() {
            axios.get(`${window.location.origin}/api/kmm/magang`)
            .then((response) => {
                this.magang = response.data.data;
            })
            .catch((error) => {
                console.log(error);
            });
        },
        getRencana() {
            axios.get(`${window.location.origin}/api/kmm/rencana_magang`)
            .then((response) => {
                this.rencanas = response.data.data;
            })
            .catch((error) => {
                console.log(error);
            });
        },
        getTahuns() {
            axios.get(`${window.location.origin}/api/kmm/tahun`)
            .then((response) => {
                this.tahun_options = [
                    { label: 'Open this select menu', value: ''}
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
        getTopiks() {
            axios.get(`${window.location.origin}/api/kmm/topik`)
            .then((response) => {
                this.topik_options = [
                    { label: 'Open this select menu', value: ''}
                ];
                //map and push to options
                response.data.data.map((item) => {
                    this.topik_options.push({
                        label: item.nama_topik,
                        value: item.id_topik
                    });
                });
            })
            .catch((error) => {
                console.log(error);
            });
        },
        getInstansis() {
            axios.get(`${window.location.origin}/api/kmm/instansi/all`)
            .then((response) => {
                this.instansi_options = [
                    { label: 'Open this select menu', value: ''}
                ];
                //map and push to options
                response.data.data.map((item) => {
                    this.instansi_options.push({
                        label: item.nama,
                        value: item.id_instansi
                    });
                });
            })
            .catch((error) => {
                console.log(error);
            });
        },
        getDosens(id_topik) {
            axios.get(`${window.location.origin}/api/kmm/dosen/all/${id_topik}`)
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
        storeMagang() {
            this.$store.dispatch('showLoadingAlert');
            axios.post(`${window.location.origin}/api/kmm/magang/add/first_step`, this.addMagang)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil mendaftarkan magang!');
                this.getMagang();
                this.magang_validation = {
                    tahun: {
                        invalid: false,
                        feedback: ''
                    },
                    id_topik: {
                        invalid: false,
                        feedback: ''
                    }
                }
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    this.magang_validation = {
                        tahun: {
                            invalid: !!e.response.data.errors.tahun,
                            feedback: e.response.data.errors.tahun ? e.response.data.errors.tahun.join(' & ') : ""
                        },
                        id_topik: {
                            invalid: !!e.response.data.errors.id_topik,
                            feedback: e.response.data.errors.id_topik ? e.response.data.errors.id_topik.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal mendaftarkan magang!',
                        message: e.response.data.message
                    });
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${e.response.status}`,
                        message: e.response.data.message ?? e.response.data
                    });
                }
            });
        },
        askInstansi() {
            this.$store.dispatch('showLoadingAlert');
            axios.post(`${window.location.origin}/api/kmm/magang/add/second_step`, this.chooseInstansi)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil memilih instansi!');
                this.getMagang();
                this.chooseInstansi_validation = {
                    id_instansi: {
                        invalid: false,
                        feedback: ''
                    }
                }
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    this.chooseInstansi_validation = {
                        id_instansi: {
                            invalid: !!e.response.data.errors.id_instansi,
                            feedback: e.response.data.errors.id_instansi ? e.response.data.errors.id_instansi.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal memilih instansi!',
                        message: e.response.data.message
                    });
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${e.response.status}`,
                        message: e.response.data.message ?? e.response.data
                    });
                }
            });
        },
        storeInstansi() {
            this.$store.dispatch('showLoadingAlert');
            axios.post(`${window.location.origin}/api/kmm/instansi`, this.addInstansi)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menambahkan instansi!');
                this.chooseInstansi.id_instansi = response.data.data.id_instansi;
                this.askInstansi();
                this.getInstansis();
                this.addInstansi = {
                    nama: '',
                    email: '',
                    no_telp: '',
                    web: '',
                    alamat: ''
                }
                this.instansi_validation = {
                    nama: {
                        invalid: false,
                        feedback: ''
                    },
                    email: {
                        invalid: false,
                        feedback: ''
                    },
                    no_telp: {
                        invalid: false,
                        feedback: ''
                    },
                    web: {
                        invalid: false,
                        feedback: ''
                    },
                    alamat: {
                        invalid: false,
                        feedback: ''
                    }
                }
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    this.instansi_validation = {
                        nama: {
                            invalid: !!e.response.data.errors.nama,
                            feedback: e.response.data.errors.nama ? e.response.data.errors.nama.join(' & ') : ""
                        },
                        email: {
                            invalid: !!e.response.data.errors.email,
                            feedback: e.response.data.errors.email ? e.response.data.errors.email.join(' & ') : ""
                        },
                        no_telp: {
                            invalid: !!e.response.data.errors.no_telp,
                            feedback: e.response.data.errors.no_telp ? e.response.data.errors.no_telp.join(' & ') : ""
                        },
                        web: {
                            invalid: !!e.response.data.errors.web,
                            feedback: e.response.data.errors.web ? e.response.data.errors.web.join(' & ') : ""
                        },
                        alamat: {
                            invalid: !!e.response.data.errors.alamat,
                            feedback: e.response.data.errors.alamat ? e.response.data.errors.alamat.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal menambahkan instansi!',
                        message: e.response.data.message
                    });
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${e.response.status}`,
                        message: e.response.data.message ?? e.response.data
                    });
                }
            });
        },
        removeInstansi() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/kmm/magang/add/second_step/delete`)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menghapus instansi!');
                this.getMagang();
            })
            .catch((e) => {
                console.log('Error: ', e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        },
        storeRencana() {
            this.$store.dispatch('showLoadingAlert');
            axios.post(`${window.location.origin}/api/kmm/magang/add/third_step`, this.addRencana)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menambahkan rencana magang!');
                this.rencanas = response.data.data;
                this.addRencana = {
                    kegiatan: ''
                }
                this.rencana_validation = {
                    kegiatan: {
                        invalid: false,
                        feedback: ''
                    }
                }
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    this.rencana_validation = {
                        kegiatan: {
                            invalid: !!e.response.data.errors.kegiatan,
                            feedback: e.response.data.errors.kegiatan ? e.response.data.errors.kegiatan.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal menambahkan rencana magang!',
                        message: e.response.data.message
                    });
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${e.response.status}`,
                        message: e.response.data.message ?? e.response.data
                    });
                }
            });
        },
        removeRencana() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/kmm/magang/add/third_step`)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menghapus rencana magang!');
                if (response.data.data != null) {
                    this.rencanas = response.data.data;
                } else {
                    this.rencanas = [];
                }
            })
            .catch((e) => {
                console.log('Error: ', e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        },
        modalRencana(id_rencana) {
            let rencana = this.rencanas.find(rencana => rencana.id_rencana == id_rencana);
            this.editrencana = JSON.parse(JSON.stringify(rencana));
            console.log(this.editrencana);
            this.showEditModal = true;
        },
        closeModal() {
            this.showEditModal = false;
            this.editrencana = {
                minggu: '',
                kegiatan: ''
            }
            this.editrencana_validation = {
                kegiatan: {
                    invalid: false,
                    feedback: ''
                }
            }
        },
        updateRencana() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/kmm/magang/add/third_step/${this.editrencana.minggu}`, this.editrencana)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil mengubah rencana magang!');
                this.rencanas = response.data.data;
                this.closeModal();
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    this.editrencana_validation = {
                        kegiatan: {
                            invalid: !!e.response.data.errors.kegiatan,
                            feedback: e.response.data.errors.kegiatan ? e.response.data.errors.kegiatan.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal mengubah rencana magang!',
                        message: e.response.data.message
                    });
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${e.response.status}`,
                        message: e.response.data.message ?? e.response.data
                    });
                }
            });
        },
        storeDosen() {
            this.$store.dispatch('showLoadingAlert');
            axios.post(`${window.location.origin}/api/kmm/magang/add/fourth_step`, this.addDosen)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menambahkan dosen pembimbing!');
                this.getMagang();
                this.dosen_validation = {
                    id_dosen: {
                        invalid: false,
                        feedback: ''
                    }
                }
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    this.dosen_validation = {
                        id_dosen: {
                            invalid: !!e.response.data.errors.id_dosen,
                            feedback: e.response.data.errors.id_dosen ? e.response.data.errors.id_dosen.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal menambahkan dosen pembimbing!',
                        message: e.response.data.message
                    });
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${e.response.status}`,
                        message: e.response.data.message ?? e.response.data
                    });
                }
            });
        },
        updateDosen() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/kmm/magang/add/fourth_step`, this.editDosen)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil mengubah dosen pembimbing!');
                this.getMagang();
                this.editdosen_validation = {
                    id_dosen: {
                        invalid: false,
                        feedback: ''
                    }
                }
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    this.editdosen_validation = {
                        id_dosen: {
                            invalid: !!e.response.data.errors.id_dosen,
                            feedback: e.response.data.errors.id_dosen ? e.response.data.errors.id_dosen.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal mengubah dosen pembimbing!',
                        message: e.response.data.message
                    });
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${e.response.status}`,
                        message: e.response.data.message ?? e.response.data
                    });
                }
            });
        },
        removeDosen() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/kmm/magang/add/fourth_step/delete`)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menghapus dosen pembimbing!');
                this.getMagang();
            })
            .catch((e) => {
                console.log('Error: ', e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        },
        updateMagang() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/kmm/magang/add/first_step`, this.editMagang)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil mengubah data magang!');
                this.getMagang();
                this.editmagang_validation = {
                    tahun: {
                        invalid: false,
                        feedback: ''
                    },
                    id_topik: {
                        invalid: false,
                        feedback: ''
                    }
                }
            })
            .catch((e) => {
                if (e.response.status == 422) {
                    this.editmagang_validation = {
                        tahun: {
                            invalid: !!e.response.data.errors.tahun,
                            feedback: e.response.data.errors.tahun ? e.response.data.errors.tahun.join(' & ') : ""
                        },
                        id_topik: {
                            invalid: !!e.response.data.errors.id_topik,
                            feedback: e.response.data.errors.id_topik ? e.response.data.errors.id_topik.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal mengubah data magang!',
                        message: e.response.data.message
                    });
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${e.response.status}`,
                        message: e.response.data.message ?? e.response.data
                    });
                }
            });
        },
        deleteMagang() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/kmm/magang/add/first_step/delete`)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menghapus data magang!');
                this.magang = null;
            })
            .catch((e) => {
                console.log('Error: ', e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        }
    }
  }
  </script>
  