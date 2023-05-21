import {createApp} from 'vue'
import store from './store'
import router from './router'
import './index.css';
import currencyUSD from './filters/currency.js'

import App from './App.vue'

import Notifications from '@kyvg/vue3-notification'
import "@mdi/font/css/materialdesignicons.css";

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    components,
    directives,
    theme: { defaultTheme: 'dark' },
})

const app = createApp(App);

app
  .use(store)
  .use(router)
  .use(Notifications)
  .use(VueSweetalert2)
  .use(vuetify)
  .mount('#app')
;

app.config.globalProperties.$filters = {
  currencyUSD
}
