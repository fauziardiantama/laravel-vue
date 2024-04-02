<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-6">Daftar Jawaban</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="searchJawaban" :value="searchJawaban" @keyup.enter="getJawabans"/>
                <CInputGroupText @click="getJawabans" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
          </CCardHeader>
          <CCardBody>
            <table-lite
                  class="table-lite"
                  :is-slot-mode="true"
                  :is-re-search="jawaban.research"
                  :is-loading="jawaban.isLoading"
                  :columns="jawaban.columns"
                  :rows="jawaban.rows"
                  :total="jawaban.totalRecordCount"
                  :sortable="jawaban.sortable"
                  :messages="jawaban.messages"
                  @do-search="jawabanSearch"
              >
                <template v-slot:id_surat="data">
                  {{ data.value.index }}
                </template>
                <template v-slot:nim="data">
                  {{ data.value.magang?.mahasiswa?.nim ?? '-' }}
                </template>
                <template v-slot:nama="data">
                  {{ data.value.magang?.mahasiswa?.nama ?? '-' }}
                </template>
                <template v-slot:file_surat="data">
                  <a href="javascript:void(0)" class="dokumen-link" :id="'jawaban-'+data.value.id_surat" @click="getDokumenJ(data.value)">{{ data.value.file_surat ?? '-' }}</a>
                </template>
                <template v-slot:status_diterima_instansi="data">
                  <CBadge v-if="data.value.magang?.status_diterima_instansi == 1" color="success">disetujui</CBadge>
                  <CBadge v-if="data.value.magang?.status_diterima_instansi == -1" color="danger">Ditolak</CBadge>
                  <CBadge v-if="data.value.magang?.status_diterima_instansi == 0" color="warning">Menunggu</CBadge>
                </template>
                <template v-slot:none="data">
                    <CButton color="success" @click="approve(data.value)">Approve</CButton>
                    <CButton color="danger" @click="reject(data.value)">Tolak</CButton>
                </template>
            </table-lite>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-6">Daftar Surat Magang</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="searchSurat" :value="searchSurat" @keyup.enter="getSurats"/>
                <CInputGroupText @click="getSurats" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
          </CCardHeader>
          <CCardBody>
            <table-lite
                  class="table-lite"
                  :is-slot-mode="true"
                  :is-re-search="surat.research"
                  :is-loading="surat.isLoading"
                  :columns="surat.columns"
                  :rows="surat.rows"
                  :total="surat.totalRecordCount"
                  :sortable="surat.sortable"
                  :messages="surat.messages"
                  @do-search="suratSearch"
              >
              <template v-slot:id_surat="data">
                  {{ data.value.index }}
                </template>
                <template v-slot:nim="data">
                  {{ data.value.magang?.nim ?? '-' }}
                </template>
                <template v-slot:jenis_surat="data">
                  {{ data.value.jenis_surat == 1 ? 'Surat Pengantar' : (data.value.jenis_surat == 2 ? 'Surat serah terima' : '-') }}
                </template>
                <template v-slot:status_pengajuan_instansi="data">
                  <CBadge v-if="data.value.magang?.status_pengajuan_instansi == 1" color="success">Sudah mengajukan</CBadge>
                  <CBadge v-else color="secondary">Belum mengajukan</CBadge>
                </template>
                <template v-slot:file_surat="data">
                  <a href="javascript:void(0)" :id="'magang-'+data.value.id_surat" class="dokumen-link" @click="getDokumen(data.value)">{{ data.value.file_surat ?? '-' }}</a>
                </template>
            </table-lite>
          </CCardBody>
        </CCard>
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
import TableLite from "vue3-table-lite";


export default {
  name: 'Surat',
  components: {
    TableLite
  },
  data() {
    return {
      jawaban: {
        isLoading: false,
        columns: [
          {
            label: "#",
            field: "id_surat",
            headerClasses: ["table-header", "text-center"],
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
            width: "10%",
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
            label: "File Surat",
            field: "file_surat",
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
            label: "Status Diterima Instansi",
            field: "status_diterima_instansi",
            headerClasses: ["table-header"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)"
            },
            width: "20%",
            sortable: true,
          },
          {
            label: "Action",
            field: "none",
            headerClasses: ["table-header"],
            columnClasses: ["text-center"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "25%",
            sortable: false,
          }
        ],
        rows: [],
        totalRecordCount: 0,
        sortable: {
          order: "id_surat",
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
      surat: {
        isLoading: false,
        columns: [
          {
            label: "#",
            field: "id_surat",
            headerClasses: ["table-header", "text-center"],
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
            width: "10%",
            sortable: true,
          },
          {
            label: "Jenis Surat",
            field: "jenis_surat",
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
            label: "Status Pengajuan Instansi",
            field: "status_pengajuan_instansi",
            headerClasses: ["table-header"],
            columnClasses: ["text-center"],
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
            label: "File Surat",
            field: "file_surat",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "25%",
            sortable: true,
          }
        ],
        rows: [],
        totalRecordCount: 0,
        sortable: {
          order: "id_surat",
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
      searchJawaban: '',
      searchSurat: ''
    }
  },
  async created() {
    console.log('Dashboard component created.');
    this.getSurats();
    this.getJawabans();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen("Mgng", (e) => {
      console.log(e);
      this.getMagangs();
      if (e.type == "update" && e.item.id_magang == this.activeMagang?.id_magang) {
          this.openDetailModal(e.item);
        } else if (e.type == "destroy" && e.item.id_magang == this.activeMagang?.id_magang) {
          this.closeModal();
        }
    });
  },
  methods: {
    suratSearch(offset, limit, order, sort) {
      this.surat.isLoading = true;
      let page = offset / limit + 1;
      let url = `${window.location.origin}/api/kmm/surat_magang/all?kueri=${this.searchSurat}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
      axios.get(url)
      .then((response) => {
        this.surat.research = false;
        this.surat.rows = response.data.data.data;
        let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
        this.surat.rows.forEach((item, index) => {
          item.index = index + 1 + pagination;
        });
        this.surat.totalRecordCount = response.data.data.total;
        this.surat.page = {
          limit: limit, 
          offset: offset,
        };
        this.surat.sortable = {
          order: order,
          sort: sort
        };
        this.surat.isLoading = false;
      });
    },
    getSurats() {
      this.searchSurat = document?.getElementById('searchSurat')?.value ?? this.searchSurat;
      this.surat.totalRecordCount = 0;
      this.surat.rows = [];
      this.surat.page = {
        limit: 10,
        offset: 0
      };
      this.surat.research = true;
      this.suratSearch(this.surat.page.offset, this.surat.page.limit, this.surat.sortable.order, this.surat.sortable.sort);
    },
    jawabanSearch(offset, limit, order, sort) {
      this.jawaban.isLoading = true;
      let page = offset / limit + 1;
      let url = `${window.location.origin}/api/kmm/surat_jawaban/all?kueri=${this.searchJawaban}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
      axios.get(url)
      .then((response) => {
        this.jawaban.research = false;
        this.jawaban.rows = response.data.data.data;
        let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
        this.jawaban.rows.forEach((item, index) => {
          item.index = index + 1 + pagination;
        });
        this.jawaban.totalRecordCount = response.data.data.total;
        this.jawaban.page = {
          limit: limit, 
          offset: offset,
        };
        this.jawaban.sortable = {
          order: order,
          sort: sort
        };
        this.jawaban.isLoading = false;
      });
    },
    getJawabans() {
      this.searchJawaban = document?.getElementById('searchJawaban')?.value ?? this.searchJawaban;
      this.jawaban.totalRecordCount = 0;
      this.jawaban.rows = [];
      this.jawaban.page = {
        limit: 10,
        offset: 0
      };
      this.jawaban.research = true;
      this.jawabanSearch(this.jawaban.page.offset, this.jawaban.page.limit, this.jawaban.sortable.order, this.jawaban.sortable.sort);
    },
    approve(item) {
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/kmm/magang/${item.magang?.id_magang}/instansi/approve`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil menyetujui magang');
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
      this.$store.dispatch('showLoadingAlert');
      axios.post(`${window.location.origin}/api/kmm/magang/${item.magang?.id_magang}/instansi/reject`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil menolak magang');
      })
      .catch(e => {
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
        console.log(e);
      });
    },
    getDokumen(surat) {
      this.$store.dispatch('showLoadingAlert');
      axios.get(`${window.location.origin}/api/kmm/surat_magang/all/${surat.id_surat}/dokumen`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil mengambil dokumen')
        surat = response.data.data;
        if (surat != null) {
          const url = `${window.location.origin}/mahasiswa/magang/${ surat.jenis_surat == 1 ? 'surat-pengantar' : (surat.jenis_surat == 2 ? 'surat-serah-terima' : '')}/${surat.token}/${surat.file_surat}`;
          console.log(url);
          //change href to a with id = surat.id_surat and _blank
          document.getElementById('magang-'+surat.id_surat).href = url;
          document.getElementById('magang-'+surat.id_surat).target = '_blank';
          //delete click event
          document.getElementById('magang-'+surat.id_surat).removeAttribute('onclick');
          window.open(url, '_blank');
        } else {
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil dokumen',
            message: 'Dokumen tidak ditemukan'
          });
        }
      })
      .catch(e => {
        console.log(e);
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
      });
    },
    getDokumenJ(jawaban) {
      this.$store.dispatch('showLoadingAlert');
      console.log(jawaban);
      axios.get(`${window.location.origin}/api/kmm/surat_jawaban/all/${jawaban.id_surat}/dokumen`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Berhasil mengambil dokumen')
        jawaban = response.data.data;
        if (jawaban != null) {
          console.log(jawaban);
          const url = `${window.location.origin}/mahasiswa/magang/surat-jawaban/${jawaban.token}/${jawaban.file_surat}`;
          //change href to a with id = jawaban.id_surat and _blank
          document.getElementById('jawaban-'+jawaban.id_surat).href = url;
          document.getElementById('jawaban-'+jawaban.id_surat).target = '_blank';
          //delete @click event
          document.getElementById('jawaban-'+jawaban.id_surat).removeAttribute('onclick');
          window.open(url, '_blank');
        } else {
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal mengambil dokumen',
            message: 'Dokumen tidak ditemukan'
          });
        }
      })
      .catch(e => {
        console.log(e);
        this.$store.dispatch('showErrorAlert', {
          title: `Error ${e.response.status}`,
          message: e.response.data.message ?? e.response.data
        });
      });
    }
  }
}
</script>
