<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { nextTick, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Tancar sessió</h2>

            <p class="mt-1 text-sm text-gray-600">
                Tancar la sessió de la teva compte actual, pots tornar a iniciar sessió en qualsevol moment.
            </p>
        </header>

        <Link :href="route('logout')" method="post" as="button">
        <button
            class="btn hover:bg-[#c84c00] hover:text-white bg-[#FF6100] text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF6100] focus:ring-offset-white active:bg-[#FF6100] active:text-white px-4 py-2 rounded-md transition ease-in-out duration-150">
            Tancar sessió
        </button>
        </Link>
    </section>
</template>