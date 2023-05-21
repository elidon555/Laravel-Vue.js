import axiosClient from "../../axios";

export const Contents = {
    state: () => (
        {
            loading: false,
            data: [],
            subscriptionPlans:[],
            user:[],
            links: [],
            from: null,
            to: null,
            page: 1,
            limit: null,
            total: null
        }
    ),
    mutations: {
        setContents(state, [loading, data = null]) {
            console.log(data);
            if (data) {
                Object.assign(state, {
                    data: data.data,
                    links: data.meta?.links,
                    page: data.meta.current_page,
                    limit: data.meta.per_page,
                    from: data.meta.from,
                    to: data.meta.to,
                    total: data.meta.total,
                    subscriptionPlans: data.subscriptionPlans,
                    user: data.user,
                });
            }
            state.loading = loading;
        }

    },
    actions: {
        async getContents({ commit, state,rootState }, { url = null, search = '', per_page,id } = {}) {
            try {
                commit('setContents', [true])
                url = url || '/contents'

                if (!rootState.user.token && url==='/contents'){
                    url = 'preview'+url
                }

                const params = {
                    per_page: per_page || state.limit,
                    search,
                    id
                }
                const response = await axiosClient.get(url, { params })
                commit('setContents', [false, response.data])
            } catch (error) {
                // debugger
                console.error('Error getting contents:', error)
                commit('setContents', [false])
            }
        },
        createContent({commit}, content) {
            return axiosClient.post('/contents', content)
        }
    },
    getters: {}
}