<template>
    <div>
      <CRow>
        <CCol v-if="!haveproposal" :md="12">
          <p class="text-center">Belum mendaftarkan proposal</p>
        </CCol>
        <CCol v-if="havebimbingan" :md="12">
          <CCard class="mb-4">
            <CCardHeader class="row">
              <p class="col-7">Bimbingan</p>
            </CCardHeader>
            <CCardBody>
                <div>
                    <CRow>
                        <CCol md="3">Proposal</CCol>
                        <CCol md="9">: {{ proposal?.judul_proposal }}</CCol>
                    </CRow>
                    <CRow>
                        <CCol md="3">Dosen pembimbing</CCol>
                        <CCol md="9">: {{ bimbingan?.dosen?.nama }}</CCol>
                    </CRow>
                </div>
            </CCardBody>
            <CCardFooter>
                <CButton color="primary" @click="untaut(bimbingan)">Hapus tautan pembimbing dan magang</CButton>
            </CCardFooter>
          </CCard>
        </CCol>
        <CCol v-if="havemagang&&!havebimbingan&&haveproposal" :md="12">
            <CCard class="mb-4">
            <CCardHeader class="row">
                <p class="col-7">Magang</p>
            </CCardHeader>
            <CCardBody>
                <CTable align="middle" class="mb-0 border" hover responsive>
                <CTableHead color="light">
                    <CTableRow>
                    <CTableHeaderCell class="text-center">
                        #
                    </CTableHeaderCell>
                    <CTableHeaderCell>Instansi</CTableHeaderCell>
                    <CTableHeaderCell>Topik</CTableHeaderCell>
                    <CTableHeaderCell>Dosen</CTableHeaderCell>
                    <CTableHeaderCell>Actions</CTableHeaderCell>
                    </CTableRow>
                </CTableHead>
                <CTableBody v-if="magangs.length > 0">
                    <CTableRow v-for="(magang,index) in magangs" :key="magang.id_magang">
                    <CTableDataCell class="text-center">
                        {{ index + 1 }}
                    </CTableDataCell>
                    <CTableDataCell>
                        {{ magang.instansi?.nama_instansi ?? '-' }}
                    </CTableDataCell>
                    <CTableDataCell>
                        {{ magang.topik?.nama_topik ?? '-' }}
                    </CTableDataCell>
                    <CTableDataCell>
                        {{ magang.dosen?.nama ?? '-' }}
                    </CTableDataCell>
                    <CTableDataCell>
                        <CButton color="primary" @click="taut(magang)">Tautkan pembimbing dan magang</CButton>
                    </CTableDataCell>
                    </CTableRow>
                </CTableBody>
                <CTableBody v-else>
                    <CTableRow>
                    <CTableDataCell class="text-center" colspan="4">
                        {{ itemstatus }}
                    </CTableDataCell>
                    </CTableRow>
                </CTableBody>
                </CTable>
            </CCardBody>
          </CCard>
        </CCol>
        <CCol v-if="!havemagang&&!havebimbingan" :md="12">
          <p class="text-center">Belum mendaftarkan magang</p>
        </CCol>
      </CRow>
    </div>
  </template>

  <style scoped>
    .dokumen-link {
        color: #000;
        text-decoration: none;
        background-color: rgba(114, 114, 114, 0.484);
        padding: 5px 10px;
        border-radius: 10px;
        margin: 0 2px;
        font-size: 0.8rem;
        font-weight: 1000;
    }
  </style>
  
  
  <script>

  export default {
    name: 'Pembimbing',
    components: {},
    data() {
      return {
        user: null,
        haveproposal: false,
        havemagang: false,
        havebimbingan: false,
        bimbingan: {},
        proposal: {},
        magangs: []
      }
    },
    watch: {
        '$store.state.user': {
            immediate: true,
            handler(user) {
                if (user != null) {
                    this.user = user;
                    this.getBimbingan();
                    this.getMagangs();
                    this.getProposal();
                    this.echo();
                }
            }
        }
    },
    methods: {
        getMagangs() {
            axios.get(`${window.location.origin}/api/kmm/magang`).then(response => {
                if (response.data.data != null) {
                    this.havemagang = true;
                }
                this.magangs = [response.data.data]
            }).catch(e => {
                console.log('Error: ', e);
                if (e.response.status === 404) {
                    this.havemagang = false;
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', { title: `Error ${e.response.status}`, message: `${e.response.data.message ?? e.response.data}`});
                }
            });
        },
        getProposal() {
            axios.get(`${window.location.origin}/api/ta/proposal_ta`).then(response => {
                this.haveproposal = true;
                this.proposal = response.data.data;
            }).catch(e => {
                if (e.response.status === 404) {
                    this.haveproposal = false;
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', { title: `Error ${e.response.status}`, message: `${e.response.data.message ?? e.response.data}`});
                }
            }); 
        },
        echo() {
            console.log('Echoing...');
            Echo.private(`User.${this.user.nim}`).listen('DokReg', (e) => {

            }).listen('Prop', (e) => {
                this.haveproposal = !e.isEmpty;
            });
        },
        getBimbingan() {
            axios.get(`${window.location.origin}/api/ta/bimbingan`).then(response => {
                if (response.data.data != null) {
                    this.havebimbingan = true;
                }
                this.bimbingan = response.data.data;
                console.log(this.bimbingan);
            }).catch(e => {
                if (e.response.status === 404) {
                    this.havebimbingan = false;
                } else {
                    console.log('Error: ', e);
                    this.$store.dispatch('showErrorAlert', { title: `Error ${e.response.status}`, message: `${e.response.data.message ?? e.response.data}`});
                }
            });
        },
        taut(magang) {
            this.$store.dispatch('showLoadingAlert');
            axios.post(`${window.location.origin}/api/ta/bimbingan/link`, {
                id_magang: magang.id_magang
            }).then(response => {
                this.$store.dispatch('showSuccessAlert', "Berhasil menautkan pembimbing dan magang");
                this.getMagangs();
                this.getBimbingan();
                this.getProposal();
            }).catch(e => {
                console.log('Error: ', e);
                this.$store.dispatch('showErrorAlert', { title: `Error ${e.response.status}`, message: `${e.response.data.message ?? e.response.data}`});
            });
        },
        untaut() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/ta/bimbingan/unlink`).then(response => {
                this.$store.dispatch('showSuccessAlert', "Berhasil menghapus tautan pembimbing dan magang");
                this.getMagangs();
                this.getBimbingan();
                this.getProposal();
            }).catch(e => {
                console.log('Error: ', e);
                this.$store.dispatch('showErrorAlert', { title: `Error ${e.response.status}`, message: `${e.response.data.message ?? e.response.data}`});
            });
        }
    }
  }
  </script>
  