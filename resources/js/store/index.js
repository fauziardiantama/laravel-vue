import { createStore } from 'vuex';

export default createStore({
  state: {
    sidebarVisible: '',
    sidebarUnfoldable: false,
    token: null, // Store the token here
    user: null, // Optional for user data
  },
  mutations: {
    toggleSidebar(state) {
      state.sidebarVisible = !state.sidebarVisible
    },
    toggleUnfoldable(state) {
      state.sidebarUnfoldable = !state.sidebarUnfoldable
    },
    updateSidebarVisible(state, payload) {
      state.sidebarVisible = payload.value
    },
    setToken(state, token) {
      state.token = token;
    },
    setUser(state, user) {
      state.user = user;
    },
  },
  actions: {
    mahasiswaLogin({ commit }, parameter) {
      axios.post('/api/mahasiswa/login', parameter.credentials)
        .then(response => {
          // Extract token and user data from response
          const token = response.data.token;
          const user = response.data.user; // Optional
          //store the token in the local storage
          localStorage.setItem('token', token);
          window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          // Commit token and user data to Vuex store
          commit('setToken', token);
          commit('setUser', {
            type: 'mahasiswa',
            data: user
          });
          parameter.router();
        })
        .catch(error => {
          // Handle login errors (e.g., display error messages)
          console.error('Login error:', error);
          // Provide feedback to the user
          // ...
        });
    },
    dosenLogin({ commit }, parameter) {
      console.log('dosen');
      axios.post('/api/dosen/login', parameter.credentials)
        .then(response => {
          // Extract token and user data from response
          console.log(response);
          // Extract token and user data from response
          const token = response.data.token;
          const user = response.data.user; // Optional
          //store the token in the local storage
          localStorage.setItem('token', token);
          window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          // Commit token and user data to Vuex store
          commit('setToken', token);
          commit('setUser', {
            type: 'dosen',
            data: user
          });
          parameter.router();
        })
        .catch(error => {
          // Handle login errors (e.g., display error messages)
          console.error('Login error:', error);
          // Provide feedback to the user
          // ...
        });
    },
    adminLogin({ commit }, parameter) {
      axios.post('/api/admin/login', parameter.credentials)
        .then(response => {
          // Extract token and user data from response
          // Extract token and user data from response
          const token = response.data.token;
          const user = response.data.user; // Optional
          //store the token in the local storage
          localStorage.setItem('token', token);
          window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          // Commit token and user data to Vuex store
          commit('setToken', token);
          commit('setUser', {
            type: 'admin',
            data: user
          });
          parameter.router();
        })
        .catch(error => {
          // Handle login errors (e.g., display error messages)
          console.error('Login error:', error);
          // Provide feedback to the user
          // ...
        });
    },
    logout({ commit }, parameter) {
      axios.post('/api/logout')
        .then(() => {
          commit('setToken', null);
          commit('setUser', null);

          axios.defaults.headers.post['Authorization'] = null;
          localStorage.removeItem('token');
          console.log('logout done');
          parameter.router();
        })
        .catch(error => {
          // Handle login errors (e.g., display error messages)
          console.error('Logout error:', error);
          // Provide feedback to the user
          // ...
        });
    }
  },
  modules: {},
})
