# 🚀 Plataforma Matemática - Guia de Execução

**IMPORTANTE:** Este guia detalha como executar o projeto em um computador diferente com Docker Desktop. Leia completamente antes de iniciar.

---

## 📋 Tabela de Conteúdo

1. [Pré-requisitos](#pré-requisitos)
2. [Entender a Infraestrutura](#entender-a-infraestrutura)
3. [Primeiros Passos](#primeiros-passos)
4. [Comando Principal](#comando-principal)
5. [Verificar Status](#verificar-status)
6. [Acesso à Aplicação](#acesso-à-aplicação)
7. [Banco de Dados](#banco-de-dados)
8. [Troubleshooting](#troubleshooting)
9. [Fluxo de Uso](#fluxo-de-uso)

---

## Pré-requisitos

Antes de começar, certifique-se de ter instalado:

- ✅ **Docker Desktop** (Windows) - Versão 4.0+
- ✅ **Git** (para clonar/atualizar o repositório)
- ✅ **VS Code ou editor de código** (opcional, mas recomendado)

**Verificar instalação:**
```powershell
# No PowerShell, execute:
docker --version
docker compose version
```

Se ambos retornam uma versão, está tudo pronto. Se não, instale o Docker Desktop primeiro.

---

## Entender a Infraestrutura

Este projeto usa **Docker Compose** com 3 serviços:

### 📦 Serviços

| Serviço | Container | Porta Host | Porta Container | Dados | Tipo |
|---------|-----------|-----------|------------------|-------|------|
| **Laravel App** | `laravel.test` | 8005 | 80 | Código (bind mount) | Aplicação |
| **MySQL** | `mysql` | 3307 | 3306 | Volume nomeado `sail-mysql` | Banco de Dados |
| **Redis** | `redis` | 6379 | 6379 | Volume nomeado `sail-redis` | Cache/Sessões |

### 📁 Volumes e Bind Mounts

**Volumes Nomeados (dados persistentes em `docker-data/`):**
```
sail-mysql:/var/lib/mysql         -> Banco de dados MySQL
sail-redis:/data                  -> Cache Redis
```

**Bind Mounts (código sincronizado):**
```
./plataforma:/var/www/html        -> Código Laravel (TUDO sincronizado)
```

**Importante:**
- Alterações no código no seu editor aparecem **imediatamente** no container.
- Dados do banco e cache **persistem** mesmo após `docker compose down`.
- Se deletar os volumes, **perde o banco de dados**. Não faça isso!

---

## Primeiros Passos

### 1️⃣ Clonar/Abrir o Projeto

```powershell
# Se ainda não tem o projeto:
git clone <repo-url> Matematica
cd Matematica\plataforma

# Se já tem:
cd <caminho-do-projeto>\plataforma
```

### 2️⃣ Verificar Arquivo `.env`

O arquivo `.env` já está configurado. **Não mude os valores abaixo:**

```dotenv
# BANCO DE DADOS (comunicação interna)
DB_HOST=mysql          # Nome do serviço (interno no Docker)
DB_PORT=3306           # Porta interna
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=matematica2024

# REDIS (comunicação interna)
REDIS_HOST=redis       # Nome do serviço (interno no Docker)
REDIS_PORT=6379

# ACESSO EXTERNO
APP_PORT=8005          # Porta que você acessa no navegador
FORWARD_DB_PORT=3307   # Porta para acessar MySQL do seu PC
```

**Se precisar acessar MySQL externamente:**
- Host: `localhost`
- Porta: `3307`
- Usuário: `sail`
- Senha: `matematica2024`

### 3️⃣ Construir a Imagem (Primeira Vez APENAS)

⚠️ **ATENÇÃO:** Execute este comando **APENAS na primeira vez** que for rodar o projeto em um computador novo.

```powershell
cd plataforma
docker compose up -d --build
```

⏱️ **Isso pode levar 5-10 minutos.** Aguarde até aparecer todos os containers "Up".

**❌ NUNCA execute `docker compose build` sozinho!** Sempre use `docker compose up -d --build` na primeira vez.

**DEPOIS DA PRIMEIRA VEZ:** Use apenas `docker compose up -d` (sem `--build`).

---

## Comando Principal

### ▶️ Iniciar os Serviços

```powershell
cd <caminho-do-projeto>\plataforma
docker compose up -d
```

Isso irá:
- ✅ Iniciar o container Laravel
- ✅ Iniciar o MySQL (se primeira vez, aguarde)
- ✅ Iniciar o Redis
- ✅ Sincronizar o código automaticamente

### ⚙️ Após subir containers (obrigatório)

Execute o Shield para registrar permissões/policies do painel admin (faça sempre que recriar volumes ou na primeira vez):
```powershell
docker compose exec laravel.test php artisan shield:install admin --no-interaction
docker compose exec laravel.test php artisan shield:generate --all --panel=admin --no-interaction
docker compose exec laravel.test php artisan cache:clear
```

### ⏸️ Parar os Serviços

```powershell
docker compose down
```

**Resultado:**
- ❌ Containers param
- ✅ Volumes persistem (banco de dados fica)
- ✅ Code bind mount fica

### 🔄 Reiniciar (sem perder dados)

```powershell
docker compose restart
```

### 🗑️ Limpar TUDO (CUIDADO!)

```powershell
# Isso deleta containers E volumes (banco de dados some!)
docker compose down -v
```

**⚠️ Nunca use `down -v` a menos que tenha certeza!**

---

## Verificar Status

### Ver Containers Rodando

```powershell
docker compose ps
```

Deve mostrar algo assim:
```
NAME              STATUS              PORTS
laravel.test      Up (healthy)        0.0.0.0:8005->80/tcp
mysql             Up (healthy)        0.0.0.0:3307->3306/tcp
redis             Up (healthy)        0.0.0.0:6379->6379/tcp
```

### Ver Logs em Tempo Real

```powershell
# Todos os serviços
docker compose logs -f

# Apenas Laravel
docker compose logs -f laravel.test

# Apenas MySQL
docker compose logs -f mysql
```

Pressione `Ctrl+C` para sair.

### Executar Comandos Dentro do Container

```powershell
# Exemplo: Limpar cache
docker compose exec laravel.test php artisan cache:clear

# Exemplo: Rodar migrations
docker compose exec laravel.test php artisan migrate

# Exemplo: Acessar Tinker (console interativo)
docker compose exec laravel.test php artisan tinker
```

---

## Acesso à Aplicação

Quando tudo estiver rodando:

| Serviço | URL | Descrição |
|---------|-----|-----------|
| **Painel Admin** | http://localhost:8005/admin | Filament admin |
| **Site** | http://localhost:8005 | Frontend (se existir) |
| **MySQL** | localhost:3307 | Conectar com DBeaver/Workbench |
| **Redis** | localhost:6379 | Cache/Sessões |

**Para acessar o admin:**
1. Vá em http://localhost:8005/admin
2. Use suas credenciais (se tiver seed, verifique na documentação)
3. Crie um usuário se necessário:

```powershell
docker compose exec laravel.test php artisan tinker
```

```php
use App\Models\User;
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
]);
exit
```

---

## Banco de Dados

### Acessar MySQL Externamente

Use qualquer cliente SQL (DBeaver, MySQL Workbench, VS Code):

```
Host:     localhost
Port:     3307
Database: laravel
User:     sail
Password: matematica2024
```

### Backup Automático

Os dados persistem em `docker-data/mysql/`. Se quiser fazer backup manual:

```powershell
docker compose exec mysql mysqldump -u root -pmatematica2024 laravel > backup.sql
```

### Restaurar Backup

```powershell
docker compose exec -T mysql mysql -u root -pmatematica2024 laravel < backup.sql
```

---

## ⚠️ IMPORTANTE: O QUE NUNCA FAZER

### 🚫 NUNCA Execute Estes Comandos

Estes comandos podem **quebrar completamente** o ambiente Docker e fazer você perder horas reconstruindo:

#### ❌ NUNCA: `docker compose build`

**POR QUÊ:** Este projeto usa uma imagem pré-construída do Laravel Sail (`sail-8.4/app`). O comando `build` pode reconstruir a imagem de forma incorreta, criando um container sem servidor web (nginx), resultando em um Laravel que **sobe mas não responde**.

**O QUE ACONTECE:**
- Container inicia normalmente (`docker compose ps` mostra "Up")
- Porta 8005 fica mapeada
- Mas http://localhost:8005 não carrega (conexão recusada)
- PHP-FPM roda, mas falta o nginx para servir as páginas

**SE VOCÊ FEZ ISSO POR ENGANO:**
```powershell
# 1. Pare tudo
docker compose down

# 2. Delete a imagem quebrada
docker rmi laravel-app:latest

# 3. Reconstrua CORRETAMENTE
docker compose up -d --build

# 4. Aguarde 30-60 segundos e teste http://localhost:8005
```

#### ❌ NUNCA: `docker compose down -v`

**POR QUÊ:** O `-v` **deleta TODOS os volumes**, incluindo o banco de dados MySQL. Você perde:
- Todos os usuários criados
- Todas as lições
- Todo o conteúdo
- Configurações de permissões

**USE APENAS:** `docker compose down` (sem `-v`)

#### ❌ NUNCA: Deletar `docker-data/`

**POR QUÊ:** Esta pasta contém os dados persistentes do MySQL e Redis. Deletá-la = perder todo o banco de dados.

**SE DELETOU:** Você precisará refazer as migrations e seeders do zero.

#### ❌ NUNCA: Editar `docker-compose.yml` sem backup

**POR QUÊ:** O arquivo está configurado corretamente. Mudanças podem quebrar:
- Mapeamento de volumes (código não sincroniza)
- Portas (conflito ou inacessível)
- Build context (imagem quebrada)

**ANTES DE EDITAR:**
```powershell
# Faça backup
copy docker-compose.yml docker-compose.yml.backup
```

#### ❌ NUNCA: Mudar `DB_HOST` no `.env` para `localhost`

**POR QUÊ:** Dentro do Docker, os serviços se comunicam por **nome do serviço**, não `localhost`.

**CORRETO:**
```dotenv
DB_HOST=mysql        # Nome do serviço no docker-compose.yml
REDIS_HOST=redis     # Nome do serviço no docker-compose.yml
```

**ERRADO:**
```dotenv
DB_HOST=localhost    # ❌ Não funciona dentro do Docker
DB_HOST=127.0.0.1    # ❌ Não funciona dentro do Docker
```

---

## Troubleshooting

### ❌ Porta 8005 Já em Uso

**Problema:** `Error starting userland proxy: listen tcp4 0.0.0.0:8005: bind: An attempt was made to use a port in a state preventing its use.`

**Solução:**
```powershell
# Encontrar qual processo usa a porta
netstat -ano | findstr :8005

# Se quer usar outra porta, edite .env:
# APP_PORT=8006

# Depois reinicie:
docker compose down
docker compose up -d
```

### ❌ MySQL Demora para Ficar Pronto

**Problema:** Container MySQL fica reiniciando nos primeiros minutos.

**Solução:** Normal. Deixe 2-3 minutos rodando. Verifique com:
```powershell
docker compose logs mysql | tail -20
```

### ❌ "Connection refused" ao tentar acessar o site

**Problema:** http://localhost:8005 não carrega.

**Possíveis Causas:**

**1. Container não está rodando:**
```powershell
# Verifique status
docker compose ps

# Se não estiver "Up", inicie
docker compose up -d

# Aguarde 30 segundos e tente novamente
```

**2. Container subiu mas sem servidor web (você rodou `docker compose build` por engano):**

**SINTOMAS:**
- `docker compose ps` mostra "Up"
- Porta 8005 mapeada corretamente
- http://localhost:8005 dá "conexão recusada"
- Dentro do container só roda `php-fpm` (sem nginx)

**SOLUÇÃO COMPLETA:**
```powershell
# 1. Pare containers
docker compose down

# 2. Verifique se docker-compose.yml tem isto:
#    laravel.test:
#        build:
#            context: .
#            dockerfile: Dockerfile.dev
#        volumes:
#            - .:/var/www/html:cached
#
# Se tiver "image: laravel-app:latest" em vez de "build:", EDITE para usar build!

# 3. Reconstrua a imagem corretamente
docker compose up -d --build

# 4. Aguarde 60 segundos

# 5. Teste
curl http://localhost:8005 -UseBasicParsing

# Se retornar HTML, está funcionando!
```

**3. Verificar logs:**
```powershell
# Veja os últimos 50 logs
docker compose logs laravel.test | tail -50

# Se aparecer apenas "php-fpm: ready to handle connections" SEM nada sobre nginx,
# você precisa reconstruir (veja solução acima)

# Se aparecer erros do Laravel, leia a mensagem e corrija
```

### ❌ Código Não Atualiza no Container

**Problema:** Você edita um arquivo mas não aparece no navegador.

**Solução:**
```powershell
# 1. Limpe cache Laravel
docker compose exec laravel.test php artisan cache:clear

# 2. Limpe config
docker compose exec laravel.test php artisan config:clear

# 3. Se estiver desenvolvendo frontend (Vite)
docker compose exec laravel.test npm run dev

# 4. Último recurso: reinicie
docker compose restart laravel.test
```

### ❌ Erro de Permissão em Arquivo

**Problema:** "Permission denied" ao tentar salvar dados.

**Solução:**
```powershell
docker compose exec laravel.test chmod -R 775 storage bootstrap/cache
```

---

## Fluxo de Uso

### 📝 Desenvolvimento Diário

**Cada dia que voltar ao projeto:**

```powershell
# 1. Abra PowerShell na pasta plataforma
cd <caminho-do-projeto>\plataforma

# 2. Inicie os containers
docker compose up -d

# 3. Aguarde 30 segundos

# 4. Acesse http://localhost:8005/admin

# 5. Desenvolva normalmente - as mudanças sincronizam
#    Se quiser compilar Vite em tempo real:
docker compose exec laravel.test npm run dev

# 6. Ao terminar, pode deixar rodando ou parar
docker compose down
```

### 🧰 Adicionar Dependência PHP

```powershell
# 1. Você edita composer.json normalmente

# 2. Execute no container
docker compose exec laravel.test composer install

# 3. Pode precisar do artisan
docker compose exec laravel.test php artisan optimize:clear
```

### 📦 Adicionar Dependência NPM

```powershell
# 1. Execute
docker compose exec laravel.test npm install <package>

# 2. Compile
docker compose exec laravel.test npm run build
```

### 🚀 Deploy Preparatório

Antes de fazer push para produção:

```powershell
# 1. Limpe cache
docker compose exec laravel.test php artisan cache:clear
docker compose exec laravel.test php artisan config:clear

# 2. Rode migrations
docker compose exec laravel.test php artisan migrate

# 3. Build assets
docker compose exec laravel.test npm run build

# 4. Teste tudo localmente
# ... teste features no navegador ...
```

---

## ✨ Sistema de Blocos Gutenberg

O projeto incluí um editor tipo WordPress Gutenberg com 11+ tipos de blocos:

- ✅ Parágrafo (TipTap Rich Editor)
- ✅ Título (H2, H3, H4)
- ✅ Imagem (com upload)
- ✅ Código (com syntax highlight)
- ✅ Fórmulas LaTeX (com KaTeX)
- ✅ Citações
- ✅ 2 e 3 Colunas
- ✅ Listas
- ✅ E mais...

**Documentação:** `docs/lesson-editor-architecture.md`

---

## 📚 Documentação Adicional

- `docs/lesson-editor-architecture.md` - Arquitetura modular do editor
- `docs/fluxo-criacao-conteudo.md` - Como criar cursos/módulos/lições
- `docs/storage-config.md` - Configuração de armazenamento
- `docs/manual-criador.md` - Manual para criadores de conteúdo

---

## 🆘 Resumo Rápido

Se alguém te disser "suba o Docker", faça:

```powershell
cd <caminho-do-projeto>\plataforma
docker compose up -d
```

Aguarde 30 segundos, acesse http://localhost:8005/admin e pronto.

Se der problema, rode:

```powershell
docker compose logs -f
```

e procure pela mensagem de erro.

---

## ✅ Checklist Inicial

- [ ] Docker Desktop instalado e rodando
- [ ] Projeto clonado em `<caminho>/Matematica/plataforma`
- [ ] `.env` configurado (padrão já está ok)
- [ ] `docker compose build` executado (primeira vez)
- [ ] `docker compose up -d` rodando
- [ ] `docker compose ps` mostra 3 containers "Up (healthy)"
- [ ] http://localhost:8005/admin acessível
- [ ] Consegue criar um curso/módulo/lição

🎉 Pronto! Bom desenvolvimento!
1. Verifique os logs do Docker
2. Limpe o banco com os comandos acima
3. Verifique o console do navegador (F12)
4. Leia a documentação em `docs/`

---

**Última atualização**: 2025-12-11

