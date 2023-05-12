import io from 'socket.io-client';
import {serverAddress} from './settings';

// API_ORIGIN is http://localhost:5000
export let socket = io(serverAddress, {
    autoConnect: true,
});
