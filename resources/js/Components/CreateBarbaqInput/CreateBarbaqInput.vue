<script setup>
import { ref, createApp } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import { useAuthStore } from "@/stores/auth";
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const authStore = useAuthStore();

const app = createApp()
app.component('QuillEditor', QuillEditor)

const isOpen = ref(false);
const quillContent = ref(null);

const barbecueForm = useForm({
    title: '',
    content: '',
    latitude: '123',
    longitude: '123',
    address: '123',
});

const toggleDropdown = () => {
    if (!isOpen.value) {
        isOpen.value = true;
    }
};

const submitBarbecueForm = () => {
    // barbecueForm.content = quillContent.value.getHTML();

    // axios.post("/barbecues", barbecueForm)
    //     .then((response) => {
    //         const barbecueId = response.data.id;
    //         const images = imagesForm.image;

    //         axios.post(`/barbecues/${barbecueId}/images`, { images })
    //             .then(() => {
    //                 console.log('Imatges pujades correctament');
    //                 console.log('imatges: ' + images);
    //                 imagesForm.image = [];
    //             }).catch((e) => {
    //                 console.log('Error al pujar les imatges: ', e);
    //             })
    //         ;

    //         barbecueForm.reset();
    //         quillContent.value.setHTML('');
    //         isOpen.value = false;
    //     })

    const content = quillContent.value.getHTML();
    const images = imagesForm.image;
    const barbecueId = response.data.id;

    const formData = new FormData();
    formData.append('content', content);

    // Agregar las imágenes al objeto FormData
    images.forEach((image, index) => {
        formData.append(`image_${index}`, image);
    });

    axios.post(`/barbecues/${barbecueId}/images`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
    .then((response) => {
        console.log('Imágenes subidas correctamente');
        console.log('Respuesta del servidor:', response);
    })
    .catch((error) => {
        console.error('Error al subir imágenes:', error);
    });
};

const showModal = ref(false);

function openModal() {
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
}

const imagesForm = useForm({
    image: [],
});

function updateImageNames(event) {
    const files = event.target.files;

    if (files.length > 0 && files.length <= 4) {
        this.imagesForm.image = Array.from(files).map(file => file.name);
        
        const names = this.imagesForm.image.join(',&nbsp;');
        const words = names.split(/\s+/);
        const paragraphs = words.map(word => `<p>${word}</p>`).join('');

        document.querySelector('#imagesNames').innerHTML = paragraphs;
    } else if (files.length > 4) {
        alert('Només pots pujar fins a 4 imatges.');
    }
};


function submitImages() {
    console.log("Noms dels arxius seleccionats:");

    console.log(imagesForm.image);

    closeModal();
}
</script>

<template>
    <form 
        @submit.prevent="submitBarbecueForm" 
        class="dropdown "
        :class="{ open: isOpen }"
        @click="toggleDropdown"
        v-if="authStore.user"
    >
        <div class="flex">
            <img :src="authStore.user.image" alt="Foto de perfil" class="mr-2 h-16 w-16 rounded-full imgprofile">
            <div class="w-full mt-auto mb-auto ">
                <div class="input-container">
                    <input type="text" placeholder="Crear nova barbacoa..." v-model="barbecueForm.title">
                </div>
            </div>
            <img src="/assets/svg/edit.svg" alt="Imatge de editar" class="">
        </div>

        <div class="content" :style="{ height: isOpen ? 'auto' : '0', cursor: isOpen ? 'default' : 'pointer' }">
            <section class="mt-3 ">

                <QuillEditor theme="snow" class="rounded-b-lg min-h-24" ref="quillContent"></QuillEditor>

            </section>

            <div class="flex items-center">
                <img 
                    src="/assets/svg/addphoto.svg" 
                    alt="Imatge de editar" 
                    class="add-info cursor-pointer" 
                    @click="openModal"
                >

                <img 
                    src="/assets/svg/addlocation.svg" 
                    alt="Imatge de editar" 
                    class="add-info cursor-pointer"
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

        <dialog id="my_modal_3" class="modal cursor-auto" :open="showModal" @click.self="closeModal">
            <div class="modal-box w-auto min-w-[30%] max-w-5xl">
                <button 
                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                    @click="closeModal"
                >
                    ✕
                </button>
            
                <form 
                    @submit.prevent="submitImages"
                    class="flex flex-col items-center gap-4"
                >
                                  
                    <h3 class="font-bold text-lg mb-2">Selecciona la imatge/s </h3>
                    <label class="custum-file-upload" for="file">
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
                            id="file" 
                            name="images" 
                            multiple 
                            accept="image/*" 
                            @input="updateImageNames($event)"
                        >
                    </label>

                    <div class="flex justify-center mt-4 space-x-4">
                        <button class="btn" type="submit">Guardar</button>
                        <button class="btn " @click="closeModal">Cancelar</button>
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
    cursor: pointer;
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
</style>
