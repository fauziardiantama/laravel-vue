<template>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
      <CContainer>
        <CRow class="justify-content-center">
          <CCol :md="9" :lg="7" :xl="6">
            <CCard class="mx-4">
              <CCardBody class="p-4">
                <CForm>
                  <h1>Register</h1>
                  <p class="text-medium-emphasis">Create your account</p>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput placeholder="Username" v-model="nama" />
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>@</CInputGroupText>
                    <CFormInput placeholder="Email" v-model="email" />
                  </CInputGroup>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-lock-locked" />
                    </CInputGroupText>
                    <CFormInput
                      type="password"
                      placeholder="Password"
                        v-model="password" 
                    />
                  </CInputGroup>
                  <CInputGroup class="mb-4">
                    <CInputGroupText>
                      <CIcon icon="cil-lock-locked" />
                    </CInputGroupText>
                    <CFormInput
                      type="password"
                      placeholder="Repeat password"
                        v-model="password_confirmation"
                    />
                  </CInputGroup>
                  <div class="d-grid">
                    <CButton color="success" @click="register">Create Account</CButton>
                  </div>
                </CForm>
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
  export default {
    name: 'Register',
    data() {
      return {
        nama: '',
        email: '',
        password: '',
        password_confirmation: '',
        app: 'http://127.0.0.1:8000/'
      }
    },
    mounted() {
      console.log('Register Component mounted.')
    },
    methods: {
        register() {
            axios.post(this.app + 'api/register', {
                nama: this.nama,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
            })
                .then(response => {
                    console.log(response);
                    this.nama = '';
                    this.email = '';
                    this.password = '';
                    //token
                    localStorage.setItem('token', response.data.token);
                    this.$router.push({ name: 'Home' });
                })
                .catch(error => {
                    console.log(error);
                    alert('Register failed');
                });
        }
    }
  }
  </script>
  