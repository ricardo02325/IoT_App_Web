// --- Elementos del DOM ---
const liveTemp = document.getElementById("live-temp");
const liveHum = document.getElementById("live-hum");
const liveLux = document.getElementById("live-lux");
const liveFan = document.getElementById("live-fan");
const slider = document.getElementById("desired-temp-slider");
const desiredTempValue = document.getElementById("desired-temp-value");

// --- Configuración Particle ---
const DEVICE_ID = "25001d000847313037363132";
const USERNAME = "rgregorio0@ucol.mx";
const PASSWORD = "Pacofran25?";

let token = null;
let lastTemp = undefined;

// --- Inicializar Particle ---
var particle = new Particle();

// --- Login a Particle Cloud ---
particle.login({ username: USERNAME, password: PASSWORD }).then(
    function (data) {
        token = data.body.access_token;
        console.log("✅ Login correcto, token obtenido.");

        // Escuchar eventos del dispositivo
        particle.getEventStream({ deviceId: DEVICE_ID, auth: token })
            .then(function (stream) {
                stream.on("event", function (event) {

                    // --- TEMPERATURA ---
                    if (event.name === "Temp_C") {
                        const t = parseFloat(event.data);
                        lastTemp = t;
                        liveTemp.textContent = t.toFixed(1);
                        liveFan.textContent = t >= slider.value ? "Encendido" : "Apagado";

                        // Guardar temperatura en Laravel
                        enviarLectura(1, t); // id_sensor = 1
                    }

                    // --- HUMEDAD ---
                    if (event.name === "Humedad") {
                        const h = parseFloat(event.data);
                        liveHum.textContent = h.toFixed(1);

                        // Guardar humedad en Laravel
                        enviarLectura(2, h); // id_sensor = 2
                    }

                    // --- LUMINOSIDAD ---
                    if (event.name === "Luminosidad") {
                        const l = parseInt(event.data);
                        liveLux.textContent = l;

                        // Guardar luminosidad en Laravel
                        enviarLectura(3, l); // id_sensor = 3
                    }
                });
            })
            .catch(err => console.error("❌ Error getEventStream:", err));
    },
    function (err) {
        console.error("❌ No se pudo iniciar sesión en Particle:", err);
    }
);

// --- Slider para enviar límite de temperatura al dispositivo ---
slider.addEventListener("input", function () {
    desiredTempValue.textContent = this.value;

    if (token) {
        particle.callFunction({
            deviceId: DEVICE_ID,
            name: "Valor",
            argument: this.value.toString(),
            auth: token
        }).then(result => {
            console.log("✅ Nuevo límite enviado:", result.return_value);

            // Actualizar estado del ventilador
            if (lastTemp !== undefined) {
                liveFan.textContent = lastTemp >= slider.value ? "Encendido" : "Apagado";
            }
        }).catch(err => console.error("❌ Error al enviar límite:", err));
    }
});

// --- Función auxiliar para enviar lecturas al backend Laravel ---
function enviarLectura(id_sensor, valor) {
    fetch("/lecturas", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({ id_sensor, valor })
    })
    .then(res => res.json())
    .then(data => console.log(`📤 Lectura guardada (sensor ${id_sensor}):`, data))
    .catch(err => console.error("❌ Error al guardar lectura:", err));
}