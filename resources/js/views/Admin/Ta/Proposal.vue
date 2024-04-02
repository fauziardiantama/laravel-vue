<template>
  <div>
    <CRow>
      <CCol :md="12">
        <CCard class="mb-4">
          <CCardHeader class="row">
            <p class="col-6">Daftar Proposal</p>
            <!--search bar-->
            <div class="col-6">
              <CInputGroup>
                <CFormInput type="text" placeholder="Search" id="search" :value="search" @keyup.enter="getProposal"/>
                <CInputGroupText @click="getProposal" class="cursor-pointer">
                  <font-awesome-icon :icon="['fas', 'search']" />
                </CInputGroupText>
              </CInputGroup>
            </div>
          </CCardHeader>
          <CCardBody>
            <table-lite
                class="table-lite"
                :is-slot-mode="true"
                :is-re-search="proposal.research"
                :is-loading="proposal.isLoading"
                :columns="proposal.columns"
                :rows="proposal.rows"
                :total="proposal.totalRecordCount"
                :sortable="proposal.sortable"
                :messages="proposal.messages"
                @do-search="proposalSearch"
            >
            <template v-slot:id="data">
              {{ data.value.index }}
            </template>
            <template v-slot:nama="data">
              {{ data.value.mahasiswa?.nama }}
            </template>
            <template v-slot:semester_id="data">
              {{ data.value.semester_id == 1 ? 'Ganjil' : (data.value.semester_id == 2 ? 'Genap' : '-') }}
            </template>
            <template v-slot:status_proposal="data">
              <CBadge v-if="data.value.status_proposal == 1" color="success">disetujui</CBadge>
              <CBadge v-if="data.value.status_proposal == 2 || data.value.status_proposal == -1" color="danger">Ditolak</CBadge>
              <CBadge v-if="data.value.status_proposal == 0" color="warning">Menunggu</CBadge>
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
      <CModalTitle>{{ activeProposal.judul_proposal }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <CRow>
        <CCol :md="4">
          <CLabel>NIM</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.nim }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>NIM</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.mahasiswa.nama }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Judul Proposal</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.judul_proposal }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Tahun</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.tahun }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Semester</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.semester_id == 1 ? 'Ganjil' : 'Genap' }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Status proposal</CLabel>
        </CCol>
        <CCol :md="8">
          <p><CBadge v-if="activeProposal.status_proposal > 0" color="success">disetujui</CBadge><CBadge v-if="activeProposal.status_proposal < 0" color="danger">Ditolak</CBadge><CBadge v-if="activeProposal.status_proposal == 0" color="warning">Menunggu</CBadge></p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Pembimbing TA</CLabel>
        </CCol>
        <CCol :md="8">
          <p>{{ activeProposal.magang?.dosen?.nama ?? '-' }}</p>
        </CCol>
      </CRow>
      <CRow>
        <CCol :md="4">
          <CLabel>Dokumen proposal</CLabel>
        </CCol>
        <CCol :md="8">
          <a :href="`${app}/mahasiswa/ta/proposal-ta/${activeProposal.token}/${activeProposal.file_proposal}`" target="_blank" class="dokumen-link"><font-awesome-icon :icon="['far', 'file']" /> {{ activeProposal.judul_proposal }}</a>
        </CCol>
      </CRow>
    </CModalBody>
    <CModalFooter>
      <CButton color="success" @click="approve(activeProposal)">
        Terima
      </CButton>
      <CButton color="danger" @click="reject(activeProposal)">
        Tolak
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
  name: 'Proposal',
  data() {
    return {
      proposal: {
        isLoading: false,
        columns: [
          {
            label: "#",
            field: "id",
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
            isKey: true
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
            sortable: true
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
            sortable: true
          },
          {
            label: "Judul Proposal",
            field: "judul_proposal",
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
            label: "Tahun",
            field: "tahun",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "5%",
            sortable: true
          },
          {
            label: "Semester",
            field: "semester_id",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "10%",
            sortable: true
          },
          {
            label: "Status",
            field: "status_proposal",
            headerClasses: ["table-header"],
            headerStyles: 
            {
              background: "#f0f2f4",
              color: "rgba(44, 56, 74, 0.95)",
              border: "1px solid rgba(200, 204, 209, 0.99)",
            },
            width: "10%",
            sortable: true
          },
          {
            label: "Action",
            field: "none",
            width: "10%",
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
          order: "id",
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
      showDetailModal: false,
      activeProposal: null,
      app: window.location.origin
    }
  },
  async created() {
    this.getProposal();
  },
  mounted() {
    console.log('Dashboard component mounted.');
    Echo.private('Admin')
    .listen('Prop', (e) => {
      this.getProposal();
      console.log({
        event: "Prop",
        data: e,
        activeid: this.activeProposal.id,
        isTrue: e.type == "update" && e.item.id == this.activeProposal.id
      })
      if (e.type == "update" && e.item.id == this.activeProposal.id) {
          console.log("update");
          console.log(e.item);
          this.openDetailModal(e.item);
        } else if (e.type == "destroy" && e.item.id == this.activeProposal.id) {
          this.closeModal();
        }
    });
  },
  methods: {
    proposalSearch(offset, limit, order, sort) {
      this.proposal.isLoading = true;
      //calculate page based on offset and limit
      let page = offset / limit + 1;
      let url = `${window.location.origin}/api/ta/proposal_ta?kueri=${this.search}&page=${page}&limit=${limit}&order=${order}&sort=${sort}`;
      axios.get(url)
      .then((response) => {
        this.research = false;
        console.log(this.search == '' ? '[kosong]' : this.search);
        this.proposal.rows = response.data.data.data;
        //add iteration index and push to rows as 'index'
        let pagination = (response.data.data.current_page - 1) * response.data.data.per_page;
        this.proposal.rows.forEach((item, index) => {
          //calculate index based on current page
          item.index = index + 1 + pagination;
        });
        this.proposal.totalRecordCount = response.data.data.total;
        this.proposal.page = {
          limit: limit, 
          offset: offset,
        };
        this.proposal.sortable = {
          order: order,
          sort: sort
        };
        this.proposal.isLoading = false;
      });
    },
    getProposal() {
      this.search = document?.getElementById('search')?.value ?? this.search;
      this.proposal.totalRecordCount = 0;
      this.proposal.rows = [];
      this.proposal.page = {
        limit: 10,
        offset: 0
      };
      this.proposal.research = true;
      this.proposalSearch(this.proposal.page.offset, this.proposal.page.limit, this.proposal.sortable.order, this.proposal.sortable.sort);
    },
    openDetailModal(item) {
      this.activeProposal = item;
      this.showDetailModal = true;
    },
    closeModal() {
      this.showDetailModal = false;
      this.activeProposal = null;
    },
    approve(proposal) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${this.app}/api/ta/proposal_ta/${proposal.nim}/approved`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Proposal disetujui!');
        this.closeModal();
        this.openDetailModal(response.data.data);
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menyetujui proposal!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menyetujui proposal!',
            message: error.response.status
          });
        }
      });
    },
    reject(proposal) {
      this.$store.dispatch('showLoadingAlert');
      axios.put(`${this.app}/api/ta/proposal_ta/${proposal.nim}/rejected`)
      .then(response => {
        this.$store.dispatch('showSuccessAlert', 'Proposal ditolak!');
        this.closeModal();
        this.openDetailModal(response.data.data);
      })
      .catch(error => {
        if (error.response.status === 400) {
          console.log(error.response.data);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menolak proposal!',
            message: error.response.data.message
          });
        } else {
          console.log(error);
          this.$store.dispatch('showErrorAlert', {
            title: 'Gagal menolak proposal!',
            message: error.response.status
          });
        }
      });
    }
  }
}
</script>
