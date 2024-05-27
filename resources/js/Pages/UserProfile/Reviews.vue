<script setup>
import { defineProps } from "vue";
import { useProfileStore } from "@/stores/profile";
import { useAuthStore } from "@/stores/auth";
import MainLayout from "@/Layouts/MainLayout.vue";
import FindFriendsRightAside from "@/Components/Asides/FindFriendsRightAside.vue";
import { Link } from "@inertiajs/vue3";

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
    <MainLayout title="Valoracions">
        <template #main-content>
            <div class="container mx-auto" v-if="profileStore.user.reviews.length > 0">
                <div class="grid grid-reviews">
                    <div class="review-card w-40 bg-white border border-gray-200 rounded-2xl dark:bg-gray-800 dark:border-gray-700"
                        v-for="review in profileStore.user.reviews" :key="review.id">
                        <Link :href="`/profile/${review.guest.id}`">
                        <div class="title flex items-center justify-left p-4 gap-2">
                            <img :src="review.guest.image" :alt="review.guest.name"
                                class="w-12 h-12 rounded-full fit-content object-cover">
                            <p class="font-bold ">
                                {{ review.guest.name }} {{ review.guest.surnames }}
                            </p>
                        </div>
                    </Link>
                        <div class="rating pl-4">
                            <template v-for="n in 5">
                                <input type="radio" :name="'rating-' + review.id" class="mask mask-star-2"
                                    :class="{ 'bg-orange-400': n <= review.rating, 'bg-gray-300': n > review.rating }" />
                            </template>
                        </div>

                        <p class="pl-4 pb-2">
                            {{ review.content }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="container mx-auto px-4 md:px-12" v-else>
                <p class="text-center text-2xl font-bold mt-10">Aquest usuari encara no té cap valoració.</p>
            </div>
        </template>
        <template #right-aside>
            <div class="aside-menu">
                <FindFriendsRightAside />
            </div>
        </template>
    </MainLayout>
</template>


<style scoped>
.grid-reviews {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(11rem, 1fr));
    gap: 15px;
}

.review-card {
    width: 100%;
    background-color: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 0.8rem;
    transition: all 0.3s;
}

.review-card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.mask-star-2 {
    width: 1.25rem;
    height: 1.25rem;
}

.bg-gray-300 {
    background-color: #d1d5db;
}

.bg-orange-400 {
    background-color: #fb923c;
}

.aside-menu {
    width: 100%;

    background: white;
    border-radius: 20px;
    padding: 26px 30px;
}
</style>