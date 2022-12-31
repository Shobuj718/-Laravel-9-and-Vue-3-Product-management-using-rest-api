import './bootstrap';
import '../sass/app.scss'
import Router from '@/router'
import store from '@/store'

import Toaster from '@meforma/vue-toaster';

import { createApp } from 'vue/dist/vue.esm-bundler';

const app = createApp({})
app.use(Router)
app.use(store)
app.use(Toaster)
app.mount('#app')