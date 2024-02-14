import { createStore } from 'vuex';
import Swal from 'sweetalert2';

export default createStore({
  state: {
    sidebarVisible: '',
    sidebarUnfoldable: false,
    status: null,
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
    updateStatus(state, value) {
      state.status = value
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
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
      });
      return axios.post('/api/mahasiswa/login', parameter.credentials)
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
          Swal.fire({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            icon: "success",
            title: "Login berhasil!"
          });
          console.log('redirect');
          parameter.router();
          return response;
        })
        .catch(error => {
          if (error.response.status === 422) {
            // Handle login errors (e.g., display error messages)
            console.log('Login error:', error);
            // Provide feedback to the user
            Swal.fire({
              title: 'Gagal login!',
              text: error.response.data.message,
              icon: 'error'
            });
          } else {
            console.log('Login error:', error);
            // Provide feedback to the user
            Swal.fire({
              title: `Error ${error.response.status}`,
              text: error.response.data.message ?? error.response.data,
              icon: 'error'
            });
          }
          throw error;
        });
    },
    dosenLogin({ commit }, parameter) {
      console.log('dosen');
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
      });
      return axios.post('/api/dosen/login', parameter.credentials)
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
          Swal.fire({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            icon: "success",
            title: "Login berhasil!"
          });
          parameter.router();
          return response;
        })
        .catch(error => {
          if (error.response.status === 422) {
            // Handle login errors (e.g., display error messages)
            console.log('Login error:', error);
            // Provide feedback to the user
            Swal.fire({
              title: 'Gagal login!',
              text: error.response.data.message,
              icon: 'error'
            });
          } else {
            console.log('Login error:', error);
            // Provide feedback to the user
            Swal.fire({
              title: `Error ${error.response.status}`,
              text: error.response.data.message ?? error.response.data,
              icon: 'error'
            });
          }
          throw error;
        });
    },
    adminLogin({ commit }, parameter) {
      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => Swal.showLoading()
      });
      return axios.post('/api/admin/login', parameter.credentials)
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
          Swal.fire({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            icon: "success",
            title: "Login berhasil!"
          });
          parameter.router();
          return response;
        })
        .catch(error => {
          if (error.response.status === 422) {
            // Handle login errors (e.g., display error messages)
            console.log('Login error:', error);
            // Provide feedback to the user
            Swal.fire({
              title: 'Gagal login!',
              text: error.response.data.message,
              icon: 'error'
            });
          } else {
            console.log('Login error:', error);
            // Provide feedback to the user
            Swal.fire({
              title: `Error ${error.response.status}`,
              text: error.response.data.message ?? error.response.data,
              icon: 'error'
            });
          }
          throw error;
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
    },
    user({ commit }) {
      return axios.get('/api/user')
        .then(response => {
          console.log('User:', response.data);
          commit('setUser', response.data);
          return response;
        })
        .catch(error => {
          console.error('User error:', error);
          throw error;
        });
    }
  },
  modules: {},
})
