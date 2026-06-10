import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/main.css'
import 'tom-select/dist/css/tom-select.default.min.css'

const app = createApp(App)
app.use(router)
app.mount('#app')
