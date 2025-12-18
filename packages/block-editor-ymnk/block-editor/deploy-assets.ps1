# 🚀 Script de Deploy - Block Editor YMNK (Windows PowerShell)
# Uso: .\deploy-assets.ps1

Write-Host "🔄 Republicando assets do Block Editor..." -ForegroundColor Cyan
Write-Host ""

# 1. Republicar assets JS/CSS
Write-Host "📦 [1/4] Publicando assets JavaScript e CSS..." -ForegroundColor Yellow
php artisan vendor:publish --tag=block-editor-assets --force

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Assets publicados com sucesso!" -ForegroundColor Green
} else {
    Write-Host "❌ Erro ao publicar assets!" -ForegroundColor Red
    exit 1
}

Write-Host ""

# 2. Limpar cache de views
Write-Host "🧹 [2/4] Limpando cache de views..." -ForegroundColor Yellow
php artisan view:clear

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Cache de views limpo!" -ForegroundColor Green
} else {
    Write-Host "⚠️ Aviso: Não foi possível limpar cache de views" -ForegroundColor Yellow
}

Write-Host ""

# 3. Limpar cache de config
Write-Host "🧹 [3/4] Limpando cache de configuração..." -ForegroundColor Yellow
php artisan config:clear

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Cache de config limpo!" -ForegroundColor Green
} else {
    Write-Host "⚠️ Aviso: Não foi possível limpar cache de config" -ForegroundColor Yellow
}

Write-Host ""

# 4. Verificar estrutura
Write-Host "🔍 [4/4] Verificando estrutura publicada..." -ForegroundColor Yellow
Write-Host ""

$modulesPath = "public\vendor\block-editor\js\modules"
if (Test-Path $modulesPath) {
    Write-Host "✅ Pasta modules/ criada" -ForegroundColor Green
    
    # Conta arquivos na pasta modules
    $moduleCount = (Get-ChildItem -Path $modulesPath -Filter "*.js" | Measure-Object).Count
    Write-Host "   └─ Encontrados $moduleCount módulos"
    
    if ($moduleCount -eq 6) {
        Write-Host "   └─ ✅ Todos os 6 módulos presentes!" -ForegroundColor Green
    } else {
        Write-Host "   └─ ⚠️ Esperados 6 módulos, encontrados $moduleCount" -ForegroundColor Yellow
    }
} else {
    Write-Host "❌ Pasta modules/ não encontrada!" -ForegroundColor Red
    exit 1
}

$corePath = "public\vendor\block-editor\js\BlockEditorCore.js"
if (Test-Path $corePath) {
    $coreLines = (Get-Content $corePath | Measure-Object -Line).Lines
    Write-Host "✅ BlockEditorCore.js presente ($coreLines linhas)" -ForegroundColor Green
    
    if ($coreLines -lt 400 -and $coreLines -gt 300) {
        Write-Host "   └─ ✅ Tamanho correto (versão modular)" -ForegroundColor Green
    } else {
        Write-Host "   └─ ⚠️ Tamanho inesperado (esperado ~350 linhas)" -ForegroundColor Yellow
    }
} else {
    Write-Host "❌ BlockEditorCore.js não encontrado!" -ForegroundColor Red
    exit 1
}

$backupPath = "public\vendor\block-editor\js\BlockEditorCore-old.js"
if (Test-Path $backupPath) {
    Write-Host "✅ Backup (BlockEditorCore-old.js) presente" -ForegroundColor Green
} else {
    Write-Host "⚠️ Backup não encontrado (não crítico)" -ForegroundColor Yellow
}

$obsoletePath = "public\vendor\block-editor\js\block-editor.js"
if (Test-Path $obsoletePath) {
    Write-Host "⚠️ Arquivo obsoleto detectado: block-editor.js" -ForegroundColor Yellow
    Write-Host "   Recomendação: remover manualmente"
} else {
    Write-Host "✅ Arquivo obsoleto (block-editor.js) não presente" -ForegroundColor Green
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "🎉 Deploy concluído com sucesso!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Próximos passos:" -ForegroundColor Cyan
Write-Host "1. Abrir navegador e testar editor de lessons"
Write-Host "2. Verificar console: '✅ Block Editor iniciado (versão modular)'"
Write-Host "3. Testar inserção dos 11 tipos de blocos"
Write-Host "4. Testar formatação, drag & drop, e salvamento"
Write-Host ""
Write-Host "📚 Documentação:" -ForegroundColor Cyan
Write-Host "• README-MODULAR.md - Arquitetura"
Write-Host "• ARCHITECTURE.md - Diagramas"
Write-Host "• DEPLOY.md - Troubleshooting"
Write-Host "• REFACTORING-SUMMARY.md - Resumo das mudanças"
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
