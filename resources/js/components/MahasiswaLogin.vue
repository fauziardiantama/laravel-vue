  <template>
    <Login @login-attempt="login" :mahasiswa="true" :for="'mahasiswa'" :form_validation="form_validation"/>
  </template>
  
  <script>
   import Login from '@/components/Login.vue'
   import Swal from 'sweetalert2';
   
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
                }
            }
        },
        mounted() {
            console.log('Mahasiswa Login Component mounted.');
            if (this.$route.query.status == "verified") {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    icon: "success",
                    title: "Email telah diverifikasi! Silahkan login."
                });
            } else if (this.$route.query.status == "invalid") {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    icon: "error",
                    title: "Gagal verifikasi email! Akun tidak ditemukan atau token tidak valid."
                });
            }
        },
        methods: {
            login(credential) {
                console.log('Email2 : '+credential.email)
                this.$store.dispatch('mahasiswaLogin', {
                    credentials: {
                        email: credential.email,
                        password: credential.password
                    },
                    router: () => this.$router.push({ name: 'MahasiswaTa' })
                }).then(response => {
                    console.log(response);
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
                    }
                });
            }
        }
    }
  </script>
  