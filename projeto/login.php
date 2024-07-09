<?php
    session_start();
    ob_start();
    require_once "./conexao.php";

    function loginInvalido(){
        echo "<div class='alert alert-danger text-center' role='alert'>";
        echo "Email ou senha incorreta!";
        echo "</div>";
    }
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <title>Monitoramento de Rotas</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="css/sistema/landpage.css" rel="stylesheet"> -->
    <link href="css/sistema/login.css" rel="stylesheet">
</head>

<body>
    <header id="topo">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html">RotaTrack: Sistema de Gestão de Rotas</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.html"><i class="fa-solid fa-house"></i>&nbsp;Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-id-card"></i>&nbsp;Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($dados['logar'])) {
            $email = $dados['email'];    
            $senha = $dados['senha'];  

            $sql = "SELECT id_usuario, nome, email, senha FROM usuario WHERE email=:email LIMIT 1";
            $resultado_sql = $pdo->prepare($sql);
            $resultado_sql->bindParam(':email', $email, PDO::PARAM_STR);
            $resultado_sql->execute();

            if (($resultado_sql) && ($resultado_sql->rowCount() != 0)) {
                $linha_usuario = $resultado_sql->fetch(PDO::FETCH_ASSOC);

                if (password_verify($senha, $linha_usuario['senha'])) { // Verifica se a senha corresponde ao hash
                    $_SESSION['id_usuario'] = $linha_usuario['id_usuario'];
                    $_SESSION['nome'] = $linha_usuario['nome']; 
                    header("Location: menu.php");
                } else {
                    $_SESSION['msg'] = loginInvalido();
                }
                
            } else {
                $_SESSION['msg'] = loginInvalido();
            }
        }

        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
    ?>
    <main class="form-signin">
        <form id="formlogin" action="" method="POST">
            <h1 class="h3 mb-3 fw-normal" style="text-align: center;">Realize seu login</h1>
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required="required"> <label for="email">Email
                </label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required> <label for="senha">Senha</label>
            </div>
            <input class="w-100 btn btn-lg btn-primary" type="submit" name="logar" value="Logar">
        </form>
        <br>
        <p class="text-center"><a href="cadastro.php">Cadastrar-se!</a></p>
    </main>

    <script src="./js/jquery/jquery-3.7.1.min.js"></script>
    <script src="./js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/51b23194c0.js" crossorigin="anonymous"></script>
</body>

</html>