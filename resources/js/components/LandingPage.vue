<template>
    <nav id="navbarExample" class="navbar navbar-expand-lg fixed-top navbar-light" aria-label="Main navigation">
        <div class="container p-2">
            <a class="navbar-brand logo-text" href="#">Landing Page</a> 
            <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav ms-auto navbar-nav-scroll">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#header">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">KMM</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#details">Tugas Akhir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Lainnya</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Dropdown menu</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="#">Article Details</a></li>
                            <li><div class="dropdown-divider"></div></li>
                            <li><a class="dropdown-item" href="#">Terms Conditions</a></li>
                            <li><div class="dropdown-divider"></div></li>
                            <li><a class="dropdown-item" href="#">Privacy Policy</a></li>
                        </ul>
                    </li>
                </ul>
                <span class="nav-item">
                    <a class="btn-solid-sm" :href="login_link">Daftar/Masuk</a>
                </span>
            </div> 
        </div> 
    </nav>
    <header id="header" class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 z-top">
                <div class="text-container">
                    <h1 class="h1-large">D3 Teknik Informatika</h1>
                    <p class="p-large">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut dignissim, neque ut ultrices sollicitudin</p>
                    <form>
                        <div class="form-group col-12 col-md-10">
                        <input type="email" class="form-control-input" placeholder="youremail@student.uns.ac.id" required />
                        </div>
                    </form>
                    <a class="btn-solid-lg" :href="login_link">Klik untuk masuk!</a>
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
                        <a href="#your-link">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-facebook-f fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="#your-link">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-twitter fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="#your-link">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-pinterest-p fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="#your-link">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-instagram fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="#your-link">
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
                    <li><a href="#">Article Details</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div> 
            <div class="col-lg-3 col-md-12 col-sm-12">
                <p class="p-small statement">Copyright © <a href="#">D3 Teknik Informatika </a></p>
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

<style scoped>
@import 'public/assets/css/bootstrap.min.css';
@import 'public/assets/css/fontawesome-all.min.css';
@import 'public/assets/css/swiper.css';
@import 'public/assets/css/styles.css';

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>

<script>
export default {
    name: 'LandingPage',
    data() {
        return {
            login_link : '/kmm'
        }
    },
    mounted() {
      let bootstrap = document.createElement('script');
      bootstrap.setAttribute('src', 'http://127.0.0.1:8000/assets/js/bootstrap.min.js');
      document.head.appendChild(bootstrap);
      let script = document.createElement('script');
      script.setAttribute('src', 'http://127.0.0.1:8000/assets/js/scripts.js');
      document.head.appendChild(script);
      let swiper = document.createElement('script');
      swiper.setAttribute('src', 'http://127.0.0.1:8000/assets/js/swiper.min.js');
      document.head.appendChild(swiper);
    }
}
</script>