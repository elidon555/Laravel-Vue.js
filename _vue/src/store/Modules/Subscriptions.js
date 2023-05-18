import axiosClient from "../../axios";

export const Subscriptions = {
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
        setSubscriptions(state, [loading, data = null]) {

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
        getSubscriptions({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
            commit('setSubscriptions', [true])
            url = url || '/subscriptions'
            const params = {
                per_page: state.limit,
            }
            return axiosClient.get(url, {
                params: {
                    ...params,
                    search, per_page, sort_field, sort_direction
                }
            })
                .then((response) => {
                    commit('setSubscriptions', [false, response.data])
                })
                .catch(() => {
                    commit('setSubscriptions', [false])
                })
        },
        deleteSubscription({commit}, subscription) {
            return axiosClient.delete(`/subscriptions/${subscription.id}`)
        }
    },
    getters: {}
}