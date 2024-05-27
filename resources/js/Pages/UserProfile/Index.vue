<script setup>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import BarbaqUserProfile from "@/Components/UserProfile/BarbaqUserProfile.vue";
import { defineProps } from "vue";
import { useProfileStore } from "@/stores/profile";
import { useAuthStore } from "@/stores/auth";
import Banner from "@/Components/UserProfile/Banner.vue";

const authStore = useAuthStore();
authStore.updateUserData();

const profileStore = useProfileStore();

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    friendStatus: {
        type: String,
        required: false
    }
});

profileStore.setUser(props.user);
profileStore.setStatus(props.friendStatus);

</script>

<template>
    <ProfileLayout title="Inici" :user="user">
        <template #main-content >
            <Banner class="banner-profile "/>
            <BarbaqUserProfile />
        </template>
    </ProfileLayout>
</template>

<style scoped>
.aside-menu {
    width: 100%;
    height: 400px;
    background: white;
    border-radius: 20px;
}

.banner-profile{
    padding-bottom: 20px;
} 
</style>