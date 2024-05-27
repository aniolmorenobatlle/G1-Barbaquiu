<script setup>
    import { onMounted, ref, defineProps } from 'vue';
    import SearchOnSteroids from '@/Components/Steroids/SearchOnSteroids.vue';
    import ScaleOnSteroids from '@/Components/Steroids/ScaleOnSteroids.vue';
    import SelectOnSteroids from '@/Components/Steroids/SelectOnSteroids.vue';
    import FilterOnSteroids from '@/Components/Steroids/FilterOnSteroids.vue';
    import MapOnSteroids from '@/Components/Steroids/MapOnSteroids.vue';
    import { useBarbecueStore } from '@/stores/barbecue';

    const barbecueStore = useBarbecueStore();

    const props = defineProps({
        alwaysVisible: {
            type: Boolean,
            required: false,
            default: false
        }
    })

    const scales = [
        { value: 10, label: '10 km' },
        { value: 50, label: '50 km' },
        { value: 100, label: '100 km' },
        { value: undefined, label: 'Sense límit' }
    ]

    const timeOptions = {
        forever: 'Des de sempre',
        week: 'Fa 1 setmana',
        month: 'Fa 1 mes',
        semester: 'Fa 6 mesos',
    }

    const orderOptions = {
        desc: 'Més recents',
        asc: 'Més antic'
    }

    const filters = {
        'vegetarian': 'Vegetarià',
        'vegan': 'Vegà',
        'gluten-free': 'Sense gluten',
        'lactose-free': 'Sense lactosa'
    }

    const scaleValue = ref(3);
    const searchValue = ref('');
    const timeOption = ref('forever');
    const orderOption = ref('desc');
    const selectedFilters = ref([]);

    const mapCenter = ref([42.2736, 2.9646]);


    function updateBarbecues() {
        console.log('Updating barbecues');

        const filters = {
            search: searchValue.value,
            distance: scales[scaleValue.value].value,
            latitude: mapCenter.value[0],
            longitude: mapCenter.value[1],
            time: timeOption.value,
            order: orderOption.value,
            filters: selectedFilters.value
        }

        barbecueStore.fetchBarbecues(filters);
        document.querySelector(".main-layout-content-wrapper").scrollTop = 0;
    }

    onMounted(() => {
        updateBarbecues();
    })

</script>

<template>
    <div class="index-right-aside-container" :class="{ 'always-visible': props.alwaysVisible }">
        <div class="filter">
            <h3>Cercar barbacoes</h3>
            <SearchOnSteroids v-model="searchValue" @input="updateBarbecues" />
        </div>
        <div class="filter">
            <div class="filters">
                <h3>Distància</h3>
                <MapOnSteroids
                    :radius="scales[scaleValue].value"
                    :offsetX="-550"
                    :offsetY="20"
                    v-model="mapCenter"
                    :allowChangeLocation="true"
                    @input="updateBarbecues"
                    :markers="barbecueStore.barbecues"
                />
            </div>
            <ScaleOnSteroids :scales="scales" v-model="scaleValue" @input="updateBarbecues" />
        </div>
        <div class="filter">
            <h3>Data de publicació</h3>
            <SelectOnSteroids
                v-model="timeOption"
                :options="timeOptions"
                @input="updateBarbecues"
            />
        </div>
        <div class="filter">
            <h3>Ordenar</h3>
            <SelectOnSteroids
                v-model="orderOption"
                :options="orderOptions"
                @input="updateBarbecues"
            />
        </div>
        <div class="filter">
            <div class="filters">
                <h3>Filtres</h3>
                <span class="reset-filters" @click="selectedFilters = []">Elimina tots</span>
            </div>
            <FilterOnSteroids
                v-model="selectedFilters"
                :filters="filters"
                @input="updateBarbecues"
            />
        </div>
    </div>
</template>

<style scoped>
    .index-right-aside-container {
        background: #fff;
        width: 400px;
        border-radius: 20px;
        padding: 20px 30px;
        display: grid;
        gap: 20px;
        position: fixed;
    }

    h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .filters {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filters span {       
        color: #cc4e00;
        filter: brightness(1.23);
        cursor: pointer;
        font-size: 0.9rem;
    }

    .reset-filters:hover {
        text-decoration: underline;
    }

    @media (max-width: 1200px) {
        .index-right-aside-container:not(.always-visible) {
            /* width: 100%;
            border-radius: 0;
            height: 100%;
            grid-template-columns: 1fr 1fr 1fr 1fr; */
            visibility: hidden;
        }

        h3 {
            display: none;
        }
    }
</style>