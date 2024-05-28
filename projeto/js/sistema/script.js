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
			
				function filtrarTempoParada() {
					dadosCriarMapaPadrao();
					// Obtém o valor mínimo de tempo de parada em minutos definido pelo usuário
					const tempoMinimoParada = parseInt(document.getElementById('tempoMinimoParada').value, 10) * 60 * 1000;
				
					dadosJson.forEach((dado) => {
						const dataFormatada = moment(dado.data).format('DD-MM-YYYY');
				
						if (dado.velocidade < 1 && primeiraParada === null) {
							const dataHoraObjeto = new Date(dado.data + ' ' + dado.hora);
							primeiraParada = new Date(dataHoraObjeto); // Atualiza a última parada
						} else if (dado.velocidade > 0 && primeiraParada !== null) {
							const dataHoraObjeto = new Date(dado.data + ' ' + dado.hora);
							const dataInicioParada = primeiraParada;
							const dataFimParada = new Date(dataHoraObjeto);
							const duracaoParada = dataFimParada - dataInicioParada; // Em milissegundos
				
							if (duracaoParada >= tempoMinimoParada) { // Verifica se a duração da parada atende ao mínimo definido
								const pontoIgnicaoOn = L.marker([dado.latitude, dado.longitude]).addTo(map);
				
								pontoIgnicaoOn.bindPopup(`
								Data: ${dataFormatada}<br>
								Hora: ${dado.hora}<br>
								N° linha: ${dado.numero_linha}<br>
								Tempo Parada: ${exibirTempoDeParada(duracaoParada)}<br>
								`).openPopup();
							}
				
							primeiraParada = null; // Reseta a última parada
						}
					});
				}
			
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

				// Adiciona um listener de evento para detectar alterações nos inputs de data/hora
				document.getElementById("aplicarFiltroTempoParada").addEventListener("click", filtrarTempoParada);
			}


			function mapaDataHora(dadosJson) {
				dadosCriarMapaPadrao();

				// Obter datas únicas e exibir no texto
				const datasUnicas = Array.from(new Set(dadosJson.map(dado => moment(dado.data).format('DD-MM-YYYY'))));
				const datasDisponiveis = document.getElementById('datasDisponiveisDataHora');
				datasDisponiveis.innerHTML = 'Datas disponíveis: ' + datasUnicas.join(', ');

				// Função para filtrar os dados com base no intervalo definido pelo usuário
				function filtrarDataHora() {
					dadosCriarMapaPadrao();
					const filtroDataInicio = document.getElementById("filtroDataInicio").value;
					const filtroDataFim = document.getElementById("filtroDataFim").value;
					const filtroHoraInicio = document.getElementById("filtroHoraInicio").value;
					const filtroHoraFim = document.getElementById("filtroHoraFim").value;
			
					if (filtroDataInicio && filtroDataFim && filtroHoraInicio && filtroHoraFim) {
						const dataHoraInicio = moment(filtroDataInicio + ' ' + filtroHoraInicio);
						const dataHoraFim = moment(filtroDataFim + ' ' + filtroHoraFim);
			
						const pontosNoIntervalo = dadosJson.filter(dado => {
							const dataHoraDado = moment(dado.data + ' ' + dado.hora);
							return dataHoraDado.isBetween(dataHoraInicio, dataHoraFim, null, '[]');
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
				}
			
				// Adiciona um listener de evento para detectar alterações nos inputs de data/hora
				document.getElementById("aplicarFiltroDataHora").addEventListener("click", filtrarDataHora);
			}
			
			// Listener para exibir os filtros de data e hora quando a opção é selecionada
			document.getElementById('filtro').addEventListener('change', function() {
				const selectedFilter = this.value;
			
				if (selectedFilter === 'data_hora') {
					document.getElementById('filtroDataHora').style.display = 'block';
					document.getElementById('filtroTempoParada').style.display = 'none';
					document.getElementById('exibirBtnFiltroDataHora').style.display = 'block';
					document.getElementById('exibirBtnFiltroTempoParada').style.display = 'none';
					limparCampos('#filtroDataHora input[type="date"], #filtroDataHora input[type="time"]');
				} else if(selectedFilter === 'tempoParada'){
					document.getElementById('filtroTempoParada').style.display = 'block';
					document.getElementById('filtroDataHora').style.display = 'none';
					document.getElementById('exibirBtnFiltroTempoParada').style.display = 'block';
					document.getElementById('exibirBtnFiltroDataHora').style.display = 'none';
				} else {
					document.getElementById('filtroDataHora').style.display = 'none';
					document.getElementById('filtroTempoParada').style.display = 'none';
					document.getElementById('.exibirBtnFiltroDataHora').style.display = 'none';
					document.getElementById('.exibirBtnFiltroTempoParada').style.display = 'none';
				}
			});

			function limparCampos(nomeCampoId) {
				const campos = document.querySelectorAll(nomeCampoId);
				campos.forEach(input => {
					input.value = ''; // Limpa o valor do campo
				});
			}
			
			// Chame a função ao carregar a página
			document.addEventListener('DOMContentLoaded', function() {
				mapaDataHora(dadosJson);
			});
	
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
