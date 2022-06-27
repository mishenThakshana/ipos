import { defineStore } from "pinia";

const useUserStore = defineStore("user", {
    state: () => {
        return {
            userId: localStorage.getItem("encryptedId"),
            userToken: localStorage.getItem("encryptedToken"),
        };
    },
});

export default useUserStore;
