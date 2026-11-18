<?php
session_start();
require __DIR__ . '/conexao.php';

if (!isset($_SESSION['carrinho'])) $_SESSION['carrinho'] = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $id = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);
    $qtd = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
    if ($id && $qtd && $qtd > 0) {
        $stmt = $pdo->prepare('SELECT id, nome, preco, estoque FROM produtos WHERE id = ?');
        $stmt->execute([$id]);
        $p = $stmt->fetch();
        if ($p && $qtd <= (int)$p['estoque']) {
            $_SESSION['carrinho'][$id] = min((int)$p['estoque'], ($_SESSION['carrinho'][$id] ?? 0) + $qtd);
            $_SESSION['mensagem'] = 'Produto adicionado ao carrinho!';
        } else $_SESSION['mensagem'] = 'Quantidade indisponível em estoque.';
    }
    header('Location: index.php' . (!empty($_GET['categoria']) ? '?categoria='.urlencode($_GET['categoria']) : ''));
    exit;
}
if (isset($_GET['remover'])) {
    unset($_SESSION['carrinho'][(int)$_GET['remover']]);
    header('Location: index.php'); exit;
}
$categoria = $_GET['categoria'] ?? 'Todas';
$categorias = ['Todas','Frutas','Legumes','Verduras'];
if (!in_array($categoria, $categorias, true)) $categoria = 'Todas';
$sql = 'SELECT * FROM produtos';
$params = [];
if ($categoria !== 'Todas') { $sql .= ' WHERE categoria = ?'; $params[] = $categoria; }
$sql .= ' ORDER BY nome';
$stmt = $pdo->prepare($sql); $stmt->execute($params); $produtos = $stmt->fetchAll();

$carrinho = [];
$total = 0;
if ($_SESSION['carrinho']) {
    $ids = array_keys($_SESSION['carrinho']);
    $marks = implode(',', array_fill(0, count($ids), '?'));
    $s = $pdo->prepare("SELECT id,nome,preco,unidade FROM produtos WHERE id IN ($marks)");
    $s->execute($ids);
    foreach ($s as $item) {
        $q = $_SESSION['carrinho'][$item['id']];
        $subtotal = (float)$item['preco'] * $q;
        $total += $subtotal;
        $carrinho[] = $item + ['qtd'=>$q, 'subtotal'=>$subtotal];
    }
}
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Quitanda Verde | Fresquinho todo dia</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--green:#236b45;--light:#f3f8f1}body{background:var(--light);color:#24352b}
.navbar,.hero{background:var(--green)}.hero{color:white;border-radius:0 0 2rem 2rem}
.brand{font-weight:800;letter-spacing:.3px}.product-card{border:0;border-radius:1rem;overflow:hidden;transition:.2s;height:100%}
.product-card:hover{transform:translateY(-4px);box-shadow:0 12px 28px #183c2520}
.product-img{height:190px;object-fit:cover;background:#e5eee4}.price{color:var(--green);font-size:1.25rem;font-weight:800}
.btn-green{background:var(--green);color:white}.btn-green:hover{background:#174b30;color:white}
.category-link{border-radius:30px}.category-link.active{background:var(--green);color:white}
</style></head>
<body>
<nav class="navbar navbar-dark navbar-expand-lg"><div class="container">
<a class="navbar-brand brand" href="index.php">🥬 Quitanda Verde</a>
<div class="ms-auto d-flex gap-2"><a class="btn btn-outline-light btn-sm" href="admin.php">Administração</a><a class="btn btn-light btn-sm" href="#carrinho">🛒 Carrinho (<?=array_sum($_SESSION['carrinho'])?>)</a></div>
</div></nav>
<header class="hero py-5"><div class="container py-3"><div class="row align-items-center"><div class="col-lg-8">
<p class="text-uppercase small fw-bold mb-2">Do campo para sua mesa</p><h1 class="display-5 fw-bold">Fresquinho, saudável e pertinho de você.</h1>
<p class="lead mb-0">Frutas, legumes e verduras selecionados com carinho.</p></div><div class="col-lg-4 text-lg-end mt-4 mt-lg-0"><span class="display-1">🍊</span></div></div></div></header>
<main class="container py-5">
<?php if (!empty($_SESSION['mensagem'])): ?><div class="alert alert-success"><?=h($_SESSION['mensagem']); unset($_SESSION['mensagem']);?></div><?php endif; ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"><div><h2 class="fw-bold mb-1">Nossa seleção</h2><p class="text-secondary mb-0">Produtos fresquinhos todos os dias</p></div>
<div class="d-flex flex-wrap gap-2"><?php foreach($categorias as $cat): ?><a class="btn btn-sm category-link <?=$categoria===$cat?'active btn-green':'btn-outline-success'?>" href="?categoria=<?=urlencode($cat)?>"><?=h($cat)?></a><?php endforeach; ?></div></div>
<div class="row g-4">
<?php foreach($produtos as $p): ?><div class="col-6 col-md-4 col-xl-3"><div class="card product-card shadow-sm">
<img class="product-img card-img-top" src="<?=h($p['imagem_url'])?>" alt="<?=h($p['nome'])?>" onerror="this.src='https://placehold.co/600x400/eaf2e8/236b45?text=Produto+fresco'">
<div class="card-body d-flex flex-column"><span class="badge text-bg-success-subtle text-success align-self-start mb-2"><?=h($p['categoria'])?></span>
<h5 class="card-title fw-bold"><?=h($p['nome'])?></h5><div class="price mb-3">R$ <?=number_format((float)$p['preco'],2,',','.')?> <small class="text-secondary fw-normal fs-6">/ <?=h($p['unidade'])?></small></div>
<p class="small text-secondary mt-auto">Estoque: <?= (int)$p['estoque'] ?></p>
<form method="post" class="d-flex gap-2"><input type="hidden" name="produto_id" value="<?=(int)$p['id']?>"><input class="form-control form-control-sm" type="number" name="quantidade" min="1" max="<?=(int)$p['estoque']?>" value="1" style="max-width:72px" required><button class="btn btn-green btn-sm flex-grow-1" name="adicionar" <?=$p['estoque']<1?'disabled':''?>>Adicionar</button></form>
</div></div></div><?php endforeach; ?>
<?php if (!$produtos): ?><p class="text-secondary">Nenhum produto encontrado nesta categoria.</p><?php endif; ?>
</div>
<section id="carrinho" class="mt-5 p-4 bg-white rounded-4 shadow-sm"><div class="d-flex justify-content-between align-items-center mb-3"><h3 class="fw-bold mb-0">🛒 Seu carrinho</h3><span class="badge text-bg-success"><?=count($carrinho)?> produto(s)</span></div>
<?php if ($carrinho): ?><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Produto</th><th>Qtd.</th><th>Subtotal</th><th></th></tr></thead><tbody>
<?php foreach($carrinho as $item): ?><tr><td><?=h($item['nome'])?></td><td><?=$item['qtd']?> <?=h($item['unidade'])?></td><td>R$ <?=number_format($item['subtotal'],2,',','.')?></td><td><a class="btn btn-sm btn-outline-danger" href="?remover=<?=(int)$item['id']?>">Remover</a></td></tr><?php endforeach; ?>
</tbody></table></div><div class="text-end"><div class="fs-4 fw-bold text-success">Total: R$ <?=number_format($total,2,',','.')?></div><small class="text-secondary">Demonstração: o carrinho não finaliza pagamentos nem pedidos.</small></div>
<?php else: ?><p class="text-secondary mb-0">Seu carrinho está vazio. Adicione produtos para começar.</p><?php endif; ?></section>
</main><footer class="py-4 text-center text-secondary"><small>© <?=date('Y')?> Quitanda Verde · Frescor e qualidade</small></footer>
</body></html>
