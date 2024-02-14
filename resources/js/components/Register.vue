<template>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
      <CContainer>
        <CRow class="justify-content-center">
          <CCol :md="9" :lg="7" :xl="6">
            <CCard class="mx-4">
              <CCardBody class="p-4">
                <CForm>
                  <h1>Formulir Pendaftaran</h1>
                  <p class="text-medium-emphasis">Daftar Sebagai Mahasiswa</p>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput
                      placeholder="Nomor Induk Mahasiswa (NIM)"
                      v-model="form.nim"
                      :feedback="form_validation.nim.feedback"
                      :invalid="form_validation.nim.invalid"
                      required/>
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput
                      placeholder="Nama lengkap"
                      v-model="form.nama"
                      :feedback="form_validation.nama.feedback"
                      :invalid="form_validation.nama.invalid"
                      required/>
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>@</CInputGroupText>
                    <CFormInput
                      type="email"
                      placeholder="E-mail mahasiswa (@student.uns.ac.id)"
                      v-model="form.email"
                      :feedback="form_validation.email.feedback"
                      :invalid="form_validation.email.invalid"
                      required/>
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput
                      placeholder="Nomor telepon"
                      v-model="form.no_telp"
                      :feedback="form_validation.no_telp.feedback"
                      :invalid="form_validation.no_telp.invalid"
                      required/>
                  </CInputGroup>
                  <CInputGroup :class="!visible ? 'mb-3' : ''">
                    <CInputGroupText>
                      <CIcon icon="cil-lock-locked" />
                    </CInputGroupText>
                    <CFormInput
                      :type="showPassword ? 'text' : 'password'"
                      placeholder="Buat password baru"
                      v-model="form.password"
                      :invalid="form_validation.password.invalid"
                      required/>
                      <CInputGroupText>
                        <CLink style="color:#5b5b5b !important" href="#" @click="toggleShow">
                          <font-awesome-icon :icon="showPassword ? ['fas', 'eye-slash'] : ['fas','eye']" />
                        </CLink>
                      </CInputGroupText>
                      <div class="invalid-feedback">{{ form_validation.password.feedback }}</div>
                  </CInputGroup>
                  <CCollapse :visible="visible">
                    <CCard class="mb-3">
                      <CCardBody>
                        password harus memiliki minimal satu huruf kapital, huruf kecil, angka, dan simbol. panjang minimal 8 karakter.
                      </CCardBody>
                    </CCard>
                  </CCollapse>
                    <CInputGroup class="mb-4">
                    <CInputGroupText>
                      <CIcon icon="cil-lock-locked" />
                    </CInputGroupText>
                    <CFormInput                      
                      :type="showConfirmationPassword ? 'text' : 'password'"
                      placeholder="Ulangi password"
                      v-model="form.password_confirmation"
                      :invalid="form_validation.password.invalid"
                      required/>
                      <CInputGroupText>
                        <CLink style="color:#5b5b5b !important" href="#" @click="toggleShowCP">
                          <font-awesome-icon :icon="showConfirmationPassword ? ['fas', 'eye-slash'] : ['fas','eye']" />
                        </CLink>
                      </CInputGroupText>
                  </CInputGroup>
                  <div class="d-grid">
                    <CButton color="success" @click="register">Buat Akun</CButton>
                  </div>
                </CForm>
                <router-link :to="{ name: 'MahasiswaLogin' }" class="d-block mt-3 text-center">Sudah punya akun? Login disini</router-link>
              </CCardBody>
            </CCard>
          </CCol>
        </CRow>
      </CContainer>
    </div>
  </template>
  
  <style type="scss">
  @import '../styles/style.scss';
  </style>
  <script>
  import Swal from 'sweetalert2';
  
  export default {
    name: 'Register',
    data() {
      return {
        form: {
          nim: '',
          nama: '',
          email: '',
          no_telp: '',
          password: '',
          password_confirmation: ''
        },
        form_validation: {
          nim: {
            invalid: false,
            feedback: ''
          },
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
          password: {
            invalid: false,
            feedback: ''
          }
        },
        visible: false,
        showPassword: false,
        showConfirmationPassword: false
      }
    },
    watch: {
        'form.password'(newValue) {
            this.visible = newValue.trim().length > 0;
        },
    },
    mounted() {
      console.log('Register Component mounted.')
    },
    methods: {
        register() {
            this.showLoadingAlert();
            axios.post(`${window.location.origin}/api/mahasiswa/register`, this.form)
            .then(response => {
                this.showSuccessAlert();
                console.log(response);
                this.form = {
                  nim: '',
                  nama: '',
                  email: '',
                  no_telp: '',
                  password: '',
                  password_confirmation: ''
                };
                this.form_validation = {
                  nim: {
                    invalid: false,
                    feedback: ''
                  },
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
                  password: {
                    invalid: false,
                    feedback: ''
                  },
                  password_confirmation: {
                    invalid: false,
                    feedback: ''
                  }
                };
            })
            .catch(e => {
              if (e.response.status === 422) {
                this.showErrorAlert(e.response.data.message, e);
                this.form_validation = {
                  nim: {
                    invalid: !!e.response.data.errors.nim,
                    feedback: e.response.data.errors.nim ? e.response.data.errors.nim.join(' & ') : ""
                  },
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
                  password: {
                    invalid: !!e.response.data.errors.password,
                    feedback: e.response.data.errors.password ? e.response.data.errors.password.join(' & ') : ""
                  }
                }
                this.form.password = '';
                this.form.password_confirmation = '';
              } else {
                console.log(e);
                this.showErrorAlert('Failed to register!', e);
              }
            });
        },
        toggleShow() {
          this.showPassword = !this.showPassword;
        },
        toggleShowCP() {
          this.showConfirmationPassword = !this.showConfirmationPassword;
        },
        showLoadingAlert() {
          Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
          });
        },
        showSuccessAlert() {
          Swal.fire({
            text: "Silahkan verifikasi akun dari kotak masuk email anda.",
            icon: "success",
            title: "Berhasil mendaftar!",
            confirmButtonText: "Pergi ke halaman login",
          }).then((result) => {
            if (result.isConfirmed) {
              this.$router.push({ name: 'MahasiswaLogin' });
            }
          });
        },
        showErrorAlert(message, error) {
          Swal.fire({
            title: `Error ${error.response.status}`,
            text: message,
            icon: 'error'
          });
        }
    }
  }
  </script>
  