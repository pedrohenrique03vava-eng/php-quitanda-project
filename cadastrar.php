<?php
require __DIR__ . '/conexao.php';
$erros = [];
$categorias = ['Frutas','Legumes','Verduras'];
$valores = ['nome'=>'','categoria'=>'Frutas','preco'=>'','unidade'=>'kg','estoque'=>'0','imagem_url'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach($valores as $k=>$v) $valores[$k] = trim((string)($_POST[$k] ?? $v));
    if ($valores['nome']==='') $erros[]='Informe o nome.';
    if (!in_array($valores['categoria'],$categorias,true)) $erros[]='Categoria inválida.';
    if (!is_numeric($valores['preco']) || (float)$valores['preco']<0) $erros[]='Preço inválido.';
    if ($valores['unidade']==='') $erros[]='Informe a unidade.';
    if (filter_var($valores['estoque'],FILTER_VALIDATE_INT)===false || (int)$valores['estoque']<0) $erros[]='Estoque inválido.';
    if ($valores['imagem_url']!=='' && !filter_var($valores['imagem_url'],FILTER_VALIDATE_URL)) $erros[]='URL da imagem inválida.';
    if (!$erros) {
        $stmt=$pdo->prepare('INSERT INTO produtos (nome,categoria,preco,unidade,estoque,imagem_url) VALUES (:nome,:categoria,:preco,:unidade,:estoque,:imagem_url)');
        $stmt->execute(['nome'=>$valores['nome'],'categoria'=>$valores['categoria'],'preco'=>$valores['preco'],'unidade'=>$valores['unidade'],'estoque'=>$valores['estoque'],'imagem_url'=>$valores['imagem_url'] ?: null]);
        header('Location: admin.php?ok=1'); exit;
    }
}
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Cadastrar produto</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">
<div class="container py-5" style="max-width:760px"><a href="admin.php" class="text-success">← Voltar ao painel</a><div class="card border-0 shadow-sm mt-3"><div class="card-body p-4"><h1 class="h3 mb-4">Cadastrar produto</h1>
<?php if($erros): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($erros as $e): ?><li><?=h($e)?></li><?php endforeach;?></ul></div><?php endif;?>
<form method="post"><div class="row g-3">
<div class="col-md-8"><label class="form-label">Nome</label><input class="form-control" name="nome" required maxlength="150" value="<?=h($valores['nome'])?>"></div>
<div class="col-md-4"><label class="form-label">Categoria</label><select class="form-select" name="categoria"><?php foreach($categorias as $c):?><option <?= $valores['categoria']===$c?'selected':''?>><?=h($c)?></option><?php endforeach;?></select></div>
<div class="col-md-4"><label class="form-label">Preço (R$)</label><input class="form-control" type="number" name="preco" min="0" step=".01" required value="<?=h($valores['preco'])?>"></div>
<div class="col-md-4"><label class="form-label">Unidade</label><input class="form-control" name="unidade" maxlength="30" required value="<?=h($valores['unidade'])?>"></div>
<div class="col-md-4"><label class="form-label">Estoque</label><input class="form-control" type="number" name="estoque" min="0" required value="<?=h($valores['estoque'])?>"></div>
<div class="col-12"><label class="form-label">URL da imagem</label><input class="form-control" type="url" name="imagem_url" value="<?=h($valores['imagem_url'])?>"></div>
</div><div class="mt-4 d-flex gap-2"><button class="btn btn-success">Salvar produto</button><a class="btn btn-outline-secondary" href="admin.php">Cancelar</a></div></form>
</div></div></div></body></html>
