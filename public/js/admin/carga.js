// ==============================
//  TOKEN FIJO DE PARTICLE
// ==============================
const PARTICLE_TOKEN = "8efccb8eef8eb88173f8186330df2cbfc11af57d";

// Ruta fija del firmware que está en Laravel
const FIRMWARE_URL = "/storage/firmware/firmware_eventos.bin";

document.addEventListener("DOMContentLoaded", () => {

    console.log("Script de flasheo cargado y listo.");

    const form = document.getElementById("addSalonForm");
    const guardarBtn = document.getElementById("guardarSalonBtn");

    guardarBtn.addEventListener("click", async (event) => {
        event.preventDefault();

        console.log("Botón 'Guardar salón' presionado");
        console.log("Ejecutando flashFirmware()...");

        const resultado = await flashFirmware();

        console.log("Resultado de flashFirmware():", resultado);

        // Ahora siempre envía el formulario aunque falle
        console.log("Enviando formulario a Laravel...");
        form.submit();
    });

});

// ===========================================
//  FUNCIÓN PARA FLASHEAR FIRMWARE AL PARTICLE
// ===========================================
async function flashFirmware() {

    const deviceId = document.getElementById("particleID").value.trim();
    const statusDiv = document.getElementById("status");

    console.log("Iniciando flashFirmware()");
    console.log("Device ID ingresado:", deviceId);
    console.log("Token fijo:", PARTICLE_TOKEN);
    console.log("Firmware URL:", FIRMWARE_URL);

    // Evita que falle si no existe el div
    if (statusDiv) {
        statusDiv.style.display = "block";
        statusDiv.className = "loading";
        statusDiv.innerText = "Cargando firmware desde el servidor...";
    } else {
        console.warn("Advertencia: No se encontró el elemento #status");
    }

    if (!deviceId) {
        console.log("Error: No se proporcionó el deviceId.");

        if (statusDiv) {
            statusDiv.innerText = "Error: Falta el ID del dispositivo.";
            statusDiv.className = "error";
        }

        // Aun así devolver false
        return false;
    }

    try {
        console.log("Descargando firmware desde Laravel...");

        // 1) Descargar archivo desde Laravel
        const firmwareResponse = await fetch(FIRMWARE_URL);

        console.log("Respuesta de descarga firmware:", firmwareResponse);

        if (!firmwareResponse.ok) {
            throw new Error("No se pudo cargar el firmware desde el servidor.");
        }

        const firmwareBlob = await firmwareResponse.blob();

        console.log("Firmware descargado. Tamaño:", firmwareBlob.size, "bytes");

        // 2) Preparar FormData
        const formData = new FormData();
        formData.append("file", firmwareBlob, "firmware_eventos.bin");
        formData.append("file_type", "binary");

        console.log("FormData preparado:", formData);

        const url = `https://api.particle.io/v1/devices/${deviceId}`;

        console.log("Enviando firmware a Particle...");
        console.log("URL:", url);

        // 3) Enviar a Particle
        const response = await fetch(url, {
            method: "PUT",
            headers: {
                Authorization: `Bearer ${PARTICLE_TOKEN}`,
            },
            body: formData,
        });

        console.log("Respuesta bruta de Particle:", response);

        const data = await response.json();

        console.log("Respuesta JSON de Particle:", data);

        if (response.ok && data.ok) {
            if (statusDiv) {
                statusDiv.innerText = "Actualización enviada correctamente al dispositivo.";
                statusDiv.className = "success"
            }
            console.log("Flasheo exitoso.");
            return true;
        } else {
            throw new Error(data.error_description || data.error || "Error desconocido.");
        }

    } catch (error) {
        if (statusDiv) {
            statusDiv.innerText = "Error: " + error.message;
            statusDiv.className = "error";
        }

        console.error("ERROR en flashFirmware():", error);
        return false;
    }
}