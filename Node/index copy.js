const path = require('path')
const express = require('express')
var cors = require('cors')

const http = require('http');

const app = express(),
            DIST_DIR = __dirname


app.use(express.static(DIST_DIR))
app.use(cors);
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server);

io.on('connection', (socket) => {
    console.log('a user connected');
    res.json({
        "ok": true
    })
});

app.get('/', (req, res) => {
    console.log("Call !!")
    res.json({
        "ok": true
    })
})

const PORT = process.env.PORT || 1865;

app.listen(PORT, () => {
    console.log(`Socket.IO server running at http://localhost:${PORT}/`);
})