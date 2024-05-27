<script setup>
import { ref, createApp, onMounted } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import { useAuthStore } from "@/stores/auth";
import { useBarbecueStore } from '@/stores/barbecue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';


const authStore = useAuthStore();
const barbecueStore = useBarbecueStore();

const app = createApp()
app.component('QuillEditor', QuillEditor)

const isOpen = ref(false);
const showModalLocation = ref(false);
const showModalCalendar = ref(false);
const showModalImages = ref(false);

const quillContent = ref(null);

const barbecueForm = useForm({
    title: '',
    content: '',
    latitude: '',
    longitude: '',
    address: '',
    date: '',
});

const openDropdown = () => {
    if (!isOpen.value) {
        isOpen.value = true;
    }
};

const closeDropdown = () => {
    if (isOpen.value) {
        isOpen.value = false;
    }
};

function cancelDate() {
    dateForm.day = '';
    dateForm.month = '';
    dateForm.year = '';
    dateForm.hour = '';
    dateForm.minute = '';

    closeModalCalendar();
};

function openModalLocation() {
    showModalLocation.value = true;
}

function closeModalLocation() {
    showModalLocation.value = false;
}

function cancelLocation() {
    barbecueForm.latitude = '';
    barbecueForm.longitude = '';
    barbecueForm.address = '';

    closeModalLocation();
};

function openModalCalendar() {
    showModalCalendar.value = true;
}

function closeModalCalendar() {
    const stringDate = `${dateForm.year}-${dateForm.month}-${dateForm.day}T${dateForm.hour}:${dateForm.minute}`;
    barbecueForm.date = stringDate;

    showModalCalendar.value = false;
}

function openModalImages() {
    showModalImages.value = true;
}

function closeModalImages() {
    showModalImages.value = false;
}

function cancelImages() {
    imagesForm.image = [];
    const imagesNamesElement = document.getElementById('imagesNames');
    imagesNamesElement.innerHTML = '';

    closeModalImages();
}

const submitBarbecueForm = () => {
    barbecueForm.content = quillContent.value.getHTML();

    const mensajeTelegram = `👤 <b>${authStore.user.name} ${authStore.user.surnames}</b> ha creado la barbacoa 🔥 "${barbecueForm.title}"\n ✏️ Creat per: <a href="https://barbaq.es/profile/${authStore.user.id}">Perfil</a>`;
    const textoCodificado = encodeURIComponent(mensajeTelegram);
    const urlTelegram = `https://api.telegram.org/bot7062247411:AAFn72YwZboq4fSt3YmOuAcLsMtX-l76fHk/sendMessage?chat_id=@bbqtest&text=${textoCodificado}&parse_mode=html`;

    axios.post(urlTelegram)
        .then(() => {
            console.log('Mensaje enviado a Telegram');
        })
        .catch((error) => {
            console.error('Error al enviar el mensaje a Telegram:', error);
        });

    axios.post("/api/discordbot", {
            message: `👤 **${authStore.user.name} ${authStore.user.surnames}** ha creat la barbacoa 🔥 "${barbecueForm.title}"\n ✏️ Creat per: https://barbaq.es/profile/${authStore.user.id}\n `
        })


    const mensajeDiscord = `Holaa 1`;
    const payloadDiscord = {
        content: mensajeDiscord
    };

    const webhookURL = 'https://discord.com/api/webhooks/1234888722391109712/MFQE_6mVi1ROVCTQgJmGuV5nKojCIXggHOmwptwqi9f55io7FLYb5hRrRVg8y3L8Si3Y';

    axios.post(webhookURL, payloadDiscord, {
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(() => {
            console.log('Mensaje enviado a Discord');
        })
        .catch((error) => {
            console.error('Error al enviar el mensaje a Discord:', error);
        });


    axios.post("/barbecues", barbecueForm)
        .then(response => {
            barbecueForm.reset();
            quillContent.value.setHTML('');
            isOpen.value = false;

            const barbecueId = response.data.id;
            const formData = new FormData();
            for (const image of imagesForm.image) {
                formData.append('images[]', image);
            }
            barbecueStore.fetchBarbecues();

            axios.post(`/barbecues/${barbecueId}/images`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            })
                .then(() => {
                    console.log('Imatges pujades correctament');
                    barbecueStore.fetchBarbecues();

                    imagesForm.image = [];
                })
                .catch((error) => {
                    console.error('Error al pujar les imatges:', error);
                });
        })
};

const imagesForm = useForm({
    image: [],
});

const handleImagesChange = (event) => {
    imagesForm.image = event.target.files;

    const fileList = Array.from(imagesForm.image);
    const fileNames = fileList.map(file => file.name).join(', ');
    const imagesNamesElement = document.getElementById('imagesNames');
    imagesNamesElement.innerHTML = `<p>${fileNames}</p>`;

    console.log("File names:", fileNames);

    console.log("Selected images:", imagesForm.image);
};

const currentYear = new Date().getFullYear();
const numberOfYears = 2;
const years = [];
for (let i = 0; i < numberOfYears; i++) {
    years.push(currentYear + i);
}

const dateForm = useForm({
    day: '',
    month: '',
    year: '',
    hour: '',
    minute: '',
});

const locations = ref([]);
const ul = ref(null);

const getData = async () => {
    try {
        const response = await axios.get(
            `https://api.opencagedata.com/geocode/v1/json?q=Catalunya, ${barbecueForm.address}&key=eccf391632f347fab191d32ea7c72b34&language=ca`,
        );
        return response.data;
    } catch (error) {
        console.error(error);
    }
};

const actualitzarCerca = async () => {
    try {
        const response = await getData();
        locations.value = response.results;
    } catch (error) {
        console.error(error);
    }
};

function parseCoords(lat, lng) {
    const f = (c) => {
        const parts = c.match(/[0-9.]+|[NSWE]+/g);
        const degrees = parseFloat(parts[0]);
        const minutes = parseFloat(parts[1]);
        const seconds = parseFloat(parts[2]);
        const direction = parts[3];

        let decimal = degrees + minutes / 60 + seconds / 3600;

        if (direction === 'S' || direction === 'W') {
            decimal = -decimal;
        }
        return decimal;
    };

    return {
        latitude: f(lat),
        longitude: f(lng)
    };
}

function selectAddress(event) {
    const ul = event.target.parentElement;
    ul.style.display = 'none';

    barbecueForm.address = event.target.innerText;
    let lat = event.target.getAttribute('lat');
    let lng = event.target.getAttribute('lng');

    const coords = parseCoords(lat, lng);

    barbecueForm.latitude = coords.latitude;
    console.log('Latitut: ' + barbecueForm.latitude);

    barbecueForm.longitude = coords.longitude;
    console.log('Longitud: ' + barbecueForm.longitude);
}

onMounted(() => {
    getData()
        .then((response) => {
            locations.value = response.results;
            console.log(locations.value);
        })
        .catch((error) => {
            console.error(error);
        });
});

function showSearch() {
    if (ul.value.style.display === 'none') {
        ul.value.style.display = 'block';
    }
}

function showNoSearch() {
    ul.style.display = 'none';
}

</script>

<template>

    <form 
        @submit.prevent="submitBarbecueForm" 
        class="dropdown mb-5"
        :class="{ open: isOpen }"
        v-if="authStore.user"
    >
        <div class="flex">
            <img :src="authStore.user.image" alt="Foto de perfil" class="mr-2 h-16 w-16 rounded-full imgprofile">
            <div class="w-full mt-auto mb-auto ">
                <div class="input-container">
                    <input type="text" placeholder="Crear nova barbacoa..." v-model="barbecueForm.title" @click="openDropdown">
                </div>
            </div>
            <img 
                v-if="!isOpen" 
                src="/assets/svg/plus.svg" 
                alt="Afegir barbacoa"
                class="cursor-pointer" 
                @click="openDropdown"
            >
            <img 
                v-else 
                src="/assets/svg/minus.svg" 
                alt="Minimitzar afegir barbacoa"
                class="cursor-pointer"
                @click="closeDropdown"
            >
        </div>

        <div class="content " :style="{ height: isOpen ? 'auto' : '0' }">

            <section class="mt-3 ">
                <QuillEditor theme="snow" class="rounded-b-lg min-h-24" ref="quillContent"></QuillEditor>
            </section>

            <div class="flex items-center">
                <img 
                    src="/assets/svg/addphoto.svg" 
                    alt="Imatge insertar foto/s" 
                    class="add-info cursor-pointer" 
                    @click="openModalImages"
                >

                <img 
                    src="/assets/svg/addcalendar.svg" 
                    alt="Imatge insertar data" 
                    class="add-info cursor-pointer ml-[2px] -mr-[2px]"
                    @click="openModalCalendar"
                >

                <img 
                    src="/assets/svg/addlocation.svg" 
                    alt="Imatge insertar ubicació" 
                    class="add-info cursor-pointer"
                    @click="openModalLocation"
                >

                <button 
                    type="submit" 
                    value="Publicar" 
                    class="boton-elegante ml-auto mt-2" 
                >
                    Publicar
                </button>
            </div>
        </div>

        <dialog id="my_modal_3" class="modal cursor-auto" :open="showModalImages" @click.self="closeModalImages">
            <div class="modal-box w-auto min-w-[30%] max-w-5xl">
                <form 
                    @submit.prevent="closeModalImages"
                    class="flex flex-col items-center gap-4"
                >
                    <button 
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                        @click="cancelImages"
                    >
                        ✕
                    </button>
                
                    <h3 class="font-bold text-lg mb-2">Selecciona la imatge/s</h3>

                    <label class="custum-file-upload" for="images">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24">
                                <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
                                <g 
                                    stroke-linejoin="round" 
                                    stroke-linecap="round"
                                    id="SVGRepo_tracerCarrier">
                                </g>

                                <g id="SVGRepo_iconCarrier">
                                    <path 
                                        fill=""
                                        d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z"
                                        clip-rule="evenodd" fill-rule="evenodd">
                                    </path>
                                </g>
                            </svg>
                        </div>

                        <div class="text">
                            <span>Clica per seleccionar una imatge</span>
                        </div>

                        <div class="text" id="imagesNames">
                        </div>
                        
                        <input 
                            type="file" 
                            id="images" 
                            name="image" 
                            multiple
                            accept="image/*" 
                            @change="handleImagesChange"
                        />
                    </label>

                    <div class="flex justify-center mt-4 space-x-4">
                        <button class="btn" type="submit">Guardar</button>
                        <button class="btn" @click="cancelImages">Cancelar</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id="my_modal_3" class="modal cursor-auto" :open="showModalCalendar" @click.self="closeModalCalendar">
            <div class="modal-box w-auto min-w-[30%] max-w-5xl">
                <form 
                    @submit.prevent="closeModalCalendar"
                    class="flex flex-col gap-4"
                >
                    <button 
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                        @click="cancelDate"
                    >
                        ✕
                    </button>
                
                    <h3 class="flex justify-center font-bold text-lg mb-2">Programa la barbacoa</h3>

                    <div>
                        <h2>Data</h2>
                    
                        <div class="flex gap-2 w-full">
                            <select name="day" id="day" class="border border-black rounded-lg p-3 flex-1" v-model="dateForm.day">
                                <option value="" disabled selected hidden>Dia</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>

                            <select name="month" id="month" class="border border-black rounded-lg p-3 flex-1" v-model="dateForm.month">
                                <option value="" disabled selected hidden>Mes</option>
                                <option value="01">Gener</option>
                                <option value="02">Febrer</option>
                                <option value="03">Març</option>
                                <option value="04">Abril</option>
                                <option value="05">Maig</option>
                                <option value="06">Juny</option>
                                <option value="07">Juliol</option>
                                <option value="08">Agost</option>
                                <option value="09">Setembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Desembre</option>
                            </select>

                            <select name="year" id="year" class="border border-black rounded-lg p-3 flex-1" v-model="dateForm.year">
                                <option value="" disabled selected hidden>Any</option>
                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                            </select>
                        </div>
                    </div>
                    

                    <div>
                        <h2>Hora</h2>

                        <div class="flex gap-2 w-full">
                            <select name="hour" id="hour" class="border border-black rounded-lg p-3" v-model="dateForm.hour">
                                <option value="" disabled selected hidden>Hora</option>
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>

                            <select name="minute" id="minute" class="border border-black rounded-lg p-3"  v-model="dateForm.minute">
                                <option value="" disabled selected hidden>Minut</option>
                                <option value="00">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>


                    <div class="flex justify-center mt-4 space-x-4">
                        <button class="btn" type="submit">Guardar</button>
                        <button class="btn" @click="cancelDate">Cancelar</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id="my_modal_3" class="modal cursor-auto" :open="showModalLocation" @click.self="closeModalLocation">
            <div class="modal-box w-1/2">
                <form 
                    @submit.prevent="closeModalLocation"
                    class="flex flex-col gap-4"
                >
                    <button 
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                        @click="cancelLocation"
                    >
                        ✕
                    </button>
                
                    <h3 class="flex justify-center font-bold text-lg mb-2">Selecciona l'ubicació de la barbacoa</h3>

                    <div class="search-input max-h-52 overflow-auto">
                        <input 
                            type="text" 
                            placeholder="Buscar ubicació..." 
                            class="input input-bordered w-full max-h-40 sticky top-0 z-10"
                            id="address"
                            title="address"
                            v-model="barbecueForm.address"
                            @input="actualitzarCerca"
                            @click="showSearch"
                            @self.click="showNoSearch"
                            autocomplete="off"
                        />
                        
                        <div class="options-location w-full">
                            <ul
                                ref="ul"
                            >
                                <li
                                    v-for="(location) in locations"
                                    :key="location.formatted"
                                    :lat="location.annotations.DMS.lat"
                                    :lng="location.annotations.DMS.lng"
                                    @click="selectAddress"
                                    class="result-location w-full overflow-auto"
                                >
                                    {{ location.formatted }}
                                </li>
                            </ul>
                        </div>
                        
                    </div>

                    <div class="flex justify-center space-x-4">
                        <button class="btn" type="submit">Guardar</button>
                        <button class="btn" @click="cancelDate">Cancelar</button>
                    </div>
                </form>
            </div>
        </dialog>
    </form>
</template>

<style scoped>
.dropdown {
    position: relative;
    width: 100%;
    background-color: white;
    border-radius: 20px;
    padding: 10px 20px;
}

.content {
    overflow: hidden;
    transition: max-height 0.9s ease-out;
    max-height: 0;
    width: 100%;
}

.dropdown.open .content {
    max-height: none;

}

::placeholder {
    color: #a8a8a8;
}

.boton-elegante {
    padding: 5px 20px;
    width: 250px;
    border: 2px solid #2c2c2c;
    background-color: #1a1a1a;
    color: #ffffff;
    font-size: 1.2rem;
    cursor: pointer;
    border-radius: 20px;
    transition: all 0.4s ease;
    outline: none;
    position: relative;
    overflow: hidden;
    font-weight: bold;
}

.boton-elegante::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle,
            rgba(255, 255, 255, 0.25) 0%,
            rgba(255, 255, 255, 0) 70%);
    transform: scale(0);
    transition: transform 1.5s ease;
}

.boton-elegante:hover::after {
    transform: scale(4);
}

.boton-elegante:hover {
    border-color: #666666;
    background: #292929;
}

.add-info {
    transition: transform 0.3s, filter 0.3s;
}

.add-info:hover {
    transform: scale(1.2);
    filter: brightness(0%);
}

.input-container {
    position: relative;

}

.input-container input {
    width: 100%;
    font-size: 26px;
    border: none;
    border-bottom: 2px solid transparent;

    outline: none;
}

.input-container input:focus {
    border-bottom-color: gray;
}

.imgprofile {
    object-fit: cover;
}


input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
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

.search-input {
    position: relative;
}

.search-input input {
    height: 100%;
    padding: 15px;
    border: 1px solid #adadad;
    border-radius: 10px;
}

.search-input input:focus-visible {
    outline: none;
}

.search-input input:focus {
    border-color: #000;
}

.search-input input:focus {
    opacity: 1;
    transition: 0.2s;
}

.options-location {
    background: #eee;
    border-radius: 10px;
}

.result-location {
    padding: 10px 20px;
    border-radius: 10px;
    margin-bottom: 5px;
    margin-top: 5px;
    background: #eee;
    display: grid;
    align-items: center;
    justify-content: space-between;
}

.result-location:hover {
    cursor: pointer;
    background: #dcdcdc;
}
</style>
