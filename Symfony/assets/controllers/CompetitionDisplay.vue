<template>
    <div>
        <div v-if="selected" class="d-flex justify-content-center align-items-center" style="height:100vh;">
            <h1>Bienvenue dans la competition {{ selected.name }}</h1>
        </div>
        <div v-else class="d-flex justify-content-center align-items-center" style="height:100vh;">
            <Loader />
        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, ref } from 'vue';
import Loader from './Loader.vue'
import { socket } from '../socket.js';
import axios from 'axios';

socket.on('connect',
    console.log(socket.connected)
)

socket.on('competition_selected', function(data) {
    console.log(data)
    selected.value = data
})

console.log(socket);
const selected = ref(false);


onBeforeMount(async () => {
    const { data } = await axios.get('/dashboard/competition_active');
    console.log(data)
    if(data && data.competition) {
        const competitionJson = JSON.parse(data.competition)
        selected.value = competitionJson;
    }

    //selected.value = competitionJson;

})

</script>
<style>
.timer_time {
    font-size: 2.5em;
}
</style>