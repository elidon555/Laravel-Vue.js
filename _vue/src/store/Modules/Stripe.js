import axiosClient from "../../axios";
import store from "../index";

export const Stripe = {
    state: () => (
        {
            clientId:"",
            clientSecret:"",
            clientName:"",
            subscriptionId:"",
            planId:"",
            planName:"",
            planPrice:"",
            loading:false,
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
            state.loading = loading
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
        createPaymentIntent({commit},plan) {

            commit('setStripeClientSecret', [true])
            return axiosClient.post('/stripe/pay-intent')
                .then((response) => {
                    commit('setStripeClientSecret', [false, response.data])
                    commit('setPlan', [plan.name,plan.price,plan.price_id])
                })
                .catch(() => {
                })
                .finally(()=>{
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