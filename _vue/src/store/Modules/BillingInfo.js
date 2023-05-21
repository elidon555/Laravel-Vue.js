import axiosClient from "../../axios";

export const BillingInfo = {
    state: () => (
        {
            email: '',
            name: '',
            address: '',
            city: '',
            state: '',
            postalCode: '',
            country: '',
            loading: false,
        }
    ),
    mutations: {
        setBillingInfo(state, [loading ,data = null]) {
            if (data) {
                Object.assign(state, {
                    email: data.email,
                    name: data.name,
                    address: data.address,
                    city: data.city,
                    state: data.state,
                    postal_code: data.postal_code,
                    country: data.country,
                });
            }
            state.loading = loading
        }
    },
    actions: {
        async getBillingInfo({ commit }) {
            try {
                commit("setBillingInfo", [true]);
                const response = await axiosClient.get("/user-details/current");
                commit("setBillingInfo",[false, response.data]);
            } catch (error) {
                commit("setBillingInfo", [false]);
            }
        },
    },
    getters: {}
}