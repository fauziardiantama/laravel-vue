  <template>
    <Login @login-attempt="login"/>
  </template>
  
  <script>
   import Login from '@/components/Login.vue'
    export default {
        name: 'DosenLogin',
        components: {
            Login
        },
        mounted() {
        console.log('Dosen Login Component mounted.')
        },
        methods: {
            login(credential) {
                console.log('log in')
                this.$store.dispatch('dosenLogin', {
                    credentials: {
                        email: credential.email,
                        password: credential.password
                    },
                    router: () => this.$router.push({ name: 'DosenKmm' })
                });
            }
        }
    }
  </script>
  