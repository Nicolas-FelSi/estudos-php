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
  <title>Ajuda</title>
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
      <i class="fas fa-user nav_name">
        <?php
        if (isset($_SESSION["nome"])) {
          echo $_SESSION["nome"];
        }
      ?>  
      </i>
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
            <span class="nav_name">Sobre</span>
          </a>
        </div>
      </div>
      <a href="#" class="nav_link" id="logout_link" title="Logout"> <i class="fas fa-sign-out-alt nav_icon"></i>
        <span class="nav_name">Sair</span>
      </a>
    </nav>
  </div>
   <!--div-->
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="mt-4 row">
          <div class="col-md-4 d-flex justify-content-start">
            <h4>Ajuda</h4>
          </div>
          <div class="col-md-4 d-flex justify-content-center">
          </div>
          <div class="col-md-4 d-flex justify-content-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html" title="Home" id="home_index_importa"><i class="fas fa-home"></i>
                    <span>Home</span></a></li>
                <li class="breadcrumb-item active" aria-current="page">Mapas</li>
              </ol>
            </nav>
          </div>
        </div>
        <hr>
        <div class="container mt-5">
            <a href="/projeto/docs/manual-usuario.pdf">Clique para realizar download do <u><b>Manual do Usuário</b></u> detalhado.</a>

            <p>Mais instruções abaixo.</p>
        </div>
        <hr>
        <div class="container mt-5">
            <h4>Acesso ao sistema</h4>
                <p>
                    O primeiro passo é inicializar o Apache e o MySQL no Xampp. Em seguida, na barra de pesquisa do navegador, deve-se inserir o caminho para diretório: <br>
                    <b>"http://localhost/estudos-php/projeto/"</b>
                </p>
                <br>
                <h4>Cadastro e Login</h4>
                <p>
                    Para acessar a área de login e cadastro deve-se clicar em "login", na parte superior da página. Caso já tenha realizado seu cadastro, basta preencher os campos <b>usuário</b> e <b>senha</b> para efetivar seu login.<br>
                    <br>
                    Para realizar o cadastro, deve-se clicar em "cadastre-se" logo abaixo do botão de finalização do login.
                </p>    
                <br>
                <h4>Menu</h4>
                <p>
                    Ao acessar "Meus mapas" o usuário logado terá acesso aos mapas importados anteriormente por ele, podendo visualizá-los ou excluí-los de seu cadastro. <br>
                    <br>
                    Para a visualização são disponibilizados filtros:
                    <ul>
                        <li>
                            <b>Padrão</b>; que exibe todos os pontos registrados sem aplicação de filtros.
                        </li>
                        <li>
                            <b>Data/Hora</b>; que permite a seleção de um intervalo específico para a exibição.
                        </li>
                        <li>
                            <b>Tempo de parada</b>; que filtra momentos de parada com o <i>motor ligado</i>, evitando paradas irrelevantes. 
                        </li>
                        <li>
                            <b>Motor desligado</b>; que exibe momentos em que o veículo estava parado e com o motor deligado.
                        </li>
                    </ul>
                </p>
                <br>
                <h4>Importação</h4>
                <p>
                    Na página de importação deve-se buscar o arquivo desejado ou arrastá-lo para a área de seleção "arquivo".
                    <br> É importante preencher os campos "código" e "descrição" para que se tenha a identificação correta da busca posteriormente.
                </p>
                <br>
                <h4>Usuário</h4>
                <p>
                    Nesta área é possível que o usuário realize alterações em suas informações de login. Basta preencher os campos com os dados e clicar em <b>salvar</b>.
                </p>
        </div> 
      </div>
      <hr>
    </div>
  </div>
  <!--fim div -->
  <!--modal de sobre-->
  <div class="modal fade" id="sobre_modal" tabindex="-1" aria-labelledby="logoutlabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutlabel">Informação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>SispegMa - Monitoramento de rotas.</p>
                    <p>Desenvolvido no IFSC - Desde 2024 -
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                    </p>
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