$(document).ready(function () {
  // Função para obter parâmetros da URL
  function obterParametroDaURL(nome) {
    const parametros = new URLSearchParams(window.location.search);
    return parametros.get(nome);
  }

  // Obtém o ID da planilha da URL
  const idPlanilha = obterParametroDaURL('id_planilha');

  // Faz a requisição AJAX ao arquivo PHP
  $.ajax({
    url: "./mandarDados.php",
    type: "GET", // Pode ser GET ou POST dependendo da sua implementação
    data: { id_planilha: idPlanilha },
    dataType: "json", // Espera-se receber um JSON como resposta
    success: function (dados) {
      let map;

      const primeiraLatitude = dados[0].latitude
      const primeiraLongitude = dados[0].longitude
      const ultimaLatitude = dados[dados.length - 1].latitude
      const ultimaLongitude = dados[dados.length - 1].longitude

      function dadosCriarMapaPadrao() {
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
      }

      function bindPopupGenerico(marcador, data, hora, n_linha) {
        const dataFormatada = moment(data).format('DD-MM-YYYY');
        marcador.bindPopup(`
        Dt: ${dataFormatada}<br>
        Hr: ${hora}<br>
        N° linha: ${n_linha}
        `).openPopup();
      }

      function exibirFiltroDataHora(filtro) {
        // Verifica se o elemento com ID está visível ou oculto
        if (filtro == "data_hora") {
          $("#filtroData").show();
          $("#filtroHora").show();
        } else {
          $("#filtroData").hide();
          $("#filtroHora").hide();
        }
      }

      function criarMapa(coordenadas) {
        dadosCriarMapaPadrao();
        exibirFiltroDataHora("padrao")

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

        const primeiraData = coordenadas[0].data;
        const ultimaData = coordenadas[coordenadas.length - 1].data;
        const primeiraHora = coordenadas[0].hora;
        const ultimaHora = coordenadas[coordenadas.length - 1].hora;

        bindPopupGenerico(partida, primeiraData, primeiraHora, primeiraLinha);

        bindPopupGenerico(chegada, ultimaData, ultimaHora, ultimaLinha);

        const polyline = L.polyline(coordenadasArray, { color: 'red' }).addTo(map);

        map.fitBounds(polyline.getBounds());
        chegada.openPopup();
      }

      function mapaMotorDesligado(dadosJson) {
        dadosCriarMapaPadrao();
        exibirFiltroDataHora("motorDesligado");

        dadosJson.forEach((dado) => {
          if (dado.ignicao === 0) {
            const pontoIgnicaoOff = L.marker([dado.latitude, dado.longitude]).addTo(map);
            bindPopupGenerico(pontoIgnicaoOff, dado.data, dado.hora, dado.numero_linha);
          }
        })
      }

      function mapaTempoDeParada(dadosJson) {
        dadosCriarMapaPadrao();
        exibirFiltroDataHora("tempoParada");

        // Variável para armazenar a última marca de tempo em que o carro ficou "off"
        let primeiraParada = null;

        dadosJson.forEach((dado) => {
          const dataFormatada = moment(dado.data).format('DD-MM-YYYY');
          if (dado.ignicao === 0 && primeiraParada === null) {
            const dataHoraObjeto = new Date(dado.data + ' ' + dado.hora);
            primeiraParada = new Date(dataHoraObjeto); // Atualiza a última parada
          } else if (dado.ignicao === 1 && primeiraParada !== null) {
            const dataHoraObjeto = new Date(dado.data + ' ' + dado.hora);
            const dataInicioParada = primeiraParada;
            const dataFimParada = new Date(dataHoraObjeto);
            const duracaoParada = dataFimParada - dataInicioParada; // Converte de milissegundos para minutos
            const pontoIgnicaoOn = L.marker([dado.latitude, dado.longitude]).addTo(map);

            pontoIgnicaoOn.bindPopup(`
            Dt: ${dataFormatada}<br>
            Hr: ${dado.hora}<br>
            N° linha: ${dado.numero_linha}<br>
            Tempo Parada: ${exibirTempoDeParada(duracaoParada)}
            `).openPopup();
            primeiraParada = null; // Reseta a última parada
          }
        })

        // Exibe as durações de parada
        function exibirTempoDeParada(duracao) {
          const horas = Math.floor(duracao / (1000 * 60 * 60));
          const minutos = Math.floor((duracao % (1000 * 60 * 60)) / (1000 * 60));
          const segundos = Math.floor((duracao % (1000 * 60)) / 1000);
          return `${acrescentarZero(horas)}:${acrescentarZero(minutos)}:${acrescentarZero(segundos)}`;
        }

        function acrescentarZero(numero) {
          return numero < 10 ? `0${numero}` : numero;
        }
      }

      function mapaDataHora(dadosJson) {
        dadosCriarMapaPadrao();
        exibirFiltroDataHora("data_hora");

        // Função para verificar se um valor já existe nas opções do select
        function valorJaExiste(selectElement, valor) {
          const options = selectElement.options;
          for (let i = 0; i < options.length; i++) {
            if (options[i].value === valor) {
              return true;
            }
          }
          return false;
        }

        // // Função para adicionar valores únicos ao select
        function adicionarValorUnico(selectElement, valor) {
          if (!valorJaExiste(selectElement, valor)) {
            let option = document.createElement('option');
            option.value = valor;
            option.text = valor;
            selectElement.appendChild(option);
          }
        }

        // // Preencher o select com valores únicos
        const selectData = document.getElementById('filtroDataSelect');
        const selectHora = document.getElementById('filtroHoraSelect');
        dadosJson.forEach((valor) => {
          const dataFormatada = moment(valor.data).format('DD-MM-YYYY');
          adicionarValorUnico(selectData, dataFormatada);
          adicionarValorUnico(selectHora, valor.hora);
        });

        function filtrarData() {
          dadosCriarMapaPadrao();
          const filtro = document.getElementById("filtroDataSelect").value;
          dadosJson.forEach((dado) => {
            const dataFormatada = moment(dado.data).format('DD-MM-YYYY');
            if (filtro == dataFormatada) {
              const pontoMapa = L.marker([dado.latitude, dado.longitude]).addTo(map);
              bindPopupGenerico(pontoMapa, dado.data, dado.hora, dado.numero_linha)
            }
          })
        }

        function filtrarHora() {
          dadosCriarMapaPadrao();
          const filtro = document.getElementById("filtroHoraSelect").value;
          dadosJson.forEach((dado) => {
            if (filtro == dado.hora) {
              const pontoMapa = L.marker([dado.latitude, dado.longitude]).addTo(map);
              bindPopupGenerico(pontoMapa, dado.data, dado.hora, dado.numero_linha)
            }
          })
        }

        // Adiciona um listener de evento para detectar alterações no filtro
        document.getElementById("filtroDataSelect").addEventListener("change", filtrarData);
        document.getElementById("filtroHoraSelect").addEventListener("change", filtrarHora);
      }

      // Função para carregar o mapa
      function carregarMapa(filtro) {
        if (filtro === "motorDesligado") {
          mapaMotorDesligado(dados);
        } else if (filtro === "data_hora") {
          mapaDataHora(dados);
        } else if (filtro === "tempoParada") {
          mapaTempoDeParada(dados);
        } else if (filtro === "padrao") {
          criarMapa(dados);
        }
      }

      // Função para lidar com a alteração do filtro
      function onChangeFiltro() {
        const filtro = document.getElementById("filtro").value;
        // Chama a função para carregar o mapa com o filtro selecionado
        carregarMapa(filtro);
      }

      // Adiciona um listener de evento para detectar alterações no filtro
      document.getElementById("filtro").addEventListener("change", onChangeFiltro);
      carregarMapa("padrao")
    },
    error: function (xhr, status, error) {
      // Callback chamado em caso de erro na requisição
      console.error("Erro na requisição AJAX:", status, error);
    },
  });
});
