import axiosClient from "../../axios";

export const Stripe = {
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
        setStripeCustomerData(state, [loading,data = null]) {

            if (data) {
                state.clientId = data.id;
                state.clientName = data.name;
            }
        },
        setStripeClientSecret(state, [loading,data = null]) {

            if (data) {
                state.clientSecret = data.clientSecret;
            }
        },
        setStripeSubscriptionData(state, [loading,data = null]) {

            if (data) {
                Object.assign(state, {
                    subscriptionId: data.id,
                    planName: data.planName,
                    planPrice: data.plan.amount.toFixed(2),
                    planId: data.plan.id,
                })


            }
        },
        setStripeSubscriberData(state, [loading,data = null]) {
            if (data) {
                state.planName = data.planName
            }
        }

    },
    actions: {
        createStripeSubscription({commit}, stripeSubscription) {
            return axiosClient.post('/stripe/create-subscription', stripeSubscription)
                .then((response) => {
                    commit('setStripeSubscriptionData', [false, {...stripeSubscription,...response.data}])
                })
                .catch(() => {
                    commit('setStripeSubscriptionData', [false])
                })
        },
        createPaymentIntent({commit}) {
            return axiosClient.post('/stripe/pay-intent')
                .then((response) => {
                    commit('setStripeClientSecret', [false, response.data])
                })
                .catch(() => {
                    commit('setStripeClientSecret', [false])
                })
        },
        createStripeCustomer({commit}, stripeCustomer) {
            return axiosClient.post('/stripe/create-costumer', stripeCustomer)
                .then((response) => {
                    commit('setStripeCustomerData', [false, response.data])
                })
                .catch(() => {
                    commit('setStripeCustomerData', [false])
                })
        }
    },
    getters: {}
}