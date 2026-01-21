## CRUD de Usuários (PHP + JSON)

Como rodar:
1. Tenha o PHP instalado.
   
2. No terminal (PowerShell) a partir da raiz do projeto, execute:

```powershell
php -S localhost:8000 -t public
```

3. Abra no navegador: http://localhost:8000


Endpoints (usados pelo front-end):
- GET /api.php?action=list
- POST /api.php?action=create
- POST /api.php?action=update
- POST /api.php?action=delete

Validações realizadas no backend:

- `name` obrigatório
- `email` obrigatório e válido
- `phone` obrigatório


