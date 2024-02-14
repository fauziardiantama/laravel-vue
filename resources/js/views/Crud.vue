<template>
  <div>
    <CRow>
      <CCol :md="6">
        <CCard class="mb-4">
          <CCardHeader>Tambah item</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                <CFormLabel for="createname">Nama</CFormLabel>
                <CFormInput type="text" id="createname" placeholder="nama item" v-model="createItem.name"/>
              </div>
              <div class="mb-3">
                <CFormLabel for="createdescription">Deskripsi</CFormLabel>
                <CFormTextarea id="createdescription" rows="3" v-model="createItem.description" placeholder="deskripsi item"></CFormTextarea>
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
          <CCardHeader> Items </CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Name</CTableHeaderCell>
                  <CTableHeaderCell>Description</CTableHeaderCell>
                  <CTableHeaderCell>Actions</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="items.length > 0">
                <CTableRow v-for="(item, index) in items" :key="item.id">
                  <CTableDataCell class="text-center">
                      {{ index+1 }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ item.name }}
                  </CTableDataCell>
                  <CTableDataCell>
                    {{ item.description }}
                  </CTableDataCell>
                  <CTableDataCell>
                    <CButton color="primary" @click="openEditModal(item)">Edit</CButton>
                    <CButton color="danger" @click="deleteItem(item)">Delete</CButton>
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
  <CModal backdrop="static" :visible="showEditModal" @close="closeModal">
    <CModalHeader>
      <CModalTitle>Modal title</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CForm>
        <div class="mb-3">
          <CFormLabel for="editname">Nama</CFormLabel>
          <CFormInput type="text" id="editname" placeholder="nama item" v-model="activeItem.name"/>
        </div>
        <div class="mb-3">
          <CFormLabel for="editdescription">Deskripsi</CFormLabel>
          <CFormTextarea id="editdescription" rows="3" v-model="activeItem.description" placeholder="deskripsi item"></CFormTextarea>
        </div>
      </CForm>
    </CModalBody>
    <CModalFooter>
      <CButton color="secondary" @click="closeModal">
        Close
      </CButton>
      <CButton color="primary" @click="updateItem">Perbarui</CButton>
    </CModalFooter>
  </CModal>
</template>

<script>
import Swal from 'sweetalert2';

export default {
  name: 'Crud',
  components: {},
  data() {
    return {
      items: [],
      createItem: {
        name: '',
        description: ''
      },
      activeItem: {
        id : 0,
        name: '',
        description: ''
      },
      itemstatus: 'Mengambil items',
      showEditModal: false
    }
  },
  async created() {
    //like constructor
    try {
      const response = await axios.get(`${window.location.origin}/api/kmm/items`);
      this.items = response.data.data;
    } catch (e) {
      if (e.response.status === 404) {
        this.itemstatus = e.response.data.message;
        console.log(e.response.data);
      } else {
        console.log(e);
      }
    }
  },
  mounted() {
    //like update()
    console.log('Dashboard component mounted.');
    Echo.channel('items').listen('ItemAdded', (e) => {
      console.log(e);
      this.items.push(e.item);
    }).listen('ItemUpdated', (e) => {
      console.log(e);
      this.items = this.items.map(i => i.id === e.item.id ? e.item : i);
    }).listen('ItemDeleted', (e) => {
      console.log(e);
      this.items = this.items.filter(i => i.id !== e.id);
    });
  },
  methods: {
    addItem() {
      this.showLoadingAlert();
      axios.post(`${window.location.origin}/api/kmm/items`, {
        item : this.createItem
      })
      .then(response => {
        console.log(response);
        this.showSuccessAlert('Item added successfully!');
        this.createItem = {
          name: '',
          description: ''
        }
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.showErrorAlert('Failed to add item!', error.response.data);
        } else {
          console.log(error);
          this.showErrorAlert('Failed to add item!', error.response.status);
        }
      });
    },
    openEditModal(item) {
      this.activeItem = {
        id : item.id,
        name : item.name,
        description : item.description
      }
      this.showEditModal = true
    },
    closeModal() {
      this.activeItem = {
        id : 0,
        name : '',
        description : ''
      }
      this.showEditModal = false
    },
    updateItem() {
      this.showLoadingAlert();
      //get item's id which have same id with activeItem id
      const itemId = this.items.find(i => i.id === this.activeItem.id).id;

      axios.put(`${window.location.origin}/api/kmm/items/${itemId}`, {
          item : this.activeItem
      })
        .then(response => {
          console.log('berhasil update', response.data.data);
          this.showSuccessAlert('Item Updated successfully!');
          this.closeModal();
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
    deleteItem(item) {
      this.showLoadingAlert();
      const itemId = this.items.find(i => i.id === item.id).id;
      
      axios.delete(`${window.location.origin}/api/kmm/items/${itemId}`)
        .then(response => {
          console.log('berhasil delete', response.data.data);
          this.showSuccessAlert('Item Deleted successfully!');
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
