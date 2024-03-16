import { createStore } from 'vuex';
import Swal from 'sweetalert2';

export default createStore({
    state: {
        sidebarVisible: "",
        sidebarUnfoldable: false,
        status: null,
        token: null,
        auth: null,
        user: null,
    },
    mutations: {
        toggleSidebar(state) {
            state.sidebarVisible = !state.sidebarVisible;
        },
        toggleUnfoldable(state) {
            state.sidebarUnfoldable = !state.sidebarUnfoldable;
        },
        updateSidebarVisible(state, payload) {
            state.sidebarVisible = payload.value;
        },
        setStatus(state, value) {
            state.status = value;
        },
        setAuth(state, value) {
            state.auth = value;
        },
        setToken(state, token) {
            state.token = token;
        },
        setUser(state, user) {
            state.user = user;
        },
    },
    actions: {
        mahasiswaLogin({ commit, dispatch }, parameter) {
            return axios
                .post("/api/mahasiswa/login", parameter)
                .then((response) => {
                    //get token
                    const token = response.data.token;

                    //save token
                    localStorage.setItem("token", token);
                    window.axios.defaults.headers.common[
                        "Authorization"
                    ] = `Bearer ${token}`;
                    commit("setToken", token);

                    //get user data
                    dispatch("user");

                    //done
                    return response;
                })
                .catch((error) => {
                    console.error("Axios.MahasiswaLogin() error:", error);
                    throw error;
                });
        },
        dosenLogin({ commit, dispatch }, parameter) {
            return axios
                .post("/api/dosen/login", parameter)
                .then((response) => {
                    //get token
                    const token = response.data.token;

                    //save token
                    localStorage.setItem("token", token);
                    window.axios.defaults.headers.common[
                        "Authorization"
                    ] = `Bearer ${token}`;
                    commit("setToken", token);

                    //get user data
                    dispatch("user");

                    //done
                    return response;
                })
                .catch((error) => {
                    console.error("Axios.MahasiswaLogin() error:", error);
                    throw error;
                });
        },
        adminLogin({ commit, dispatch }, parameter) {
            return axios
                .post("/api/admin/login", parameter)
                .then((response) => {
                    //get token
                    const token = response.data.token;

                    //save token
                    localStorage.setItem("token", token);
                    window.axios.defaults.headers.common[
                        "Authorization"
                    ] = `Bearer ${token}`;
                    commit("setToken", token);

                    //get user data
                    dispatch("user");

                    //done
                    return response;
                })
                .catch((error) => {
                    console.error("Axios.MahasiswaLogin() error:", error);
                    throw error;
                });
        },
        logout({ commit }) {
            return axios
                .post("/api/logout")
                .then((response) => {
                    commit("setToken", null);
                    commit("setAuth", null);
                    commit("setUser", null);

                    localStorage.removeItem("token");

                    return response;
                })
                .catch((error) => {
                    console.error("Axios.Logout() error:", error);
                    throw error;
                });
        },
        user({ commit }) {
            return axios
                .get("/api/user")
                .then((response) => {
                    console.log("connected to user");
                    commit("setAuth", response.data.auth);
                    commit("setUser", response.data.user);
                    if (response.data.auth.name == "mahasiswa") {
                        commit("setStatus", response.data.user.status);
                    } else {
                        commit("setStatus", 1);
                    }
                    return response;
                })
                .catch((error) => {
                    console.error("Axios.User() error:", error);
                    throw error;
                });
        },
        showLoadingAlert({}) {
            Swal.fire({
                title: "Loading...",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading(),
            });
        },
        showSuccessAlert({}, parameter) {
            Swal.fire({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                icon: "success",
                title: parameter,
            });
        },
        showErrorAlert({}, parameter) {
            Swal.fire({
                title: parameter.title,
                text: parameter.message,
                icon: "error",
            });
        },
    },
    modules: {},
});
