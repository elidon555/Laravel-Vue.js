
export function setUser(state, user) {
  state.user.data = user;
    if (user) {
        sessionStorage.setItem('AUTH', JSON.stringify(user));
    } else {
        sessionStorage.removeItem('AUTH')
    }
}

export function setRole(state, role) {
  state.role.data = role;
}

export function setToken(state, token) {
  state.user.token = token;
  if (token) {
    sessionStorage.setItem('TOKEN', token);
  } else {
    sessionStorage.removeItem('TOKEN')
  }
}

export function setProducts(state, [loading, data = null]) {

  if (data) {
    state.products = {
      ...state.products,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.products.loading = loading;
}

export function setUsers(state, [loading, data = null]) {

  if (data) {
    state.users = {
      ...state.users,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
      roles: data.roles,
      permissions: data.permissions
    }
  }
  state.products.loading = loading;
}
export function setSubscriptionPlans(state, [loading, data = null]) {

  if (data) {
    state.subscription_plans = {
      ...state.subscription_plans,
      data: data.data,
    }
  }
}

export function setRoles(state, [loading, data = null]) {

    if (data) {
        state.roles = {
            ...state.roles,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total,
            permissions: data.permissions
        }
    }
    state.products.loading = loading;
}

export function setPermissions(state, [loading, data = null]) {

    if (data) {
        state.permissions = {
            ...state.permissions,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total,
        }
    }
    state.products.loading = loading;
}
export function setContents(state, [loading, data = null]) {

    if (data) {
        state.contents = {
            ...state.contents,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total,
        }
    }
    state.products.loading = loading;
}
export function setPlans(state, [loading = null, data = null]) {

    if (data) {
        state.plans = {
            ...state.plans,
            data: data.data,
        }
    }
    state.products.loading = loading;
}

export function setBillingInfo(state, data = null) {

    if (data) {
        state.billingInfo = {
            email: data.email,
            name: data.name,
            address: data.address,
            city: data.city,
            state: data.state,
            postal_code: data.postal_code,
        }
    }
}

export function setStripeCustomerData(state, [loading,data = null]) {

    if (data) {
        state.stripe.clientId = data.id;
        state.stripe.clientName = data.name;
    }
}
export function setStripeSubscriptionData(state, [loading,data = null]) {

    if (data) {
        console.log(data)
        state.stripe.subscriptionId = data.id;
        state.stripe.planName = data.planName;
        state.stripe.planPrice = data.plan.amount.toFixed(2)
        state.stripe.planId = data.plan.id;

    }
}
export function setStripeSubscriberData(state, [loading,data = null]) {

    if (data) {
        state.stripe.planName = data.planName
    }
}

export function setCustomers(state, [loading, data = null]) {

  if (data) {
    state.customers = {
      ...state.customers,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.products.loading = loading;
}

export function setOrders(state, [loading, data = null]) {

  if (data) {
    state.orders = {
      ...state.orders,
      data: data.data,
      links: data.meta?.links,
      page: data.meta.current_page,
      limit: data.meta.per_page,
      from: data.meta.from,
      to: data.meta.to,
      total: data.meta.total,
    }
  }
  state.orders.loading = loading;
}

export function showToast(state, message) {
  state.toast.show = true;
  state.toast.message = message;
}

export function hideToast(state) {
  state.toast.show = false;
  state.toast.message = '';
}

