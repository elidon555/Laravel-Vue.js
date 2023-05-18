<template>
    <StripeElements
        class="w-50 text-light"
        v-slot="{ elements, instance }"
        ref="elms"
        :stripe-key="stripeKey"
        :instance-options="instanceOptions"
        :elements-options="elementsOptions"
    >
        <StripeElement
            class="text-light"
            ref="card"
            :elements="elements"
            :options="cardOptions"
        />
    </StripeElements>
    <button type="button" @click="pay">Pay</button>
</template>

<script >
import { StripeElements, StripeElement } from 'vue-stripe-js';
import { defineComponent, ref, onBeforeMount } from 'vue';

export default defineComponent({
    name: 'CardOnly',
    components: {
        StripeElements,
        StripeElement,
    },
    setup() {
        const stripeKey = ref(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY); // test key
        const instanceOptions = ref({
            // https://stripe.com/docs/js/initializing#init_stripe_js-options
        });
        const elementsOptions = ref({
            // https://stripe.com/docs/js/elements_object/create#stripe_elements-options
        });
        const cardOptions = ref({
            // https://stripe.com/docs/stripe.js#element-options
            value: {
                postalCode: '12345',
            },
        });
        const stripeLoaded = ref(false);
        const card = ref();
        const elms = ref();

        const pay = () => {
            // Get stripe element
            const cardElement = card.value.stripeElement;

            // Access instance methods, e.g. createToken()
            elms.value.instance.createToken(cardElement).then((result) => {
                // Handle result.error or result.token
                console.log(result);
            });
        };

        return {
            stripeKey,
            stripeLoaded,
            instanceOptions,
            elementsOptions,
            cardOptions,
            card,
            elms,
            pay,
        };
    },
});
</script>
