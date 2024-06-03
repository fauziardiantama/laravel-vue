<template>
    <div>
        <CCard class="mb-4">
            <CCardHeader component="h5">Data seminar</CCardHeader>
            <CCardBody v-if="bimbins_min_five && bimbdos_min_five && have_diterima && have_approved_dosen && seminar_is_null">
                <CCardText class="mb-5">Daftar seminar</CCardText>
                <div>
                    <CForm class="row g-3" novalidate>
                        <CRow class="mb-3">
                            <CCol :md="12">
                                <CFormInput type="text" label="Judul KMM" placeholder="Judul KMM"
                                    v-model="judul_kmm"
                                    :feedback="form_validation.judul_kmm.feedback"
                                    :invalid="form_validation.judul_kmm.invalid"
                                />
                            </CCol>
                        </CRow>
                        <CRow class="mb-3">
                            <CCol :md="6">
                                <CFormInput type="file" label="Laporan kmm" accept="application/pdf" placeholder="File"
                                    @change="handleFileDraftKmm"
                                    :feedback="form_validation.draft_kmm.feedback"
                                    :invalid="form_validation.draft_kmm.invalid"
                                />
                            </CCol>
                            <CCol :md="6">
                                <CFormInput type="file" label="Foto Kegiatan" placeholder="File"
                                    @change="handleFileFoto"
                                    :feedback="form_validation.foto.feedback"
                                    :invalid="form_validation.foto.invalid"
                                />
                            </CCol>
                        </CRow>
                        <CRow class="mb-3">
                            <CCol :md="12">
                                <CFormInput type="file" label="KRS" accept="application/pdf" placeholder="File"
                                    @change="handleFileKrs"
                                    :feedback="form_validation.krs.feedback"
                                    :invalid="form_validation.krs.invalid"
                                />
                            </CCol>
                        </CRow>
                    </CForm>
                </div>
            </CCardBody>
            <CCardBody v-if="bimbins_min_five && bimbdos_min_five && have_diterima && have_approved_dosen && !seminar_is_null">
                <!-- show all data from form above not as input -->
                <CCardText class="mb-5">Daftar seminar</CCardText>
                <div>
                    <CRow class="mb-3">
                        <CCol :md="4">
                            Judul KMM
                        </CCol>
                        <CCol :md="8">
                            <div v-if="edit_seminar">
                                <CFormInput type="text" placeholder="Judul KMM"
                                    v-model="edit.judul_kmm"
                                    :feedback="edit_validation.judul_kmm.feedback"
                                    :invalid="edit_validation.judul_kmm.invalid"
                                />
                            </div>
                            <div v-else>
                                {{ seminar?.judul_kmm }}
                            </div>
                        </CCol>
                    </CRow>
                    <CRow class="mb-3">
                        <CCol :md="4">
                            Tanggal Seminar
                        </CCol>
                        <CCol :md="8">
                            <div>
                                {{ seminar?.tgl_seminar ?? '-' }}
                            </div>
                        </CCol>
                    </CRow>
                    <CRow class="mb-3">
                        <CCol :md="4">
                            Ruangan
                        </CCol>
                        <CCol :md="8">
                            {{ seminar?.ruangan?.nama ?? '-' }}
                        </CCol>
                    </CRow>
                    <CRow class="mb-3">
                        <CCol :md="4">
                            Penguji
                        </CCol>
                        <CCol :md="8">
                            <!-- list penguji -->
                            <div v-if="seminar?.penguji.length > 0">
                                <div v-for="(penguji, index) in seminar.penguji" :key="index">
                                    <div>- {{ penguji.nama }}</div>
                                </div>
                            </div>
                            <div v-else>
                                <div>-</div>
                            </div>
                        </CCol>
                    </CRow>
                    <CRow class="mb-3">
                        <CCol :md="4">
                            Laporan kmm
                        </CCol>
                        <CCol :md="8">
                            <div v-if="edit_seminar">
                                <CFormInput type="file" accept="application/pdf" placeholder="File"
                                    @change="handleFileDraftKmmEdit"
                                    :feedback="edit_validation.draft_kmm.feedback"
                                    :invalid="edit_validation.draft_kmm.invalid"
                                />
                            </div>
                            <div v-else>
                                <a :href="`${app}/mahasiswa/magang/seminar/draft-kmm/${seminar?.draft_kmm}`" target="_blank">Download</a>
                            </div>
                        </CCol>
                    </CRow>
                    <CRow class="mb-3">
                        <CCol :md="4">
                            Foto Kegiatan
                        </CCol>
                        <CCol :md="8">
                            <div v-if="edit_seminar">
                                <CFormInput type="file" placeholder="File"
                                    @change="handleFileFotoEdit"
                                    :feedback="edit_validation.foto.feedback"
                                    :invalid="edit_validation.foto.invalid"
                                />
                            </div>
                            <div v-else>
                                <a :href="`${app}/mahasiswa/magang/seminar/foto/${seminar?.foto}`" target="_blank">Download</a>
                            </div>
                        </CCol>
                    </CRow>
                    <CRow class="mb-3">
                        <CCol :md="4">
                            KRS
                        </CCol>
                        <CCol :md="8">
                            <div v-if="edit_seminar">
                                <CFormInput type="file" accept="application/pdf" placeholder="File"
                                    @change="handleFileKrsEdit"
                                    :feedback="edit_validation.krs.feedback"
                                    :invalid="edit_validation.krs.invalid"
                                />
                            </div>
                            <div v-else>
                                <a :href="`${app}/mahasiswa/magang/seminar/krs/${seminar?.krs}`" target="_blank">Download</a>
                            </div>
                        </CCol>
                    </CRow>
                    <CRow class="mb-3">
                        <CCol :md="4">
                            Status
                        </CCol>
                        <CCol :md="8">
                            {{ seminar?.status }}
                        </CCol>
                    </CRow>
                </div>
            </CCardBody>
            <CCardBody v-if="!have_diterima || !have_approved_dosen">
                <CCardText class="text-center">Anda belum diterima oleh instansi atau belum memiliki dosen pembimbing</CCardText>
            </CCardBody>
            <CCardBody v-if="!bimbins_min_five || !bimbdos_min_five">
                <CCardText class="text-center">Anda belum memiliki minimal 5x bimbingan dari instansi dan dosen</CCardText>
            </CCardBody>
            <CCardFooter v-if="bimbins_min_five && bimbdos_min_five && have_diterima && have_approved_dosen">
                <CButton v-if="seminar_is_null" color="primary" class="m-2" @click="storeSeminar">Submit</CButton>
                <CButton v-if="!seminar_is_null&&edit_seminar" color="primary" class="m-2" @click="updateSeminar">Update</CButton>
                <CButton v-if="!seminar_is_null&&!edit_seminar" color="primary" class="m-2" @click="editt(true)">Edit</CButton>
                <CButton v-if="!seminar_is_null&&edit_seminar" color="primary" class="m-2" @click="editt(false)">Cancel</CButton>
                <CButton v-if="!seminar_is_null" color="danger" class="m-2" @click="deleteSeminar">Delete</CButton>
            </CCardFooter>
            <CCardFooter v-else>
            </CCardFooter>
        </CCard>
    </div>
</template>

<style scoped>
.custom-height {
    height: 400px;
    overflow: auto;
}
</style>

<script>
export default {
    name: "Seminar",
    data() {
        return {
            seminar: null,
            edit: {
                judul_kmm: "",
                draft_kmm: null,
                krs: null,
                foto: null,
            },
            judul_kmm: "",
            draft_kmm: null,
            krs: null,
            foto: null,
            magang: null,
            edit_seminar: false,
            form_validation: {
                judul_kmm: {
                    invalid: false,
                    feedback: ""
                },
                draft_kmm: {
                    invalid: false,
                    feedback: ""
                },
                krs: {
                    invalid: false,
                    feedback: ""
                },
                foto: {
                    invalid: false,
                    feedback: ""
                },
            },
            edit_validation: {
                judul_kmm: {
                    invalid: false,
                    feedback: ""
                },
                draft_kmm: {
                    invalid: false,
                    feedback: ""
                },
                krs: {
                    invalid: false,
                    feedback: ""
                },
                foto: {
                    invalid: false,
                    feedback: ""
                },
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
                    this.getSeminar();
                }
            }
        },
    },
    computed: {
        have_diterima() {
            return this.magang?.status_diterima_instansi == 1;
        },
        have_approved_dosen() {
            return this.magang?.status_dosen == 1;
        },
        seminar_is_null() {
            return this.seminar == null || this.seminar == undefined;
        },
        bimbdos_min_five() {
            return this.magang?.bimbingan_dosen.length >= 5;
        },
        bimbins_min_five() {
            return this.magang?.bimbingan_instansi.length >= 5;
        }
    },
    methods: {
        editt(bool){
            this.edit_seminar = bool;
        },
        handleFileDraftKmm(e) {
            this.draft_kmm = e.target.files[0];
        },
        handleFileKrs(e) {
            this.krs = e.target.files[0];
        },
        handleFileFoto(e) {
            this.foto = e.target.files[0];
        },
        handleFileDraftKmmEdit(e) {
            this.edit.draft_kmm = e.target.files[0];
        },
        handleFileKrsEdit(e) {
            this.edit.krs = e.target.files[0];
        },
        handleFileFotoEdit(e) {
            this.edit.foto = e.target.files[0];
        },
        getMagang() {
            axios.get(`${this.app}/api/kmm/magang`).then( response => {
                this.magang = response.data.data;
                console.log(this.magang);
            }).catch( e => {
                console.log(e);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${e.response.status}`,
                    message: e.response.data.message ?? e.response.data
                });
            });
        },
        getSeminar() {
            axios.get(`${this.app}/api/kmm/seminar`).then( response => {
                this.seminar = response.data.data;
                if (this.seminar != null) {
                    this.edit.judul_kmm = this.seminar.judul_kmm;
                    this.edit.tgl_seminar = this.seminar.tgl_seminar;
                }
            }).catch( e => {
                console.log(e);
            });
        },
        storeSeminar() {
            let formData = new FormData();
            formData.append('judul_kmm', this.judul_kmm);
            formData.append('draft_kmm', this.draft_kmm);
            formData.append('krs', this.krs);
            formData.append('foto', this.foto);
            this.$store.dispatch('showLoadingAlert');

            axios.post(`${this.app}/api/kmm/seminar`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then( response => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil mengupload file!');
                this.getSeminar();
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
        updateSeminar() {
            let formData = new FormData();
            formData.append('judul_kmm', this.edit.judul_kmm);
            formData.append('draft_kmm', this.edit.draft_kmm);
            formData.append('krs', this.edit.krs);
            formData.append('foto', this.edit.foto);
            this.$store.dispatch('showLoadingAlert');

            axios.post(`${this.app}/api/kmm/seminar/update`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then( response => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil mengupdate file!');
                this.getSeminar();
                this.edit_seminar = false;
                console.log(response);
            }).catch( e => {
                console.log(e);
                if (e.response.status === 422) {
                    this.edit_validation = {
                        judul_kmm: {
                            invalid: !!e.response.data.errors.judul_kmm,
                            feedback: e.response.data.errors.judul_kmm ? e.response.data.errors.judul_kmm.join(' & ') : ""
                        },
                        tgl_seminar: {
                            invalid: !!e.response.data.errors.tgl_seminar,
                            feedback: e.response.data.errors.tgl_seminar ? e.response.data.errors.tgl_seminar.join(' & ') : ""
                        },
                        draft_kmm: {
                            invalid: !!e.response.data.errors.draft_kmm,
                            feedback: e.response.data.errors.draft_kmm ? e.response.data.errors.draft_kmm.join(' & ') : ""
                        },
                        krs: {
                            invalid: !!e.response.data.errors.krs,
                            feedback: e.response.data.errors.krs ? e.response.data.errors.krs.join(' & ') : ""
                        },
                        foto: {
                            invalid: !!e.response.data.errors.foto,
                            feedback: e.response.data.errors.foto ? e.response.data.errors.foto.join(' & ') : ""
                        }
                    }
                }
                this.$store.dispatch('showErrorAlert', {
                    title: 'Gagal mengupdate file!',
                    message: e.response.data.message
                });
            }); 
        },
        deleteSeminar() {
            this.$store.dispatch('showLoadingAlert');
            axios.delete(`${this.app}/api/kmm/seminar`).then( response => {
                this.$store.dispatch('showSuccessAlert', 'Berhasil menghapus file!');
                this.getSeminar();
                this.edit_seminar = false;
                console.log(response);
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