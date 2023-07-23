// my-component.js
import { createApp } from 'vue';

const app = createApp({
  data() {
    return {
      message: 'Hello, Vue 3!'
    }
  },
});

app.mount('#codeTrendApp');
