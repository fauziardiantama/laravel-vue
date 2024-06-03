<template>
  <div>
    <CRow>
      <CCol :md="5">
        <CCard class="mb-4">
          <CCardHeader>Tambah Informasi</CCardHeader>
          <CCardBody>
            <CForm>
              <div class="mb-3">
                <CFormLabel for="judul">Judul</CFormLabel>
                <CFormInput type="text" id="judul" placeholder="Judul Informasi"
                  v-model="form.judul"
                  :feedback="form_validation.judul.feedback"
                  :invalid="form_validation.judul.invalid" />
              </div>
              <div class="mb-3">
                <CFormLabel for="deskripsi">Deskripsi</CFormLabel>
                <CFormTextarea
                  id="deskripsi"
                  rows="3"
                  placeholder="deskripsi"
                  v-model="form.deskripsi"
                  :feedback="form_validation.deskripsi.feedback"
                    :invalid="form_validation.deskripsi.invalid"
                ></CFormTextarea>
              </div>
              <div class="mb-3">
                <CFormLabel for="judul">Judul Informasi</CFormLabel>
                <CFormInput
                      type="file"
                      placeholder="Dokumen terlampir" required
                      @change="handleFileUpload"
                      :feedback="form_validation.dokumen.feedback"
                      :invalid="form_validation.dokumen.invalid"
                      id="file"
                    />
              </div>
              <div class="mb-3 text-center">
                <CRow>
                  <CCol :md="6">
                    <CButton color="primary" class="w-100" @click="addItem(false)">Tambah</CButton>
                  </CCol>
                  <CCol :md="6">
                    <CButton color="success" class="w-100" @click="addItem(true)">Publish</CButton>
                  </CCol>
                </CRow>
              </div>
            </CForm>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="7">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-6">Daftar Instansi</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getInformasis"/>
                <CInputGroupText @click="getInformasis" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
          </CCardHeader>
          <CCardBody>
            <table-lite
                class="table-lite"
                :pageOptions="[ { value: 10, text: 10 }, { value: 25, text: 25 }, { value: 50, text: 50 }, { value: 100, text: 100 } ]"
                :is-slot-mode="true"
                :is-re-search="informasi.research"
                :is-loading="informasi.isLoading"
                :columns="informasi.columns"
                :rows="informasi.rows"
                :total="informasi.totalRecordCount"
                :sortable="informasi.sortable"
                :messages="informasi.messages"
                @do-search="informasiSearch"
            >
              <template v-slot:id_informasi="data">
                {{ data.value.index }}
              </template>
              <template v-slot:status_publikasi="data">
                <CBadge v-if="data.value.status_publikasi == 1" color="success">dipublish</CBadge>
                <CBadge v-if="data.value.status_publikasi == 0" color="secondary">draft</CBadge>
              </template>
              <template v-slot:none="data">
                <CButton color="primary" @click="openDetailModal(data.value)">Detail</CButton>
              </template>
            </table-lite>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  </div>
  <CModal size="lg" backdrop="static" :visible="showDetailModal" @close="closeModal">
        <CModalHeader>
        <CModalTitle>Detail {{ activeInformasi?.judul ?? '-' }}</CModalTitle>
        </CModalHeader>
        <CModalBody>
        <CRow>
            <CCol :md="4">
            <CLabel><b>Tanggal</b></CLabel>
            </CCol>
            <CCol :md="8">
            <p>{{ activeInformasi?.tanggal }}</p>
            </CCol>
        </CRow>
        <CRow>
            <CCol :md="4">
            <CLabel><b>Judul</b></CLabel>
            </CCol>
            <CCol :md="8">
            <p v-if="!edit">{{ activeInformasi?.judul }}</p>
            <CFormInput v-else type="text" v-model="activeInformasi.judul"
                :feedback="edit_validation.judul.feedback"
                :invalid="edit_validation.judul.invalid" />
            </CCol>
        </CRow>
        <CRow>
            <CCol :md="4">
            <CLabel><b>Deskripsi</b></CLabel>
            </CCol>
        </CRow>
        <div v-if="!edit" v-html="activeInformasi?.deskripsi"></div>
        <CFormTextarea v-else
            id="deskripsi"
            rows="3"
            placeholder="deskripsi"
            v-model="activeInformasi.deskripsi"
            :feedback="edit_validation.deskripsi.feedback"
            :invalid="edit_validation.deskripsi.invalid"
        ></CFormTextarea>
        <div class="mb-3"></div>
        <CRow>
            <CCol :md="4">
            <CLabel><b>Dokumen</b></CLabel>
            </CCol>
            <CCol v-if="!edit" :md="8">
                <a v-if="activeInformasi?.dokumen" :href="app+'/kmm/informasi/'+activeInformasi?.dokumen" target="_blank" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ activeInformasi?.dokumen }}</a>
                <p v-else>-</p>
            </CCol>
            <CCol v-else :md="8">
                <CFormInput
                    type="file"
                    placeholder="Dokumen terlampir" required
                    @change="handleFileEdit"
                    :feedback="edit_validation.dokumen.feedback"
                    :invalid="edit_validation.dokumen.invalid"
                    id="editfile"
                />
            </CCol>
        </CRow>
        <div class="mb-3"></div>
        <CRow>
            <CCol :md="4">
            <CLabel><b>Status Publikasi</b></CLabel>
            </CCol>
            <CCol :md="8">
            <p>{{ activeInformasi?.status_publikasi ? 'Published' : 'Unpublished' }}</p>
            </CCol>
        </CRow>
        </CModalBody>
        <CModalFooter>
          <CButton color="primary" v-if="!edit" @click="edit = !edit">
              Edit
          </CButton>
          <CButton color="success" v-else @click="update(activeInformasi)">
              Save
          </CButton>
          <CButton color="success" v-if="!edit" @click="publish(activeInformasi)">
              Publish
          </CButton>
          <CButton color="danger" v-if="!edit" @click="unpublish(activeInformasi)">
              Unpublish
          </CButton>
          <CButton color="danger" @click="deleteTopik(activeInformasi)">
              Delete
          </CButton>
          <CButton color="secondary" @click="closeModal">
              Close
          </CButton>
        </CModalFooter>
    </CModal>
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
  .cursor-pointer {
    cursor: pointer;
  }
  .table-header {
    background: #f0f2f4;
    color: rgba(44, 56, 74, 0.95);
    border: 1px solid rgba(200, 204, 209, 0.99);
  }

  :deep(table.vtl-table) {
    display: table !important;
  }
</style>

<script>
export default {
  name: 'Informasi',
  data() {
    return {
      informasi: {
        isLoading: false,
        columns : [
          {
            label: "#",
            field: "id_informasi",
            headerClasses: ["table-header","text-center"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "10%",
            sortable: true,
            isKey: true,
          },
          {
            label: "Judul Informasi",
            field: "judul",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "50%",
            sortable: true,
          },
          {
            label: "Status Publikasi",
            field: "status_publikasi",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "25%",
            sortable: true,
          },
          {
            label: "Action",
            field: "none",
            width: "15%",
            sortable: false,
            headerClasses: ["table-header", "text-center"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            }
          },
        ],
        rows: [],
        totalRecordCount: 0,
        sortable: {
          order: "id_informasi",
          sort: "desc"
        },
        messages: {
          pagingInfo: "{0}-{1}/{2}",
          pageSizeChangeLabel: "Per Halaman ",
          gotoPageLabel: " Ke Hal. ",
          noDataAvailable: "Tidak ada data",
        },
        page: {
          limit: 10,
          offset: 0
        },
        research: false
      },
      search: '',
      form: {
        judul: '',
        deskripsi: '',
        dokumen: '',
        status_publikasi: false
      },
      form_validation: {
        judul: {
          invalid: false,
          feedback: ''
        },
        deskripsi: {
          invalid: false,
          feedback: ''
        },
        dokumen: {
          invalid: false,
          feedback: ''
        }
      },
      edit_validation: {
        judul: {
          invalid: false,
          feedback: ''
        },
        deskripsi: {
          invalid: false,
          feedback: ''
        },
        dokumen: {
          invalid: false,
          feedback: ''
        }
      },
      itemstatus: 'Mengambil items',
      showDetailModal: false,
      activeInformasi: null,
      editFile : null,
      edit: false,
      app: window.location.origin
    }
  },
  created() {
    this.getInformasis();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen('Tpk', (e) => {
      this.getTopiks();
      if (e.item.id_topik === this.activeTopik.id) {
        if (e.type === 'destroy') {
          this.closeModal();
        }
        if (e.type === 'update') {
          this.openDetailModal(e.item);
        }
      }
    });
  },
  methods: {
    informasiSearch(offset, limit, order, sort) {
      this.informasi.isLoading = true;
      //calculate page based on offset and limit
      let page = offset / limit + 1;
      let url = `${window.location.origin}/api/kmm/informasi/all?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
      axios.get(url)
      .then((response) => {
        this.informasi.research = false;
        console.log(this.search == '' ? '[kosong]' : this.search);
        this.informasi.rows = response.data.data.data;
        //add iteration index and push to rows as 'index'
        let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
        this.informasi.rows.forEach((item, index) => {
          //calculate index based on current page
          item.index = index + 1 + pagination;
        });
        this.informasi.totalRecordCount = response.data.data.total;
        this.informasi.page = {
          limit: limit, 
          offset: offset,
        };
        this.informasi.sortable = {
          order: order,
          sort: sort
        };
        this.informasi.isLoading = false;
      });
    },
    getInformasis() {
      this.search = document?.getElementById('search')?.value ?? this.search;
      this.informasi.totalRecordCount = 0;
      this.informasi.rows = [];
      this.informasi.page = {
        limit: 10,
        offset: 0
      };
      this.informasi.research = true;
      this.informasiSearch(this.informasi.page.offset, this.informasi.page.limit, this.informasi.sortable.order, this.informasi.sortable.sort);
    },
    addItem(status_publikasi = false) {
      let formData = new FormData();
      formData.append('judul', this.form.judul);
      formData.append('deskripsi', this.form.deskripsi);
      formData.append('dokumen', this.form.dokumen);
      formData.append('status_publikasi', status_publikasi);
      
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${this.app}/api/kmm/informasi`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        console.log(response);
        this.$store.dispatch('showSuccessAlert', 'Item Added successfully!');
        this.emptyFile('file');
        this.form = {
          judul: '',
          deskripsi: '',
          dokumen: '',
          status_publikasi: false
        };
        //remove file from input
        this.getInformasis();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.form_validation = {
            judul: {
              invalid: !!error.response.data.errors.judul,
              feedback: error.response.data.errors.judul.join(' & ')
            },
            deskripsi: {
              invalid: !!error.response.data.errors.deskripsi,
              feedback: error.response.data.errors.deskripsi.join(' & ')
            },
            dokumen: {
              invalid: !!error.response.data.errors.dokumen,
              feedback: error.response.data.errors.dokumen.join(' & ')
            }
          }
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambah informasi!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menambah informasi!',
            message: error.response.status
          });
        }
      });
    },
    update(item) {
      let formData = new FormData();
      formData.append('judul', this.activeInformasi.judul);
      formData.append('deskripsi', this.activeInformasi.deskripsi);
      formData.append('dokumen', this.editFile);
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${this.app}/api/kmm/informasi/${item.id_informasi}/update`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        console.log('berhasil update', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
        this.emptyFile('editfile');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getInformasis();
      })
      .catch(error => {
        if (error.response.status === 422) {
          this.edit_validation = {
            judul: {
              invalid: !!error.response.data.errors.judul,
              feedback: error.response.data.errors.judul.join(' & ')
            },
            deskripsi: {
              invalid: !!error.response.data.errors.deskripsi,
              feedback: error.response.data.errors.deskripsi.join(' & ')
            },
            dokumen: {
              invalid: !!error.response.data.errors.dokumen,
              feedback: error.response.data.errors.dokumen.join(' & ')
            }
          }
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengubah topik!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengubah topik!',
            message: error.response.data.message
          });
        }
      });
    },
    publish(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${this.app}/api/kmm/informasi/${item.id_informasi}/publish`)
      .then(response => {
        console.log('berhasil publish', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Published successfully!');
        this.closeModal();
        this.getInformasis();
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
          title: 'Gagal mempublish item!',
          message: error.response.data.message
        });
      });
    },
    unpublish(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${this.app}/api/kmm/informasi/${item.id_informasi}/unpublish`)
      .then(response => {
        console.log('berhasil unpublish', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Unpublished successfully!');
        this.closeModal();
        this.getInformasis();
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
          title: 'Gagal mempublish item!',
          message: error.response.data.message
        });
      });
    },
    deleteInformasi(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.delete(`${this.app}/api/kmm/informasi/${item.id_informasi}`)
      .then(response => {
        console.log('berhasil delete', response.data.data);
        this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
        this.closeModal();
        this.getInformasis();
      })
      .catch(error => {
        console.log(error);
        this.$store.dispatch('showErrorAlert', {
          title: 'Gagal menghapus item!',
          message: error.response.data.message
        });
      });
    },
    openDetailModal(item) {
      this.activeInformasi = item;
      this.edit_validation = {
        judul: {
          invalid: false,
          feedback: ''
        },
        deskripsi: {
          invalid: false,
          feedback: ''
        },
        dokumen: {
          invalid: false,
          feedback: ''
        }
      }
      this.showDetailModal = true;
    },
    closeModal() {
      this.edit = false;
      this.showDetailModal = false;
      this.activeInformasi = null;
    },
    handleFileUpload( event ){
      this.form.dokumen = event.target.files[0];
    },
    handleFileEdit( event ){
      this.editFile = event.target.files[0];
    },
    emptyFile(file) {
      if (document.getElementById(file)) {
        document.getElementById(file).value = '';
      }
    },
  }
}
</script>
