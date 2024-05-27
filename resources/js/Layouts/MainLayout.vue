<script setup>
import { defineProps } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import AuthAccount from '@/Components/Auth/AuthAccount.vue';
import MainLeftAside from '@/Components/Asides/MainLeftAside.vue';

const props = defineProps({
    title: {
        type: String,
        required: false
    }
});

</script>

<template>
    <div class="main-layout-container">
        <div class="main-layout-header">
            <div class="main-layout-application-logo">
                <ApplicationLogo />
            </div>
            <div class="main-layout-title">{{ title ?? 'Barbeq' }}</div>
            <div class="main-layout-profile">
                <AuthAccount />
            </div>
        </div>
        <div class="main-layout-content-wrapper">
            <div class="main-layout-left-aside">
                <MainLeftAside />
            </div>
            <div class="main-layout-content">
                <slot name="main-content"></slot>
            </div>
            <div class="main-layout-right-aside">
                <slot name="right-aside"></slot>
            </div>
        </div>
    </div>
</template>

<style scoped>

.main-layout-container {
    height: 100vh;
    width: 100%;
}

.main-layout-content-wrapper {
    height: calc(100% - 90px);
    overflow-y: scroll;
    overflow-x: hidden;
    /* no scrollbar space */
    scrollbar-gutter: stable;
    position: absolute;
    top: 90px;
    width: 100%;
    display: grid;
    grid-template-columns: auto 720px auto;
    gap: 20px;
    padding-top: 20px;
    padding-inline: 20px;
    justify-content: center;
    position: absolute;
}

.main-layout-left-aside {
    width: 245px;
}

.main-layout-right-aside {
    width: 400px;
}

@media (max-width: 1450px) {
    .main-layout-left-aside {
        position: fixed;
        bottom: 0;
        z-index: 100;
    }
    .main-layout-content-wrapper {
        grid-template-columns: 720px auto;
        height: calc(100% - 175px);
    }

    .main-layout-container {
        height: calc(100vh - 120px);
    }
}

@media (max-width: 1200px) {
    .main-layout-content-wrapper {
        grid-template-columns: 720px;
    }

    .main-layout-right-aside {
    }

    .main-layout-header {
        grid-template-columns: 270px auto !important;
        justify-content: space-between;

    }

    .main-layout-title {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .main-layout-content-wrapper {
        grid-template-columns: 100%;
    }

    .main-layout-header {
        grid-template-columns: 150px auto !important;    
        justify-content: space-between;
    }
}

/* Header */
.main-layout-header {
    padding-inline: 12%;
    grid-template-columns: 270px auto 350px;
    display: grid;
    grid-template-rows: 100%;
    height: 90px;
    background: #fff;
    position: fixed;
    width: 100%;
    z-index: 100;
}

.main-layout-application-logo {
    display: flex;
    align-items: center;
}

.main-layout-container {
    height: 100vh;
    background: #E9E9E9;
}

.main-layout-title {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
}
.main-layout-profile {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>

<style>
    body {
        overflow: hidden;
    }
</style>