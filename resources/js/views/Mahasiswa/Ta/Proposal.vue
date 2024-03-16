<template>
  <div>
    <CRow>
      <CCol md="6">
        <CCard class="mb-4">
          <CCardHeader>Upload Proposal TA</CCardHeader>
          <CCardBody>
            <CForm class="g-3" novalidate>
              <CRow class="mb-3">
                <CCol md="12">
                  <CFormInput
                  label="Judul"
                  type="text"
                  placeholder="Judul Proposal"
                  v-model="form.judul"
                  :feedback="form_validation.judul.feedback"
                  :invalid="form_validation.judul.invalid"
                  required/>
                </CCol>
              </CRow>
              <CRow class="mb-3">
                <CCol md="4">
                  <CFormSelect
                    :options="options"
                    v-model="form.tahun"
                    label="Tahun"
                    :feedback="form_validation.tahun.feedback"
                    :invalid="form_validation.tahun.invalid"
                    required>
                  </CFormSelect>
                </CCol>
                <CCol md="8">
                  <CFormSelect
                    :options="semesteroptions"
                    v-model="form.semester_id"
                    label="Semester"
                    :feedback="form_validation.semester.feedback"
                    :invalid="form_validation.semester.invalid"
                    required>
                  </CFormSelect>
                </CCol>
              </CRow>
              <CRow class="mb-5">
                <CCol md="12">
                  <CFormInput
                      type="file"
                      label="File"
                      accept="application/pdf"
                      placeholder="Proposal TA (.pdf)" required
                      @change="handleFileUpload"
                      :feedback="form_validation.file.feedback"
                      :invalid="form_validation.file.invalid"
                      id="file"
                    />
                </CCol>
              </CRow>
              <CCol class="text-center">
                <CButton v-if="!done&&(fileData == null || fileData.status_proposal < 1)" color="primary" class="w-50" @click="submitFile">Upload</CButton>
                <CButton v-if="done&&fileData.status_proposal < 1" color="primary" class="w-25 left" @click="updateFile">Update</CButton>
                <CButton v-if="done&&fileData.status_proposal < 1" color="danger" class="w-25 right" @click="deleteFile">Delete</CButton>
              </CCol>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol md="6">
        <CCard class="mb-4">
          <CCardHeader> Status Proposal </CCardHeader>
          <CCardBody>
            <h5 v-if="!done" class="text-center text-danger">Belum upload proposal</h5>
            <h5 v-if="done" class="text-center text-success">Proposal sudah diupload</h5>
            <div v-if="done">
              <CRow>
                <CCol md="3">Judul</CCol>
                <CCol md="9">: {{ fileData.judul_proposal }}</CCol>
              </CRow>
              <CRow>
                <CCol md="3">Tahun</CCol>
                <CCol md="9">: {{ fileData.tahun }}</CCol>
              </CRow>
              <CRow>
                <CCol md="3">Semester</CCol>
                <CCol md="9">: {{ fileData.semester_id == 1 ? 'Ganjil' : 'Genap' }}</CCol>
              </CRow>
              <CRow>
                <CCol md="3">File</CCol>
                <CCol md="9">
                  : <a :href="`${app}/mahasiswa/ta/proposal-ta/${fileData.token}/${fileData.file_proposal}`" target="_blank" >Lihat</a>
                </CCol>
              </CRow>
              <CRow>
                <CCol md="3">Status</CCol>
                <CCol md="9">: {{ fileData.status_proposal > 0 ? 'disetujui' : (fileData.status_proposal < 0 ? 'ditolak' : 'menunggu persetujuan') }}</CCol>
              </CRow>
            </div>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </div>
</template>

<style scoped>
  .left {
    margin-right: 10px;
  }
  .right {
    margin-left: 10px;
  }
</style>

<script>
export default {
  name: 'Proposal',
  components: {},
  data() {
    return {
      form: {
        tahun: '',
        semester_id: '',
        judul: '',
        file: ''
      },
      options: [
        { label: 'Pilih Tahun', value: ''}
      ],
      semesteroptions: [
        { label: 'Pilih Semester', value: ''},
        { label: 'Ganjil', value: 1},
        { label: 'Genap', value: 2}
      ],
      form_validation: {
        judul: {
          feedback: '',
          invalid: false
        },
        tahun: {
          feedback: '',
          invalid: false
        },
        file: {
          feedback: '',
          invalid: false
        },
        semester: {
          feedback: '',
          invalid: false
        }
      },
      done: false,
      app: window.location.origin,
      fileData: null
    }
  },
  watch: {
        '$store.state.user': {
            immediate: true,
            handler(user) {
                if (user != null) {
                  Echo.private(`Mahasiswa`)
                  .listen('Thn', (e) => {
                    console.log({
                      event: "Thn",
                      data: e
                    })
                    this.options = e.item.map((tahun) => {
                      return { label: tahun.tahun, value: tahun.tahun.toString() }
                    });

                    this.form.tahun = this.fileData ? this.fileData.tahun : this.options[0].value;
                  });
                  Echo.private(`User.${user.nim}`)
                  .listen('Prop', (e) => {
                      this.done = !e.isEmpty;
                      if (this.done) {
                        this.fileData = e.item;
                        console.log('File data: ', this.fileData);
                        this.form.judul = this.fileData ? this.fileData.judul_proposal : '';
                        this.form.tahun = this.fileData ? this.fileData.tahun : this.options[0].value;
                        this.form.semester_id = this.fileData ? this.fileData.semester_id : this.semesteroptions[0].value;
                      } else {
                        this.fileData = null;
                        this.judul = '';
                        this.tahun = this.options[0].value;
                        this.emptyFile('file');
                      }
                  });

                }
            }
        }
  },
  created() {
      axios.get(`${this.app}/api/ta/tahun`).then((tahun) => {
        this.options = tahun.data.data.map((tahun) => {
          return { label: tahun.tahun, value: tahun.tahun.toString() }
        });
        this.form.tahun = this.options[0].value;
      }).catch((error) => {
        console.log(error);
      });
      axios.get(`${this.app}/api/ta/proposal_ta`).then((response) => {
        this.done = response.data.data ? true : false;
        console.log(this.done);
        this.fileData = response.data.data;
        this.form.judul = this.fileData ? this.fileData.judul_proposal : '';
        this.form.tahun = this.fileData ? this.fileData.tahun : this.options[0].value;
        this.form.semester_id = this.fileData ? this.fileData.semester_id : this.semesteroptions[0].value;
      }).catch((error) => {
        if (error.response.status === 404) {
          this.done = false;
          this.fileData = null;
          console.log(error.response.data);
        } else {
          console.log(error);
        }
      });
  },
  methods: {
    submitFile() {
      let formData = new FormData();
      formData.append('file_proposal', this.form.file);
      formData.append('judul_proposal', this.form.judul);
      formData.append('tahun', this.form.tahun);
      formData.append('semester_id', this.form.semester_id);

      this.$store.dispatch('showLoadingAlert');
      axios.post(`${this.app}/api/ta/proposal_ta`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item uploaded successfully!');
        this.fileData = response.data.data;
        this.done = true;
        this.form_validation = {
          judul: {
            feedback: '',
            invalid: false
          },
          tahun: {
            feedback: '',
            invalid: false
          },
          file: {
            feedback: '',
            invalid: false
          },
          semester: {
            feedback: '',
            invalid: false
          }
        }
        document.getElementById('file').value = '';
      })
      .catch(error => {
        if (error.response.status === 422) {
          console.log('Error: ', error.response.data);
          this.form_validation = {
            judul: {
              invalid: !!error.response.data.errors.judul_proposal,
              feedback: error.response.data.errors.judul_proposal ? error.response.data.errors.judul_proposal.join(' & ') : ""
            },
            file: {
              invalid: !!error.response.data.errors.file_proposal,
              feedback: error.response.data.errors.file_proposal ? error.response.data.errors.file_proposal.join(' & ') : ""
            },
            tahun: {
              invalid: !!error.response.data.errors.tahun,
              feedback: error.response.data.errors.tahun ? error.response.data.errors.tahun.join(' & ') : ""
            },
            semester: {
              invalid: !!error.response.data.errors.semester_id,
              feedback: error.response.data.errors.semester_id ? error.response.data.errors.semester_id.join(' & ') : ""
            }
          }
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengupload file!',
            message: error.response.data.message
          });
        } else {
          console.log('Error: ', error);
          this.$store.dispatch('showErrorAlert', {title: `Error ${error.response.status}`, message: error.response.data.message ?? error.response.data});
        }
      });
    },
    updateFile() {
      let formData = new FormData();
      if (this.file) {
        formData.append('file_proposal', this.form.file);
      }
      formData.append('judul_proposal', this.form.judul);
      formData.append('tahun', this.form.tahun);
      formData.append('semester_id', this.form.semester_id);
      
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${this.app}/api/ta/proposal_ta/${this.fileData.nim}/update`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item updated successfully!');
        this.fileData = response.data.data;
        this.done = true;
        this.form_validation = {
          judul: {
            feedback: '',
            invalid: false
          },
          tahun: {
            feedback: '',
            invalid: false
          },
          file: {
            feedback: '',
            invalid: false
          },
          semester: {
            feedback: '',
            invalid: false
          }
        }
        this.emptyFile('file');
      })
      .catch(error => {
        if (error.response.status === 422) {
          console.log('Error: ', error.response.data);
          this.form_validation = {
            judul: {
              invalid: !!error.response.data.errors.judul_proposal,
              feedback: error.response.data.errors.judul_proposal ? error.response.data.errors.judul_proposal.join(' & ') : ""
            },
            file: {
              invalid: !!error.response.data.errors.file_proposal,
              feedback: error.response.data.errors.file_proposal ? error.response.data.errors.file_proposal.join(' & ') : ""
            },
            tahun: {
              invalid: !!error.response.data.errors.tahun,
              feedback: error.response.data.errors.tahun ? error.response.data.errors.tahun.join(' & ') : ""
            },
            semester: {
              invalid: !!error.response.data.errors.semester_id,
              feedback: error.response.data.errors.semester_id ? error.response.data.errors.semester_id.join(' & ') : ""
            }
          }
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengupdate file!',
            message: error.response.data.message
          });
        } else {
          console.log('Error: ', error);
          this.$store.dispatch('showErrorAlert', {title: `Error ${error.response.status}`, message: error.response.data.message ?? error.response.data});
        }
      });
    },
    deleteFile() {
      this.$store.dispatch('showLoadingAlert');
      axios.delete(`${this.app}/api/ta/proposal_ta/${this.fileData.nim}`)
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item deleted successfully!');
        this.fileData = null;
        this.judul = '';
        this.tahun = this.options[0].value;
        document.getElementById('file').value = '';
        this.done = false;
      })
      .catch(error => {
        console.log('Error: ', error);
        this.$store.dispatch('showErrorAlert', {title: `Error ${error.response.status}`, message: error.response.data.message ?? error.response.data});
      });
    },
    handleFileUpload( event ){
      this.form.file = event.target.files[0];
    },
    emptyFile(file) {
      if (document.getElementById(file)) {
        document.getElementById(file).value = '';
      }
    },
  }
}
</script>
