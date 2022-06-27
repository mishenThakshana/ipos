import { createRouter, createWebHistory } from "vue-router";
import Main from "./views/Main.vue";
import Login from "./views/Login.vue";
import Register from "./views/Register.vue";
import Management from "./views/Management.vue";

const Router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "Main",
            component: Main,
        },
        {
            path: "/login",
            name: "Login",
            component: Login,
        },
        {
            path: "/register",
            name: "Register",
            component: Register,
        },
        {
            path: "/management",
            name: "Management",
            component: Management,
        },
    ],
});

export default Router;
