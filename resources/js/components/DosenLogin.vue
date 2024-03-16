  <template>
    <Login @login-attempt="login"  :mahasiswa="false" :userType="'dosen'" :form_validation="form_validation"/>
  </template>
  
  <script>
   import Login from '@/components/Login.vue'

    export default {
        name: 'DosenLogin',
        components: {
            Login
        },
        data() {
            return {
                form_validation: {
                    email: {
                        invalid: false,
                        feedback: ''
                    },
                    password: {
                        invalid: false,
                        feedback: ''
                    }
                }
            }
        },
        mounted() {
            console.log('Dosen Login Component mounted.')
        },
        methods: {
            login(credential) {
                console.log('Email2 : '+credential.email)
                this.$store.dispatch('showLoadingAlert');
                this.$store.dispatch('dosenLogin', credential)
                .then(response => {
                    console.log(response);
                    this.$store.dispatch('showSuccessAlert', 'Login Berhasil');
                    this.form_validation = {
                        email: {
                            invalid: false,
                            feedback: ''
                        },
                        password: {
                            invalid: false,
                            feedback: ''
                        }
                    };
                    this.$router.push({ name: 'Landing' });
                }).catch(e => {
                    if (e.response.status === 422) {
                        this.form_validation = {
                            email: {
                                invalid: !!e.response.data.errors.email,
                                feedback: e.response.data.errors.email ? e.response.data.errors.email.join(' & ') : ""
                            },
                            password: {
                                invalid: !!e.response.data.errors.password,
                                feedback: e.response.data.errors.password ? e.response.data.errors.password.join(' & ') : ""
                            }
                        }
                        this.$store.dispatch('showErrorAlert', {
                            title: 'Login Gagal',
                            message: e.response.data.message
                        });
                    } else {
                        this.$store.dispatch('showErrorAlert', {
                            title: `Error ${e.response.status}`,
                            message: e.response.data.message || e.response.data
                        });
                    }
                });
            }
        }
    }
  </script>
  