const express = require('express');
const cors = require('cors');
const app = express();
// /db/session is express-session related code
const { createServer } = require('http');
const { Server } = require('socket.io');
const PORT = process.env.PORT || 3000;

const httpServer = createServer(app);
DEV_ORIGIN = 'http://localhost:8003/'
app.use(cors({
    // DEV_ORIGIN is http://localhost:3000
    origin: '*',
    //credentials: true,
}));

const io = new Server(httpServer, {
    cors: {
        origin: '*',
        methods: ["GET", "POST"],
        //credentials: true
    }
});



httpServer.listen(PORT, () => console.log(`Server running on port ${PORT}`));

// this never seems to do anything
io.on('connection', function(socket) {
    console.log('Connected user !!!!!!')
    io.emit("hi", "everyone");
    socket.on('competition_selected', function(data) {
        console.log('competition_selected')
        console.log(data)
        io.emit("competition_selected", data);

    })

    socket.on('disconnect', function() {
        console.log('disconnected')
    })

});

