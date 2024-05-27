<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';


defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    surnames: user.surnames,
    vegetarian: user.vegetarian,
    lactose: user.lactose,
    gluten: user.gluten,
    spicy: user.spicy,
    halal: user.halal,
});

const updateCheckbox = (event) => {
    form[event.target.name] = event.target.checked;
    console.log(form);
};



</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informació del perfil</h2>
        </header>

        <form @submit.prevent="form.patch(route('profile.update', user.id))" class="my-6 space-y-6">
            <div class="flex flex-row justify-between gap-4">
                <div class="w-1/2">
                    <InputLabel for="name" value="Nom" />
                    <TextInput id="name" type="text"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        v-model="form.name" required autofocus autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div class="w-1/2">
                    <InputLabel for="surnames" value="Cognoms" />
                    <TextInput id="surnames" type="text"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        v-model="form.surnames" required autofocus autocomplete="surnames" />
                    <InputError class="mt-2" :message="form.errors.surnames" />
                </div>
            </div>

            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    v-model="form.email" required autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="intolerances" value="Intoleràncies" />
                <div class="flex flex-row gap-4 justify-between">
                    <div class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md">
                        <input type="checkbox" id="vegetarian" name="vegetarian" value="vegetarian" class="checkbox mr-1 border-orange-400 checked:border-white [--chkbg:theme(colors.orange.400)] [--chkfg:white]"
                            :checked="form.vegetarian" @change="updateCheckbox">
                        <img src="/assets/img/intolerance/vegetarian.png" class="h-7" alt="">
                        <label for="vegetarian" class="font-semibold text-green-700 mr-1 cursor-pointer">
                            Vegetarià</label>
                    </div>
                    <div class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md">
                        <input type="checkbox" id="lactose" name="lactose" value="lactose" class="checkbox mr-1 border-orange-400 checked:border-white [--chkbg:theme(colors.orange.400)] [--chkfg:white]"
                            :checked="form.lactose" @change="updateCheckbox">
                        <img src="/assets/img/intolerance/lactose.png" class="h-7" alt="">

                        <label for="lactose" class="font-semibold text-black mr-1 cursor-pointer">Lactosa</label>
                    </div>
                    <div class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md">
                        <input type="checkbox" id="gluten" name="gluten" value="gluten" class="checkbox mr-1 border-orange-400 checked:border-white [--chkbg:theme(colors.orange.400)] [--chkfg:white]"
                            :checked="form.gluten" @change="updateCheckbox">
                        <img src="/assets/img/intolerance/gluten.png" class="h-7" alt="">

                        <label for="gluten" class="font-semibold text-yellow-600 mr-1 cursor-pointer">Gluten</label>
                    </div>
                    <div class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md">
                        <input type="checkbox" id="spicy" name="spicy" value="spicy" class="checkbox mr-1 border-orange-400 checked:border-white [--chkbg:theme(colors.orange.400)] [--chkfg:white]"
                            :checked="form.spicy" @change="updateCheckbox">
                        <img src="/assets/img/intolerance/spicy.png" class="h-7" alt="">

                        <label for="spicy" class="font-semibold text-red-700 mr-1 cursor-pointer">Picant</label>
                    </div>
                    <div class="border border-inherit w-auto flex justify-center items-center ml-2 p-1 rounded-md">
                        <input type="checkbox" id="halal" name="halal" value="halal" class="checkbox mr-1 border-orange-400 checked:border-white [--chkbg:theme(colors.orange.400)] [--chkfg:white]"
                            :checked="form.halal" @change="updateCheckbox">
                        <img src="/assets/img/intolerance/halal.png" class="h-7" alt="">

                        <label for="halal" class="font-semibold text-black mr-1 cursor-pointer">Halal</label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button
                    class="btn hover:bg-[#c84c00]  hover:text-white bg-[#FF6100] text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6100] focus:ring-offset-white active:bg-[#FF6100] active:text-white px-4 py-2 rounded-md transition ease-in-out duration-150"
                    :disabled="form.processing">Guardar</button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Guardat.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
