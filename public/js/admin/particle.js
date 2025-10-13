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

// Rutas Laravel
const LECTURAS_ROUTE = "/lecturas"; // Debe existir en web.php

let token = null;
let lastTemp = undefined;
let sliderInitialized = false;

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

            const limit = parseFloat(variable.body.result);
            if (!sliderInitialized) {
                slider.value = limit;
                valueDisplay.textContent = `${limit.toFixed(1)} °C`;
            }
            console.log(`🌡️ Límite inicial obtenido desde Particle: ${limit} °C`);
        } catch (err) {
            console.warn("⚠️ No se pudo obtener temp_limit, usando 30 °C");
            if (!sliderInitialized) {
                slider.value = 30;
                valueDisplay.textContent = "30.0 °C";
            }
        }

        // === Escuchar eventos del dispositivo ===
        particle.getEventStream({ deviceId: DEVICE_ID, auth: token })
            .then(function (stream) {
                stream.on("event", function (event) {
                    let sensorId;

                    if (event.name === "Temp_C") {
                        lastTemp = parseFloat(event.data);
                        liveTemp.textContent = lastTemp.toFixed(1);
                        sensorId = 1; // ID de sensor temperatura
                    }

                    if (event.name === "Humedad") {
                        liveHum.textContent = parseFloat(event.data).toFixed(1);
                        sensorId = 2; // ID de sensor humedad
                    }

                    if (event.name === "Luminosidad") {
                        liveLux.textContent = parseInt(event.data);
                        sensorId = 3; // ID de sensor luminosidad
                    }

                    // Guardar lectura en Laravel
                    if (sensorId !== undefined) {
                        fetch(LECTURAS_ROUTE, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                id_sensor: sensorId,
                                valor: parseFloat(event.data)
                            })
                        })
                        .then(res => res.json())
                        .then(data => console.log(`📤 Lectura guardada (sensor ${sensorId}):`, data))
                        .catch(err => console.error("❌ Error al guardar lectura:", err));
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
    sliderInitialized = true; // marcar que usuario movió slider
    const newLimit = parseFloat(this.value);
    valueDisplay.textContent = `${newLimit.toFixed(1)} °C`;

    if (!token) return;

    try {
        // Enviar nuevo límite a Particle
        await particle.callFunction({
            deviceId: DEVICE_ID,
            name: "Valor",
            argument: newLimit.toString(),
            auth: token
        });
        console.log(`✅ Nuevo límite enviado al dispositivo: ${newLimit} °C`);
    } catch (err) {
        console.error("❌ Error al enviar límite:", err);
    }
});