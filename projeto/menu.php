<?php
require "./conexao.php";
session_start();
ob_start();

if (!(isset($_SESSION['id_usuario']) && isset($_SESSION['nome']))) {
    header("Location: login.php");
    exit();
}

// Configuração de paginação
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 2; // Número de registros por página
$offset = ($page - 1) * $records_per_page;

// Recupera as importações do usuário atual com paginação
$idUsuario = $_SESSION['id_usuario'];
$sql = $pdo->prepare("SELECT id_planilha, codigo, descricao FROM planilha WHERE fk_id_usuario = ? LIMIT $records_per_page OFFSET $offset");
$sql->execute([$idUsuario]);
$importacoes = $sql->fetchAll(PDO::FETCH_ASSOC);

// Obtenha o número total de registros
$total_sql = $pdo->prepare("SELECT COUNT(*) FROM planilha WHERE fk_id_usuario = ?");
$total_sql->execute([$idUsuario]);
$total_records = $total_sql->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);
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
      <i class="fa-solid fa-user"></i>
      <?php
        if (isset($_SESSION["nome"])) {
          echo $_SESSION["nome"];
        }
      ?>  
    </div>
  </header>
  <div class="l-navbar" id="nav-bar">
    <nav class="navmenu">
      <div>
        <a href="menu.php" class="nav_logo" title="Mapas" id="planilhas_link">
          <i class="fa-solid fa-globe nav_icon"></i>
          <span class="nav_logo-name">Mapas</span>
        </a>
        <div class="nav_list">
          <a href="cadastroInvestigacao.php" class="nav_link" title="Importação de Dados" id="importa_link">
            <i class="fas fa-upload nav_icon"></i>
            <span class="nav_name">Importação</span>
          </a>
          <a href="usuario.php" class="nav_link" title="Usuário" id="usuario_link">
            <i class="fas fa-user-cog nav_icon"></i>
            <span class="nav_name">Usuário</span>
          </a>
          <a href="help.php" class="nav_link" title="Ajuda" id="ajuda_link">
            <i class="fas fa-question-circle nav_icon"></i>
            <span class="nav_name">Ajuda</span>
          </a>
          <a href="#" class="nav_link" title="Sobre" id="sobre_link">
            <i class="fas fa-search nav_icon"></i>
            <span class="nav_name"> Sobre</span>
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
            <h4>Meus mapas</h4>
          </div>
          <div class="col-md-4 d-flex justify-content-center">
          </div>
          <div class="col-md-4 d-flex justify-content-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" title="Home" id="home_index_importa"><i class="fas fa-home"></i>
                    <span>Home</span></a></li>
                <li class="breadcrumb-item active" aria-current="page">Mapas</li>
              </ol>
            </nav>
          </div>
        </div>
        <hr>
        <div class="container mt-5">
          <ul class="list-group">
          <?php
            if ($importacoes) {
              echo '<table class="table table-bordered mt-5">';
              echo '<thead class="thead-dark">';
              echo '<tr>';
              echo '<th>ID</th>';
              echo '<th>Código</th>';
              echo '<th>Descrição</th>';
              echo '<th>Mapa</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';
              
              foreach ($importacoes as $importacao) {          
                echo '<tr>';
                echo '<td>' . htmlspecialchars($importacao['id_planilha']) . '</td>';
                echo '<td>' . htmlspecialchars($importacao['codigo']) . '</td>';
                echo '<td>' . htmlspecialchars($importacao['descricao']) . '</td>';
                echo '<td>';
                echo '<a href="mapa.php?id_planilha=' . htmlspecialchars($importacao['id_planilha']) . '" class="btn btn-primary btn-sm">Ver Mapa</a> ';
                echo '<button class="btn btn-danger btn-sm" onclick="confirmDelete(' . htmlspecialchars($importacao['id_planilha']) . ')">Excluir</button>';
                echo '</td>';
                echo '</tr>';
              }
              
              echo '</tbody>';
              echo '</table>';
            } else {
                echo 'Nenhuma importação encontrada.';
            }
          ?>
          </ul>
          <!-- Navegação de paginação -->
          <nav>
            <ul class="pagination justify-content-center">
              <?php
              if ($page > 1) {
                  echo '<li class="page-item"><a class="page-link" href="menu.php?page=' . ($page - 1) . '">Anterior</a></li>';
              }

              for ($i = 1; $i <= $total_pages; $i++) {
                  echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '"><a class="page-link" href="menu.php?page=' . $i . '">' . $i . '</a></li>';
              }

              if ($page < $total_pages) {
                  echo '<li class="page-item"><a class="page-link" href="menu.php?page=' . ($page + 1) . '">Próxima</a></li>';
              }
              ?>
            </ul>
          </nav>
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
          <p>RotaTrack - Sistema de Gestão de Rotas</p>
          <p>Desenvolvido no IFSC - Desde 2024 –
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
  <script>
    function confirmDelete(id) {
        if (confirm("Você tem certeza que deseja excluir este mapa?")) {
            window.location.href = 'excluirMapa.php?id_planilha=' + id;
        }
    }
  </script>

</body>

</html>
