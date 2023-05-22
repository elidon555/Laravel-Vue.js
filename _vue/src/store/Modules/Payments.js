import axiosClient from "../../axios";

export const Payments = {
    state: () => (
        {
            loading: false,
            data: [],
            links: [],
            from: null,
            to: null,
            page: 1,
            limit: null,
            total: null
        }
    ),
    mutations: {
        setPayments(state, [loading, data = null]) {
            if (data) {
                Object.assign(state, {
                    data: data.data,
                    links: data.meta?.links,
                    page: data.meta.current_page,
                    limit: data.meta.per_page,
                    from: data.meta.from,
                    to: data.meta.to,
                    total: data.meta.total,
                });
            }
            state.loading = loading;
        }

    },
    actions: {
        async getPayments({ commit, state }, { url = null, search = '', per_page, sort_field, sort_direction } = {}) {
            commit('setPayments', [true]);
            url = url || '/payments';
            const params = {
                per_page: state.limit,
            };

            try {
                const response = await axiosClient.get(url, {
                    params: {
                        ...params,
                        search,
                        per_page,
                        sort_field,
                        sort_direction
                    }
                });
                commit('setPayments', [false, response.data]);
            } catch (error) {
                commit('setPayments', [false]);
            }
        },
        deletePayment({commit}, payment) {
            return axiosClient.delete(`/payments/${payment.id}`)
        }
    },
    getters: {}
}
