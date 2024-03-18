$(document).ready(function () {
  // Faz a requisição AJAX ao arquivo PHP
  $.ajax({
    url: "./mandarDados.php",
    type: "POST", // Pode ser GET ou POST dependendo da sua implementação
    dataType: "json", // Espera-se receber um JSON como resposta
    success: function (dados) {
      let map;

      function formatarData(dataString) {
        return moment(dataString, "DD/MM/YYYY HH:mm:ss").toDate();
      }

      function criarMapa(coordenadas) {

        const primeiraLatitude = coordenadas[0].latitude
        const primeiraLongitude = coordenadas[0].longitude
        const ultimaLatitude = coordenadas[coordenadas.length - 1].latitude
        const ultimaLongitude = coordenadas[coordenadas.length - 1].longitude

        if (map === undefined) {
          map = L.map("map").setView([primeiraLatitude, primeiraLongitude], 13);
        } else {
          map.remove();
          map = L.map("map").setView([primeiraLatitude, primeiraLongitude], 13);
        }

        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
          maxZoom: 19,
          attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        const partida = L.marker([primeiraLatitude, primeiraLongitude]).addTo(map);
        const chegada = L.marker([ultimaLatitude, ultimaLongitude]).addTo(map);

        const coordenadasArray = [];

        coordenadas.forEach(coordenada => {
          const latitude = coordenada.latitude;
          const longitude = coordenada.longitude;
          coordenadasArray.push([latitude, longitude]);
        });

        const primeiraLinha = coordenadas[0].numero_linha;
        const ultimaLinha = coordenadas[coordenadas.length - 1].numero_linha;

        const primeiraDataHora = formatarData(coordenadas[0].data_hora);
        const ultimaDataHora = formatarData(coordenadas[coordenadas.length - 1].data_hora);

        const primeiraData = primeiraDataHora.toLocaleDateString();
        const ultimaData = ultimaDataHora.toLocaleDateString();

        const primeiraHoras = primeiraDataHora.toLocaleTimeString();
        const ultimaHoras = ultimaDataHora.toLocaleTimeString();

        partida.bindPopup(`
        Data: ${primeiraData} -
        Hora: ${primeiraHoras} -
        Número da linha: ${primeiraLinha}
        `).openPopup();

        chegada.bindPopup(`
        Data: ${ultimaData} -
        Hora: ${ultimaHoras} -
        Número da linha: ${ultimaLinha}
        `).openPopup();

        const polyline = L.polyline(coordenadasArray, { color: 'red' }).addTo(map);

        map.fitBounds(polyline.getBounds());
        chegada.openPopup();
      }

      criarMapa(dados)

    },
    error: function (xhr, status, error) {
      // Callback chamado em caso de erro na requisição
      console.error("Erro na requisição AJAX:", status, error);
    },
  });
});
