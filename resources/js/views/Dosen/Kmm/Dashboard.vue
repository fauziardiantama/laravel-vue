<template>
  <div>
    <CRow>
      <CCol :md="12">
        
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
      itemstatus : 'Mengambil items'
    }
  },
  async created() {
    //like constructor
    try {
      console.log(axios.defaults.headers.post['Authorization']);
      const response = await axios.get(`${window.location.origin}/api/Ta/items`);
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
