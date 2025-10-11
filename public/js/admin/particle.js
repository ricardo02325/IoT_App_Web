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

// --- Función para guardar lecturas en Laravel ---
function guardarLectura(id_sensor, valor) {
    fetch("/lecturas", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            id_sensor: id_sensor,
            valor: valor,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.message) {
                console.log("✅ Lectura guardada:", data.data);
            } else if (data.errors) {
                console.warn("⚠️ Error validación:", data.errors);
            }
        })
        .catch((err) => console.error("❌ Error al guardar lectura:", err));
}

// --- Login ---
particle.login({ username: USERNAME, password: PASSWORD }).then(
    function (data) {
        token = data.body.access_token;
        console.log("✅ Login correcto, token obtenido.");

        // Escuchar eventos del dispositivo
        particle
            .getEventStream({ deviceId: DEVICE_ID, auth: token })
            .then(function (stream) {
                stream.on("event", function (event) {
                    if (event.name === "Temp_C") {
                        lastTemp = parseFloat(event.data);
                        liveTemp.textContent = lastTemp.toFixed(1);
                        liveFan.textContent =
                            lastTemp >= slider.value ? "Encendido" : "Apagado";

                        // 🔥 Guardar lectura de temperatura
                        guardarLectura(1, lastTemp);
                    }

                    if (event.name === "Humedad") {
                        const humedad = parseFloat(event.data);
                        liveHum.textContent = humedad.toFixed(1);

                        // 💧 Guardar lectura de humedad
                        guardarLectura(2, humedad);
                    }

                    if (event.name === "Luminosidad") {
                        const lux = parseInt(event.data);
                        liveLux.textContent = lux;

                        // 💡 Guardar lectura de luminosidad
                        guardarLectura(3, lux);
                    }
                });
            })
            .catch((err) => console.error("❌ Error getEventStream:", err));
    },
    function (err) {
        console.error("❌ No se pudo iniciar sesión en Particle:", err);
    }
);

// --- Slider para enviar límite de temperatura ---
slider.addEventListener("input", function () {
    desiredTempValue.textContent = this.value;

    if (token) {
        particle
            .callFunction({
                deviceId: DEVICE_ID,
                name: "Valor",
                argument: this.value.toString(),
                auth: token,
            })
            .then((result) => {
                console.log("✅ Nuevo límite enviado al dispositivo:", result.return_value);
                if (lastTemp !== undefined) {
                    liveFan.textContent =
                        lastTemp >= slider.value ? "Encendido" : "Apagado";
                }
            })
            .catch((err) => console.error("❌ Error al enviar límite:", err));
    }
});