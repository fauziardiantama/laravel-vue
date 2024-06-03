<template>
  <div>
    <CRow>
      <CCol>
        <CCard class="mb-4">
          <CCardHeader>Nilai Bimbingan</CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Parameter</CTableHeaderCell>
                  <CTableHeaderCell>Nilai</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="nilaibimbingans.length > 0">
                <CTableRow v-for="(nilai, index) in nilaibimbingans" :key="nilai.id_nilai_bimbingan">
                    <CTableDataCell class="text-center">
                        {{ index + 1 }}
                    </CTableDataCell>
                    <CTableDataCell>
                        {{ nilai?.parameter_nilai_bimbingan?.parameter }}
                    </CTableDataCell>
                    <CTableDataCell>
                        {{ nilai?.nilai }}
                    </CTableDataCell>
                </CTableRow>
              </CTableBody>
                <CTableBody v-else>
                    <CTableRow>
                    <CTableDataCell class="text-center" colspan="4">
                        {{ bimbinganstatus }}
                    </CTableDataCell>
                    </CTableRow>
                </CTableBody>
            </CTable>
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
    <CRow>
      <CCol>
        <CCard class="mb-4">
          <CCardHeader>Nilai Seminar</CCardHeader>
          <CCardBody>
            <CTable align="middle" class="mb-0 border" hover responsive>
              <CTableHead color="light">
                <CTableRow>
                  <CTableHeaderCell class="text-center">
                    #
                  </CTableHeaderCell>
                  <CTableHeaderCell>Parameter</CTableHeaderCell>
                  <CTableHeaderCell>Nilai</CTableHeaderCell>
                </CTableRow>
              </CTableHead>
              <CTableBody v-if="nilaiseminars.length > 0">
                <CTableRow v-for="(nilai, index) in nilaiseminars" :key="nilai.id_nilai_seminar">
                    <CTableDataCell class="text-center">
                        {{ index + 1 }}
                    </CTableDataCell>
                    <CTableDataCell>
                        {{ nilai?.parameter_nilai_seminar?.parameter }}
                    </CTableDataCell>
                    <CTableDataCell>
                        {{ nilai?.nilai }}
                    </CTableDataCell>
                </CTableRow>
              </CTableBody>
                <CTableBody v-else>
                    <CTableRow>
                    <CTableDataCell class="text-center" colspan="4">
                        {{ seminarstatus }}
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

<script>
export default {
    name: "Nilai",
    data() {
        return {
            nilaibimbingans: [],
            nilaiseminars: [],
            bimbinganstatus: 'Belum ada nilai',
            seminarstatus: 'Belum ada nilai',
        }
    },
    watch: {
        '$store.state.user': {
            immediate: true,
            handler(user) {
                if (user != null) {
                    this.user = user;
                    this.getNilaiBimbingan();
                    this.getNilaiSeminar();
                }
            }
        },
    },
    computed: {

    },
    methods: {
        getNilaiBimbingan() {
            axios.get(`/api/kmm/nilai/bimbingan`).then((response) => {
                this.nilaibimbingans = response.data.data;
            }).catch((error) => {
                console.log(error);
            });
        },
        getNilaiSeminar() {
            axios.get(`/api/kmm/nilai/seminar`).then((response) => {
                this.nilaiseminars = response.data.data;
            }).catch((error) => {
                console.log(error);
            });
        }
    }
}
</script>