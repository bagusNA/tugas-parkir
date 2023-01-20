const ticket = document.getElementById('ticket');
const webcam = document.getElementById('webcam');
const canvas = document.getElementById('canvas');
const photo = document.getElementById('photo');
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

webcam.addEventListener(
    "canplay",
    (ev) => {
      if (!streaming) {
        height = (webcam.videoHeight / webcam.videoWidth) * width;
  
        webcam.setAttribute("width", width);
        webcam.setAttribute("height", height);
        canvas.setAttribute("width", width);
        canvas.setAttribute("height", height);
        streaming = true;
      }
    },
    false
);

form.onsubmit((ev) => {
    takepicture();
    ev.preventDefault();
})

function takepicture() {
    const context = canvas.getContext("2d");
    if (width && height) {
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);

        const data = canvas.toDataURL("image/png");
        photo.setAttribute("src", data);
    } else {
        clearphoto();
    }
}

function clearphoto() {
    const context = canvas.getContext("2d");
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);

    const data = canvas.toDataURL("image/png");
    photo.setAttribute("src", data);
}
