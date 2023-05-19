import axiosClient from "../../axios";

export const User = {
    state: () => ({
        token: sessionStorage.getItem("TOKEN"),
        data: JSON.parse(sessionStorage.getItem("AUTH")) || {},
        guest: true
    }),
    mutations: {
        setUser(state, user) {
            state.data = user;
            state.guest= false;
            if (user) {
                sessionStorage.setItem("AUTH", JSON.stringify(user));
            } else {
                sessionStorage.removeItem("AUTH");
            }
        },
        setToken(state, token) {
            state.token = token;
            state.guest= false;
            if (token) {
                sessionStorage.setItem("TOKEN", token);
            } else {
                sessionStorage.removeItem("TOKEN");
            }
        },
    },
    actions: {
        async getCurrentUser({ commit, state }, data) {
            if (state.guest===true) return;
            try {
                const response = await axiosClient.post("/user", data);
                commit("setUser", response.data);
                return response.data;
            } catch (error) {
                throw error;
            }
        },
        async logout({ commit }) {
            try {
                const response = await axiosClient.post("/logout");
                commit("setToken", null);
                commit("setUser", null);
                return response;
            } catch (error) {
                throw error;
            }
        },
        async login({ commit }, data) {
            try {
                const response = await axiosClient.post("/login", data);
                commit("setUser", response.data.user);
                commit("setToken", response.data.token);
                return response.data;
            } catch (error) {
                throw error;
            }
        },
        async signup({ commit }, data) {
            try {
                const response = await axiosClient.post("/signup", data);
                commit("setUser", response.data.user);
                commit("setToken", response.data.token);
                return response.data;
            } catch (error) {
                throw error;
            }
        },
    },
    getters: {},
};