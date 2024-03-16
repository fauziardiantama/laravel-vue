<template>
    <nav id="navbarExample" class="navbar navbar-expand-lg fixed-top navbar-light" aria-label="Main navigation">
        <div class="container p-2">
            <a class="navbar-brand logo-text" href="#">D3TI</a> 
            <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav ms-auto navbar-nav-scroll">
                    <li class="nav-item">
                        <router-link class="nav-link active" :to="{ name: 'Landing'}">Home</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="kmmLink">Kuliah Magang Mahasiswa</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="taLink">Tugas Akhir</router-link>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Web terkait</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="https://d3ti.vokasi.uns.ac.id/" target="_blank">D3 Teknik Informatika</a></li>
                            <li><div class="dropdown-divider"></div></li>
                            <li><a class="dropdown-item" href="https://vokasi.uns.ac.id/" target="_blank">Sekolah Vokasi</a></li>
                            <li><div class="dropdown-divider"></div></li>
                            <li><a class="dropdown-item" href="https://uns.ac.id/" target="_blank">Universitas Sebelas Maret</a></li>
                        </ul>
                    </li>
                </ul>
                <span class="nav-item">
                    <router-link v-if="!logged" class="btn-solid-sm" :to="{ name: 'MahasiswaLogin'}">Daftar/Masuk</router-link>
                    <button v-else class="btn-solid-sm" @click="logout">Keluar</button>
                </span>
            </div> 
        </div> 
    </nav>
    <header id="header" class="header">
    <div class="container d-block">
        <div class="row">
            <div class="col-lg-6 z-top">
                <div class="text-container">
                    <h1 class="h1-large">D3 Teknik Informatika</h1>
                    <p v-if="!logged" class="p-large">Selamat datang! Untuk mengakses fitur-fitur yang lebih lengkap, silakan masuk atau buat akun anda.</p>
                    <form v-if="!logged">
                        <div class="form-group col-12 col-md-10">
                            <input
                                type="email"
                                :class="'form-control form-control-input' + (form_validation.email.invalid ? ' is-invalid text-danger' : '')"
                                placeholder="youremail@student.uns.ac.id"
                                v-model="form.email"
                                required />
                            <div v-show="form_validation.email.invalid" class="invalid-feedback lefty">
                                {{  form_validation.email.feedback }}
                            </div>
                        </div>
                        <collapse-transition :duration="200">
                            <div v-show="emailIsFilled">
                                <div :class="'form-group col-12 col-md-10 text-left d-flex'+(form_validation.password.invalid ? ' mb-0' : '')">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        :class="'form-control form-control-input password' + (form_validation.password.invalid ? ' is-invalid text-danger' : '')"
                                        placeholder="password"
                                        v-model="form.password"
                                        required />
                                    <div class="show-password">
                                        <button class="btn h-100" type="button" @click="showPassword = !showPassword">
                                            <i v-if="!showPassword" class="fas fa-eye"></i>
                                            <i v-else class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div v-show="form_validation.password.invalid" :class="'invalid-feedback lefty' + (form_validation.password.invalid ? ' d-block mb-4' : '')">
                                    {{ form_validation.password.feedback }}
                                </div>
                            </div>
                        </collapse-transition>
                    </form>
                    <button v-if="!logged" class="btn-solid-lg" @click="login">Klik untuk masuk!</button>
                    <p v-if="logged" class="p-large">Selamat datang, {{ user.nama }}! Silahkan pilih akses. </p>
                    <router-link v-if="logged" class="btn-solid-lg" :to="kmmLink">Kuliah Magang Mahasiswa</router-link>
                    <router-link v-if="logged" class="btn-solid-lg" :to="taLink">Tugas Akhir</router-link>
                </div>
            </div> 
            <div class="col-lg-6">
                <div class="image-container">
                    <img class="img-fluid" src="assets/images/hd.png" alt="alternative" />
                </div> 
            </div> 
        </div> 
    </div> 
    </header> 
    <!-- rest of landing page -->
    <div class="footer bg-gray">
    <img class="decoration-circles" src="assets/images/decoration-circles.png" alt="alternative" />
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h4>D3 Teknik Informatika - Sekolah Vokasi - Universitas Sebelas Maret</h4>
                <div class="social-container">
                    <span class="fa-stack">
                        <a href="https://d3ti.vokasi.uns.ac.id/" target="_blank">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fas fa-globe fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="tel:(0271)663450">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fas fa-phone fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="mailto:kontak@d3ti.vokasi.uns.ac.id">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fas fa-envelope fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="https://www.instagram.com/d3tiuns/" target="_blank">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-instagram fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="https://www.youtube.com/@teknikinformatikauns" target="_blank">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-youtube fa-stack-1x"></i>
                        </a>
                    </span>
                </div> 
            </div> 
        </div>
    </div> 
    </div> 
    <div class="copyright bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <ul class="list-unstyled li-space-lg p-small">
                    <li><a href="https://d3ti.vokasi.uns.ac.id/" target="_blank">D3 Teknik Informatika</a></li>
                    <li><a href="https://vokasi.uns.ac.id/" target="_blank">Sekolah Vokasi</a></li>
                    <li><a href="https://uns.ac.id/" target="_blank">Universitas Sebelas Maret</a></li>
                </ul>
            </div> 
            <div class="col-lg-3 col-md-12 col-sm-12">
                <p class="p-small statement">Copyright © <a href="https://d3ti.vokasi.uns.ac.id/" target="_blank">D3 Teknik Informatika </a></p>
            </div> 
            <div class="col-lg-3 col-md-12 col-sm-12">
                <p class="p-small statement">Landing page design by <a href="https://masukmia.com" target="_blank">Masuk Mia </a></p>
            </div> 
        </div> 
    </div> 
    </div>
    <button id="myBtn">
    <img src="assets/images/up-arrow.png" alt="alternative" />
    </button>
</template>

<style type="scss" scoped>

@import '../../../public/assets/css/bootstrap.min.css';
@import '../../../public/assets/css/fontawesome-all.min.css';
@import '../../../public/assets/css/swiper.css';
@import '../../../public/assets/css/styles.css';

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease-in-out; /* Fade animation */
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.password {
    border-radius: 8px 0px 0px 8px;
}

.show-password {
    border-radius: 0px 8px 8px 0px;
    background-color: #cbcbd1;
}

.show-password button {
    padding-left: 15px;
    padding-right: 15px;
    font-size: 20px;
    color: #757575;
}

.lefty {
    text-align: left;
}

</style>

<script>
import CollapseTransition from '@ivanv/vue-collapse-transition/src/CollapseTransition.vue';

export default {
    name: 'LandingPage',
    components: {
        CollapseTransition
    },
    data() {
        return {
            form : {
                email: '',
                password: ''
            },
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
            showPassword: false,
            logged: false,
            auth: null,
            user: null
        }
    },
    computed: {
        emailIsFilled() {
            return this.form.email.trim().length > 0;
        },
        kmmLink() {
            return !this.logged ? { name: 'MahasiswaKmm' } : { name: `${this.auth.name.charAt(0).toUpperCase() + this.auth.name.slice(1)}Kmm` };
        },
        taLink() {
            return !this.logged ? { name: 'MahasiswaTa' } : { name: `${this.auth.name.charAt(0).toUpperCase() + this.auth.name.slice(1)}Ta` };
        }
    },
    watch: {
        emailIsFilled() {
            this.password = '';
        }
    },
    created() {
        console.log('LandingPage Component created.')
        this.$store.dispatch('user').then((response) => {
            this.logged = true;
            this.auth = response.data.auth;
            this.user = response.data.user;
        }).catch(() => {
            this.logged = false;
            this.user = null;
        });
    },
    mounted() {
      let bootstrap = document.createElement('script');
        bootstrap.setAttribute('src', `${window.location.origin}/assets/js/bootstrap.min.js`);
        document.head.appendChild(bootstrap);
      let script = document.createElement('script');
        script.setAttribute('src', `${window.location.origin}/assets/js/scripts.js`);
        document.head.appendChild(script);
      let swiper = document.createElement('script');
        swiper.setAttribute('src', `${window.location.origin}/assets/js/swiper.min.js`);
        document.head.appendChild(swiper);
    },
    methods: {
        login() {
            this.$store.dispatch('showLoadingAlert');
            this.$store.dispatch('mahasiswaLogin', this.form).then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Login Berhasil');
                this.logged = true;
                this.form = {
                    email: "",
                    password: ""
                }
                this.form_validation = {
                    email: {
                        invalid: false,
                        feedback: ""
                    },
                    password: {
                        invalid: false,
                        feedback: ""
                    }
                }
                this.user = response.data.user;
                console.log(this.user);
            }).catch((error) => {
                if (error.response.status === 422) {
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Login Gagal',
                        message: error.response.data.message
                    });
                    this.form_validation = {
                        email: {
                            invalid: !!error.response.data.errors.email,
                            feedback: error.response.data.errors.email ? error.response.data.errors.email.join(' & ') : ""
                        },
                        password: {
                            invalid: !!error.response.data.errors.password,
                            feedback: error.response.data.errors.password ? error.response.data.errors.password.join(' & ') : ""
                        }
                    }
                    this.form.password = "";
                } else {
                    console.log(error.response);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${error.response.status}`,
                        message: error.response.data.message || error.response.data
                    });
                }
            });
        },
        logout() {
            this.$store.dispatch('showLoadingAlert');
            this.$store.dispatch('logout').then(() => {
                this.$store.dispatch('showSuccessAlert', 'Logout Berhasil')
                this.logged = false;
                this.user = null;
            }).catch(e => {
                this.$store.dispatch('showErrorAlert', {
                title: 'Logout Gagal',
                message: e.response.data.message || e.response.data
                })
            })
        }
    }
}
</script>