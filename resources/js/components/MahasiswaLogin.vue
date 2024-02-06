  <template>
    <Login @login-attempt="login"/>
  </template>
  
  <script>
   import Login from '@/components/Login.vue'
    export default {
        name: 'MahasiswaLogin',
        components: {
            Login
        },
        mounted() {
        console.log('Mahasiswa Login Component mounted.')
        },
        methods: {
            login(credential) {
                this.$store.dispatch('mahasiswaLogin', {
                    credentials: {
                        email: credential.email,
                        password: credential.password
                    },
                    router: () => this.$router.push({ name: 'MahasiswaKmm' })
                });
            }
        }
    }
  </script>
  