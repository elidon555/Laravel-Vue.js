<template>
    <StripeElements
        class="w-100 text-light"
        v-slot="{ elements, instance }"
        ref="elms"
        :stripe-key="stripeKey"
        :instance-options="instanceOptions"
        :elements-options="elementsOptions"
    >
      <div class="bg-dark p-2 m-2 rounded-lg">
        <StripeElement
            class="text-light"
            ref="card"
            :elements="elements"
            :options="cardOptions"
        />
      </div>

    </StripeElements>
  <div class="justify-center mx-3">
    <v-btn
        color="blue"
        @click="pay"
        class="w-full h-8 mb-3 text-white shadow-md bg-indigo-500 border mt-5 rounded-md hover:bg-indigo-400 pb-1"
    >
      Pay with Stripe
    </v-btn>
  </div>
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
      const style = {
        base: {
          color: "#CFD7DF",
          fontWeight: 500,
          fontFamily: "Inter, Open Sans, Segoe UI, sans-serif",
          fontSize: "18px",
          fontSmoothing: "antialiased",

          "::placeholder": {
            color: "#CFD7DF"
          }
        },
        invalid: {
          color: "#E25950"
        }
      }

        const stripeKey = ref(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY); // test key
        const instanceOptions = ref({
            // https://stripe.com/docs/js/initializing#init_stripe_js-options
        });
        const elementsOptions = ref({
            // https://stripe.com/docs/js/elements_object/create#stripe_elements-options
        });
        const cardOptions = ref({
            // https://stripe.com/docs/stripe.js#element-options
          style:style
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
          style,
            pay,
        };
    },
});
</script>
