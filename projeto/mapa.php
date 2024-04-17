<?php
session_start();
ob_start();

function loginNaoEncontrado(){
  echo "<div class='alert alert-danger text-center' role='alert'>";
  echo "Faça login para entrar no sistema!";
  echo "</div>";
}

if (!(isset($_SESSION['idUsuario']) && isset($_SESSION['nome']))) {
  $_SESSION['msg']  = loginNaoEncontrado();
  header("Location: login.php");
}
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <title>Mapa</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="./css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="./css/sistema/menu.css" rel="stylesheet">
    <!-- link leaflet css -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- link css -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<body id="body">
    <header class="header" id="header">
        <div class="header_toggle">
            <i class="fas fa-bars" id="header-toggle"></i>
        </div>
        <div class="header_user">
        </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="navmenu">
            <div>
                <a href="menu.php" class="nav_logo" title="Importação de Dados" id="importa_link">
                    <i class="fas fa-upload nav_icon"></i>
                    <span class="nav_logo-name">solvEDM</span>
                </a>
                <div class="nav_list">
                    <a href="mapa.php" class="nav_link" title="Ponto" id="ponto_link">
                        <i class="fas fa-map-marker-alt nav_icon"></i>
                        <span class="nav_name">Ponto</span>
                    </a>
                    <a href="#" class="nav_link" title="Usuário" id="usuario_link">
                        <i class="fas fa-user-cog nav_icon"></i>
                        <span class="nav_name">Usuário</span>
                    </a>
                    <a href="#" class="nav_link" title="Sobre" id="sobre_link">
                        <i class="fas fa-question-circle nav_icon"></i>
                        <span class="nav_name">Sobre</span>
                    </a>
                </div>
            </div>
            <a href="#" class="nav_link" id="logout_link" title="Logout"> <i class="fas fa-sign-out-alt nav_icon"></i>
                <span class="nav_name">Sair</span>
            </a>
        </nav>
    </div>
    <!--div main-->
    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <label for="filtro" class="form-label">Filtros:</label>
                <select id="filtro" class="form-select">
                    <option value="padrao" selected>Padrão</option>
                    <option value="data_hora">Data/Hora</option>
                    <option value="tempoParada">Tempo de parada</option>
                    <option value="motorDesligado">Motor desligado</option>
                </select>
            </div>
        </div>

        <div id="filtroData" class="row mt-3" style="display: none;">
            <label for="filtroDataSelect" class="col-sm-2 col-form-label">Data:</label>
            <div class="col-sm-10">
                <select id="filtroDataSelect" class="form-select">
                    
                </select>
            </div>
        </div>

        <div id="filtroHora" class="row mt-3" style="display: none;">
            <label for="filtroHoraSelect" class="col-sm-2 col-form-label">Hora:</label>
            <div class="col-sm-10">
                <select id="filtroHoraSelect" class="form-select">
                    
                </select>
            </div>
        </div>
    </div>

    <div id="map"></div>

    <!--modal de importação-->
    <div class="modal fade" id="modal_importa" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutlabel_eletrodo">Pergunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Confirma importação de dados?
                    <input type="hidden" id="id_importa_eletrodo_modal" value="" />
                    <input type="hidden" id="id_importa_polaridade_modal" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="modal_importa_sim">Sim</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>
    <!--fim div main-->
    <!--modal de sobre-->
    <div class="modal fade" id="sobre_modal" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutlabel">Informação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>solvEDM - Simulador de resultados para eletroerosão</p>
                    <p>Desenvolvido no IFSC - Desde 2024–
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                    </p>
                    <p>Licença Creative Commons - Com direito de atribuição e não comercial</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!--modal de logout-->
    <div class="modal fade" id="logout_modal" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutlabel">Pergunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Deseja sair do sistema?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="logout_modal_sim">Sim</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/jquery/jquery-3.7.1.min.js"></script>
    <script src="./js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/51b23194c0.js" crossorigin="anonymous"></script>
    <script src="./js/sistema/menu.js"></script>

    <!-- link leaflet js -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- link moment js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>

    <!-- link js -->
    <script src="./js/sistema/script.js"></script>
</body>

</html>