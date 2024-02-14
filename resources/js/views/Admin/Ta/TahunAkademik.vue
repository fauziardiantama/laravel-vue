<template>
  <div>
    <CRow>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader>Tambah Tahun</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                <CFormLabel for="createname">tahun</CFormLabel>
                <CFormInput type="text" id="createname" placeholder="tahun" v-model="createItem.tahun" :invalid="form_validation.tahun.invalid" />
              </div>
              <div class="mb-3 text-center">
                <CButton color="primary" class="w-100" @click="addItem">Tambah</CButton>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader> Tahun akademik </CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Tahun</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="tahuns.data.length > 0">
                <CTableRow v-for="(tahun, index) in tahuns.data" :key="tahun.id">
                  <CTableDataCell class="text-center">
                      {{ tahuns.current_page * tahuns.per_page - tahuns.per_page + index + 1 }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ tahun.tahun }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="danger" @click="deleteItem(tahun)">Delete</CButton>
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
          <CCardFooter class="row">
            <div class="col-12 mt-2 text-right right">
              <pagination  :pagination="tahuns"
                     @paginate="getTahun()"
                     :offset="4">
            </pagination>
            </div>
          </CCardFooter>
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
import Swal from 'sweetalert2';
import pagination from '@/components/Pagination.vue';

export default {
  name: 'TahunAkademik',
  components: {
    pagination
  },
  data() {
    return {
      tahuns: {
            total: 0,
            per_page: 2,
            from: 1,
            to: 0,
            current_page: 1,
            data: []
        },
      createItem: {
        tahun: ''
      },
      form_validation: {
        tahun: {
          invalid: false,
          feedback: ''
        }
      },
      itemstatus: 'Mengambil items',
      offset: 4,
    }
  },
  async created() {
    //like constructor
    this.getTahun();
  },
  mounted() {
    //like update()
    console.log('Dashboard component mounted.');
    // Echo.channel('items').listen('ItemAdded', (e) => {
    //   console.log(e);
    //   this.items.push(e.item);
    // }).listen('ItemUpdated', (e) => {
    //   console.log(e);
    //   this.items = this.items.map(i => i.id === e.item.id ? e.item : i);
    // }).listen('ItemDeleted', (e) => {
    //   console.log(e);
    //   this.items = this.items.filter(i => i.id !== e.id);
    // });
  },
  methods: {
    getTahun() {
      axios.get(`${window.location.origin}/api/ta/tahun?page=${this.tahuns.current_page}`)
          .then(response => {
            this.tahuns = response.data.data;
          })
          .catch(error => {
            this.itemstatus = error.response.data.message;
            console.log(error);
          });
    },
    addItem() {
      this.showLoadingAlert();
      axios.post(`${window.location.origin}/api/ta/tahun`, this.createItem)
      .then(response => {
        console.log(response);
        this.showSuccessAlert('Item added successfully!');
        this.createItem = {
          tahun: ''
        }
        this.getTahun();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.form_validation.tahun.invalid = !!error.response.data.errors.tahun;
          this.form_validation.tahun.feedback = error.response.data.errors.tahun.join(' & ');
          this.showErrorAlert('Failed to add item!', error.response.data.message);
        } else {
          console.log(error);
          this.showErrorAlert('Failed to add item!', error.response.status);
        }
      });
    },
    deleteItem(item) {
      this.showLoadingAlert();      
      axios.delete(`${window.location.origin}/api/ta/tahun/${item.tahun}`)
        .then(response => {
          console.log('berhasil delete', response.data.data);
          this.showSuccessAlert('Item Deleted successfully!');
          this.getTahun();
        })
        .catch(error => {
          if (error.response.status === 400) {
            console.log(error.response.data);
            this.showErrorAlert('Failed to delete item!', error.response.data);
          } else {
            console.log(error);
            this.showErrorAlert('Failed to delete item!', error.response.status);
          }
        });
    },
    showLoadingAlert() {
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
      });
    },
    showSuccessAlert(message) {
      Swal.fire({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        icon: "success",
        title: message
      });
    },
    showErrorAlert(message, error) {
      Swal.fire({
        title: `Error ${error.status}`,
        text: message,
        icon: 'error',
        details: error.message || error // Display detailed error message if available
      });
    },
  }
}
</script>
