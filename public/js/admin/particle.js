// Selección de elementos HTML
var $Temp = $('#Temperatura');
var $Hum  = $('#Humedad');

// Particle.io
var particle = new Particle();
var token;

// Datos para la gráfica y tabla
let dataHistory = [];
let currentPage = 1;
const rowsPerPage = 5;

// =======================
// Login en Particle
// =======================
particle.login({ username: 'rgregorio0@ucol.mx', password: 'Pacofran25?' }).then(
  function (data) {
    token = data.body.access_token;
    console.log("Login correcto, token obtenido.");
  },
  function (err) {
    console.error('No se pudo iniciar sesión en Particle:', err);
  }
);

// =======================
// Configuración gráfica D3
// =======================
let svg = d3.select("#chart"),
    width = +svg.attr("width"),
    height = +svg.attr("height"),
    margin = {top: 20, right: 20, bottom: 30, left: 50};

let x = d3.scaleLinear().domain([0, 50]).range([margin.left, width - margin.right]);
let y = d3.scaleLinear().domain([0, 100]).range([height - margin.bottom, margin.top]);

let lineTemp = d3.line()
  .x((d,i) => x(i))
  .y(d => y(d.temp));

let lineHum = d3.line()
  .x((d,i) => x(i))
  .y(d => y(d.hum));

svg.append("path").attr("id","tempLine").attr("stroke","red").attr("fill","none").attr("stroke-width",2);
svg.append("path").attr("id","humLine").attr("stroke","blue").attr("fill","none").attr("stroke-width",2);

// =======================
// Función para actualizar la tabla
// =======================
function renderTable() {
  let tbody = $("#dataTable tbody");
  tbody.empty();

  let start = (currentPage - 1) * rowsPerPage;
  let end = start + rowsPerPage;
  let pageData = dataHistory.slice().reverse().slice(start, end);

  pageData.forEach((row, idx) => {
    tbody.append(`<tr>
      <td>${start + idx + 1}</td>
      <td>${row.temp.toFixed(2)} °C</td>
      <td>${row.hum.toFixed(2)} %</td>
      <td>${row.time}</td>
    </tr>`);
  });

  $("#pageInfo").text(`Página ${currentPage} de ${Math.ceil(dataHistory.length / rowsPerPage)}`);
  $("#prevPage").prop("disabled", currentPage === 1);
  $("#nextPage").prop("disabled",currentPage === Math.ceil(dataHistory.length / rowsPerPage));
}

// =======================
// Paginación
// =======================
$("#prevPage").click(() => { 
  if(currentPage > 1){ currentPage--; renderTable(); } 
});
$("#nextPage").click(() => { 
  if(currentPage < Math.ceil(dataHistory.length / rowsPerPage)){ currentPage++; renderTable(); } 
});

// =======================
// Guardar en DB
// =======================
function guardarEnDB(temp, hum){
  fetch('guardar_lectura.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ temp, hum })
  })
  .then(res => res.json())
  .then(res => {
    if(res.status !== "ok") console.error("Error guardando en DB:", res.msg);
  })
  .catch(err => console.error("Error fetch:", err));
}

// =======================
// Obtener datos de Particle cada 15 segundos
// =======================
setInterval(function () {
  if (!token) return;

  Promise.all([
    particle.getVariable({ deviceId: '29002b000b47313037363132', name: 'TEMP', auth: token }),
    particle.getVariable({ deviceId: '29002b000b47313037363132', name: 'HUM',  auth: token })
  ]).then(results => {
    let temp = results[0].body.result;
    let hum = results[1].body.result;

    // Actualizar tarjetas
    $Temp.text(temp.toFixed(2) + " °C");
    $Hum.text(hum.toFixed(2) + " %");

    // Guardar historial para gráfica y tabla
    dataHistory.push({ temp, hum, time: new Date().toLocaleTimeString() });
    if (dataHistory.length > 50) dataHistory.shift();

    // Actualizar gráfica
    svg.select("#tempLine").datum(dataHistory).attr("d", lineTemp);
    svg.select("#humLine").datum(dataHistory).attr("d", lineHum);

    // Actualizar tabla
    renderTable();

    // Guardar en DB
    guardarEnDB(temp, hum);

  }).catch(err => console.error("Error al obtener datos:", err));

}, 15000);