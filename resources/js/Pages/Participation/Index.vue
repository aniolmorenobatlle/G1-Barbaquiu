<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useAuthStore } from "@/stores/auth";
import { ref, onMounted } from 'vue';
import MainLayout from "@/Layouts/MainLayout.vue";
import UserLink from "@/Components/User/UserLink.vue";
import Timestamp from "@/Components/Time/Timestamp.vue";
import FindFriendsRightAside from "@/Components/Asides/FindFriendsRightAside.vue";
import axios from 'axios';


const authStore = useAuthStore();
authStore.updateUserData();

const allBarbecues = ref([]);
const futureBarbecues = ref([]);
const pastBarbecues = ref([]);

onMounted(() => {
    axios.get('/api/my-barbecues')
        .then(response => {
            allBarbecues.value = response.data.sort((a, b) => {
                return new Date(b.created_at) - new Date(a.created_at);
            });

            for (const barbecue of allBarbecues.value) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const [day, month, year, hourMinute] = barbecue.date.split(/[/ :h]/);
                const barbecueDate = new Date(`${year}-${month}-${day}T${hourMinute}:00`);

                if (barbecueDate >= today) {
                    futureBarbecues.value.push(barbecue);
                } else {
                    pastBarbecues.value.push(barbecue);
                }
            }
        });
});

const disableLinks = (event) => {
    const links = event.currentTarget.querySelectorAll('a');
    links.forEach(link => {
        link.style.pointerEvents = 'none';
    });
}

const enableLinks = (event) => {
    const links = event.currentTarget.querySelectorAll('a');
    links.forEach(link => {
        link.style.pointerEvents = 'auto';
    });
}

const leaveBarbecue = (barbecueId, userId) => {

    console.log(barbecueId, userId);
    axios.delete(route('destroyfriendship'), {
    }).then(response => {
        console.log(response);
    }).catch(error => {
        console.log(error);
    });
}

const showModal = ref(false);

const closeModal = () => {
    showModal.value = false;
    form.product_name = '';
    form.product_price = '';
};

const form = useForm({
    content: '',
    rating: 1,
    barbecue_id: '',
    guest_id: '',
    user_id: ''
});

const submitReview = (barbecueId, guestId, userId) => {
    form.barbecue_id = barbecueId;
    form.user_id = userId;
    form.guest_id = guestId;
    console.log(form);
    form.post(route('review'));
    closeModal();
    form.reset();


}

const updateRating = event => {
    form.rating = parseInt(event.target.value);
    console.log(form.rating);
}
</script>

<template>

    <Head title="Barbacoes en que participes" />

    <MainLayout title="Barbacoes en que participes">
        <template #main-content>

            <section>
                <h1 class="activestext">
                    Actives
                </h1>

                <div class="grid gap-5">
                    <div v-for="barbecue in futureBarbecues" :key="barbecue.id" class="barbecue-container">
                        <div class="barbecue-header">
                            <div class="barbecue-left-section">
                                <div class="barbecue-profile-image">
                                    <Link :href="route('profile.show', barbecue.user.id)">
                                    <img :src="barbecue.user.image" alt="Profile image">
                                    </Link>
                                </div>

                                <div class="barbecue-left-texts">
                                    <UserLink :userId="barbecue.user.id"
                                        :name="barbecue.user.name + ' ' + barbecue.user.surnames" />

                                    <div class="barbecue-address">
                                        <img src="/assets/svg/marker.svg" alt="Location" />
                                        <span>{{ barbecue.address }}</span>

                                    </div>
                                </div>
                            </div>


                            <div class="barbecue-right-section">
                                <Timestamp :datetime="barbecue.created_at" class="barbecue-creation text-sm" />
                            </div>

                        </div>

                        <div class="barbecue-content-wrapper flex justify-between">
                            <div class="flex flex-col w-auto">
                                <Link :href="'/barbecues/' + barbecue.id"
                                    class="barbecue-title hover:text-orange-500 transition-colors">
                                {{ barbecue.title }}

                                </Link>
                                <span class="barbecue-date">{{ barbecue.date }}</span>
                            </div>

                            <div class="reviews flex align-end justify-end ">
                                <div class="ml-auto mr-2 flex justify-end items-end">
                                    <button title="Abandonar"
                                        @click="leaveBarbecue(barbecue.id, authStore.user.id)"
                                        class="btn hover:bg-[#c84c00] items-center hover:text-white bg-[#FF6100] text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6100] focus:ring-offset-white active:bg-[#FF6100] active:text-white px-2 py-2 rounded-md transition ease-in-out duration-150">
                                        <svg width="27px" height="27px" viewBox="0 0 24 24" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <title>Session-Leave</title>
                                                <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <g id="Session-Leave">
                                                        <rect id="Rectangle" fill-rule="nonzero" x="0" y="0" width="24"
                                                            height="24"> </rect>
                                                        <line x1="9" y1="12" x2="19" y2="12" id="Path" stroke="#ffffff"
                                                            stroke-width="2" stroke-linecap="round"> </line>
                                                        <path
                                                            d="M16,8 L18.5858,10.5858 C19.3668,11.3668 19.3668,12.6332 18.5858,13.4142 L16,16"
                                                            id="Path" stroke="#ffffff" stroke-width="2"
                                                            stroke-linecap="round"> </path>
                                                        <path
                                                            d="M16,4 L6,4 C4.89543,4 4,4.89543 4,6 L4,18 C4,19.1046 4.89543,20 6,20 L16,20"
                                                            id="Path" stroke="#ffffff" stroke-width="2"
                                                            stroke-linecap="round"> </path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        <span class="text-sm text-white font-bold pr-1">Abandonar</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <section class="my-5">
                <h1 class="finalitzadestext">
                    Finalitzades
                </h1>

                <div class="grid gap-5">
                    <div v-for="barbecue in pastBarbecues" :key="barbecue.id">
                        <div class="barbecue-container past-barbecues" @mouseover="disableLinks"
                            @mouseleave="enableLinks">
                            <div class="barbecue-header">
                                <div class="barbecue-left-section">
                                    <div class="barbecue-profile-image">
                                        <Link :href="route('profile.show', barbecue.user.id)">
                                        <img :src="barbecue.user.image" alt="Profile image">
                                        </Link>
                                    </div>
                                    <div class="barbecue-left-texts">
                                        <UserLink :userId="barbecue.user.id"
                                            :name="barbecue.user.name + ' ' + barbecue.user.surnames" />
                                        <div class="barbecue-address">
                                            <img src="/assets/svg/marker.svg" alt="Location" />
                                            <span>{{ barbecue.address }}</span>
                                        </div>
                                    </div>
                                </div>
                                <Timestamp :datetime="barbecue.created_at" class="barbecue-creation" />
                            </div>

                            <div class="barbecue-content-wrapper flex justify-between">
                                <div class="flex flex-col w-auto">
                                    <Link :href="'/barbecues/' + barbecue.id"
                                        class="barbecue-title hover:text-orange-500 transition-colors">
                                    {{ barbecue.title }}
                                    </Link>
                                    <span class="barbecue-date">{{ barbecue.date }}</span>
                                </div>

                                <div class="reviews flex align-end justify-end ">
                                    <div class="flex justify-end items-end">
                                        <button
                                            class="btn hover:bg-[#c84c00] items-center hover:text-white bg-[#FF6100] text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6100] focus:ring-offset-white active:bg-[#FF6100] active:text-white px-4 py-2 rounded-md transition ease-in-out duration-150"
                                            @click="showModal = true">Valorar</button>
                                    </div>
                                    <dialog id="my_modal_3" class="modal" :open="showModal" @click.stop>
                                        <div class="modal-box cursor-default">
                                            <form method="dialog">
                                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                                                    @click="closeModal">
                                                    ✕
                                                </button>
                                            </form>

                                            <h1 class="text-center">
                                                Deixa la teva valoració a <span class=" font-bold">{{ barbecue.user.name
                                                    }}</span>
                                            </h1>
                                            <h2 class="text-center">
                                                per a <span class=" font-bold">{{ barbecue.title }}</span></h2>
                                            <form
                                                @submit.prevent="submitReview(barbecue.id, authStore.user.id, barbecue.user.id)">
                                                <div class="flex flex-col items-start gap-4 p-4">
                                                    <div class="rating">
                                                        <input type="radio" name="rating"
                                                            class="mask mask-star-2 bg-orange-400" value="1"
                                                            @change="updateRating" checked />
                                                        <input type="radio" name="rating"
                                                            class="mask mask-star-2 bg-orange-400" value="2"
                                                            @change="updateRating" />
                                                        <input type="radio" name="rating"
                                                            class="mask mask-star-2 bg-orange-400" value="3"
                                                            @change="updateRating" />
                                                        <input type="radio" name="rating"
                                                            class="mask mask-star-2 bg-orange-400" value="4"
                                                            @change="updateRating" />
                                                        <input type="radio" name="rating"
                                                            class="mask mask-star-2 bg-orange-400" value="5"
                                                            @change="updateRating" />
                                                    </div>

                                                    <textarea class="textarea textarea-warning w-full max-h-32"
                                                        maxlength="500" v-model="form.content"
                                                        placeholder="Deixa un comentari..."></textarea>

                                                </div>
                                                <div class="flex justify-center w-full">


                                                    <button
                                                        class="btn w-1/4 hover:bg-[#c84c00] items-center hover:text-white bg-[#FF6100] text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6100] focus:ring-offset-white active:bg-[#FF6100] active:text-white px-4 py-2 rounded-md transition ease-in-out duration-150">
                                                        Enviar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </template>

        <template #right-aside>
            <div class="aside-menu mb-5">
                <FindFriendsRightAside />
            </div>
        </template>

    </MainLayout>
</template>

<style scoped>
.activestext {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: 700;
    color: #00ac1d;

    background-color: rgb(255, 255, 255);
    border-radius: 20px;
}

.finalitzadestext {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: 700;
    color: #cc4e00;

    background-color: rgb(255, 255, 255);
    border-radius: 20px;
}

.barbecue-container {
    display: flex;
    width: 100%;
    max-width: 100%;
    height: 100%;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    padding: 12px 20px 19px 20px;
    flex-direction: column;
    gap: 10px;
}

.modal {
    background-color: rgba(0, 0, 0, 0.407);


}

.barbecue-container.past-barbecues {
    transition: all 0.3s ease;
}

.barbecue-container.past-barbecues:hover {
    background: #dddddd;
}

.barbecue-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.barbecue-left-section {
    height: 100%;
    width: fit-content;
    display: flex;
    align-items: center;
    gap: 10px;
}

.barbecue-profile-image {
    display: flex;
    align-items: center;
    height: 100%;
}

.barbecue-profile-image img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.barbecue-left-texts {
    display: flex;
    flex-direction: column;
}

.barbecue-address {
    display: flex;
    align-items: center;
    gap: 3px;
    filter: opacity(0.6);
    font-size: 14px;
}

.barbecue-address img {
    width: 13px;
    height: 13px;
    transform: translateY(-2px);
}

.barbecue-right-section {
    text-align: right;
}

.barbecue-creation {
    height: 100%;
    display: flex;
    align-items: center;
    color: #888;
}

.barbecue-title {
    font-size: 24px;
}

.barbecue-date {
    display: block;
    font-size: 12px;
    color: #888888;
}

.aside-menu {
    width: 100%;
    background: white;
    border-radius: 20px;
    padding: 26px 30px;
}
</style>