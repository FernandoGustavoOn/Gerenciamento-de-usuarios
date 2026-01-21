# CRUD de Usuários (PHP + JSON)

Aplicação web simples para gerenciar usuários (listar, criar, editar, excluir) usando PHP sem frameworks e persistindo em um arquivo JSON.

Estrutura principal:

- `public/` - arquivos servidos pelo servidor PHP (frontend e endpoints API)
  - `index.php` - página principal
  - `api.php` - endpoints: `?action=list|create|update|delete`
  - `assets/` - CSS e JS
- `src/` - lógica PHP
  - `UserRepository.php` - leitura/escrita do arquivo JSON
  - `UserController.php` - validação e respostas JSON
- `data/users.json` - arquivo JSON que guarda os usuários

Endpoints (usados pelo front-end):

- GET /api.php?action=list
- POST /api.php?action=create
- POST /api.php?action=update
- POST /api.php?action=delete

Obs: ao rodar o servidor com `-t public`, os endpoints ficam em `http://localhost:8000/api.php?action=...`.

Validações realizadas no backend:

- `name` obrigatório
- `email` obrigatório e válido
- `phone` obrigatório

Como rodar (PHP embutido):

1. No terminal (PowerShell) a partir da raiz do projeto, execute:

```powershell
php -S localhost:8000 -t public
```

2. Abra no navegador: http://localhost:8000

Notas:
- O arquivo de dados é `data/users.json`. Faça backup se necessário.
- Saída para HTML é protegida com `htmlspecialchars` no `index.php`. No JS, valores colocados no DOM usam `textContent`/escape para evitar XSS.
