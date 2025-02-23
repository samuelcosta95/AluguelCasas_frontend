import { createApp } from 'vue'
import App from './components/App.vue' // Caminho correto!
import router from './router'

createApp(App).use(router).mount('#app')