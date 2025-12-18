#!/usr/bin/env bash

# 🚀 Script de Deploy - Block Editor YMNK
# Uso: bash deploy-assets.sh

echo "🔄 Republicando assets do Block Editor..."
echo ""

# 1. Republicar assets JS/CSS
echo "📦 [1/4] Publicando assets JavaScript e CSS..."
php artisan vendor:publish --tag=block-editor-assets --force

if [ $? -eq 0 ]; then
    echo "✅ Assets publicados com sucesso!"
else
    echo "❌ Erro ao publicar assets!"
    exit 1
fi

echo ""

# 2. Limpar cache de views
echo "🧹 [2/4] Limpando cache de views..."
php artisan view:clear

if [ $? -eq 0 ]; then
    echo "✅ Cache de views limpo!"
else
    echo "⚠️ Aviso: Não foi possível limpar cache de views"
fi

echo ""

# 3. Limpar cache de config
echo "🧹 [3/4] Limpando cache de configuração..."
php artisan config:clear

if [ $? -eq 0 ]; then
    echo "✅ Cache de config limpo!"
else
    echo "⚠️ Aviso: Não foi possível limpar cache de config"
fi

echo ""

# 4. Verificar estrutura
echo "🔍 [4/4] Verificando estrutura publicada..."
echo ""

if [ -d "public/vendor/block-editor/js/modules" ]; then
    echo "✅ Pasta modules/ criada"
    
    # Conta arquivos na pasta modules
    MODULE_COUNT=$(find public/vendor/block-editor/js/modules -name "*.js" | wc -l)
    echo "   └─ Encontrados $MODULE_COUNT módulos"
    
    if [ $MODULE_COUNT -eq 6 ]; then
        echo "   └─ ✅ Todos os 6 módulos presentes!"
    else
        echo "   └─ ⚠️ Esperados 6 módulos, encontrados $MODULE_COUNT"
    fi
else
    echo "❌ Pasta modules/ não encontrada!"
    exit 1
fi

if [ -f "public/vendor/block-editor/js/BlockEditorCore.js" ]; then
    CORE_LINES=$(wc -l < public/vendor/block-editor/js/BlockEditorCore.js)
    echo "✅ BlockEditorCore.js presente ($CORE_LINES linhas)"
    
    if [ $CORE_LINES -lt 400 ] && [ $CORE_LINES -gt 300 ]; then
        echo "   └─ ✅ Tamanho correto (versão modular)"
    else
        echo "   └─ ⚠️ Tamanho inesperado (esperado ~350 linhas)"
    fi
else
    echo "❌ BlockEditorCore.js não encontrado!"
    exit 1
fi

if [ -f "public/vendor/block-editor/js/BlockEditorCore-old.js" ]; then
    echo "✅ Backup (BlockEditorCore-old.js) presente"
else
    echo "⚠️ Backup não encontrado (não crítico)"
fi

if [ -f "public/vendor/block-editor/js/block-editor.js" ]; then
    echo "⚠️ Arquivo obsoleto detectado: block-editor.js"
    echo "   Recomendação: remover manualmente"
else
    echo "✅ Arquivo obsoleto (block-editor.js) não presente"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🎉 Deploy concluído com sucesso!"
echo ""
echo "📋 Próximos passos:"
echo "1. Abrir navegador e testar editor de lessons"
echo "2. Verificar console: '✅ Block Editor iniciado (versão modular)'"
echo "3. Testar inserção dos 11 tipos de blocos"
echo "4. Testar formatação, drag & drop, e salvamento"
echo ""
echo "📚 Documentação:"
echo "• README-MODULAR.md - Arquitetura"
echo "• ARCHITECTURE.md - Diagramas"
echo "• DEPLOY.md - Troubleshooting"
echo "• REFACTORING-SUMMARY.md - Resumo das mudanças"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
