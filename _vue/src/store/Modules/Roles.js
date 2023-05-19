import axiosClient from "../../axios";

export const Roles = {
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
        setRoles(state, [loading, data = null]) {
            if (data) {
                Object.assign(state, {
                    data: data.data,
                    links: data.meta?.links,
                    page: data.meta.current_page,
                    limit: data.meta.per_page,
                    from: data.meta.from,
                    to: data.meta.to,
                    total: data.meta.total,
                    permissions: data.permissions
                })
            }
            state.loading = loading;
        }

    },
    actions: {
        getRoles({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
            commit('setRoles', [true])
            url = url || '/roles'
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
                    commit('setRoles', [false, response.data])
                })
                .catch(() => {
                    commit('setRoles', [false])
                })
        },
        createRole({commit}, role) {
            return axiosClient.post('/roles', role)
        },
        updateRole({commit}, role) {
            return axiosClient.put(`/roles/${role.id}`, role)
        },
        deleteRole({commit}, role) {
            return axiosClient.delete(`/roles/${role.id}`)
        }
    },
    getters: {}
}