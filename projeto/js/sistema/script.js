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

					if (dado.velocidade < 1 && primeiraParada === null) {
						const dataHoraObjeto = new Date(dado.data + ' ' + dado.hora);
						primeiraParada = new Date(dataHoraObjeto); // Atualiza a última parada
					} else if (dado.velocidade > 0 && primeiraParada !== null) {
						const dataHoraObjeto = new Date(dado.data + ' ' + dado.hora);
						const dataInicioParada = primeiraParada;
						const dataFimParada = new Date(dataHoraObjeto);
						const duracaoParada = dataFimParada - dataInicioParada; // Converte de milissegundos para minutos
						const pontoIgnicaoOn = L.marker([dado.latitude, dado.longitude]).addTo(map);

						pontoIgnicaoOn.bindPopup(`
						Data: ${dataFormatada}<br>
						Hora: ${dado.hora}<br>
						N° linha: ${dado.numero_linha}<br>
						Tempo Parada: ${exibirTempoDeParada(duracaoParada)}<br>
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
			
				// Função para agrupar as horas em intervalos de tempo
				function obterIntervaloHora(hora) {
					const horaInt = parseInt(hora);
					if (horaInt >= 0 && horaInt < 6) {
						return "00:00 - 06:00";
					} else if (horaInt >= 6 && horaInt < 12) {
						return "06:00 - 12:00";
					} else if (horaInt >= 12 && horaInt < 18) {
						return "12:00 - 18:00";
					} else {
						return "18:00 - 00:00";
					}
				}
			
				// Preencher o select de hora com os intervalos de tempo
				const selectHora = document.getElementById('filtroHoraSelect');
				const intervalosHora = ["00:00 - 06:00", "06:00 - 12:00", "12:00 - 18:00", "18:00 - 00:00"];
				intervalosHora.forEach(intervalo => {
					const option = document.createElement('option');
					option.value = intervalo;
					option.text = intervalo;
					selectHora.appendChild(option);
				});
			
				// Preencher o select de data com as datas únicas
				const selectData = document.getElementById('filtroDataSelect');
				const datasUnicas = Array.from(new Set(dadosJson.map(dado => moment(dado.data).format('DD-MM-YYYY'))));
				datasUnicas.forEach(data => {
					const option = document.createElement('option');
					option.value = data;
					option.text = data;
					selectData.appendChild(option);
				});
			
				function filtrarDataHora() {
					dadosCriarMapaPadrao();
					const filtroData = document.getElementById("filtroDataSelect").value;
					const filtroHora = document.getElementById("filtroHoraSelect").value;
					const pontosNoIntervalo = dadosJson.filter(dado => {
						const dataFormatada = moment(dado.data).format('DD-MM-YYYY');
						const intervaloHora = obterIntervaloHora(dado.hora);
						return filtroData === dataFormatada && filtroHora === intervaloHora;
					});
					if (pontosNoIntervalo.length > 0) {
						const primeiraLatitude = pontosNoIntervalo[0].latitude;
						const primeiraLongitude = pontosNoIntervalo[0].longitude;
						const ultimaLatitude = pontosNoIntervalo[pontosNoIntervalo.length - 1].latitude;
						const ultimaLongitude = pontosNoIntervalo[pontosNoIntervalo.length - 1].longitude;

						const partida = L.marker([primeiraLatitude, primeiraLongitude]).addTo(map);
						const chegada = L.marker([ultimaLatitude, ultimaLongitude]).addTo(map);

						const coordenadasArray = pontosNoIntervalo.map(coordenada => [coordenada.latitude, coordenada.longitude]);

						const primeiraLinha = pontosNoIntervalo[0].numero_linha;
						const ultimaLinha = pontosNoIntervalo[pontosNoIntervalo.length - 1].numero_linha;

						const primeiraData = pontosNoIntervalo[0].data;
						const ultimaData = pontosNoIntervalo[pontosNoIntervalo.length - 1].data;
						const primeiraHora = pontosNoIntervalo[0].hora;
						const ultimaHora = pontosNoIntervalo[pontosNoIntervalo.length - 1].hora;

						bindPopupGenerico(partida, primeiraData, primeiraHora, primeiraLinha);
						bindPopupGenerico(chegada, ultimaData, ultimaHora, ultimaLinha);

						const polyline = L.polyline(coordenadasArray, { color: 'red' }).addTo(map);

						map.fitBounds(polyline.getBounds());
						chegada.openPopup();
					}
				}
			
				// Adiciona um listener de evento para detectar alterações no filtro
				document.getElementById("filtroDataSelect").addEventListener("change", filtrarDataHora);
				document.getElementById("filtroHoraSelect").addEventListener("change", filtrarDataHora);
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
