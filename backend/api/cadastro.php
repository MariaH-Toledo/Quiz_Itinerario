<?php
require_once(__DIR__ . "/../config/db.php")

function jsonResponse($arr) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($arr);
    exit;
}

function redirectToFrontend($path, $status, $mensagem) {
    $qs = http_build_query(['status' => $status, 'mensagem' => $mensagem]);
    header("Location: {$path}?{$qs}");
    exit;
}

$raw = file_get_contents("php://input");
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

$isJson = stripos($contentType, 'application/json') !== false && !empty($raw);

$nomeCompleto = "";
$nomeUsuario = "";
$senha = "";

if ($isJson) {
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        jsonResponse(["status" => "erro", "mensagem" => "JSON inválido."]);
    }
    $nomeCompleto = trim($data["nome_completo"] ?? "");
    $nomeUsuario  = trim($data["nome_usuario"] ?? "");
    $senha        = trim($data["senha"] ?? "");
} else {
    $nomeCompleto = trim($_POST["nome_completo"] ?? "");
    $nomeUsuario  = trim($_POST["nome_usuario"] ?? "");
    $senha        = trim($_POST["senha"] ?? "");
}

if ($nomeCompleto === "" || $nomeUsuario === "" || $senha === "") {
    if ($isJson) {
        jsonResponse(["status" => "erro", "mensagem" => "Preencha todos os campos."]);
    } else {
        redirectToFrontend("../../frontend/cadastro.html", "erro", "Preencha todos os campos.");
    }
}

$stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE nome_usuario = ?");
if (!$stmt) {
    if ($isJson) jsonResponse(["status"=>"erro","mensagem"=>"Erro interno (DB)."]);
    redirectToFrontend("../../frontend/cadastro.html", "erro", "Erro interno.");
}
$stmt->bind_param("s", $nomeUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    if ($isJson) {
        jsonResponse(["status" => "erro", "mensagem" => "Nome de usuário já existe."]);
    } else {
        redirectToFrontend("../../frontend/cadastro.html", "erro", "Nome de usuário já existe.");
    }
}
$stmt->close();

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuario (nome_completo, nome_usuario, senha_hash) VALUES (?, ?, ?)");
if (!$stmt) {
    if ($isJson) jsonResponse(["status"=>"erro","mensagem"=>"Erro ao preparar inserção."]);
    redirectToFrontend("../../frontend/cadastro.html", "erro", "Erro ao cadastrar.");
}
$stmt->bind_param("sss", $nomeCompleto, $nomeUsuario, $senhaHash);

if ($stmt->execute()) {
    $stmt->close();
    if ($isJson) {
        jsonResponse(["status" => "sucesso", "mensagem" => "Usuário cadastrado com sucesso!"]);
    } else {
        redirectToFrontend("../../frontend/login.html", "sucesso", "Usuário cadastrado com sucesso.");
    }
} else {
    $stmt->close();
    if ($isJson) {
        jsonResponse(["status" => "erro", "mensagem" => "Erro ao cadastrar usuário."]);
    } else {
        redirectToFrontend("../../frontend/cadastro.html", "erro", "Erro ao cadastrar usuário.");
    }
}
