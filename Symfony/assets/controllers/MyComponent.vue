<template>
    <div>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center" style="height: 500px;">
            <Loader />
        </div>
        <div v-else>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="d-flex align-items-center"><span>Compétition <strong>{{ competition.name }}</strong></span> <span v-if="competition.is_active" class="badge badge-success ml-2 text-lg">en cours</span></h1>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button v-if="competition.is_active" class="btn btn-danger" @click="stopCompetition">Arreter la compétition</button>
                    <button v-else class="btn btn-success" @click="startCompetition">Commencer la compétition</button>
                </div>
            </div>
            <div v-if="!wodInProgress" class="mt-4">
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <h3 class="alert alert-success">Choisissez un wod à lancer</h3>
                    </div>
                    <div v-for="wod in wods" class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><strong>{{ wod.name }}</strong></h5>
                                <p class="card-text">{{ wod.description }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <button class="btn btn-success"><i class="fas fa-play fa-solid fa-sm text-white"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="row d-flex justify-content-center">
                    <div class="col-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title d-flex align-items-center"><span class="mr-2">En cours :</span><span class="badge badge-success text-lg">{{ wodInProgress.name }}</span></h5>
                                <p class="card-text">
                                    <div class="alert alert-info text-lg text-center">
                                        Heat <strong>1 / {{ heats }}</strong>
                                    </div>
                                    <div class="text-danger text-center mb-2">
                                        Attention de bien être sûr de lancer le wod avant d'appuyer sur play ! Action non reversible
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center timer_time">
                                        <div class="p-3 bg-dark text-white rounded mr-2 shadow">{{minutes}}</div>
                                        <div class="">:</div>
                                        <div class="p-3 bg-dark text-white rounded ml-2 shadow">{{seconds}}</div>
                                    </div>
                                </p>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                <button class="btn btn-success mr-2 text-lg" @click="launchTimer()"><i class="fas fa-play fa-solid fa-sm text-white"></i></button>
                                <button class="btn btn-warning mr-2 text-lg" @click="pause = true"><i class="fas fa-pause fa-solid fa-sm text-white"></i></button>
                                <button :disabled="true" class="btn btn-info text-lg"><i class="fas fa-arrow-right fa-solid fa-sm text-white"></i></button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, ref } from 'vue';
import Loader from './Loader.vue'
import axios from 'axios';
import { socket } from '../socket.js';

socket.on('connect',
    console.log(socket.connected)
)

console.log(socket);
const props = defineProps({
    id: String
});

const minutes = ref(0);
const seconds = ref(0);

const wods = ref({});
const isLoading = ref(false);
const pause = ref(false);   

const heats = ref();

const competition = ref({});
const wodInProgress = ref({});


onBeforeMount(async () => {
    isLoading.value = true;
    const { data } = await axios.get(`/dashboard/competition/${props.id}`);
    console.log(data)
    const wodList = JSON.parse(data.wods);
    const competitionJson = JSON.parse(data.competition)
    const inProgress = JSON.parse(data.in_progress)

    competition.value = competitionJson;
    wods.value = wodList;
    wodInProgress.value = inProgress;
    console.log(wodInProgress.value)
    if(wodInProgress.value) {
        createHeats(wodInProgress.value);
        const duration_secondes = wodInProgress.value.duration * 60;

        const secondsCalculated = parseInt(duration_secondes);
        const minutesCalculated = parseInt(secondsCalculated / 60);

        console.log(secondsCalculated)
        const realSeconds = secondsCalculated % 60
        minutes.value = minutesCalculated.toString().length > 1 ? minutesCalculated : `0${minutesCalculated}`;
        seconds.value = (realSeconds).toString().length > 1 ? realSeconds : `0${realSeconds}`;

    }
    isLoading.value = false;
})


function createHeats(wodInProgress) {
    const part = wodInProgress.nb_of_participants;
    const lines = wodInProgress.nb_of_lines;
    const nbOfHeats = Math.ceil(part / lines);
    heats.value = nbOfHeats;
    console.log('Il y aura : ' + nbOfHeats + ' heats');
}

async function stopCompetition() {
    const { data } = await axios.get(`/dashboard/deactivate_competition/${competition.value.id}`);
    competition.value.is_active = false;

    console.log(data)
    socket.emit('competition_selected', null)

}

async function startCompetition() {
    const { data } = await axios.get(`/dashboard/activate_competition/${competition.value.id}`);
    competition.value.is_active = true;
    console.log(data)
    socket.emit('competition_selected', competition.value)

}


function launchTimer() {
    socket.emit('play', true)
    pause.value = false;
    //const duration_secondes = wodInProgress.value.duration * 60;
    let i = 0;
    setInterval(() => {
        const duration_secondes = parseInt(minutes.value) * 60 + parseInt(seconds.value);
        console.log(pause.value)
        if(!pause.value) {
            const rest_duration = duration_secondes - 1;
            console.log(rest_duration)
            const secondsCalculated = parseInt(rest_duration);
            const minutesCalculated = parseInt(secondsCalculated / 60)
            const realSeconds = secondsCalculated % 60

            minutes.value = minutesCalculated.toString().length > 1 ? minutesCalculated : `0${minutesCalculated}`;
            seconds.value = realSeconds.toString().length > 1 ? realSeconds : `0${realSeconds}`;

        }
    }, 1000)
}

function addMinutes(date, minutes) {
    return new Date(date.getTime() + minutes * 60000);
}


console.log(props.id)
</script>
<style>
.timer_time {
    font-size: 2.5em;
}
</style>