<template>
  <div>
    <CRow>
      <CCol :md="5">
        <CCard class="mb-4">
          <CCardHeader>Tambah Topik</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                <CFormLabel for="nama_topik">Nama topik</CFormLabel>
                <CFormSelect
                        aria-label="Default select example"
                        :options="topik_options"
                        v-model="tambah.id_topik"
                        label="Tipe"
                        :feedback="tambah_validation.id_topik.feedback"
                        :invalid="tambah_validation.id_topik.invalid"
                        required>
                    </CFormSelect>
              </div>
              <div class="mb-3 text-center">
                <CButton color="primary" class="w-100" @click="addItem">Tambah</CButton>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="7">
        <CCard class="mb-4">
          <CCardHeader> Daftar Topik</CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Nama Topik</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="topiks.length > 0">
                <CTableRow v-for="(topik, index) in topiks" :key="topik.id">
                  <CTableDataCell class="text-center">
                      {{ index + 1 }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ topik.nama_topik }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="danger" @click="remove(topik)">Lepas</CButton>
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
    </CRow>
  </div>
</template>

<style scoped>
  .right {
    display: flex;
    justify-content: flex-end;
  }
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
import pagination from '@/components/Pagination.vue';

export default {
  name: 'Topik',
  components: {
    pagination
  },
  data() {
    return {
      topiks: [],
      tambah: {
        id_topik: ''
      },
      tambah_validation: {
        id_topik: {
          invalid: false,
          feedback: ''
        }
      },
      topik_options: [
        { label: 'Open this select menu', value: ''}
      ],
      itemstatus: 'Mengambil items',
      user: null
    }
  },
  watch : {
    '$store.state.user': {
      handler(user) {
        if (user != null) {
          this.user = user;
          this.echo();
        }
      }
    }
  },
  created() {
    this.getTopiks();
    this.getOptions();
  },
  mounted() {
    console.log('Dashboard component mounted.');
  },
  methods: {
    echo() {
      Echo.private('Duser.' + this.user.nik)
      .listen('Tpk', (e) => {
        this.getTopiks();
        this.getOptions();
      });
    },
    getTopiks() {
      axios.get(`${window.location.origin}/api/kmm/topik/mine`)
      .then(response => {
        this.topiks = response.data.data;
      })
      .catch(error => {
        this.itemstatus = error.response.data.message;
        console.log(error);
      });
    },
    getOptions() {
      axios.get(`${window.location.origin}/api/kmm/topik`)
            .then((response) => {
                this.topik_options = [
                    { label: 'Open this select menu', value: ''}
                ];
                //map and push to options
                response.data.data.map((item) => {
                    this.topik_options.push({
                        label: item.nama_topik,
                        value: item.id_topik
                    });
                });
            })
            .catch((error) => {
                console.log(error);
            });
    },
    addItem() {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/topik/assign`, this.tambah)
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
        this.tambah.id_topik = '';
        this.getTopiks();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.form_validation.nama_topik.invalid = !!error.response.data.errors.nama_topik;
          this.form_validation.nama_topik.feedback = error.response.data.errors.nama_topik.join(' & ');
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambahkan item!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambahkan item!',
            message: error.response.status
          });
        }
      });
    },
    remove(topik) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/topik/unassign`, topik)
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item Removed successfully!');
        this.getTopiks();
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
          title: 'Gagal melepaskan item!',
          message: error.response.data.message
        });
      });
    }
  }
}
</script>
