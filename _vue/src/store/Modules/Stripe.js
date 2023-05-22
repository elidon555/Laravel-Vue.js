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
        async createStripeSubscription({ commit }, stripeSubscription) {
            try {
                const response = await axiosClient.post('/stripe/create-subscription', stripeSubscription);
                commit('setStripeSubscriptionData', [false, { ...stripeSubscription, ...response.data }]);
            } catch (error) {
                commit('setStripeSubscriptionData', [false]);
            }
        },
        async createPaymentIntent({ commit }, plan) {
            commit('setStripeClientSecret', [true]);
            try {
                const response = await axiosClient.post('/stripe/pay-intent');
                commit('setStripeClientSecret', [false, response.data]);
                commit('setPlan', [plan.name, plan.price, plan.price_id]);
            } catch (error) {
                // Handle error
            } finally {
                commit('setStripeClientSecret', [false]);
            }
        },
        async createStripeCustomer({ commit }, stripeCustomer) {
            try {
                const response = await axiosClient.post('/stripe/create-costumer', stripeCustomer);
                commit('setStripeCustomerData', [false, response.data]);
            } catch (error) {
                commit('setStripeCustomerData', [false]);
            }
        }
    },
    getters: {}
}
