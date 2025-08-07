import './bootstrap';
import { createApp } from 'vue';
import ShopifySync from './components/ShopifySync.vue';

const app = createApp({});

app.component('shopify-sync', ShopifySync);

app.mount('#app');