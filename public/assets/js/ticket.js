const ticket = document.getElementById('ticket');
const webcam = document.getElementById('webcam');
const form = document.getElementById('form');

if (ticket) {
    setTimeout(() => {
        ticket.remove();
    }, 5000);
};

const constrains = {
    video: true,
};

navigator.mediaDevices.getUserMedia(constrains)
.then((mediaStream) => {
    webcam.srcObject = mediaStream;
    webcam.onloadedmetadata = () => {
      webcam.play();
    };
})
.catch(err => console.error(err));
