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
        }
    ),
    mutations: {
        setBillingInfo(state, data = null) {
            if (data) {
                state = {
                    ...state,
                    email: data.email,
                    name: data.name,
                    address: data.address,
                    city: data.city,
                    state: data.state,
                    postal_code: data.postal_code,
                    country: data.country,
                }
            }
        }
    },
    actions: {
        async getBillingInfo({ commit }) {
            try {
                const response = await axiosClient.get("/user-details/current");
                commit("setBillingInfo", response.data);
            } catch (error) {
                commit("setBillingInfo", [false]);
            }
        },
    },
    getters: {}
}