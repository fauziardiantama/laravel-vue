  <template>
    <Login @login-attempt="login"/>
  </template>
  
  <script>
   import Login from '@/components/Login.vue'
    export default {
        name: 'AdminLogin',
        components: {
            Login
        },
        mounted() {
        console.log('Admin Login Component mounted.')
        },
        methods: {
            login(credential) {
                this.$store.dispatch('adminLogin', {
                    credentials: {
                        email: credential.email,
                        password: credential.password
                    },
                    router: () => this.$router.push({ name: 'AdminKmm' })
                });
            }
        }
    }
  </script>
  