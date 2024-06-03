<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-6">Daftar Seminar</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getSeminars"/>
                <CInputGroupText @click="getSeminars" class="cursor-pointer">
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
                  :is-re-search="seminar.research"
                  :is-loading="seminar.isLoading"
                  :columns="seminar.columns"
                  :rows="seminar.rows"
                  :total="seminar.totalRecordCount"
                  :sortable="seminar.sortable"
                  :messages="seminar.messages"
                  @do-search="seminarSearch"
              >
                <template v-slot:id_seminar="data">
                  {{ data.value.index }}
                </template>
                <template v-slot:nim="data">
                  {{ data.value.magang?.mahasiswa?.nim ?? '-' }}
                </template>
                <template v-slot:nama="data">
                  {{ data.value.magang?.mahasiswa?.nama ?? '-' }}
                </template>
                <template v-slot:status="data">
                  {{ data.value.status == 1 ? 'Disetujui' : (data.value.status == -1 ? 'Ditolak' : 'Menunggu') }}
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
      <CModalTitle>Detail {{ activeSeminar.judul_kmm ?? '-' }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow>
        <CCol :md="4">
          <CLabel>NIM</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeSeminar.magang?.mahasiswa?.nim ?? '-'}}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Nama</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeSeminar.magang?.mahasiswa?.nama ?? '-'}}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Judul</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeSeminar.judul_kmm ?? '-' }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Tanggal Mendaftar</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeSeminar.tgl_daftar ?? '-' }}</p>
        </CCol>
      </CRow> 
      <CRow class="mb-3">
        <CCol :md="4">
          <CLabel>Tanggal Seminar</CLabel>
        </CCol>
        <CCol :md="8">
          <div>
            <CRow>
              <CCol :md="8">
              <CFormInput type="date" placeholder="tanggal seminar"
                  v-model="activeSeminar.tgl_seminar"
                  :feedback="edit_validation.tgl_seminar.feedback"
                  :invalid="edit_validation.tgl_seminar.invalid"
              />
              </CCol>
              <CCol :md="4">
                <CButton color="primary" @click="changeTgl">Update</CButton>
              </CCol>
            </CRow>
          </div>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Status</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeSeminar.status == 1 ? 'Disetujui' : (activeSeminar.status == -1 ? 'Ditolak' : 'Menunggu') }}</p>
        </CCol>
      </CRow>
      <!-- Draft KMM, foto, krs, lembar_revisi, daftar_hadir, selesai_kmm -->
      <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Draft KMM</CLabel>
          </CCol>
          <CCol :md="8">
            <a :href="`${app}/mahasiswa/magang/seminar/draft-kmm/${activeSeminar?.draft_kmm}`" target="_blank" class="dokumen-link">{{ activeSeminar.draft_kmm ?? '-' }}</a>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Foto</CLabel>
          </CCol>
          <CCol :md="8">
            <a :href="`${app}/mahasiswa/magang/seminar/foto/${activeSeminar?.foto}`" target="_blank" class="dokumen-link">{{ activeSeminar.foto ?? '-' }}</a>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>KRS</CLabel>
          </CCol>
          <CCol :md="8">
            <a :href="`${app}/mahasiswa/magang/seminar/krs/${activeSeminar?.krs}`" target="_blank" class="dokumen-link">{{ activeSeminar.krs ?? '-' }}</a>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Lembar Revisi</CLabel>
          </CCol>
          <CCol :md="8">
            <a :href="`${app}/mahasiswa/magang/seminar/lembar-revisi/${activeSeminar?.lembar_revisi}`" target="_blank" class="dokumen-link">{{ activeSeminar.lembar_revisi ?? '-' }}</a>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Daftar Hadir</CLabel>
          </CCol>
          <CCol :md="8">
            <a :href="`${app}/mahasiswa/magang/seminar/daftar-hadir/${activeSeminar?.daftar_hadir}`" target="_blank" class="dokumen-link">{{ activeSeminar.daftar_hadir ?? '-' }}</a>
          </CCol>
        </CRow>
        <CRow class="mb-3">
          <CCol :md="4">
            <CLabel>Selesai KMM</CLabel>
          </CCol>
          <CCol :md="8">
            <a :href="`${app}/mahasiswa/magang/seminar/selesai-kmm/${activeSeminar?.selesai_kmm}`" target="_blank" class="dokumen-link">{{ activeSeminar.selesai_kmm ?? '-' }}</a>
          </CCol>
        </CRow>
      </CModalBody>
    <CModalFooter>
      <CButton v-if="activeSeminar.status != 1" color="success" @click="approve(activeSeminar)">
        Approve
      </CButton>
      <CButton v-if="activeSeminar.status != -1" color="danger" @click="reject(activeSeminar)">
        Reject
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
  name: 'Seminar',
  data() {
    return {
      seminar: {
        isLoading: false,
        columns: [
          {
            label: "#",
            field: "id_seminar",
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
            label: "NIM",
            field: "nim",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "15%",
            sortable: true,
          },
          {
            label: "Nama",
            field: "nama",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "20%",
            sortable: true,
          },
          {
            label: "Judul KMM",
            field: "judul_kmm",
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
            label: "Status",
            field: "status",
            headerClasses: ["table-header", "text-center"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "15%",
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
          order: "id_seminar",
          sort: "desc",
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
      showDetailModal: false,
      activeSeminar: null,
      edit_validation: {
        tgl_seminar: {
          invalid: false,
          feedback: ''
        }
      },
      app: window.location.origin
    }
  },
  async created() {
    this.getSeminars();
  },
  mounted() {
    console.log('Dashboard component mounted.');
  },
  methods: {
    seminarSearch(offset, limit, order, sort) {
      this.seminar.isLoading = true;
      //calculate page based on offset and limit
      let page = offset / limit + 1;
      let url = `${window.location.origin}/api/kmm/seminar?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
      axios.get(url)
      .then((response) => {
        this.seminar.research = false;
        console.log(this.search == '' ? '[kosong]' : this.search);
        this.seminar.rows = response.data.data.data;
        //add iteration index and push to rows as 'index'
        let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
        this.seminar.rows.forEach((item, index) => {
          //calculate index based on current page
          item.index = index + 1 + pagination;
        });
        this.seminar.totalRecordCount = response.data.data.total;
        this.seminar.page = {
          limit: limit, 
          offset: offset,
        };
        this.seminar.sortable = {
          order: order,
          sort: sort
        };
        this.seminar.isLoading = false;
      });
    },
    getSeminars() {
      this.search = document?.getElementById('search')?.value ?? this.search;
      this.seminar.totalRecordCount = 0;
      this.seminar.rows = [];
      this.seminar.page = {
        limit: 10,
        offset: 0
      };
      this.seminar.research = true;
      this.seminarSearch(this.seminar.page.offset, this.seminar.page.limit, this.seminar.sortable.order, this.seminar.sortable.sort);
    },
    openDetailModal(item) {
      this.activeSeminar = item;
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeSeminar = null;
    },
    approve(item) {
      console.log(item.id_seminar);
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/seminar/${item.id_seminar}/approve`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil menyetujui seminar');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getSeminars();
      })
      .catch(e => {
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
        console.log(e);
      });
    },
    reject(item) {
      console.log(item.id_seminar);
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${window.location.origin}/api/kmm/seminar/${item.id_seminar}/reject`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil menolak seminar');
        this.closeModal();
        this.openDetailModal(response.data.data);
        this.getSeminars();
      })
      .catch(e => {
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
        console.log(e);
      });
    },
    changeTgl() {
      this.$store.dispatch('showLoadingAlert');

      axios.put(`${this.app}/api/kmm/seminar/${this.activeSeminar.id_seminar}/tgl_seminar`, {
          tgl_seminar: this.activeSeminar.tgl_seminar
      })
      .then( response => {
          this.$store.dispatch('showSuccessAlert', 'Berhasil mengupdate file!');
          this.getSeminar();
          this.closeModal();
          this.openDetailModal(response.data.data);
      }).catch( e => {
              console.log(e);
              if (e.response.status === 422) {
                  this.edit_validation = {
                      tgl_seminar: {
                          invalid: !!e.response.data.errors.tgl_seminar,
                          feedback: e.response.data.errors.tgl_seminar ? e.response.data.errors.tgl_seminar.join(' & ') : ""
                      }
                  }
              }
              this.$store.dispatch('showErrorAlert', {
                  title: 'Gagal mengupdate file!',
                  message: e.response.data.message
              });
      });
    }
  }
}
</script>
