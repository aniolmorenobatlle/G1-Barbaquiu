<script setup>
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { useAuthStore } from "@/stores/auth";
import { Link } from '@inertiajs/vue3';
import { useProfileStore } from "@/stores/profile";
import Logout from '@/Pages/Profile/Partials/Logout.vue';
import Banner from "@/Components/UserProfile/Banner.vue";


const profileStore = useProfileStore();

const authStore = useAuthStore();
authStore.updateUserData();


</script>
 
<template>
    <ProfileLayout title="Inici">
        <template #main-content>
            <div class="max-w-7xl mx-auto space-y-6 overflow">

                <div class="flex justify-start items-center bg-white shadow sm:rounded-[20px] p-2 w-full" >
                    <Link 
                        class="flex items-center gap-2 cursor-pointer hover:opacity-70 transition-opacity"
                        :href="'/profile/' + authStore.user.id" v-if="authStore.user">

                        <img src="/assets/svg/flecha-izquierda.svg"
                        alt="flecha-izquierda" 
                        class="w-5 h-5 cursor-pointer hover:opacity-70 transition-opacity pl-4 pr-2" >

                        <h1 class="text-lg font-medium text-gray-900">Tornar al perfil</h1>
                    </Link>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-[20px]">
                    
                    <UpdateProfileInformationForm
                        class=""
                    />
                </div>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-[20px]">
                    <UpdatePasswordForm class="" />
                </div>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-[20px]">
                    <DeleteUserForm class="" />
                </div>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-[20px]">
                    <Logout class="" />
                </div>
            </div>
        </template>
    </ProfileLayout>
</template>



<!-- <Head title="Profile" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div> -->


      