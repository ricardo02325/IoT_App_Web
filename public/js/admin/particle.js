// --- Elementos del DOM ---
const liveTemp = document.getElementById("live-temp");
const liveHum = document.getElementById("live-hum");
const liveLux = document.getElementById("live-lux");
const slider = document.getElementById("temp-slider");
const valueDisplay = document.getElementById("temp-value");

// --- Configuración Particle ---
const DEVICE_ID = "29002b000b47313037363132";
const USERNAME = "rgregorio0@ucol.mx";
const PASSWORD = "Pacofran25?";

let token = null;
let lastTemp = undefined;

// --- Inicializar Particle ---
const particle = new Particle();

// --- Login ---
particle.login({ username: USERNAME, password: PASSWORD }).then(
    async function (data) {
        token = data.body.access_token;
        console.log("✅ Login correcto, token obtenido.");

        // === Obtener valor inicial de temp_limit desde Particle ===
        try {
            const variable = await particle.getVariable({
                deviceId: DEVICE_ID,
                name: "temp_limit",
                auth: token
            });

            const limit = variable.body.result;
            slider.value = limit;
            valueDisplay.textContent = `${limit.toFixed(1)} °C`;
            console.log(`🌡️ Límite inicial obtenido desde Particle: ${limit} °C`);
        } catch (err) {
            console.warn("⚠️ No se pudo obtener temp_limit inicial, usando valor por defecto 30 °C");
            slider.value = 30;
            valueDisplay.textContent = "30.0 °C";
        }

        // === Escuchar eventos del dispositivo ===
        particle.getEventStream({ deviceId: DEVICE_ID, auth: token })
            .then(function (stream) {
                stream.on("event", function (event) {
                    if (event.name === "Temp_C") {
                        lastTemp = parseFloat(event.data);
                        liveTemp.textContent = lastTemp.toFixed(1);
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
slider.addEventListener("input", async function () {
    const newLimit = this.value;
    valueDisplay.textContent = `${newLimit} °C`;

    if (!token) return;

    try {
        const result = await particle.callFunction({
            deviceId: DEVICE_ID,
            name: "Valor",
            argument: newLimit.toString(),
            auth: token
        });

        console.log(`✅ Nuevo límite enviado al dispositivo: ${newLimit} °C`, result);
    } catch (err) {
        console.error("❌ Error al enviar límite:", err);
    }
});