# Configuração de Storage

## 📦 Storage de Imagens

A plataforma suporta dois tipos de storage para imagens:

### 1. Storage Local (Atual - Temporário)

Por padrão, as imagens são salvas no storage local público do Laravel.

**Configuração no `.env`:**
```env
FILESYSTEM_DISK=public
APP_URL=http://localhost:8005
```

As imagens serão salvas em: `storage/app/public/lessons/images/YYYY/MM/`

### 2. Bunny.net Storage (Preparado para Ativação)

Quando o Bunny.net estiver configurado, o sistema automaticamente migra para usar o CDN.

**Configuração no `.env` (quando ativar Bunny.net):**
```env
# Storage
FILESYSTEM_DISK=public

# Bunny.net Storage (para imagens)
BUNNY_STORAGE_ZONE=seu-storage-zone
BUNNY_STORAGE_API_KEY=sua-api-key
BUNNY_CDN_URL=https://seu-cdn.b-cdn.net

# Bunny.net Stream (para vídeos - futuro)
BUNNY_STREAM_LIBRARY_ID=seu-library-id
BUNNY_STREAM_API_KEY=sua-stream-api-key
```

## 🔄 Migração Automática

O `StorageService` detecta automaticamente se o Bunny.net está configurado:

- **Se configurado:** Usa Bunny.net CDN
- **Se não configurado:** Usa storage local público

**Não é necessário alterar código** - apenas adicionar as variáveis de ambiente.

## 📁 Estrutura de Pastas

As imagens são organizadas automaticamente por data:

```
lessons/images/
  ├── 2024/
  │   ├── 12/
  │   │   ├── uuid-1.jpg
  │   │   ├── uuid-2.png
  │   │   └── ...
  │   └── 11/
  │       └── ...
  └── 2025/
      └── ...
```

## 🔒 Regra de Ouro

**NUNCA salvar hotlinks externos no banco de dados.**

- ✅ Upload direto → Salva no nosso storage
- ✅ Ctrl+V de imagem → Download automático → Salva no nosso storage
- ✅ Importação de Google Docs → Download de todas as imagens → Salva no nosso storage
- ❌ NUNCA salvar URLs do Google Drive, Imgur, etc.

## 🚀 Como Ativar Bunny.net

1. Crie uma conta no [Bunny.net](https://bunny.net)
2. Crie um Storage Zone
3. Obtenha a API Key
4. Configure o CDN URL
5. Adicione as variáveis no `.env`
6. Pronto! O sistema migra automaticamente

## 📝 Notas

- O storage local continua funcionando mesmo com Bunny.net configurado
- Você pode ter ambos configurados e alternar via `FILESYSTEM_DISK`
- Imagens antigas no storage local continuam acessíveis
- Novas imagens usarão o storage configurado

