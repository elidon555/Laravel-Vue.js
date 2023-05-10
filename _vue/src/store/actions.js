import axiosClient from "../axios";

export function getCurrentUser({commit}, data) {
  return axiosClient.get('/user', data)
    .then(({data}) => {
      commit('setUser', data);
      return data;
    })
}

export function login({commit}, data) {
  return axiosClient.post('/login', data)
    .then(({data}) => {
      commit('setUser', data.user);
      commit('setToken', data.token)
      return data;
    })
}export function signup({commit}, data) {
  return axiosClient.post('/signup', data)
    .then(({data}) => {
      commit('setUser', data.user);
      commit('setToken', data.token)
      return data;
    })
}

export function logout({commit}) {
  return axiosClient.post('/logout')
    .then((response) => {
      commit('setToken', null)
      commit('setUser', null)
      return response;
    })
}

export function getUsers({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
  commit('setUsers', [true])
  url = url || '/users'
  const params = {
    per_page: state.users.limit,
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
}

export function getSubscriptionPlans({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
    commit('setSubscriptionPlans', [true])
    url = url || '/subscription-plans'
    const params = {
        per_page: state.users.limit,
    }
    return axiosClient.get(url, {
        params: {
            ...params,
            search, per_page, sort_field, sort_direction
        }
    })
        .then((response) => {
            commit('setSubscriptionPlans', [false, response.data])
        })
        .catch(() => {
            commit('setSubscriptionPlans', [false])
        })
}

export function getRoles({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
    commit('setRoles', [true])
    url = url || '/roles'
    const params = {
        per_page: state.roles.limit,
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
}

export function getPermissions({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
    commit('setPermissions', [true])
    url = url || '/permissions'
    const params = {
        per_page: state.permissions.limit,
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
}

export function getContents({commit, state}, {url = null, search = '', per_page, id} = {}) {
    commit('setContents', [true])
    url = url || '/contents'
    const params = {
        per_page: state.contents.limit,
    }
    // let params1 = {
    // ...params,
    //         search, per_page, id
    // }
    return axiosClient.get(url, {
        params: {
            ...params,
            search, per_page, id
        }
    })
        .then((response) => {
            console.log(response)
            commit('setContents', [false, response.data])
        })
        .catch((response) => {
            console.log(response)

            commit('setContents', [false])
        })

}

export function createUser({commit}, user) {
  return axiosClient.post('/users', user)
}

export function updateUser({commit}, user) {
    console.log(user)
  return axiosClient.put(`/users/${user.id}`, user)
}

export function createRole({commit}, role) {
  return axiosClient.post('/roles', role)
}

export function updateRole({commit}, role) {
  return axiosClient.put(`/roles/${role.id}`, role)
}

export function createSubscriptionPlan({commit}, subscriptionPlan) {
    return axiosClient.post('/subscription-plans', subscriptionPlan)
}

export function updateSubscriptionPlan({commit}, subscriptionPlan) {
    return axiosClient.put(`/subscription-plans/${subscriptionPlan.id}`, subscriptionPlan)
}

export function createPermission({commit}, permission) {
    return axiosClient.post('/permissions', permission)
}

export function updatePermission({commit}, permission) {
    return axiosClient.put(`/permissions/${permission.id}`, permission)
}

export function createContent({commit}, content) {
    return axiosClient.post('/contents', content)
}

export function createStripeCustomer({commit}, stripeCustomer) {
    return axiosClient.post('/stripe/create-costumer', stripeCustomer)
        .then((response) => {
            commit('setStripeCustomerData', [false, response.data])
        })
        .catch(() => {
            commit('setStripeCustomerData', [false])
        })
}

export function createStripeSubscription({commit}, stripeSubscription) {
    return axiosClient.post('/stripe/create-subscription', stripeSubscription)
        .then((response) => {
            console.log(stripeSubscription)
            commit('setStripeSubscriptionData', [false, {...stripeSubscription,...response.data}])
        })
        .catch(() => {
            commit('setStripeSubscriptionData', [false])
        })
}

export function createPaymentIntent({commit}) {
    return axiosClient.post('/stripe/pay-intent')
        .then((response) => {
            commit('setStripeClientSecret', [false, response.data])
        })
        .catch(() => {
            commit('setStripeClientSecret', [false])
        })
}
export function deleteRole({commit}, role) {
  return axiosClient.delete(`/roles/${role.id}`)
}
export function deletePermission({commit}, permission) {
    return axiosClient.delete(`/permissions/${permission.id}`)
}

export function guestImage({commit},image) {
    commit('setGuestLayoutImage', [image])
}

