import axiosClient from "../../axios";

export const SubscriptionPlans = {
    state: () => (
        {
            loading: false,
            data: [],
            links: [],
            from: null,
            to: null,
            page: 1,
            limit: null,
            total: null,
            monthly: [],
            yearly: []
        }
    ),
    mutations: {
        setSubscriptionPlans(state, [loading, data = null,userId= null]) {
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
            if (userId) {
                let monthlyPlans = [];
                let yearlyPlans = [];
                for (const plan of data.data) {
                    if (plan['interval']==='Monthly') {
                        monthlyPlans.push(plan)
                    } else {
                        yearlyPlans.push(plan)
                    }
                }
                state.monthly = monthlyPlans;
                state.yearly = yearlyPlans;
            }
            state.loading = loading;
        }
    },
    actions: {
        async getSubscriptionPlans({ commit, state, rootState }, { url = null, search = '', per_page, sort_field, sort_direction, id } = {}) {
            commit('setSubscriptionPlans', [true]);

            url = url || '/subscription-plans';
            if (!rootState.user.token) {
                url = '/preview' + url;
            }
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
                        sort_direction,
                        id
                    }
                });
                commit('setSubscriptionPlans', [false, response.data, id]);
            } catch (error) {
                commit('setSubscriptionPlans', [false]);
            }
        },
        createSubscriptionPlan({commit}, subscriptionPlan) {
            return axiosClient.post('/subscription-plans', subscriptionPlan)
        },
        updateSubscriptionPlan({commit}, subscriptionPlan) {
            return axiosClient.put(`/subscription-plans/${subscriptionPlan.id}`, subscriptionPlan)
        }
    },
    getters: {}
}
