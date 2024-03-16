<template>
    <div>
      <CRow>
        <CCol :md="12">
          <CCard v-if="!havedokumen&&(isNotActived||isRejected)" class="mb-4">
            <CCardHeader component="h5" class="text-danger"><font-awesome-icon :icon="['fas', 'exclamation-triangle']" /> Pemberitahuan</CCardHeader>
            <CCardBody>
              <CCardTitle class="text-danger">Akun belum diaktifkan oleh admin</CCardTitle>
              <CCardText>Untuk menggunakan fitur-fitur yang tersedia, silahkan mengupload Kartu Rencana Studi (KRS) paling baru atau Kartu Mahasiswa dalam bentuk file pdf untuk diverifikasi oleh admin.</CCardText>
              <CForm class="row g-3" novalidate>
                <CCol md="4">
                    <CFormSelect
                        aria-label="Default select example"
                        :options="[
                            { label: 'Open this select menu', value: ''},
                            { label: 'Kartu Rencana Studi (KRS)', value: 'KRS' },
                            { label: 'Kartu Mahasiswa', value: 'KartuMahasiswa' }
                        ]"
                        v-model="form.type"
                        label="Tipe"
                        :feedback="form_validation.type.feedback"
                        :invalid="form_validation.type.invalid"
                        required>
                    </CFormSelect>
                </CCol>
                <CCol md="8">
                  <CFormInput
                    type="file"
                    label="File"
                    accept="application/pdf"
                    @change="handleFileUpload"
                    placeholder="KRS/Kartu Mahasiswa (.pdf)" required
                    :feedback="form_validation.file.feedback"
                    :invalid="form_validation.file.invalid"
                    id="file"
                  />
                </CCol>
                <CCol xs="12">
                    <CButton color="primary" @click="submitFile">Upload</CButton>
                </CCol>
              </CForm>
            </CCardBody>
          </CCard>
          <CCard v-if="havedokumen&&(isNotActived||isRejected)" class="mb-4">
            <CCardHeader v-if="isNotActived" component="h5" class="text-warning"><font-awesome-icon :icon="['fas', 'check-circle']" /> Pemberitahuan</CCardHeader>
            <CCardHeader v-else component="h5" class="text-danger"><font-awesome-icon :icon="['fas', 'exclamation-triangle']" /> Pemberitahuan</CCardHeader>
            <CCardBody>
              <CCardTitle v-if="isNotActived" class="text-warning">Akun menunggu diaktifkan oleh admin</CCardTitle>
              <CCardTitle v-else class="text-danger">Akun ditolak oleh admin</CCardTitle>
              <CCardText v-if="isNotActived">Terima kasih telah mengupload Kartu Rencana Studi (KRS) paling baru atau Kartu Mahasiswa. Admin akan segera memverifikasi file yang telah diupload. <a href="#" @click="openEditModal">Ajukan pembaruan dokumen?</a></CCardText>
              <CCardText v-else>Admin belum dapat mengaktifkan akun anda, mohon mengupload ulang Kartu Rencana Studi (KRS) paling baru atau Kartu Mahasiswa. Jika anda merasa ada kesalahan, silahkan hubungi segera. <a href="#" @click="openEditModal">Ajukan pembaruan dokumen?</a></CCardText>
              <div>
                Dokumen anda :
                <a :href="dokumen.link" target="_blank" v-for="dokumen in dokumens" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ dokumen.nama }}</a>
              </div>
            </CCardBody>
          </CCard>
          <div v-if="havedokumen&&isActived">
            <CCard class="mb-4">
                <CCardHeader component="h5" class="text-success"><font-awesome-icon :icon="['fas', 'check-circle']" /> Pemberitahuan</CCardHeader>
                <CCardBody>
                <CCardTitle class="text-success">Akun telah diaktifkan</CCardTitle>
                <CCardText>Anda dapat menggunakan fitur web ini sebagai mahasiswa.</CCardText>
                <div>
                    Dokumen anda :
                    <a :href="dokumen.link" target="_blank" v-for="dokumen in dokumens" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ dokumen.nama }}</a>
                </div>
                </CCardBody>
            </CCard>
            <CCard>
                <CCardHeader component="h5"><font-awesome-icon :icon="['fas', 'file']" /> Proposal TA</CCardHeader>
                <CCardBody>
                    <CCardTitle v-if="!haveproposal">Silahkan upload proposal TA</CCardTitle>
                    <CCardTitle v-else>Proposal TA sudah diupload</CCardTitle>
                    <CCardText><RouterLink :to="{ name: 'MahasiswaTaProposal' }">halaman Proposal TA</RouterLink></CCardText>
                </CCardBody>
            </CCard>
          </div>
        </CCol>
      </CRow>
        <CModal v-if="havedokumen&&(isNotActived||isRejected)" backdrop="static" :visible="showEditModal" @close="closeModal">
            <CModalHeader>
            <CModalTitle>Update atau tambah dokumen registrasi</CModalTitle>
            </CModalHeader>
            <CModalBody>
                <CForm class="row g-3" novalidate>
                    <CCol md="12">
                        <CFormSelect
                            aria-label="Default select example"
                            :options="[
                                { label: 'Open this select menu', value: ''},
                                { label: 'Kartu Rencana Studi (KRS)', value: 'KRS' },
                                { label: 'Kartu Mahasiswa', value: 'KartuMahasiswa' }
                            ]"
                            v-model="form.type"
                            label="Tipe"
                            :feedback="form_validation.type.feedback"
                            :invalid="form_validation.type.invalid"
                            required>
                        </CFormSelect>
                    </CCol>
                    <CCol md="12">
                    <CFormInput
                        type="file"
                        label="File"
                        accept="application/pdf"
                        @change="handleFileUpload"
                        placeholder="KRS/Kartu Mahasiswa (.pdf)"
                        :feedback="form_validation.file.feedback"
                        :invalid="form_validation.file.invalid"
                        id="fileedit"
                        required
                    />
                    </CCol>
                </CForm>
            </CModalBody>
            <CModalFooter>
                <CButton color="secondary" @click="closeModal">
                    Close
                </CButton>
                <CButton color="danger" @click="deleteFile">Hapus</CButton>
                <CButton color="primary" @click="submitFile">Upload</CButton>
            </CModalFooter>
        </CModal>
    </div>
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
   import Swal from 'sweetalert2';

  export default {
    name: 'Dashboard',
    components: {},
    data() {
      return {
        form: {
            type: '',
            file: null
        },
        form_validation: {
            type: {
                invalid: false,
                feedback: ''
            },
            file: {
                invalid: false,
                feedback: ''
            }
        },
        havedokumen: false,
        haveproposal: false,
        dokumens: null,
        user: {
            nim: '',
            status: 0
        },
        showEditModal: false,
      }
    },
    computed: {
        statusUser() {
            return this.user.status;
        },
        isActived() {
            return this.user.status > 0;
        },
        isNotActived() {
            return this.user.status == 0;
        },
        isRejected() {
            return this.user.status < 0;
        },
    },
    watch: {
        '$store.state.user': {
            immediate: true,
            handler(user) {
                if (user != null) {
                    this.user = user;
                    this.getDokumenRegistration();
                    if (this.isActived) {
                        this.getProposal();
                    }
                    this.echo();
                }
            }
        }
    },
    methods: {
        getDokumenRegistration() {
            axios.get(`${window.location.origin}/api/ta/dokumen_registrasi/`+this.user.nim).then(response => {
                this.havedokumen = !response.data.isEmpty;
                if (this.havedokumen) {
                    this.fillDokumen(response.data.data);
                }
            }).catch(e => {
                console.log('Error: ', e);
            });
        },
        getProposal() {
            axios.get(`${window.location.origin}/api/ta/proposal_ta`).then(response => {
                this.haveproposal = true;
            }).catch(e => {
                if (e.response.status === 404) {
                    this.haveproposal = false;
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', { title: `Error ${e.response.status}`, message: `${e.response.data.message ?? e.response.data}`});
                }
            }); 
        },
        echo() {
            console.log('Echoing...');
            Echo.private(`User.${this.user.nim}`).listen('DokReg', (e) => {
                this.havedokumen = !e.isEmpty;
                if (this.havedokumen) {
                    this.fillDokumen(e.item);
                }
            }).listen('Prop', (e) => {
                this.haveproposal = !e.isEmpty;
            });
        },
        openEditModal() {
            this.showEditModal = true
        },
        closeModal() {
            this.form = {
                type: '',
                file: ''
            }
            this.emptyFile('file');
            this.emptyFile('fileedit');
            this.showEditModal = false;
        },
        handleFileUpload( event ){
            this.form.file = event.target.files[0];
        },
        emptyFile(file) {
            if (document.getElementById(file)) {
                document.getElementById(file).value = '';
            }
        },
        submitFile(){
            let formData = new FormData();
            formData.append('file', this.form.file);
            formData.append('type', this.form.type);

            this.$store.dispatch('showLoadingAlert');

            axios.post(`${window.location.origin}/api/ta/dokumen_registrasi`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                console.log(response);
                this.$store.dispatch('showSuccessAlert', 'Berhasil mengupload file!');
                this.closeModal();
                this.form_validation = {
                    type: {
                        invalid: false,
                        feedback: ''
                    },
                    file: {
                        invalid: false,
                        feedback: ''
                    }
                }
            })
            .catch(e => {
                if (e.response.status === 422) {
                    this.form_validation = {
                        type: {
                            invalid: !!e.response.data.errors.type,
                            feedback: e.response.data.errors.type ? e.response.data.errors.type.join(' & ') : ""
                        },
                        file: {
                            invalid: !!e.response.data.errors.file,
                            feedback: e.response.data.errors.file ? e.response.data.errors.file.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal mengupload file!',
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
        deleteFile() {
            Swal.fire({
                title: `Hapus dokumen ${this.form.type != '' ? this.form.type : '(belum memilih tipe dokumen)'} ?`,
                showCancelButton: true,
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$store.dispatch('showLoadingAlert');
                    axios.put(`${window.location.origin}/api/ta/dokumen_registrasi/${this.user.nim}`, {
                        type: this.form.type
                    })
                    .then(response => {
                        console.log(response);
                        this.$store.dispatch('showSuccessAlert', 'Berhasil menghapus file!');
                        this.closeModal();
                    })
                    .catch(e => {
                        console.log('Error: ', e);
                        this.$store.dispatch('showErrorAlert', {
                            title: `Error ${e.response.status}`,
                            message: e.response.data.message ?? e.response.data
                        });
                    });
                }
            });
            
        },
        fillDokumen(data) {
              this.dokumens = [];
              if (data.krs != null) {
                  this.dokumens.push({
                      nama: 'KRS',
                      link: `${window.location.origin}/mahasiswa/dokumen-registrasi/krs/${data.token}/${data.krs}`
                  });
              }
              if (data.kartu_mahasiswa != null) {
                  this.dokumens.push({
                      nama: 'Kartu Mahasiswa',
                      link: `${window.location.origin}/mahasiswa/dokumen-registrasi/kartu-mahasiswa/${data.token}/${data.kartu_mahasiswa}`
                  });
              }
              if (data.transkrip != null) {
                  this.dokumens.push({
                      nama: 'Transkrip',
                      link: `${window.location.origin}/mahasiswa/dokumen-registrasi/transkrip/${data.token}/${data.transkrip}`
                  });
              }
              if (data.bukti_seminar != null) {
                  this.dokumens.push({
                      nama: 'Bukti Seminar',
                      link: `${window.location.origin}/mahasiswa/dokumen-registrasi/bukti-seminar/${data.token}/${data.bukti_seminar}`
                  });
              }
        }
    }
  }
  </script>
  