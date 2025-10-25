<?php
require '../config/db.php';

if (isset($_POST['cadastrar_usuario'])) {
    $nome_completo = mysqli_real_escape_string($conn, trim($_POST['nome_completo']));
    $nome_usuario = mysqli_real_escape_string($conn, trim($_POST['nome_usuario']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $verifica_sql = "SELECT nome_usuario FROM usuario WHERE nome_usuario = '$nome_usuario'";
    $resultado = mysqli_query($conn, $verifica_sql);

    if (mysqli_num_rows($resultado) > 0) {
        echo "<script>
                alert('Nome de usuário já existe! Escolha outro.');
                window.location.href = '../../frontend/cadastro.html';
              </script>";
        exit;
    }

    $sql = "INSERT INTO usuario (nome_completo, nome_usuario, senha_hash)
            VALUES ('$nome_completo', '$nome_usuario', '$senha_hash')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Usuário cadastrado com sucesso! Agora faça seu login.');
                window.location.href = '../../frontend/login.html';
              </script>";
    } else {
        echo "<script>
                alert('Erro ao cadastrar usuário!');
                window.location.href = '../../frontend/cadastro.html';
              </script>";
    }

    mysqli_close($conexao);
}
?>