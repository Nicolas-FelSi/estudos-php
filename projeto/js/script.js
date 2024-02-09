$(document).ready(function () {
  // Faz a requisição AJAX ao arquivo PHP
  $.ajax({
    url: "./mandarDados.php",
    type: "POST", // Pode ser GET ou POST dependendo da sua implementação
    dataType: "json", // Espera-se receber um JSON como resposta
    success: function (dados) {
      let map;

      function criarMapa(coordenadas) {
        if (map === undefined) {
          map = L.map("map").setView([coordenadas[0].latitude, coordenadas[0].longitude], 13);
        } else {
          map.remove();
          map = L.map("map").setView([coordenadas[0].latitude, coordenadas[0].longitude], 13);
        }

        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
          maxZoom: 19,
          attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        const partida = L.marker([coordenadas[0].latitude, coordenadas[0].longitude]).addTo(map);
        const chegada = L.marker([coordenadas[coordenadas.length-1].latitude, coordenadas[coordenadas.length-1].longitude]).addTo(map);

        const coordenadasArray = [];

        coordenadas.forEach(coordenada => {
          const latitude = coordenada.latitude;
          const longitude = coordenada.longitude;
          coordenadasArray.push([latitude, longitude]);
        });

        partida.bindPopup("Começou aqui.").openPopup();
        chegada.bindPopup("Terminou aqui.").openPopup();
        const polyline = L.polyline(coordenadasArray, {color: 'red'}).addTo(map);

        map.fitBounds(polyline.getBounds());
      }

      criarMapa(dados)

    },
    error: function (xhr, status, error) {
      // Callback chamado em caso de erro na requisição
      console.error("Erro na requisição AJAX:", status, error);
    },
  });
});
