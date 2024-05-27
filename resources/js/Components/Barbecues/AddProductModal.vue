<script setup>
import { ref } from 'vue';
import { useBarbecueStore } from "@/stores/barbecue";
import { useAuthStore } from "@/stores/auth";
import { Link, useForm, usePage } from '@inertiajs/vue3';

const authStore = useAuthStore();
const barbecueStore = useBarbecueStore();
console.log('Basket', barbecueStore.basket.basket_product);
const showModal = ref(false);
const showNewProduct = ref(false);
const showOldProducts = ref(false);

const closeModal = () => {
    showModal.value = false;
    form.product_name = '';
    form.product_price = '';
};

const showNewProductForm = () => {
    showModal.value = true;
    showNewProduct.value = true;
    showOldProducts.value = false;
};

const showOldProductList = () => {
    showModal.value = true;
    showNewProduct.value = false;
    showOldProducts.value = true;
};

const form = useForm({
    product_name: '',
    product_price: '',
});

const addProduct = () => {
    axios.post(route('addproduct', { id: barbecueStore.barbecue.id }), {
        product_name: form.product_name,
        product_price: form.product_price,
    })
        .then(response => {
            if (response.data) {
                console.log(response.data);
                barbecueStore.setBasketProduct(response.data.id, response.data.quantity, authStore.user, form.product_name, form.product_price);
                closeModal();
                console.log(response.data);
            } else {
                throw new Error('Invalid product data from server');
            }
        })
        .catch(error => {
            console.error('Error adding product:', error);
        });
    console.log(form, barbecueStore.basket.basket_product);
};

const addOldProduct = (product) => {
    const user = authStore.user;
    axios.post(route('addproduct', { id: barbecueStore.barbecue.id }), {
        product_name: product.name,
        product_price: product.price,
    })
        .then(response => {
            console.log(response.data, user, product.name, product.price);
            barbecueStore.setBasketProduct(product.id, response.data.quantity, user, product.name, product.price);
        })
        .catch(error => {
            console.error('Error adding product:', error);
        });
};

const showMainContent = () => {
    showNewProduct.value = false;
    showOldProducts.value = false;
    form.product_name = '';
    form.product_price = '';
};
</script>

<template>
    <div class="dropdown dropdown-end" @click.stop>
        <div tabindex="0" role="button" class="p-2 hover:bg-orange-500 rounded-full bg-transparent">
            <img src="/assets/svg/carrito.svg" alt="Afegir producte" class="img-fluid" style="width: 20px;">
        </div>
        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
            <li class="focus:bg-orange-500">
                <a class="focus:bg-orange-500" @click="showNewProductForm">
                    Producte nou
                </a>
            </li>
            <li class="focus:bg-orange-500">
                <a class="focus:bg-orange-500" @click="showOldProductList">
                    Producte afegit anteriorment
                </a>
            </li>
        </ul>
    </div>
    <dialog id="my_modal_3" class="modal" :open="showModal" @click.stop>
        <div class="modal-box cursor-default">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" @click="closeModal">
                    ✕
                </button>
            </form>

            <div class="newproduct" v-if="showNewProduct">
                <h3 class="font-bold text-lg cursor-pointer flex flex-row gap-2">
                    Afegir producte nou
                </h3>
                <form class="w-full" @submit.prevent="addProduct">
                    <div class="mb-4 mt-4">
                        <label for="product_name" class="block text
                        text-sm font-medium text-gray-700">Nom del producte</label>
                        <input type="text" name="product_name" id="product_name" autocomplete="product_name"
                            v-model="form.product_name"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="product_price" class="block text text-sm font-medium text-gray-700">Preu del
                            producte en €</label>
                        <input type="number" name="product_price" id="product_price" autocomplete="product_price"
                            v-model="form.product_price" step="0.01"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="w-full items-center justify-center flex">
                        <button type="submit"
                            class="btn items-center justify-center hover:bg-orange-500 bg-white hover:text-white border-[#FF6100] text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6100] focus:ring-offset-white active:bg-[#FF6100] active:text-white px-4 py-1 rounded-md transition ease-in-out duration-150">
                            Afegir
                        </button>
                    </div>
                </form>
            </div>
            <div class="oldproducts" v-if="showOldProducts">
                <h3 class="font-bold text-lg cursor-pointer flex flex-row gap-2">
                    Afegir un producte de la meva llista
                </h3>
                <div class="flex justify-center flex-col items-center mt-4 space-y-2">
                    <ul v-for="product in authStore.user.products" class="w-full ul p-1">
                        <li class="">
                            <form class="flex flex-row gap-4 justify-between" @submit.prevent="addOldProduct(product)">
                                <p class="w-1/4 bg-white">
                                    {{ product.name }}
                                </p>
                                <p class=" bg-white flex items-center justify-center text-center">
                                    {{ product.price }} €
                                </p>
                                <button class="btn btn-sm bg-green-500 text-white hover:bg-green-300 w-1/5"
                                    type="submit">
                                    <img src="/assets/svg/carrito.svg" alt="Afegir producte" class="img-fluid"
                                        style="width: 20px;">
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </dialog>
</template>

<style scoped>
.modal {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>