<?php
require "./conexao.php";
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/sistema/landpage.css" rel="stylesheet">
    <link href="css/sistema/cadastro.css" rel="stylesheet">
</head>

<body>
    <header id="topo">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html">Mecânica</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.html"><i class="fas fa-home"></i>&nbsp;Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-id-card"></i>&nbsp;Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="form-cadastro">
        <?php
            if (isset($_POST['nome'])) {
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $nome = $dados['nome'];
                $email = $dados['email'];
                $senha = $dados['senha'];
            
                $sql = "INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)";
                $resultado_sql = $pdo->prepare($sql);
            
                $resultado_sql->bindParam(':nome', $nome);
                $resultado_sql->bindParam(':email', $email);
                $resultado_sql->bindParam(':senha', $senha);
                $resultado_sql->execute();
                echo "<div class='alert alert-success text-center' role='alert'>";
                echo "Cadastro realizado com sucesso";
                echo "</div>";
                header("Location: login.php");
            }
        ?>
        <h4>Cadastro inicial do usuário</h4>
        <form id="usuario_cadastro" action="cadastro.php" method="post">
            <div class="row mb-3">
                <label for="nome" class="col-sm-2 col-form-label col-form-label">Nome</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control form-control" maxlength="50" id="nome" name="nome" value="" autofocus>
                </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label col-form-label">E-mail</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control form-control" maxlength="100" id="email" name="email" value="">
                </div>
            </div>
            <div class="row mb-3">
                <label for="senha" class="col-sm-2 col-form-label col-form-label">Senha</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control form-control" maxlength="10" id="senha" name="senha" value="">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="botao_cadastrar">Cadastrar</button>
            <button type="reset" class="btn btn-secondary" id="botao_limpar">Limpar</button>
        </form>
    </main>

    <footer class="container">
        <hr class="featurette-divider">
        <p>
            &copy; 2024–<script>
                document.write(new Date().getFullYear())
            </script>
            | Sysconel - O Seu Sistema de Simulação
        </p>
    </footer>

    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/51b23194c0.js" crossorigin="anonymous"></script>
    <script src="js/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="js/sistema/cadastro.js"></script>
</body>

</html>