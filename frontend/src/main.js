import { createApp } from 'vue'
import { createI18n } from 'vue-i18n'
import App from './App.vue'
import router from './router'
import store from './store'
import en from './locales/en'
import fr from './locales/fr'
import es from './locales/es'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

// Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css'
// Font Awesome CSS
import '@fortawesome/fontawesome-free/css/all.min.css'

const i18n = createI18n({
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en,
    fr,
    es
  }
})

const app = createApp(App)

app.use(store)
app.use(router)
app.use(i18n)
app.use(VueSweetalert2)

app.mount('#app')
