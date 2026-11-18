QUITANDA VERDE - E-COMMERCE PHP + MYSQL
========================================

REQUISITOS
- XAMPP ou Laragon com Apache, PHP 8+ e MySQL/MariaDB.
- Extensão PDO MySQL habilitada no PHP.
- Conexão local configurada em conexao.php: localhost / root / senha vazia.

INSTALAÇÃO
1. Extraia a pasta quitanda_ecommerce para a pasta pública do servidor:
   - XAMPP: C:\xampp\htdocs\quitanda_ecommerce
   - Laragon: C:\laragon\www\quitanda_ecommerce
2. Inicie Apache e MySQL.
3. Abra http://localhost/phpmyadmin.
4. Importe o arquivo database.sql (aba Importar). O script cria quitanda_db,
   a tabela produtos e insere 12 produtos de exemplo.
5. Acesse http://localhost/quitanda_ecommerce/ para abrir a loja.
6. Acesse http://localhost/quitanda_ecommerce/admin.php para administrar
   os produtos (criar, listar, editar e excluir).

FUNCIONALIDADES
- Vitrine responsiva com filtro por categoria.
- Carrinho por sessão PHP, com quantidades, remoção e total calculado.
- CRUD de produtos com PDO Prepared Statements e validação básica.
- Imagens carregadas por URL externa; é necessário acesso à internet para vê-las.

OBSERVAÇÕES IMPORTANTES
- O carrinho é demonstrativo: não há checkout, pagamento, cálculo de frete,
  cadastro de clientes ou gravação de pedidos.
- O painel administrativo não possui autenticação. Antes de publicar na
  internet, implemente login, autorização, proteção CSRF e HTTPS.
- Para produção, use credenciais de banco próprias e não mantenha root sem senha.
- A exclusão está ligada a um link GET para simplificar o CRUD didático; em
  produção, prefira POST com token CSRF.
