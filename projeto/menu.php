<?php
require "./conexao.php";
session_start();
ob_start();

if (!(isset($_SESSION['id_usuario']) && isset($_SESSION['nome']))) {
  header("Location: login.php");
  exit();
}
?>

<!doctype html>
<html lang="pt-BR">

<head>
  <title>Menu</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="./css/bootstrap/bootstrap.min.css" rel="stylesheet">
  <link href="./css/sistema/menu.css" rel="stylesheet">
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
        <a href="menu.php" class="nav_logo" title="Planilhas" id="planilhas_link">
          <i class="fa-solid fa-globe nav_icon"></i>
          <span class="nav_logo-name">Planilhas</span>
        </a>
        <div class="nav_list">
          <a href="cadastroInvestigacao.php" class="nav_link" title="Importação de Dados" id="importa_link">
            <i class="fas fa-upload nav_icon"></i>
            <span class="nav_name">Importação</span>
          </a>
          <a href="mapa.php" class="nav_link" title="Ponto" id="ponto_link">
            <i class="fas fa-map-marker-alt nav_icon"></i>
            <span class="nav_name">Pontos</span>
          </a>
          <a href="usuario.php" class="nav_link" title="Usuário" id="usuario_link">
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
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="mt-4 row">
          <div class="col-md-4 d-flex justify-content-start">
            <h4>Suas importações</h4>
          </div>
          <div class="col-md-4 d-flex justify-content-center">
          </div>
          <div class="col-md-4 d-flex justify-content-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html" title="Home" id="home_index_importa"><i class="fas fa-home"></i>
                    <span>Home</span></a></li>
                <li class="breadcrumb-item active" aria-current="page">Planilhas</li>
              </ol>
            </nav>
          </div>
        </div>
        <hr>
        <div class="container mt-5">
          <ul class="list-group">
            <?php
            // Recupera as importações do usuário atual
            $idUsuario = $_SESSION['id_usuario'];
            $sql = $pdo->prepare("SELECT nome_planilha, id_planilha FROM planilha WHERE fk_id_usuario = ?");
            $sql->execute([$idUsuario]);
            $importacoes = $sql->fetchAll(PDO::FETCH_ASSOC);

            // Exibe as importações
            foreach ($importacoes as $importacao) {
              echo '<li class="list-group-item">' . htmlspecialchars($importacao['nome_planilha']) . ' <a href="mapa.php?id_planilha=' . htmlspecialchars($importacao['id_planilha']) . '" class="btn btn-primary btn-sm">Ver Mapa</a></li>';
            }
            ?>
          </ul>
        </div>
        <hr>
      </div>
      <div class="col-md-12">
        <div class="alert alert-info alert-dismissible fade show" style="display: none;" id="div_mensagem_importa">
          <button type="button" class="btn-close btn-sm" aria-label="Close" id="div_mensagem_botao_importa"></button>
          <p id="div_mensagem_texto_importa"></p>
        </div>
      </div>
    </div>
  </div>
  <!--fim div main -->
  <!--modal de sobre -->
  <div class="modal fade" id="sobre_modal" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutlabel">Informação</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
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
</body>

</html>