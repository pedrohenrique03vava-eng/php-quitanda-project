<?php
require __DIR__ . '/conexao.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if(!$id){http_response_code(400);exit('ID inválido.');}
$stmt=$pdo->prepare('SELECT * FROM produtos WHERE id=?');$stmt->execute([$id]);$produto=$stmt->fetch();
if(!$produto){http_response_code(404);exit('Produto não encontrado.');}
$categorias=['Frutas','Legumes','Verduras'];$erros=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $dados=[];foreach(['nome','categoria','preco','unidade','estoque','imagem_url'] as $k)$dados[$k]=trim((string)($_POST[$k]??''));
    if($dados['nome']==='')$erros[]='Informe o nome.';
    if(!in_array($dados['categoria'],$categorias,true))$erros[]='Categoria inválida.';
    if(!is_numeric($dados['preco'])||(float)$dados['preco']<0)$erros[]='Preço inválido.';
    if($dados['unidade']==='')$erros[]='Informe a unidade.';
    if(filter_var($dados['estoque'],FILTER_VALIDATE_INT)===false||(int)$dados['estoque']<0)$erros[]='Estoque inválido.';
    if($dados['imagem_url']!==''&&!filter_var($dados['imagem_url'],FILTER_VALIDATE_URL))$erros[]='URL da imagem inválida.';
    if(!$erros){$u=$pdo->prepare('UPDATE produtos SET nome=:nome,categoria=:categoria,preco=:preco,unidade=:unidade,estoque=:estoque,imagem_url=:imagem_url WHERE id=:id');$u->execute($dados+['imagem_url'=>$dados['imagem_url']?:null,'id'=>$id]);header('Location: admin.php?ok=1');exit;}
    $produto=array_merge($produto,$dados);
}
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar produto</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light">
<div class="container py-5" style="max-width:760px"><a href="admin.php" class="text-success">← Voltar ao painel</a><div class="card border-0 shadow-sm mt-3"><div class="card-body p-4"><h1 class="h3 mb-4">Editar produto #<?=(int)$id?></h1>
<?php if($erros):?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($erros as $e):?><li><?=h($e)?></li><?php endforeach;?></ul></div><?php endif;?>
<form method="post"><div class="row g-3">
<div class="col-md-8"><label class="form-label">Nome</label><input class="form-control" name="nome" required value="<?=h($produto['nome'])?>"></div>
<div class="col-md-4"><label class="form-label">Categoria</label><select class="form-select" name="categoria"><?php foreach($categorias as $c):?><option value="<?=h($c)?>" <?=$produto['categoria']===$c?'selected':''?>><?=h($c)?></option><?php endforeach;?></select></div>
<div class="col-md-4"><label class="form-label">Preço (R$)</label><input class="form-control" type="number" name="preco" min="0" step=".01" required value="<?=h($produto['preco'])?>"></div>
<div class="col-md-4"><label class="form-label">Unidade</label><input class="form-control" name="unidade" required value="<?=h($produto['unidade'])?>"></div>
<div class="col-md-4"><label class="form-label">Estoque</label><input class="form-control" type="number" name="estoque" min="0" required value="<?=h($produto['estoque'])?>"></div>
<div class="col-12"><label class="form-label">URL da imagem</label><input class="form-control" type="url" name="imagem_url" value="<?=h($produto['imagem_url'])?>"></div>
</div><div class="mt-4 d-flex gap-2"><button class="btn btn-success">Salvar alterações</button><a class="btn btn-outline-secondary" href="admin.php">Cancelar</a></div></form>
</div></div></div></body></html>
