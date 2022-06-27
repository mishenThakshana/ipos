import "./bootstrap";
import { createApp } from "vue";
import App from "./App.vue";
import Router from "./routes";
import { createPinia } from "pinia";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const Pinia = createPinia();

createApp(App)
    .use(Pinia)
    .use(Router)
    .use(Toast, { hideProgressBar: false })
    .mount("#app");
