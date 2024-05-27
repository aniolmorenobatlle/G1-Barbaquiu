<script setup>
import { onMounted, ref, watch, computed } from 'vue';
import { useAuthStore } from "@/stores/auth";
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { useProfileStore } from "@/stores/profile";
import axios from 'axios';
import ColorThief from 'colorthief';
import { toFormData } from "axios";


const profileStore = useProfileStore();
const authStore = useAuthStore();

const form = useForm({
    id: '',
    image: null,
});


const sendFriendRequest = () => {
    let id = profileStore.user.id;
    form.post(route('sendfriendrequest', id));
    profileStore.friendStatus = 'sent';
};

const deleteFriend = () => {
    let id = profileStore.user.id;
    form.delete(route('friends.destroy', id));
    profileStore.friendStatus = 'none';

};

const cancelFrientRequest = () => {
    let id = profileStore.user.id;
    form.delete(route('friends.destroy', id));
    profileStore.friendStatus = 'none';
};

const acceptRequest = () => {
    let id = profileStore.user.id;
    form.post(route('sendfriendrequest', id));
    profileStore.friendStatus = 'friend';
};

const showModal = ref(false);
function openModal() {
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
}

function cancelChangeImage() {
    form.image = [];
    const imagesNamesElement = document.getElementById('logoImage');
    imagesNamesElement.innerHTML = '';

    closeModal();
}

const handleFileChange = (event) => {
    const file = event.target.files[0];
    form.image = file;

    const fileName = file.name;

    const logoImageDiv = document.getElementById('logoImage');

    const fileNameParagraph = document.createElement('p');

    fileNameParagraph.textContent = fileName;

    logoImageDiv.appendChild(fileNameParagraph);
};


const updateUserPhoto = () => {
    let id = profileStore.user.id;
    let formData = new FormData();

    formData.append('image', form.image);
    profileStore.user.image = URL.createObjectURL(form.image);
    showModal.value = false;

    axios.post(route('updateuserphoto', id), formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
        .then(response => {
            const imagesNamesElement = document.getElementById('logoImage');
            imagesNamesElement.innerHTML = '';

            authStore.updateUserData();
            analyzeImageColors(profileStore.user.image);
        })
        .catch(error => {
            console.log(error);
        });
};

const togglePrivateOrPublic = () => {
    console.log('togglePrivate');
    axios.post(route('toggleprivate'))
        .then(response => {
            console.log(response.data);
        })
        .catch(error => {
            console.log(error);
        });
}


const bgcolor1 = ref('#f8f9fa');
const bgcolor2 = ref('#e9ecef');
const bgcolor3 = ref('#dee2e6');
 

onMounted(() => {
    analyzeImageColors(profileStore.user.image);
});

async function analyzeImageColors(imageUrl) {
    const colorThief = new ColorThief();
    const img = new Image();

    img.src = imageUrl;

    img.onload = () => {
        const [color1, color2, color3] = colorThief.getPalette(img, 3);
        bgcolor1.value = rgbToHex(color1[0], color1[1], color1[2]);
        bgcolor2.value = rgbToHex(color2[0], color2[1], color2[2]);
        bgcolor3.value = rgbToHex(color3[0], color3[1], color3[2]);
    };
}

function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}




//

const isSaving = ref(false);
let timeoutId = null;

function saveDescription() {
    console.log('saveDescription');
    isSaving.value = true;

    if (timeoutId !== null) {
        clearTimeout(timeoutId);
    }

    timeoutId = setTimeout(() => {

        isSaving.value = false;
        console.log('Guardat!');
        console.log(profileStore.user.description);

        let id = authStore.user.id;
        axios.post(route('updateuserdescription', id), {
            description: profileStore.user.description
        })
            .then(response => {
                console.log(response.data);
            })
            .catch(error => {
                console.log(error);
            });

    }, 1500);
}

watch(() => profileStore.user.description, (newValue) => {
    if (newValue.length > 100) {
        profileStore.user.description = newValue.slice(0, 100);
    }
});

// get the total rating of the user from profileStore.user.reviews array (1 - 5)
// average the total rating

const averageRating = computed(() => {
    if (profileStore.user.reviews.length > 0) {
        const totalRating = profileStore.user.reviews.reduce((acc, review) => acc + review.rating, 0);
        return totalRating / profileStore.user.reviews.length;
    }

    return 0;
});

</script>

<template>
    <div v-if="profileStore.user && authStore.user">

        <div class="background w-full h-[150px] bg-cover bg-no-repeat bg-center rounded-t-[20px]"
            :style="{ backgroundImage: `linear-gradient(to right, ${bgcolor1}, ${bgcolor2}, ${bgcolor3})` }"></div>

        <div class="relative flex flex-1 w-full bg-white pb-2 pr-4 pl-4 pt-4">

            <div style="margin-top: -6rem;">

                <div v-if="profileStore.user.id == authStore.user.id">

                    <div class="avatar tooltip relative w-36 h-36 rounded-full" data-tip="Canvia la foto"
                        @click="openModal">
                        <div class="image-container relative w-36 h-36 rounded-full border-4 border-white bg-white">
                            <img class="image-profile absolute w-36 h-36 rounded-full" :src="profileStore.user.image"
                                alt="avatar">

                            <div
                                class="middle flex items-center justify-center opacity-0 transition-opacity duration-500">
                                <img src="/assets/img/camara.png" alt="Llàpis" class="!w-12 !h-12 cursor-pointer">
                            </div>
                        </div>
                    </div>


                    <dialog id="my_modal_3" class="modal" :open="showModal" @click.self="closeModal">
                        <div class="modal-box">
                            <form @submit.prevent="closeModal" method="dialog">
                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                                    @click="cancelChangeImage">
                                    ✕
                                </button>
                            </form>

                            <form @submit.prevent="updateUserPhoto" class="flex flex-col items-center gap-4">

                                <h3 class="font-bold text-lg mb-2">Selecciona una nova foto de perfil</h3>

                                <label class="custum-file-upload" for="file">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24">
                                            <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                                            <g stroke-linejoin="round" stroke-linecap="round"
                                                id="SVGRepo_tracerCarrier"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path fill=""
                                                    d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z"
                                                    clip-rule="evenodd" fill-rule="evenodd"></path>
                                            </g>
                                        </svg>
                                    </div>

                                    <div class="text">
                                        <span>Click per seleccionar una imatge</span>
                                    </div>

                                    <div class="text" id="logoImage">
                                    </div>

                                    <input type="file" id="file" name="image" @change="handleFileChange"
                                        accept="image/*">

                                </label>

                                <div class="flex justify-center mt-4 space-x-4">
                                    <button class="btn" type="submit">Guardar</button>
                                    <button class="btn" @click="cancelChangeImage">Cancelar</button>
                                </div>

                            </form>
                        </div>
                    </dialog>
                </div>


                <div v-else class="relative w-36 h-36 rounded-full avatar">
                    <img class="relative w-36 h-36 rounded-full border-4 border-white bg-white"
                        :src="profileStore.user.image" alt="avatar">
                    <div class="absolute"></div>
                </div>


            </div>
            <div class="flex flex-col text-left">
                <div class="flex">
                    <h1 class="text-2xl font-bold text-gray-800 ">
                        {{ profileStore.user.name }} {{ profileStore.user.surnames }}
                    </h1>

                    <div class="flex intolerances">
                        <div v-if="profileStore.user.vegetarian"
                            class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md bg-gray-50">
                            <img src="/assets/img/intolerance/vegetarian.png" class="h-7" alt="">
                            <p class="font-semibold text-green-700">Vegetarià</p>
                        </div>
                        <div v-if="profileStore.user.lactose"
                            class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md tooltip bg-gray-50"
                            data-tip="Intolerant a la lactosa">
                            <img src="/assets/img/intolerance/lactose.png" class="h-7" alt="">
                            <p class="font-semibold text-blue-400">Lactosa</p>
                        </div>
                        <div v-if="profileStore.user.gluten"
                            class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md tooltip bg-gray-50"
                            data-tip="Intolerant al gluten">
                            <img src="/assets/img/intolerance/gluten.png" class="h-7" alt="">
                            <p class="font-semibold text-yellow-600">Gluten</p>
                        </div>
                        <div v-if="profileStore.user.spicy"
                            class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md tooltip bg-gray-50"
                            data-tip="Intolerant al picant">
                            <img src="/assets/img/intolerance/spicy.png" class="h-7" alt="">
                            <p class="font-semibold text-red-700">Picant</p>
                        </div>
                        <div v-if="profileStore.user.halal"
                            class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md bg-gray-50">
                            <img src="/assets/img/intolerance/halal.png" class="h-7" alt="">
                            <p class="font-semibold text-black">Halal</p>
                        </div>

                    </div>

                </div>

                <div class="flex items-center content-center">
                    <img src="/assets/svg/marcador.svg" alt="Marcador" class="w-3 mr-1">
                    <p class="text-sm ">L'Estartit</p>
                </div>
            </div>
            <div class="flex flex-col text-right ml-auto">
                <div v-if="authStore.user && profileStore.user" class="flex gap-3">

                    <div class="reviews profilestars" v-if="profileStore.user.reviews.length > 0" >
                        <div id="average-rating">
                            <div class="rating">
                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" disabled
                                    :checked="averageRating >= 1" />
                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" disabled
                                    :checked="averageRating >= 2" />
                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" disabled
                                    :checked="averageRating >= 3" />
                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" disabled
                                    :checked="averageRating >= 4" />
                                <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" disabled
                                    :checked="averageRating >= 5" />
                            </div>
                        </div>
                        <Link :href="route('profile.reviews', { id: profileStore.user.id })"
                            class="text-sm hover:underline cursor-pointer transition duration-150 ">
                        Veure totes les valoracions
                        </Link>
                    </div>

                    <a class="" v-if="authStore.user.id == profileStore.user.id">
                        <label v-if="authStore.user.public == 1" class="swap">
                            <input type="checkbox" />

                            <div @click="togglePrivateOrPublic"
                                class="swap-on px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 transition duration-150">
                                <p class="text-red-600 font-bold">Privat</p>
                            </div>

                            <div @click="togglePrivateOrPublic"
                                class="swap-off px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 transition duration-150">
                                <p class="text-green-600 font-bold">Públic</p>
                            </div>
                        </label>

                        <label v-else class="swap">
                            <input type="checkbox" />

                            <div @click="togglePrivateOrPublic"
                                class="swap-on px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 transition duration-150">
                                <p class="text-green-600 font-bold">Public</p>
                            </div>

                            <div @click="togglePrivateOrPublic"
                                class="swap-off px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 transition duration-150">
                                <p class="text-red-600 font-bold">Privat</p>
                            </div>
                        </label>

                    </a>
                    <Link class="" :href="route('profile.edit', { profile: profileStore.user.id })"
                        v-if="authStore.user.id == profileStore.user.id" >

                    <button
                        class="px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-900 dark:hover:text-slate-300 hover:shadow transition duration-150">
                        <img class="" src="/assets/svg/editprofile.svg" loading="lazy" alt="Google Logo">
                        <span class="font-medium">Editar</span>
                    </button>

                    </Link>
                    <div v-else>
                        <div class="flex items-center justify-center dark:bg-gray-800 gap-2">
                            <Link
                                class="px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-900 dark:hover:text-slate-300 hover:shadow transition duration-150 bg-white">
                            <img class="" src="/assets/svg/sendmessage.svg" loading="lazy" alt="Google Logo">
                            <span class="text-black font-bold">Enviar missatge</span>
                            </Link>

                            <form v-if="profileStore.friendStatus === 'none'" @submit.prevent="sendFriendRequest"
                                class="form">

                                <Button
                                    class="px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-900 dark:hover:text-slate-300 hover:shadow transition duration-150 bg-orange-600">
                                    <img class="" src="/assets/svg/addfriend.svg" alt="Google Logo">
                                    <span class="text-white font-bold">Enviar solicitud d'amistat</span>
                                </Button>

                            </form>

                            <form v-if="profileStore.friendStatus === 'friend'" @submit.prevent="deleteFriend"
                                class="form">

                                <Button
                                    class="px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-900 dark:hover:text-slate-300 hover:shadow transition duration-150 bg-white">
                                    <img class="" src="/assets/svg/isfriend.svg" alt="google logo">
                                    <span class="text-black font-bold">Amics</span>
                                </Button>

                            </form>

                            <form v-if="profileStore.friendStatus === 'sent'" @submit.prevent="cancelFrientRequest"
                                class="form">
                                <Button
                                    class="px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-900 dark:hover:text-slate-300 hover:shadow transition duration-150 bg-red-600">
                                    <img class="" src="/assets/svg/cancelfriendrequest.svg" alt="google logo">
                                    <span class="text-white font-bold">Cancelar solicitud</span>
                                </Button>

                            </form>

                            <form v-if="profileStore.friendStatus === 'received'" @submit.prevent="acceptRequest"
                                class="form">

                                <Button
                                    class="px-4 py-2 border flex gap-2 border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 hover:border-slate-400 dark:hover:border-slate-500 hover:text-slate-900 dark:hover:text-slate-300 hover:shadow transition duration-150 bg-green-600">
                                    <span class="text-white font-bold">Aceptar solicitud</span>
                                </Button>
                            </form>

                        </div>
                    </div>

                </div>

            </div>


        </div>
        <div class="userdescription bg-white rounded-b-[20px] ">
            <div class="" v-if="profileStore.user.description && profileStore.user.id != authStore.user.id">
                <p class="">{{ profileStore.user.description }}</p>
            </div>
            <div v-if="profileStore.user.id == authStore.user.id" class="userdescriptiontext">
                <div class="input-container flex ">
                    <div class="underlineinput flex w-full">
                        <input type="text" id="animated-input" class="outline-none bg-transparent border-none w-full "
                            v-model="profileStore.user.description" @input="saveDescription"
                            placeholder="Escriu la teva descripció..." />
                        <span v-if="isSaving" class="loading loading-spinner loading-xs"></span>

                        <span v-if="profileStore.user.description"
                            :class="{ 'text-red-500 font-bold': profileStore.user.description.length === 100 }"
                            class="char-count">
                            {{ profileStore.user.description.length }}/100
                        </span>

                    </div>
                </div>

            </div>


        </div>

    </div>
</template>

<style scoped>
@keyframes slideIn {
    from {
        width: 0;
    }

    to {
        width: 100%;
    }
}

.swap{
    
}

.underlineinput {
    border-bottom: 0.5px solid rgb(197, 197, 197);
    font-style: italic;
}

#animated-input {
    animation: slideIn 0.5s forwards;
}


.char-count {}


.avatar .image-container {
    position: relative;
    overflow: hidden;
}

.avatar .image-profile {
    opacity: 1;
    display: block;
    transition: .5s ease;
    backface-visibility: hidden;
}

.middle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.avatar:hover .middle {
    opacity: 1;
}

.avatar:hover .image-profile {
    filter: brightness(0.5);
    cursor: pointer;
}



.modal {
    background-color: rgba(0, 0, 0, 0.5);
}

.custum-file-upload {
    height: 200px;
    display: flex;
    flex-direction: column;
    align-items: space-between;
    gap: 10px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    border: 2px dashed #cacaca;
    background-color: rgba(255, 255, 255, 1);
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0px 48px 35px -48px rgba(0, 0, 0, 0.1);
}

.custum-file-upload .icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.custum-file-upload .icon svg {
    height: 80px;
    fill: rgba(75, 85, 99, 1);
}

.custum-file-upload .text {
    display: flex;
    align-items: center;
    justify-content: center;
}

.custum-file-upload .text span {
    font-weight: 400;
    color: rgba(75, 85, 99, 1);
}

.custum-file-upload input {
    display: none;
}

.custum-file-upload svg {
    transition: transform 0.3s, filter 0.3s;
}

.custum-file-upload:hover svg {
    transform: scale(1.2);
    filter: brightness(0%);
}

.userdescription {
    padding-left: 30px;
    padding-right: 30px;
    padding-bottom: 15px;
    font-style: italic;
}


@media (max-width: 1000px) {
 .intolerances {
    display: none;
 }
 .userdescriptiontext{
    display: none;
 }
 .profilestars{
    display: none;
 }
}
</style>