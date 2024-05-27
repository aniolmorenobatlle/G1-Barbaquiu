<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Chat from "@/Components/Barbecues/Chat.vue";
import BarbacuesAside from "@/Components/Barbecues/BarbacuesAside.vue";
import NoAuthBBQ from "@/Components/Barbecues/NoAuthBBQ.vue";
import { defineProps } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useBarbecueStore } from "@/stores/barbecue";
import { useResponsiveStore } from "@/stores/responsive";
const authStore = useAuthStore();
authStore.updateUserData();

const responsiveStore = useResponsiveStore();

const props = defineProps({
    barbecue: {
        type: Object,
        required: true
    },
    friends: {
        type: Object,
        required: true,
    },
});

const barbecueStore = useBarbecueStore();
barbecueStore.setBarbecue(props.barbecue);

</script>

<template>
    <MainLayout :title="barbecue.title">
        <template #main-content>
            <!-- <div >
            
            </div> -->
            <div v-if="authStore.user && !responsiveStore.filtersOpened || responsiveStore.screenWidth > 1200">
                <Chat v-if="(props.barbecue.members.map(member => member.id).includes(authStore.user.id) || authStore.user.id === props.barbecue.user_id)" />
                <NoAuthBBQ v-else />
            </div>
            <div v-else>
                <BarbacuesAside :friends="friends" :barbecue="barbecue"/>
            </div>
        </template>
        <template #right-aside>
            <div class="aside-menu">
                <BarbacuesAside :friends="friends" :barbecue="barbecue"/>
            </div>
        </template>
    </MainLayout>
</template>

<style scoped>
.aside-menu {
    width: 100%;
    height: max-content;
    background: white   ;
    border-radius: 20px;
}

@media (max-width: 1200px) {
    .aside-menu {
        visibility: hidden;
    }
}
</style>