// --- Elementos del DOM ---
const liveTemp = document.getElementById("live-temp");
const liveHum = document.getElementById("live-hum");

// --- Rutas Laravel ---
const LECTURAS_ROUTE = "/lecturas"; // Endpoint para guardar lecturas
const DISPOSITIVOS_ROUTE = "/dispositivos"; // Endpoint para obtener todos los dispositivos

let token = null;

// --- Inicializar Particle ---
const particle = new Particle();

// --- Login Particle ---
const USERNAME = "rgregorio0@ucol.mx";
const PASSWORD = "Pacofran25?";

particle.login({ username: USERNAME, password: PASSWORD }).then(async data => {
    token = data.body.access_token;
    console.log("✅ Login correcto, token obtenido.");

    // --- Obtener dispositivos asignados a cada salón ---
    const dispositivos = await fetch(DISPOSITIVOS_ROUTE)
        .then(res => res.json())
        .catch(err => { console.error("❌ Error al obtener dispositivos:", err); return []; });

    // --- Conectar cada dispositivo ---
    dispositivos.forEach(async device => {
        try {
            const stream = await particle.getEventStream({ deviceId: device.device_id, auth: token });
            console.log(`📡 Escuchando dispositivo: ${device.nombre} (${device.device_id}) en salón ${device.salon.nombre}`);

            stream.on("event", async event => {
                let sensorId = undefined;
                let valor = parseFloat(event.data);

                if (event.name === "Temp_C") {
                    liveTemp.textContent = valor.toFixed(1);
                    sensorId = 1; // ID sensor temperatura en DB
                }

                if (event.name === "Humedad") {
                    liveHum.textContent = valor.toFixed(1);
                    sensorId = 2; // ID sensor humedad en DB
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
                            valor: valor,
                            id_device: device.id // referencia al dispositivo que generó la lectura
                        })
                    })
                    .then(res => res.json())
                    .then(data => console.log(`📤 Lectura guardada (sensor ${sensorId}):`, data))
                    .catch(err => console.error("❌ Error al guardar lectura:", err));
                }
            });

        } catch (err) {
            console.error(`❌ Error al conectar dispositivo ${device.device_id}:`, err);
        }
    });

}, err => {
    console.error("❌ No se pudo iniciar sesión en Particle:", err);
});