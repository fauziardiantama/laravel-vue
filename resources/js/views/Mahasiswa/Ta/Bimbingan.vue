<template>
    <div>
      <CRow>
        <CCol :md="12">
          <CCard class="mb-4">
            <CCardHeader class="row">
                <p class="col-6">Daftar Bimbingan dosen</p>
                <!--search bar-->
                <div class="col-6">
                    <CInputGroup v-if="magang_diterima && jawaban_diterima && proposal_diterima">
                        <CFormInput type="text" placeholder="Search" id="searchBimbinganDosen" :value="searchBimbinganDosen" @keyup.enter="getBimbinganDosen"/>
                        <CInputGroupText @click="getBimbinganDosen" class="cursor-pointer">
                        <font-awesome-icon :icon="['fas', 'search']" />
                        </CInputGroupText>
                    </CInputGroup>
                </div>
            </CCardHeader>
            <CCardBody v-if="magang_diterima && jawaban_diterima && proposal_diterima">
                <table-lite
                    class="table-lite"
                    :is-slot-mode="true"
                    :is-re-search="bimbinganDosen.research"
                    :is-loading="bimbinganDosen.isLoading"
                    :columns="bimbinganDosen.columns"
                    :rows="bimbinganDosen.rows"
                    :total="bimbinganDosen.totalRecordCount"
                    :sortable="bimbinganDosen.sortable"
                    :messages="bimbinganDosen.messages"
                    @do-search="bimbinganDosenSearch"
                >
                    <template v-slot:id_bimbingan_dosen="data">
                        {{ data.value.index }}
                    </template>
                    <template v-slot:status="data">
                        <CBadge v-if="data.value.status == 1" color="success">disetujui</CBadge>
                        <CBadge v-if="data.value.status == 0" color="warning">menunggu</CBadge>
                        <CBadge v-if="data.value.status == -1" color="danger">ditolak</CBadge>
                    </template>
                    <template v-slot:none="data">
                        <CButton color="primary" v-if="data.value.status != 1" @click="openEditDosenModal(data.value)">Edit</CButton>
                        <CButton color="danger" v-if="data.value.status != 1" @click="deleteBimbinganDosen(data.value)">Delete</CButton>
                    </template>
                </table-lite>   
            </CCardBody>            
            <CCardBody v-else>
                <p>Pastikan hal berikut ini : Anda sudah diterima magang, surat jawaban sudah disetujui, magang terhubung dengan proposal TA, dan proposal TA sudah diterima</p>
            </CCardBody>
            <CCardFooter v-if="magang_diterima && jawaban_diterima && proposal_diterima">
                <CButton color="primary" @click="showTambahDosenModal = true">Tambah</CButton>
            </CCardFooter>
            <CCardFooter v-else>
            </CCardFooter>
          </CCard>
        </CCol>
      </CRow>
    </div>
    <CModal size="lg" backdrop="static" :visible="showEditDosenModal" @close="closeModal">
        <CModalHeader>
            <CModalTitle>Edit Bimbingan Dosen</CModalTitle>
        </CModalHeader>
        <CModalBody>
            <CRow>
                <CCol>
                    <CFormInput type="date" id="tanggal_dosen_edit" placeholder="Tanggal"
                        label="Tanggal"
                        v-model="activeBimbinganDosen.tanggal"
                        :feedback="edit_dosen_validation.tanggal.feedback"
                        :invalid="edit_dosen_validation.tanggal.invalid" />
                </CCol>
            </CRow>
            <CRow>
                <CCol>
                    <CFormTextarea id="data_bimbingan_dosen_edit" placeholder="Bimbingan"
                    label="Bimbingan"
                    v-model="activeBimbinganDosen.data_bimbingan"
                    :feedback="edit_dosen_validation.data_bimbingan.feedback"
                    :invalid="edit_dosen_validation.data_bimbingan.invalid" />
                </CCol>
            </CRow>
        </CModalBody>
        <CModalFooter>
            <CButton color="success" @click="putBimbinganDosen">Update</CButton>
            <CButton color="secondary" @click="closeModal">
                Close
            </CButton>
        </CModalFooter>
    </CModal>
    <CModal size="lg" backdrop="static" :visible="showTambahDosenModal" @close="closeModal">
        <CModalHeader>
            <CModalTitle>Tambah Bimbingan Dosen</CModalTitle>
        </CModalHeader>
        <CModalBody>
            <CRow>
                <CCol>
                    <CFormInput type="date" id="tanggal_dosen" placeholder="Tanggal"
                        label="Tanggal"
                        v-model="form_dosen.tanggal"
                        :feedback="form_dosen_validation.tanggal.feedback"
                        :invalid="form_dosen_validation.tanggal.invalid" />
                </CCol>
            </CRow>
            <CRow>
                <CCol>
                    <CFormTextarea id="data_bimbingan_dosen" placeholder="Bimbingan"
                    label="Bimbingan"
                    v-model="form_dosen.data_bimbingan"
                    :feedback="form_dosen_validation.data_bimbingan.feedback"
                    :invalid="form_dosen_validation.data_bimbingan.invalid" />
                </CCol>
            </CRow>
        </CModalBody>
        <CModalFooter>
            <CButton color="success" @click="postBimbinganDosen">Tambah</CButton>
            <CButton color="secondary" @click="closeModal">
                Close
            </CButton>
        </CModalFooter>
    </CModal>
  </template>

  <style scoped>
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
    name: 'Bimbingan',
    data() {
      return {
        magang: null,
        bimbinganDosen: {
            isLoading: false,
            columns: [
                {
                    label: "#",
                    field: "id_bimbingan_dosen",
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
                    label: "Tanggal",
                    field: "tanggal",
                    headerClasses: ["table-header"],
                    headerStyles: 
                    {
                        background: "#f0f2f4",
                        color: "rgba(44, 56, 74, 0.95)",
                        border: "1px solid rgba(200, 204, 209, 0.99)",
                    },
                    width: "20%",
                    sortable: true
                },
                {
                    label: "Bimbingan",
                    field: "data_bimbingan",
                    headerClasses: ["table-header"],
                    headerStyles: 
                    {
                        background: "#f0f2f4",
                        color: "rgba(44, 56, 74, 0.95)",
                        border: "1px solid rgba(200, 204, 209, 0.99)",
                    },
                    width: "40%",
                    sortable: true
                },
                {
                    label: "Status",
                    field: "status",
                    headerClasses: ["table-header"],
                    headerStyles: 
                    {
                        background: "#f0f2f4",
                        color: "rgba(44, 56, 74, 0.95)",
                        border: "1px solid rgba(200, 204, 209, 0.99)",
                    },
                    width: "15%",
                    sortable: true
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
                order: "id_bimbingan_dosen",
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
        form_dosen: {
            tanggal: '',
            data_bimbingan: '',
        },
        form_dosen_validation: {
            tanggal: {
                feedback: '',
                invalid: false
            },
            data_bimbingan: {
                feedback: '',
                invalid: false
            }
        },
        searchBimbinganDosen: '',
        showTambahDosenModal: false,
        showEditDosenModal: false,
        activeBimbinganDosen: null,
        edit_dosen_validation: {
            tanggal: {
                feedback: '',
                invalid: false
            },
            data_bimbingan: {
                feedback: '',
                invalid: false
            }
        }
      }
    },
    computed: {
        magang_diterima() {
            return this.magang?.status_dosen == 1;
        },
        jawaban_diterima() {
            return this.magang?.status_diterima_instansi == 1;
        },
        proposal_diterima() {
            return this.magang?.proposal_ta?.status_proposal == 1;
        }
    },
    watch: {
        '$store.state.user': {
            immediate: true,
            handler(user) {
                if (user != null) {
                    this.user = user;
                    this.getMagang();
                    this.getBimbinganDosen();
                }
            }
        }
    },
    methods: {
        getMagang() {
            axios.get(`${window.location.origin}/api/kmm/magang`)
            .then((response) => {
                this.magang = response.data.data;
            })
            .catch((error) => {
                console.log(error);
            });
        },
        bimbinganDosenSearch(offset, limit, order, sort) {
            this.bimbinganDosen.isLoading = true;
            //calculate page based on offset and limit
            let page = offset / limit + 1;
            let url = `${window.location.origin}/api/kmm/magang/bimbingan/dosen?kueri=${this.searchBimbinganDosen}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
            axios.get(url)
            .then((response) => {
                this.bimbinganDosen.research = false;
                this.bimbinganDosen.rows = response.data.data.data;
                //add iteration index and push to rows as 'index'
                let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
                this.bimbinganDosen.rows.forEach((item, index) => {
                    //calculate index based on current page
                    item.index = index + 1 + pagination;
                });
                this.bimbinganDosen.totalRecordCount = response.data.data.total;
                this.bimbinganDosen.page = {
                    limit: limit, 
                    offset: offset,
                };
                this.bimbinganDosen.sortable = {
                    order: order,
                    sort: sort
                };
                this.bimbinganDosen.isLoading = false;
            });
        },
        getBimbinganDosen() {
            this.searchBimbinganDosen = document?.getElementById('searchBimbinganDosen')?.value ?? this.searchBimbinganDosen;
            this.bimbinganDosen.totalRecordCount = 0;
            this.bimbinganDosen.rows = [];
            this.bimbinganDosen.page = {
                limit: 10,
                offset: 0
            };
            this.bimbinganDosen.research = true;
            this.bimbinganDosenSearch(this.bimbinganDosen.page.offset, this.bimbinganDosen.page.limit, this.bimbinganDosen.sortable.order, this.bimbinganDosen.sortable.sort);
        },
        postBimbinganDosen() {
            this.$store.dispatch('showLoadingAlert');
            axios.post(`${window.location.origin}/api/kmm/magang/bimbingan/dosen`, this.form_dosen)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
                this.getBimbinganDosen();
                this.showTambahDosenModal = false;
            })
            .catch((error) => {
                if (error.response.status === 422) {
                    this.form_dosen_validation = {
                        tanggal: {
                            invalid: !!error.response.data.errors.tanggal,
                            feedback: error.response.data.errors.tanggal.join(' & ')
                        },
                        data_bimbingan: {
                            invalid: !!error.response.data.errors.data_bimbingan,
                            feedback: error.response.data.errors.data_bimbingan.join(' & ')
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal menambahkan bimbingan dosen!',
                        message: error.response.data.message
                    });
                } else {
                    console.log(error);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${error.response.status}`,
                        message: error.response.data.message
                    });
                }
            });
        },
        openEditDosenModal(item) {
            this.activeBimbinganDosen = item;
            this.showEditDosenModal = true;
        },
        putBimbinganDosen() {
            this.$store.dispatch('showLoadingAlert');
            axios.put(`${window.location.origin}/api/kmm/magang/bimbingan/dosen/${this.activeBimbinganDosen.id_bimbingan_dosen}`, this.activeBimbinganDosen)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Item Updated successfully!');
                this.getBimbinganDosen();
                this.showEditDosenModal = false;
            })
            .catch((error) => {
                if (error.response.status === 422) {
                    this.edit_dosen_validation = {
                        tanggal: {
                            invalid: !!error.response.data.errors.tanggal,
                            feedback: error.response.data.errors.tanggal.join(' & ')
                        },
                        data_bimbingan: {
                            invalid: !!error.response.data.errors.data_bimbingan,
                            feedback: error.response.data.errors.data_bimbingan.join(' & ')
                        }
                    }
                    this.$store.dispatch('showErrorAlert', {
                        title: 'Gagal mengubah bimbingan dosen!',
                        message: error.response.data.message
                    });
                } else {
                    console.log(error);
                    this.$store.dispatch('showErrorAlert', {
                        title: `Error ${error.response.status}`,
                        message: error.response.data.message
                    });
                }
            });
        },
        deleteBimbinganDosen(item) {
            this.$store.dispatch('showLoadingAlert');
            axios.delete(`${window.location.origin}/api/kmm/magang/bimbingan/dosen/${item.id_bimbingan_dosen}`)
            .then((response) => {
                this.$store.dispatch('showSuccessAlert', 'Item Deleted successfully!');
                this.getBimbinganDosen();
            })
            .catch((error) => {
                console.log(error);
                this.$store.dispatch('showErrorAlert', {
                    title: `Error ${error.response.status}`,
                    message: error.response.data.message
                });
            });
        },
        closeModal() {
            this.showTambahDosenModal = false;
            this.activeBimbinganDosen = null;
            this.showEditDosenModal = false;
        }
    }
  }
  </script>
  