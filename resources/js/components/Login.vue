<template>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
      <CContainer>
        <CRow class="justify-content-center">
          <CCol :md="8">
            <CCardGroup>
              <CCard class="p-4">
                <CCardBody>
                  <CForm>
                    <h1>Login</h1>
                    <p class="text-medium-emphasis">Sign In to your account</p>
                    <CInputGroup class="mb-3">
                      <CInputGroupText>
                        <CIcon icon="cil-user" />
                      </CInputGroupText>
                      <CFormInput
                        placeholder="email" v-model="email"
                      />
                    </CInputGroup>
                    <CInputGroup class="mb-4">
                      <CInputGroupText>
                        <CIcon icon="cil-lock-locked" />
                      </CInputGroupText>
                      <CFormInput
                        type="password"
                        placeholder="Password"
                        v-model="password"
                      />
                    </CInputGroup>
                    <CRow>
                      <CCol :xs="9">
                        <CButton color="primary" class="px-4" @click="login"> Login </CButton>
                      </CCol>
                      <CCol :xs="3" class="text-right">
                        <CButton color="link" class="px-0">
                          Forgot password?
                        </CButton>
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
        email: '',
        password: '',
      }
    },
    mounted() {
      console.log('Login Component mounted.')
    },
    methods: {
      login() {
        const email = this.email;
        const password = this.password;
        this.$emit('login-attempt', { email, password });
        console.log('Login attempt emitted');
      }
    }
  }
  </script>
  