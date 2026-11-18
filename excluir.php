<?php
require __DIR__ . '/conexao.php';
if($_SERVER['REQUEST_METHOD']!=='GET'){http_response_code(405);exit('Método não permitido.');}
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id){http_response_code(400);exit('ID inválido.');}
$stmt=$pdo->prepare('DELETE FROM produtos WHERE id=?');
$stmt->execute([$id]);
header('Location: admin.php?ok=1');exit;
