<template>
  <div>
    <CRow>
      <CCol :md="12">
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
                </CTableRow>
              </CTableBody>
              <CTableBody v-else>
                <CTableRow>
                  <CTableDataCell class="text-center" colspan="3">
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


<script>

export default {
  name: 'Dashboard',
  components: {},
  data() {
    return {
      items: [],
      itemstatus : 'Mengambil items',
      app: 'http://127.0.0.1:8000/'
    }
  },
  async created() {
    //like constructor
    try {
      console.log(axios.defaults.headers.post['Authorization']);
      const response = await axios.get(`${this.app}api/kmm/items`);
      if (!response.data) {
        throw new Error('Response is not in JSON format');
      }
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
    console.log('TES' + import.meta.env.APP_URL)
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
    tes() {
      console.log('tes');
    }
  }
}
</script>
