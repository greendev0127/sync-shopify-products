<template>
  <div class="bg-gray-50 min-h-screen font-sans antialiased">
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Shopify Sync Dashboard</h1>
        <button
          @click="syncProducts"
          :disabled="isSyncing"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
        >
          <svg v-if="isSyncing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ isSyncing ? 'Syncing...' : 'Sync Products' }}
        </button>
      </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div v-if="statusMessage" class="rounded-md p-4 mb-4" :class="statusClass">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5" :class="statusIconClass" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path v-if="statusType === 'success'" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
              <path v-if="statusType === 'error'" fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5a.75.75 0 01.75-.75zm0 10a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
              <path v-if="statusType === 'info'" fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 002 0v-3a1 1 0 00-2 0z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium">{{ statusMessage }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            Synced Products
          </h3>
          <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Products from Shopify are displayed here.
          </p>
        </div>
        <div v-if="products.length > 0" class="border-t border-gray-200">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-for="product in products" :key="product.id" class="px-4 py-5 sm:px-6 hover:bg-gray-50 transition-colors duration-200">
              <div class="flex items-center">
                <div class="flex-shrink-0 mr-4">
                  <div v-if="product.image_src" class="w-16 h-16 rounded-md overflow-hidden bg-gray-100">
                    <img :src="product.image_src" alt="Product Image" class="w-full h-full object-cover">
                  </div>
                  <div v-else class="w-16 h-16 rounded-md bg-gray-200 flex items-center justify-center text-xs text-gray-500 text-center p-1">
                    No Image
                  </div>
                </div>
                <div class="min-w-0 flex-1 grid grid-cols-2 lg:grid-cols-4 gap-4">
                  <div class="col-span-2 lg:col-span-1">
                    <p class="text-sm font-medium text-indigo-600 truncate">{{ product.title }}</p>
                    <p class="mt-1 text-xs text-gray-500 truncate">Vendor: {{ product.vendor }}</p>
                  </div>
                  <div class="hidden lg:block">
                    <p class="text-sm font-medium text-gray-900">Type</p>
                    <p class="mt-1 text-sm text-gray-500">{{ product.product_type }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">Status</p>
                    <p class="mt-1 text-sm font-bold" :class="{'text-green-600': product.status === 'active', 'text-gray-500': product.status === 'draft'}">{{ product.status.toUpperCase() }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">Shopify ID</p>
                    <p class="mt-1 text-sm text-gray-500">{{ product.shopify_id }}</p>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div v-else class="px-4 py-12 text-center text-gray-500">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No products synced</h3>
          <p class="mt-1 text-sm text-gray-500">
            Get started by syncing your products from Shopify.
          </p>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      isSyncing: false,
      statusMessage: '',
      statusType: '', // 'success', 'error', or 'info'
      products: []
    };
  },
  computed: {
    statusClass() {
      switch(this.statusType) {
        case 'success':
          return 'bg-green-50 border-l-4 border-green-400 text-green-700';
        case 'error':
          return 'bg-red-50 border-l-4 border-red-400 text-red-700';
        default:
          return 'bg-blue-50 border-l-4 border-blue-400 text-blue-700';
      }
    },
    statusIconClass() {
      switch(this.statusType) {
        case 'success':
          return 'text-green-400';
        case 'error':
          return 'text-red-400';
        default:
          return 'text-blue-400';
      }
    }
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    syncProducts() {
      this.isSyncing = true;
      this.statusMessage = 'Synchronization has been started. Please wait...';
      this.statusType = 'info';

      axios.post('/api/sync-products')
        .then(() => {
          setTimeout(() => {
            this.fetchProducts();
            this.isSyncing = false;
            this.statusMessage = 'Synchronization complete! Products have been updated.';
            this.statusType = 'success';
          }, 2000); 
        })
        .catch(error => {
          this.statusMessage = 'Error starting synchronization. Check your logs.';
          this.statusType = 'error';
          this.isSyncing = false;
          console.error(error);
        });
    },
    fetchProducts() {
      axios.get('/api/products')
        .then(response => {
          this.products = response.data.products;
        })
        .catch(error => {
          console.error('Error fetching products:', error);
        });
    },
  }
};
</script>