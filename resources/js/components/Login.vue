<template>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
      <CContainer>
        <CRow class="justify-content-center">
          <CCol :md="8">
            <CCardGroup>
              <CCard class="p-4">
                <CCardBody>
                  <CForm>
                    <h1>Login {{ for }}</h1>
                    <p class="text-medium-emphasis">Masuk ke akun anda</p>
                    <CInputGroup class="mb-3">
                      <CInputGroupText>
                        <CIcon icon="cil-user" />
                      </CInputGroupText>
                      <CFormInput
                        :placeholder="emailplaceholder"
                        v-model="form.email"
                        :feedback="form_validation.email.feedback"
                        :invalid="form_validation.email.invalid"
                        required
                      />
                    </CInputGroup>
                    <CInputGroup class="mb-4">
                      <CInputGroupText>
                        <CIcon icon="cil-lock-locked" />
                      </CInputGroupText>
                      <CFormInput
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Password"
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
                    <CRow>
                      <CCol :xs="7">
                        <CButton color="primary" class="px-4" @click="login"> Login </CButton>
                      </CCol>
                      <CCol :xs="5" class="text-right">
                        <router-link class="px-0" :to="{ name: 'MahasiswaRegister'}" v-show="mahasiswa">Belum punya akun? Daftar disini</router-link>
                      </CCol>
                    </CRow>
                  </CForm>
                </CCardBody>
              </CCard>
            </CCardGroup>
          </CCol>
        </CRow>
      </CContainer>
    </div>
  </template>
  
  <style type="scss">
  @import '../styles/style.scss';
  </style>

  <script>
  export default {
    name: 'Login',
    data() {
      return {
        form: {
          email: '',
          password: ''
        },
        showPassword: false
      }
    },
    computed: {
      emailplaceholder() {
        return (this.for === 'mahasiswa') ? 'E-mail mahasiswa (@student.uns.ac.id)' : 'Masukkan email';
      }
    },
    props: {
        mahasiswa: Boolean,
        for: String,
        form_validation: Object
    },
    mounted() {
      console.log('Login Component mounted.');
      console.log(this.for);
    },
    methods: {
      login() {
        this.$emit('login-attempt', this.form);
        console.log('Login attempt emitted');
        this.form.password = '';
      },
      toggleShow() {
        this.showPassword = !this.showPassword;
      }
    }
  }
  </script>
  