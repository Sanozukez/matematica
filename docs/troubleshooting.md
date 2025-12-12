# 🔧 Troubleshooting - Soluções de Problemas

## 🎨 Editor.js

### Editor fica em "Carregando..." indefinidamente

**Sintomas**:
- Editor mostra spinner de loading
- Console mostra `Timeout waiting for List` ou similar

**Causas**:
1. CDN lento ou bloqueado
2. Firewall/antivírus bloqueando scripts
3. Problema de conexão com internet

**Soluções**:

#### 1. Aguarde mais tempo
O timeout foi aumentado para 15 segundos. Aguarde.

#### 2. Verifique o console
```
F12 → Console → Procure por erros em vermelho
```

#### 3. Limpe cache do navegador
```
Ctrl + Shift + R (Windows/Linux)
Cmd + Shift + R (Mac)
```

#### 4. Teste CDN manualmente
Abra no navegador:
```
https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.28.2/dist/editorjs.umd.min.js
```

Se não carregar, pode ser problema de rede/firewall.

#### 5. Use VPN se necessário
Alguns provedores bloqueiam CDNs. Tente com VPN.

### Scripts do Editor não carregam

**Verificação**:
```javascript
// Cole no console do navegador (F12)
console.log('EditorJS:', typeof EditorJS);
console.log('Header:', typeof Header);
console.log('List:', typeof List);
console.log('ImageTool:', typeof ImageTool);
```

Todos devem mostrar `function`. Se mostrar `undefined`, o script não carregou.

**Solução**:
- Recarregue a página (Ctrl+R)
- Limpe cache (Ctrl+Shift+R)
- Verifique console por erros de rede

### Erro: "List is not defined"

**Causa**: A biblioteca List exporta como `NestedList` em algumas versões.

**Solução**: Atualizada automaticamente. Se persistir:
```javascript
// Cole no console (F12) ANTES de criar lição:
window.List = window.NestedList || window.List;
```

## 🗄️ Banco de Dados

### "Duplicate entry for key unique"

**Causa**: Tentativa de criar registro com slug duplicado.

**Solução 1 - Limpar soft deleted**:
```bash
docker exec plataforma-laravel.test-1 php artisan lessons:clean-orphans --force
```

**Solução 2 - Limpar slug específico**:
```bash
docker exec plataforma-laravel.test-1 php artisan lessons:clean-orphans --force --slug=nome-do-slug
```

**Solução 3 - Alterar título**:
- Mude o título para gerar slug diferente
- Ou edite o slug manualmente

### Módulo não aparece no dropdown

**Verificação**:
```bash
docker exec plataforma-laravel.test-1 php artisan tinker
```
```php
use App\Domain\Module\Models\Module;
Module::get(['id', 'title', 'is_active']);
```

**Soluções**:

1. **Nenhum módulo existe**:
   - Crie um módulo em `Conteúdo → Módulos`

2. **Módulo está inativo**:
   ```php
   $module = Module::first();
   $module->is_active = true;
   $module->save();
   ```

3. **Módulo soft deleted**:
   ```php
   Module::withTrashed()->get();
   // Se encontrar, restaure:
   $module = Module::withTrashed()->find('uuid');
   $module->restore();
   ```

### Lições órfãs (soft deleted mas aparecem como existentes)

**Verificar**:
```bash
docker exec plataforma-laravel.test-1 php artisan lessons:clean-orphans
```

**Limpar**:
```bash
# Soft delete para hard delete
docker exec plataforma-laravel.test-1 php artisan lessons:clean-orphans --force --hard
```

## 🐳 Docker

### Containers não iniciam

```bash
# Ver logs
docker compose logs -f

# Reiniciar
docker compose down
docker compose up -d

# Ver status
docker compose ps
```

### MySQL não aceita conexão

```bash
# Verificar se está rodando
docker compose ps

# Ver logs do MySQL
docker compose logs mysql

# Reiniciar apenas o MySQL
docker compose restart mysql
```

### Porta 8005 já está em uso

**Solução 1 - Mudar porta**:
```env
# Edite .env
APP_PORT=8006
```

**Solução 2 - Matar processo**:
```bash
# Windows
netstat -ano | findstr :8005
taskkill /PID [numero] /F

# Linux/Mac
lsof -ti:8005 | xargs kill
```

## 📝 Formulários Filament

### Validação não funciona

**Causa comum**: Validação `unique` não considera escopo.

**Verificação**:
- Lição: `unique` deve considerar `module_id`
- Módulo: `unique` deve considerar `course_id`
- Curso: `unique` global

**Já corrigido** nos Resources atualizados.

### Campo não salva

**Verificação**:
1. Verifique se está no `$fillable` do Model
2. Verifique tipo do campo no formulário
3. Verifique cast no Model

```php
// Model deve ter:
protected $fillable = ['campo'];
protected $casts = ['campo' => 'tipo'];
```

## 🔐 Autenticação

### Não consigo acessar /admin

**Soluções**:

1. **Sem usuário**:
   ```bash
   docker exec plataforma-laravel.test-1 php artisan tinker
   ```
   ```php
   use App\Models\User;
   User::create([
       'name' => 'Admin',
       'email' => 'admin@admin.com',
       'password' => bcrypt('password')
   ]);
   ```

2. **Logout forçado**:
   - Limpe cookies do navegador
   - Acesse `/admin/login`

## 🌐 CDN e Assets

### CSS/JS não carrega

```bash
# Limpar cache de views
php artisan view:clear

# Recompilar assets (se usar Vite)
npm run build

# Verificar link simbólico
php artisan storage:link
```

### Imagens não aparecem

**Verificação**:
```bash
# Link simbólico deve existir
ls -la public/storage

# Se não existir:
php artisan storage:link
```

## 🔍 Debug

### Ver queries SQL

```php
// Cole no tinker ou em qualquer lugar do código
DB::enableQueryLog();

// Execute operação

dd(DB::getQueryLog());
```

### Ver estado do Livewire

```javascript
// No console do navegador (F12)
window.Livewire.all()
```

### Ver erros de validação

```php
// No controller/Resource
dd($request->validate([...]));
```

## 📊 Performance

### Editor lento

**Soluções**:
1. Use versões específicas dos CDNs (não `@latest`)
2. Considere hospedar scripts localmente
3. Use lazy loading para scripts pesados

### Banco de dados lento

```bash
# Ver queries lentas
docker exec plataforma-mysql-1 mysql -usail -psail laravel -e "SHOW FULL PROCESSLIST;"

# Otimizar tabelas
docker exec plataforma-mysql-1 mysql -usail -psail laravel -e "OPTIMIZE TABLE lessons, modules, courses;"
```

## 🆘 Comandos de Emergência

### Reset completo do banco
```bash
docker exec plataforma-laravel.test-1 php artisan migrate:fresh --seed
```

### Limpar tudo (cache, views, config)
```bash
docker exec plataforma-laravel.test-1 php artisan optimize:clear
```

### Reiniciar Docker do zero
```bash
docker compose down -v
docker compose up -d
docker exec plataforma-laravel.test-1 php artisan migrate:fresh --seed
```

## 📞 Quando Pedir Ajuda

Antes de pedir ajuda, colete essas informações:

1. **Erro completo**:
   ```bash
   # No terminal
   docker compose logs -f
   
   # No navegador (F12 → Console)
   ```

2. **Ambiente**:
   ```bash
   php -v
   docker --version
   docker compose version
   ```

3. **Estado do sistema**:
   ```bash
   docker compose ps
   docker exec plataforma-laravel.test-1 php artisan about
   ```

4. **Passos para reproduzir**:
   - O que você tentou fazer?
   - O que esperava que acontecesse?
   - O que realmente aconteceu?

