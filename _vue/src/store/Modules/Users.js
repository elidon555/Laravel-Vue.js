import axiosClient from "../../axios";

export const Users = {
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
            roles:[],
            permissions:[]
        }
    ),
    mutations: {
        setUsers(state, [loading, data = null]) {
            if (data) {
                Object.assign(state, {
                    data: data.data,
                    links: data.meta?.links,
                    page: data.meta.current_page,
                    limit: data.meta.per_page,
                    from: data.meta.from,
                    to: data.meta.to,
                    total: data.meta.total,
                    roles: data.roles,
                    permissions: data.permissions
                });
            }
            state.loading = loading;
        }

    },
    actions: {
        getUsers({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
            commit('setUsers', [true])
            url = url || '/users'
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
                    commit('setUsers', [false, response.data])
                })
                .catch(() => {
                    commit('setUsers', [false])
                })
        },
        createUser({commit}, user) {
            return axiosClient.post('/users', user)
        },
        updateUser({commit}, user) {
            return axiosClient.put(`/users/${user.id}`, user)
        }
    },
    getters: {}
}