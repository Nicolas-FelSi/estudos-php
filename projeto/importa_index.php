

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>Menu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="./css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="./css/sistema/menu.css" rel="stylesheet">
</head>

<body>
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
                <a class="nav_logo" title="Simulador" id="simulador_link"> <i class="fas fa-cog nav_logo-icon"></i>
                    <span class="nav_logo-name">solvEDM</span>
                </a>
                <div class="nav_list">
                    <a href="#" class="nav_link" title="Ponto" id="ponto_link">
                        <i class="fas fa-map-marker-alt nav_icon"></i>
                        <span class="nav_name">Ponto</span>
                    </a>
                    <a href="#" class="nav_link" title="Usuário" id="usuario_link">
                        <i class="fas fa-user-cog nav_icon"></i>
                        <span class="nav_name">Usuário</span>
                    </a>
                    <a href="#" class="nav_link" title="Importação de Dados" id="importa_link">
                        <i class="fas fa-upload nav_icon"></i>
                        <span class="nav_name">Importação</span>
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
    <div class="container">
        <div class="row">
            <div id="carregando_importa" class="d-none text-center">
                <img src="./imagens/carregando.gif" />
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 d-flex justify-content-start">
                        <h4>Importação de Dados</h4>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
                    </div>
                    <div class="col-md-4 d-flex justify-content-end">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#" title="Home" id="home_index_importa"><i
                                            class="fas fa-home"></i>
                                        <span>Home</span></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Importa</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4 d-flex justify-content-start">
                    </div>
                    <div class="col-md-4 d-flex justify-content-center">
                        <form enctype="multipart/form-data" method="post" accept-charset="utf-8" id="importa"
                            role="form" action="importa_index.php">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="eletrodo_importa" class="form-label">Eletrodo</label>
                                    <select name="eletrodo_importa" id="eletrodo_importa" class="form-select">

                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="polaridade_importa" class="form-label">Polaridade</label>
                                    <select name="polaridade_importa" id="polaridade_importa" class="form-select">
                                        <option value="1">Positiva</option>
                                        <option value="0">Negativa</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="operacao_importa" class="form-label">Operação</label>
                                    <select name="operacao_importa" id="operacao_importa" class="form-select">

                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="idArquivo" class="form-label">Arquivo</label>
                                    <input type="file" name="arquivo" id="idArquivo" class="form-control">
                                </div>
                            </div>
                            <br>
                            <!--
                            <button type="submit" class="btn btn-primary btn-sm" title="Importar">Importar</button>
                            -->
                            <a id="botao_importa" class="btn btn-primary btn-sm" title="Importar">Importar</a>

                        </form>
                    </div>
                    <div class="col-md-4 d-flex justify-content-end">
                    </div>
                </div>
                <hr>
            </div>
            <div class="col-md-12">
                <div class="alert alert-info alert-dismissible fade show" style="display: none;"
                    id="div_mensagem_importa">
                    <button type="button" class="btn-close btn-sm" aria-label="Close"
                        id="div_mensagem_botao_importa"></button>
                    <p id="div_mensagem_texto_importa"></p>
                </div>
            </div>
        </div>
    </div>

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

    <script>
        //devido ao load precisa carregar o arquivo js dessa forma
        var url = "./js/sistema/importa.js";
        $.getScript(url);
    </script>

    <script src="./js/jquery/jquery.min.js"></script>
    <script src="./js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/51b23194c0.js" crossorigin="anonymous"></script>
    <script src="./js/sistema/menu.js"></script>
</body>

</html>