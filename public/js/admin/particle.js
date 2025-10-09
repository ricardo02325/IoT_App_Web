// --- Elementos del DOM ---
const liveTemp = document.getElementById("live-temp");
const liveHum = document.getElementById("live-hum");
const liveLux = document.getElementById("live-lux");
const liveFan = document.getElementById("live-fan");
const slider = document.getElementById("desired-temp-slider");
const desiredTempValue = document.getElementById("desired-temp-value");

// --- Configuración Particle ---
const DEVICE_ID = "29002b000b47313037363132";
const USERNAME = "rgregorio0@ucol.mx";
const PASSWORD = "Pacofran25?";

let token = null;
let lastTemp = undefined;

// --- Inicializar Particle ---
var particle = new Particle();

// --- Login ---
particle.login({ username: USERNAME, password: PASSWORD }).then(
    function (data) {
        token = data.body.access_token;
        console.log("✅ Login correcto, token obtenido.");

        // Escuchar eventos del dispositivo
        particle.getEventStream({ deviceId: DEVICE_ID, auth: token })
            .then(function (stream) {
                stream.on("event", function (event) {
                    if (event.name === "Temp_C") {
                        lastTemp = parseFloat(event.data);
                        liveTemp.textContent = lastTemp.toFixed(1);
                        liveFan.textContent = lastTemp >= slider.value ? "Encendido" : "Apagado";
                    }
                    if (event.name === "Humedad") {
                        liveHum.textContent = parseFloat(event.data).toFixed(1);
                    }
                    if (event.name === "Luminosidad") {
                        liveLux.textContent = parseInt(event.data);
                    }
                });
            })
            .catch(err => console.error("❌ Error getEventStream:", err));
    },
    function (err) {
        console.error("❌ No se pudo iniciar sesión en Particle:", err);
    }
);

// --- Slider para enviar límite de temperatura ---
slider.addEventListener("input", function () {
    desiredTempValue.textContent = this.value;

    if (token) {
        particle.callFunction({
            deviceId: DEVICE_ID,
            name: "Valor",
            argument: this.value.toString(),
            auth: token
        }).then(result => {
            console.log("✅ Nuevo límite enviado al dispositivo:", result.return_value);
            // Actualizar el estado del ventilador según el nuevo límite
            if (lastTemp !== undefined) {
                liveFan.textContent = lastTemp >= slider.value ? "Encendido" : "Apagado";
            }
        }).catch(err => console.error("❌ Error al enviar límite:", err));
    }
});