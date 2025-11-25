document.addEventListener("DOMContentLoaded", function () {
    const slider = document.getElementById("desired-temp-slider");

    const container = document.getElementById("mapa-container");

    // --- CORRECCIÓN: Validar que el contenedor exista antes de leer sus datos ---
    if (!container) return; 
    // --------------------------------------------------------------------------

    const salones = JSON.parse(container.dataset.salones || "[]");
    const sensores = JSON.parse(container.dataset.sensores || "[]");
    const dispositivos = JSON.parse(container.dataset.dispositivos || "[]");

    // --- Mapeo sensores por salón y tipo ---
    const sensoresPorSalon = {};
    sensores.forEach((s) => {
        if (!sensoresPorSalon[s.id_salon]) sensoresPorSalon[s.id_salon] = {};
        sensoresPorSalon[s.id_salon][s.tipo] = s.id_sensor;

        // Mostrar última lectura en el DOM si existe
        const spanId = s.tipo === "temperatura" ? `temp-${s.id_salon}` : `hum-${s.id_salon}`;
        const span = document.getElementById(spanId);
        if (span && s.ultima_lectura !== null) {
            span.textContent = s.ultima_lectura;
        }
    });

    // --- Salones simulados ---
    const salonesSimulados = salones.filter(s => !["5D", "LIC"].includes(s.ubicacion));

    function generarLectura(min, max) {
        return (Math.random() * (max - min) + min).toFixed(1);
    }

    function actualizarSpan(id_salon, tipo, valor) {
        const spanId = tipo === "temperatura" ? `temp-${id_salon}` : `hum-${id_salon}`;
        const span = document.getElementById(spanId);
        if (span) span.textContent = valor;
    }

    function enviarLectura(id_salon, tipo, valor) {
        const id_sensor = sensoresPorSalon[id_salon]?.[tipo];
        if (!id_sensor) return;

        fetch("/lecturas", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify({ id_sensor, valor: parseFloat(valor) }),
        }).catch(err => console.error("❌ Error al guardar lectura:", err));
    }

    // --- Simulación de salones ---
    setInterval(() => {
        salonesSimulados.forEach((salon) => {
            const temp = generarLectura(20, 28);
            const hum = generarLectura(30, 60);

            actualizarSpan(salon.id_salon, "temperatura", temp);
            actualizarSpan(salon.id_salon, "humedad", hum);

            enviarLectura(salon.id_salon, "temperatura", temp);
            enviarLectura(salon.id_salon, "humedad", hum);
        });
    }, 5000);

    // --- Particle Cloud (solo 5D y LIC) ---
    const USERNAME = "rgregorio0@ucol.mx";
    const PASSWORD = "Pacofran25?";

    const dispositivosPorSalon = {};
    dispositivos.forEach(d => dispositivosPorSalon[d.id_salon] = d.device_id);

    let token = null;
    const particle = new Particle();

    particle.login({ username: USERNAME, password: PASSWORD }).then((data) => {
        token = data.body.access_token;

        Object.entries(dispositivosPorSalon).forEach(([id_salon, device_id]) => {
            const salon = salones.find(s => s.id_salon == id_salon);
            if (!salon || !["5D", "LIC"].includes(salon.ubicacion)) return;

            particle.getEventStream({ deviceId: device_id, auth: token })
                .then(stream => {
                    stream.on("event", (event) => {
                        if (event.name === "Temp_C") {
                            const t = parseFloat(event.data);
                            actualizarSpan(salon.id_salon, "temperatura", t.toFixed(1));
                            enviarLectura(salon.id_salon, "temperatura", t);
                        }
                        if (event.name === "Humedad") {
                            const h = parseFloat(event.data);
                            actualizarSpan(salon.id_salon, "humedad", h.toFixed(1));
                            enviarLectura(salon.id_salon, "humedad", h);
                        }
                    });
                })
                .catch(err => console.error(`❌ Error getEventStream para salón ${id_salon}:`, err));
        });
    }).catch(err => console.error("❌ No se pudo iniciar sesión en Particle:", err));
});