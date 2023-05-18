import axiosClient from "../../axios";

export const Permissions = {
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
        setPermissions(state, [loading, data = null]) {
            if (data) {
                Object.assign(state, {
                    data: data.data,
                    links: data.meta?.links,
                    page: data.meta.current_page,
                    limit: data.meta.per_page,
                    from: data.meta.from,
                    to: data.meta.to,
                    total: data.meta.total,
                })
            }
            state.loading = loading;
        }

    },
    actions: {
        getPermissions({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
            commit('setPermissions', [true])
            url = url || '/permissions'
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
                    commit('setPermissions', [false, response.data])
                })
                .catch(() => {
                    commit('setPermissions', [false])
                })
        },
        createPermission({commit}, permission) {
            return axiosClient.post('/permissions', permission)
        },
        updatePermission({commit}, permission) {
            return axiosClient.put(`/permissions/${permission.id}`, permission)
        },
        deletePermission({commit}, permission) {
            return axiosClient.delete(`/permissions/${permission.id}`)
        }
    },
    getters: {}
}