<template>
    <div>
      <CRow>
        <CCol :md="6">
            <CCard class="mb-4">
                <CCardHeader component="h5">Dokumen Jawaban</CCardHeader>
                <CCardBody class="custom-height">
                    <CCardText class="mb-5">Dapatkan dokumen jawaban dari instansi</CCardText>
                    <div v-if="have_jawaban">
                        <CRow class="mb-3">
                            <CCol :md="5">
                                File surat
                            </CCol>
                            <CCol :md="7">
                                <a :href="`${app}/mahasiswa/magang/surat-jawaban/${surat_jawaban?.token}/${surat_jawaban?.file_surat}`" target="_blank">Download</a>
                            </CCol>
                        </CRow>
                    </div>
                    <div v-else>
                        <CForm class="row g-3" novalidate>
                            <CRow class="mb-3">
                                <CCol :md="12">
                                    <CFormInput type="file" label="File surat" accept="application/pdf" placeholder="File"
                                        @change="handleFileJawaban"
                                        :feedback="jawaban_validation.file_surat.feedback"
                                        :invalid="jawaban_validation.file_surat.invalid"
                                    />
                                </CCol>
                            </CRow>
                        </CForm>
                    </div>
                </CCardBody>
                <CCardFooter>
                    <CButton v-if="!have_jawaban" color="primary" class="m-2" @click="storeJawaban">Submit</CButton>
                    <CButton v-if="have_jawaban&&!have_diterima" color="primary" class="m-2" @click="openModalJawaban">Edit</CButton>
                    <CButton v-if="have_jawaban&&!have_diterima" color="danger" class="m-2" @click="deleteJawaban">Delete</CButton>
                </CCardFooter>
            </CCard>
        </CCol>
        <CCol :md="6">
            <CCard class="mb-4">
                <CCardHeader component="h5">Dokumen Serah Terima</CCardHeader>
                <CCardBody class="custom-height">
                    <CCardText class="mb-5">Buat dokumen serah terima</CCardText>
                    <div v-if="have_diterima">
                        <div>
                            <p>Download <a :href="`${app}/mahasiswa/magang/template-serah-terima`" target="_blank">template</a> atau</p>
                            <CButton color="primary" class="m-2" @click="generateSerahTerima">Generate dokumen</CButton>
                            <p>*Mungkin membutuhkan waktu</p>
                            <a id="download" class="d-none" href="#" target="_blank">Klik disini jika belum otomatis mendownload</a>
                        </div>
                    </div>
                    <div v-else-if="have_jawaban">
                        <CCardText class="mb-5">Menunggu verifikasi admin</CCardText>
                    </div>
                    <div v-else>
                        <CCardText class="mb-5">Anda belum menambahkan dokumen jawaban</CCardText>
                    </div>
                </CCardBody>
                <CCardFooter>
                </CCardFooter>
            </CCard>
        </CCol>
      </CRow>
    </div>
    <CModal backdrop="static" :visible="showEditJawaban" @close="closeModal">
            <CModalHeader>
                <CModalTitle>Update atau tambah dokumen registrasi</CModalTitle>
            </CModalHeader>
            <CModalBody>
                <CForm class="row g-3" novalidate>
                    <CRow class="mb-3">
                        <CCol :md="12">
                            <CFormInput type="file" label="File surat" accept="application/pdf" placeholder="File"
                                @change="handleFileJawabanEdit"
                                :feedback="jawabanedit_validation.file_surat.feedback"
                                :invalid="jawabanedit_validation.file_surat.invalid"
                            />
                        </CCol>
                    </CRow>
                </CForm>
            </CModalBody>
            <CModalFooter>
                <CButton color="secondary" @click="closeModal">
                    Close
                </CButton>
                <CButton color="primary" @click="updateJawaban">Update</CButton>
            </CModalFooter>
    </CModal>
</template>

<style scoped>
.custom-height {
    height: 400px;
    overflow: auto;
}
</style>

<script>
export default {
    name: "Dokumen",
    data() {
        return {
            magang: null,
            showEditJawaban: false,
            have_jawaban: false,
            surat_jawaban: null,
            jawabanedit: {
                file_surat: ""
            },
            jawabanedit_validation: {
                file_surat: {
                    invalid: false,
                    feedback: ""
                }
            },
            jawaban: {
                file_surat: ""
            },
            jawaban_validation: {
                file_surat: {
                    invalid: false,
                    feedback: ""
                }
            },
            app: window.location.origin,
            user: null
        }
    },
    watch: {
        '$store.state.user': {
            immediate: true,
            handler(user) {
                if (user != null) {
                    this.user = user;
                    this.getMagang();
                    this.getSurat();
                }
            }
        },
    },
    computed: {
        have_diterima() {
            return this.magang?.status_diterima_instansi == 1;
        }
    },
    methods: {
        getMagang() {
            axios.get(`${this.app}/api/kmm/magang`).then( response => {
                this.magang = response.data.data;
            }).catch( e => {
                console.log(e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        },
        getSurat() {
            axios.get(`${this.app}/api/kmm/surat`).then( response => {
                this.have_jawaban = response.data.data.surat_jawaban != null ? true : false;
                if (this.have_jawaban) {
                    this.surat_jawaban = response.data.data.surat_jawaban;
                }
            }).catch( e => {
                console.log(e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        },
        handleFileJawaban( event ){
            this.jawaban.file_surat = event.target.files[0];
        },
        handleFileJawabanEdit( event ){
            this.jawabanedit.file_surat = event.target.files[0];
        },
        storeJawaban(){
            let formData = new FormData();
            formData.append('file_surat', this.jawaban.file_surat);
            this.$store.dispatch('showLoadingAlert');

            axios.post(`${this.app}/api/kmm/surat_jawaban`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then( response => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil mengupload file!');
                this.getSurat();
                console.log(response);
            }).catch( e => {
                console.log(e);
                if (e.response.status === 422) {
                    this.jawaban_validation = {
                        file_surat: {
                            invalid: !!e.response.data.errors.file_surat,
                            feedback: e.response.data.errors.file_surat ? e.response.data.errors.file_surat.join(' & ') : ""
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
        openModalJawaban(){
            this.jawabanedit = {
                file_surat: ""
            }
            this.showEditJawaban = true;
        },
        closeModal(){
            //clear all value and validation
            this.pengantaredit = {
                nomor_surat: "",
                file_surat: ""
            }
            this.pengantaredit_validation = {
                nomor_surat: {
                    invalid: false,
                    feedback: ""
                },
                file_surat: {
                    invalid: false,
                    feedback: ""
                }
            }
            this.serahterimaedit = {
                nomor_surat: "",
                file_surat: ""
            }
            this.serahterimaedit_validation = {
                nomor_surat: {
                    invalid: false,
                    feedback: ""
                },
                file_surat: {
                    invalid: false,
                    feedback: ""
                }
            }
            this.jawabanedit = {
                file_surat: ""
            }
            this.jawabanedit_validation = {
                file_surat: {
                    invalid: false,
                    feedback: ""
                }
            }
            this.showEditPengantar = false;
            this.showEditJawaban = false;
            this.showEditSerahTerima = false;
        },
        updateJawaban() {
            this.$store.dispatch('showLoadingAlert');
            let formData = new FormData();
            formData.append('file_surat', this.jawabanedit.file_surat);
            axios.post(`${this.app}/api/kmm/surat_jawaban/update`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then( response => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil mengupdate file!');
                this.getSurat();
                console.log(response);
            }).catch( e => {
                console.log(e);
                if (e.response.status === 422) {
                    this.jawabanedit_validation = {
                        file_surat: {
                            invalid: !!e.response.data.errors.file_surat,
                            feedback: e.response.data.errors.file_surat ? e.response.data.errors.file_surat.join(' & ') : ""
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal mengupdate file!',
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
        deleteJawaban() {
            this.$store.dispatch('showLoadingAlert');
            axios.delete(`${this.app}/api/kmm/surat_jawaban`).then( response => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menghapus file!');
                this.getSurat();
                console.log(response);
            }).catch( e => {
                console.log(e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        },
        generateSerahTerima() {
            this.$store.dispatch('showLoadingAlert');
            axios.get(`${this.app}/api/kmm/surat_magang/generate_serah_terima`).then( response => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil membuat dokumen serah terima!');
                console.log(response);
                window.open(`${this.app}/mahasiswa/magang/surat-serah-terima-2024/${response.data.data}`, '_blank');
                //get link id download and remove class d-none and add href
                document.getElementById('download').classList.remove('d-none');
                document.getElementById('download').setAttribute('href', `${this.app}/mahasiswa/magang/surat-serah-terima-2024/${response.data.data}`);
            }).catch( e => {
                console.log(e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        }
    }
}
</script>