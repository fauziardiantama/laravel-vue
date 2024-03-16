  <template>
    <Login @login-attempt="login" :mahasiswa="true" :userType="'mahasiswa'" :form_validation="form_validation"/>
  </template>
  
  <script>
   import Login from '@/components/Login.vue'
   
    export default {
        name: 'MahasiswaLogin',
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
                },
                redirect: 'Landing'
            }
        },
        mounted() {
            console.log('Mahasiswa Login Component mounted.');
            if (this.$route.query.status == "verified") {
                this.$store.dispatch('showSuccessAlert', 'Email berhasil diverifikasi! Silahkan login.');
            } else if (this.$route.query.status == "invalid") {
                this.$store.dispatch('showErrorAlert', {
                    title: 'Verifikasi Email Gagal',
                    text: 'Token verifikasi tidak valid atau sudah kadaluarsa. Silahkan coba lagi.'
                });
            }
            if (this.$route.query.redirect) {
                this.redirect = this.$route.query.redirect;
            }
            console.log('Redirect : '+this.redirect);
        },
        methods: {
            login(credential) {
                console.log('Email2 : '+credential.email)
                this.$store.dispatch('showLoadingAlert');
                this.$store.dispatch('mahasiswaLogin', credential)
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
                    this.$router.push({ name: this.redirect });
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
  