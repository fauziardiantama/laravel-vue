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
                  v-model="judul"
                  :feedback="form_validation.judul.feedback"
                  :invalid="form_validation.judul.invalid"
                  required/>
                </CCol>
              </CRow>
              <CRow class="mb-3">
                <CCol md="4">
                  <CFormSelect
                    :options="options"
                    v-model="tahun"
                    label="Tahun"
                    :feedback="form_validation.tahun.feedback"
                    :invalid="form_validation.tahun.invalid"
                    required>
                  </CFormSelect>
                </CCol>
                <CCol md="8">
                  <CFormSelect
                    :options="semesteroptions"
                    v-model="semester_id"
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
                <CCol md="2">Judul</CCol>
                <CCol md="10">: {{ fileData.judul_proposal }}</CCol>
              </CRow>
              <CRow>
                <CCol md="2">Tahun</CCol>
                <CCol md="10">: {{ fileData.tahun }}</CCol>
              </CRow>
              <CRow>
                <CCol md="2">Semester</CCol>
                <CCol md="10">: {{ fileData.semester_id == 1 ? 'Ganjil' : 'Genap' }}</CCol>
              </CRow>
              <CRow>
                <CCol md="2">File</CCol>
                <CCol md="10">
                  : <a :href="`${window.location.origin}/mahasiswa/ta/proposal-ta/${fileData.file_proposal}`" target="_blank" >Lihat</a>
                </CCol>
              </CRow>
              <CRow>
                <CCol md="2">Status</CCol>
                <CCol md="10">: {{ fileData.status_proposal > 0 ? 'disetujui' : (fileData.status_proposal < 0 ? 'ditolak' : 'menunggu persetujuan') }}</CCol>
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
import Swal from 'sweetalert2';

export default {
  name: 'Proposal',
  components: {},
  data() {
    return {
      tahun: '',
      semester_id : '',
      judul: '',
      user: null,
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
      file: '',
      done: false,
      fileData: null
    }
  },
  async created() {
    //like constructor
    try {
      const user = await axios.get(`${window.location.origin}/api/user`);
      this.user = user.data;
      const tahun = await axios.get(`${window.location.origin}/api/ta/tahun`);
      this.options = tahun.data.data.data.map((tahun) => {
        return { label: tahun.tahun, value: tahun.tahun.toString() }
      });
      this.tahun = this.options[0].value;
      const response = await axios.get(`${window.location.origin}/api/ta/proposal_ta`);
      this.done = response.data.data ? true : false;
      this.fileData = response.data.data;
      this.judul = this.fileData ? this.fileData.judul_proposal : '';
      this.tahun = this.fileData ? this.fileData.tahun : this.options[0].value;
    } catch (e) {
      if (e.response.status === 404) {
        this.done = false;
        this.fileData = null;
        console.log(e.response.data);
      } else {
        console.log(e);
      }
    }
  },
  mounted() {
    //like update()
    console.log('Dashboard component mounted.');
    Echo.channel('proposal-ta').listen('ProposalUpdated', (e) => {
      if (this.user.token.id == e.token.id) {
        this.done = !e.isEmpty;
        if (this.done) {
          this.fileData = e.item;
          console.log('File data: ', this.fileData);
        } else {
          this.fileData = null;
          this.judul = '';
          this.tahun = this.options[0].value;
          window.document.getElementById('file').value = '';
        }
        console.log('Token matched');
      } else {
        console.log('Token not matched');
      }
      console.log(e);
    });
  },
  methods: {
    submitFile() {
      let formData = new FormData();
      formData.append('file_proposal', this.file);
      formData.append('judul_proposal', this.judul);
      formData.append('tahun', this.tahun);
      formData.append('semester_id', this.semester_id);

      this.showLoadingAlert();
      axios.post(`${window.location.origin}/api/ta/proposal_ta`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        console.log(response);
        this.showSuccessAlert('Item added successfully!');
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
          this.showErrorAlert('Gagal mengupload file!', error.response.data.message);
        } else {
          console.log('Error: ', error);
          this.showErrorAlert(`Error ${error.response.status}`, error.response.data.message ?? error.response.data);
        }
      });
    },
    updateFile() {
      let formData = new FormData();
      if (this.file) {
        formData.append('file_proposal', this.file);
      }
      formData.append('judul_proposal', this.judul);
      formData.append('tahun', this.tahun);
      formData.append('semester_id', this.semester_id);
      
      this.showLoadingAlert();
      axios.post(`${window.location.origin}/api/ta/proposal_ta/${this.fileData.nim}/update`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        console.log(response);
        this.showSuccessAlert('Item updated successfully!');
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
          this.showErrorAlert('Gagal mengupdate file!', error.response.data.message);
        } else {
          console.log('Error: ', error);
          this.showErrorAlert(`Error ${error.response.status}`, error.response.data.message ?? error.response.data);
        }
      });
    },
    deleteFile() {
      this.showLoadingAlert();
      axios.delete(`${window.location.origin}/api/ta/proposal_ta/${this.fileData.nim}`)
      .then(response => {
        console.log(response);
        this.showSuccessAlert('Item deleted successfully!');
        this.fileData = null;
        this.judul = '';
        this.tahun = this.options[0].value;
        document.getElementById('file').value = '';
        this.done = false;
      })
      .catch(error => {
        console.log('Error: ', error);
        this.showErrorAlert(`Error ${error.response.status}`, error.response.data.message ?? error.response.data);
      });
    },
    handleFileUpload( event ){
            this.file = event.target.files[0];
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
