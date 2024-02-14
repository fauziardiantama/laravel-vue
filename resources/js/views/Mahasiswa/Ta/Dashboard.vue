<template>
    <div>
      <CRow>
        <CCol :md="12">
          <CCard v-if="!havedokumen&&!actived" class="mb-4">
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
                        v-model="type"
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
          <CCard v-if="havedokumen&&!actived" class="mb-4">
            <CCardHeader component="h5" class="text-warning"><font-awesome-icon :icon="['fas', 'check-circle']" /> Pemberitahuan</CCardHeader>
            <CCardBody>
              <CCardTitle class="text-warning">Akun menunggu diaktifkan oleh admin</CCardTitle>
              <CCardText>Terima kasih telah mengupload Kartu Rencana Studi (KRS) paling baru atau Kartu Mahasiswa. Admin akan segera memverifikasi file yang telah diupload. <a href="#" @click="openEditModal()">Ajukan pembaruan dokumen?</a></CCardText>
              <div>
                Dokumen anda :
                <a :href="dokumen.link" target="_blank" v-for="dokumen in dokumens" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ dokumen.nama }}</a>
              </div>
            </CCardBody>
          </CCard>
          <div v-if="havedokumen&&actived">
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
                    <CCardTitle v-if="!proposalexist">Silahkan upload proposal TA</CCardTitle>
                    <CCardTitle v-else>Proposal TA sudah diupload</CCardTitle>
                    <CCardText><RouterLink :to="{ name: 'MahasiswaTaProposal' }">halaman Proposal TA</RouterLink></CCardText>
                </CCardBody>
            </CCard>
          </div>
        </CCol>
      </CRow>
        <CModal v-if="havedokumen&&!actived" backdrop="static" :visible="showEditModal" @close="closeModal">
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
                            v-model="type"
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
                        placeholder="KRS/Kartu Mahasiswa (.pdf)" required
                        :feedback="form_validation.file.feedback"
                        :invalid="form_validation.file.invalid"
                        id="fileedit"
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
        file: null,
        type: null,
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
        dokumens: null,
        user: null,
        showEditModal: false,
        actived: false,
        proposalexist: false
      }
    },
    async created() {
        try {
            const user = await axios.get(`${window.location.origin}/api/user`);
            this.user = user.data;
            this.actived = user.data.user.status > 0;
            const response = await axios.get(`${window.location.origin}/api/ta/dokumen_registrasi/`+user.data.user.nim);
            this.havedokumen = !response.data.isEmpty;
            
            axios.get(`${window.location.origin}/api/ta/proposal_ta`).then(response => {
                this.proposalexist = true;
            }).catch(e => {
                if (e.response.status === 404) {
                    this.proposalexist = false;
                } else {
                    console.log('Error: ', e);
                    this.showErrorAlert(`Error ${e.response.status}`, e.response.data.message ?? e.response.data);
                }
            });
            if (this.havedokumen) {
                this.dokumens = [];
                if (response.data.data.krs != null) {
                    this.dokumens.push({
                        nama: 'KRS',
                        link: window.location.origin + '/mahasiswa/dokumen-registrasi/krs/' + response.data.data.krs
                    });
                }
                if (response.data.data.kartu_mahasiswa != null) {
                    this.dokumens.push({
                        nama: 'Kartu Mahasiswa',
                        link: window.location.origin + '/mahasiswa/dokumen-registrasi/kartu-mahasiswa/' + response.data.data.kartu_mahasiswa
                    });
                }
                if (response.data.data.transkrip != null) {
                    this.dokumens.push({
                        nama: 'Transkrip',
                        link: window.location.origin + '/mahasiswa/dokumen-registrasi/transkrip/' + response.data.data.transkrip
                    });
                }
                if (response.data.data.bukti_seminar != null) {
                    this.dokumens.push({
                        nama: 'Bukti Seminar',
                        link: window.location.origin + '/mahasiswa/dokumen-registrasi/bukti-seminar/' + response.data.data.bukti_seminar
                    });
                }
            }
        } catch (error) {
            console.log('Error:', error);
        }
    },
    mounted() {
        console.log(`this use is have dokumen (${this.havedokumen}) and actived (${this.actived})`)
        console.log('Component mounted.');
        Echo.channel('dokumen-registration').listen('DokumenRegistration', (e) => {
            if (this.user.token.id == e.token.id) {
                this.havedokumen = !e.isEmpty;
                if (this.havedokumen) {
                    this.dokumens = [];
                    if (e.item.krs != null) {
                        this.dokumens.push({
                            nama: 'KRS',
                            link: window.location.origin + '/mahasiswa/dokumen-registrasi/krs/' + e.item.krs
                        });
                    }
                    if (e.item.kartu_mahasiswa != null) {
                        this.dokumens.push({
                            nama: 'Kartu Mahasiswa',
                            link: window.location.origin + '/mahasiswa/dokumen-registrasi/kartu-mahasiswa/' + e.item.kartu_mahasiswa
                        });
                    }
                    if (e.item.transkrip != null) {
                        this.dokumens.push({
                            nama: 'Transkrip',
                            link: window.location.origin + '/mahasiswa/dokumen-registrasi/transkrip/' + e.item.transkrip
                        });
                    }
                    if (e.item.bukti_seminar != null) {
                        this.dokumens.push({
                            nama: 'Bukti Seminar',
                            link: window.location.origin + '/mahasiswa/dokumen-registrasi/bukti-seminar/' + e.item.bukti_seminar
                        });
                    }
                }
                console.log('Token matched');
            } else {
                console.log('Token not matched');
            }
        });
        Echo.channel('proposal-ta').listen('ProposalUpdated', (e) => {
            if (this.user.token.id == e.token.id) {
                this.proposalexist = !e.isEmpty;
                console.log('Token matched');
            } else {
                console.log('Token not matched');
            }
            console.log(e);
        });
    },
    methods: {
        tes() {
            console.log('tes');
        },
        openEditModal(item) {
            this.showEditModal = true
        },
        closeModal() {
            console.log('awal');
            this.file = null;
            this.type = '';
            console.log('tengah');
            //check if there's element with id fileedit
            if (document.getElementById('file')) {
                document.getElementById('file').value = '';
            }
            if (document.getElementById('fileedit')) {
                document.getElementById('fileedit').value = '';
            }
            this.showEditModal = false;
            console.log('akhir');
        },
        handleFileUpload( event ){
            this.file = event.target.files[0];
        },
        submitFile(){
            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('type', this.type);

            this.showLoadingAlert();

            axios.post(`${window.location.origin}/api/ta/dokumen_registrasi`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                console.log(response);
                this.showSuccessAlert('Berhasil mengupload file!');
                console.log('before close modal')
                this.closeModal();
                console.log('after close modal')
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
                console.log(this.form_validation);
            })
            .catch(e => {
                if (e.response.status === 422) {
                    // Handle login errors (e.g., display error messages)
                    console.log('Error:', e);
                    // Provide feedback to the user
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
                    this.showErrorAlert('Gagal mengupload file!', e.response.data.message);
                } else {
                    console.log('Error: ', e);
                    this.showErrorAlert(`Error ${e.response.status}`, e.response.data.message ?? e.response.data);
                }
            });
        },
        deleteFile() {
            Swal.fire({
                title: `Hapus dokumen ${this.type ?? '(belum memilih tipe dokumen)'} ?`,
                showCancelButton: true,
                confirmButtonText: "Save"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    this.showLoadingAlert();
                    axios.put(`${window.location.origin}/api/ta/dokumen_registrasi/${this.user.user.nim}`, {
                        type: this.type
                    })
                    .then(response => {
                        console.log(response);
                        this.showSuccessAlert('Berhasil menghapus file!');
                        this.closeModal();
                    })
                    .catch(e => {
                        console.log('Error: ', e);
                        this.showErrorAlert(`Error ${e.response.status}`, e.response.data.message ?? e.response.data);
                    });
                }
            });
            
        },
        showLoadingAlert() {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
        },
        showSuccessAlert(message) {
            Swal.fire({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                icon: "success",
                title: message
            });
        },
        showErrorAlert(message, error) {
            Swal.fire({
                title: error,
                text: message,
                icon: 'error'
            });
        },
    }
  }
  </script>
  