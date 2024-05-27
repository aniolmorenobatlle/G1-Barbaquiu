<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Barbecue from "@/Components/Index/Barbecue.vue";
import CreateBarbaqInput from "@/Components/CreateBarbaqInput/CreateBarbaqInput.vue";
import { useAuthStore } from "@/stores/auth";
import { useBarbecueStore } from "@/stores/barbecue";
import { useResponsiveStore } from "@/stores/responsive";
import IndexRightAside from "@/Components/Asides/IndexRightAside.vue";
import { onMounted, ref } from "vue";

const authStore = useAuthStore();
authStore.updateUserData();

const barbecueStore = useBarbecueStore();
const responsiveStore = useResponsiveStore();

const showModal = ref(true);

onMounted(() => {
    const barbecueScroller = document.querySelector(".main-layout-content-wrapper");
    barbecueScroller.addEventListener("scroll", () => {
        const maxPossibleScroll = barbecueScroller.scrollHeight - barbecueScroller.clientHeight;
        if (barbecueScroller.scrollTop == maxPossibleScroll) {
            if (barbecueStore.barbecues.length === 0) return;
            console.log("Loading more barbecues");
            barbecueStore.fetchNext();
        }
    });
    if (getCookie("cookieAccepted")) {
        showModal.value = false; 
    }
});

const acceptCookie = () => {
    setCookie("cookieAccepted", true, 365);
    showModal.value = false;
}

const denyCookie = () => {
    showModal.value = false; 
}


const setCookie = (name, value, days) => {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

const getCookie = (name) => {
    const cookieName = name + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookieArray = decodedCookie.split(';');
    for (let i = 0; i < cookieArray.length; i++) {
        let cookie = cookieArray[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(cookieName) === 0) {
            return cookie.substring(cookieName.length, cookie.length);
        }
    }
    return "";
}

// Función para eliminar una cookie
const deleteCookie = (name) => {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}
</script>
<template>
    <MainLayout title="Inici">
        <template #main-content>
            <div v-if="!responsiveStore.filtersOpened || responsiveStore.screenWidth > 1200">
                <CreateBarbaqInput v-if="authStore.user" />
                <div class="barbecues">
                    <Barbecue v-for="barbecue in barbecueStore.preInsertedBarbecues.concat(barbecueStore.barbecues)" :key="barbecue.id" :barbecue="barbecue" />
                </div>
            </div>
            <div v-else>
                <IndexRightAside :alwaysVisible="true" />
            </div>
        </template>
        <template #right-aside>
            <IndexRightAside />

        </template>
        
    </MainLayout>
    <div v-if="showModal" class="fixed inset-0 flex items-end justify-center">
    <div class="absolute inset-0 bg-white opacity-50"></div>
    <div class="relative w-full bg-white text-black rounded-t-lg">
        <div class="p-4 flex flex-col">
            <div class="mb-2">
                <h2 class="text-2xl font-bold">Cookies</h2>
                <p class="text-sm">Les cookies són petits fitxers de text que s'emmagatzemen al teu dispositiu quan navegues per llocs web. Aquestes cookies es fan servir per millorar l'experiència de navegació, recordar les teves preferències i proporcionar funcionalitats personalitzades. Podem utilitzar cookies per analitzar el trànsit del lloc web i entendre com els visitants interactuen amb el lloc. Al acceptar les cookies, estàs consentint l'ús de les mateixes de conformitat amb la nostra política de cookies.</p>
            </div>
            <div class="flex justify-end">
                <button class="btn text-white bg-orange-600 hover:bg-orange-400 mr-2" @click="acceptCookie">Acceptar</button>
                <button class="btn text-white bg-black hover:bg-gray-800" @click="denyCookie">Rebutjar</button>
            </div>
        </div>
    </div>
</div>
</template>
<style scoped>
.barbecues {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

</style>