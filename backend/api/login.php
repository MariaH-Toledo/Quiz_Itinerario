<?php
require '../config/db.php';

if (isset($_POST['login_usuario'])) {
    $nome_usuario = mysqli_real_escape_string($conn, trim($_POST['nome_usuario']));
    $senha = mysqli_real_escape_string($conn, trim($_POST['senha']));

    $sql = "SELECT * FROM usuario WHERE nome_usuario = '$nome_usuario'";
    $resultado = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultado) === 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        if (password_verify($senha, $usuario['senha_hash'])) {
            session_start();
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
            $_SESSION['nome_completo'] = $usuario['nome_completo'];

            echo "<script>
                    alert('Login realizado com sucesso!');
                    window.location.href = '../../frontend/dashboard.html';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Senha incorreta!');
                    window.location.href = '../../frontend/login.html';
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('Usuário não encontrado!');
                window.location.href = '../../frontend/login.html';
              </script>";
        exit;
    }

    mysqli_close($conn);
}
?>
