<?php
require __DIR__ . '/conexao.php';
$produtos = $pdo->query('SELECT * FROM produtos ORDER BY id DESC')->fetchAll();
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Administração | Quitanda Verde</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{background:#f3f8f1}.top{background:#236b45;color:white}.btn-green{background:#236b45;color:white}</style></head><body>
<header class="top py-4"><div class="container d-flex justify-content-between align-items-center"><h1 class="h3 mb-0">Painel administrativo</h1><a class="btn btn-outline-light" href="index.php">Ver loja</a></div></header>
<main class="container py-5"><div class="d-flex justify-content-between align-items-center mb-4"><div><h2 class="fw-bold">Produtos</h2><p class="text-secondary mb-0">Gerencie o catálogo da quitanda.</p></div><a class="btn btn-green" href="cadastrar.php">＋ Cadastrar novo produto</a></div>
<?php if(isset($_GET['ok'])): ?><div class="alert alert-success">Operação realizada com sucesso.</div><?php endif; ?>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>ID</th><th>Produto</th><th>Categoria</th><th>Preço</th><th>Unidade</th><th>Estoque</th><th class="text-end">Ações</th></tr></thead><tbody>
<?php foreach($produtos as $p): ?><tr><td><?=(int)$p['id']?></td><td class="fw-semibold"><?=h($p['nome'])?></td><td><?=h($p['categoria'])?></td><td>R$ <?=number_format((float)$p['preco'],2,',','.')?></td><td><?=h($p['unidade'])?></td><td><?=(int)$p['estoque']?></td><td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="editar.php?id=<?=(int)$p['id']?>">Editar</a> <a class="btn btn-sm btn-outline-danger" href="excluir.php?id=<?=(int)$p['id']?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a></td></tr><?php endforeach; ?>
<?php if(!$produtos): ?><tr><td colspan="7" class="text-center py-4">Nenhum produto cadastrado.</td></tr><?php endif; ?>
</tbody></table></div></div></main></body></html>
