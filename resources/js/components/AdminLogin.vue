  <template>
    <Login @login-attempt="login" :mahasiswa="false" :for="'admin'" :form_validation="form_validation"/>
  </template>
  
  <script>
   import Login from '@/components/Login.vue'
    export default {
        name: 'AdminLogin',
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
        console.log('Admin Login Component mounted.')
        },
        methods: {
            login(credential) {
                console.log('Email2 : '+credential.email)
                this.$store.dispatch('adminLogin', {
                    credentials: {
                        email: credential.email,
                        password: credential.password
                    },
                    router: () => this.$router.push({ name: 'AdminTa' })
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
  