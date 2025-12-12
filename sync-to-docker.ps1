#!/usr/bin/env pwsh

# Script para copiar arquivos do projeto para o volume Docker
# Uso: .\sync-to-docker.ps1

$projectDir = "m:\Matematica\plataforma"
$volumeName = "plataforma_sail-app"

Write-Host "Sincronizando arquivos para volume Docker..." -ForegroundColor Green

# Obtém o caminho do volume
$volumePath = docker volume inspect $volumeName --format='{{.Mountpoint}}'

if (-not $volumePath) {
    Write-Host "Erro: Volume '$volumeName' não encontrado" -ForegroundColor Red
    exit 1
}

Write-Host "Volume montado em: $volumePath" -ForegroundColor Yellow

# Copia os arquivos (excluindo diretórios específicos)
$excludeDirs = @('node_modules', 'vendor', '.git', 'docker-data', '.docker')

Write-Host "Copiando arquivos..." -ForegroundColor Cyan

# Usa robocopy para copiar eficientemente
robocopy $projectDir "$volumePath" /S /XD $excludeDirs /XF "docker-compose*" "Dockerfile*" /MT:16 /R:1 /W:1

Write-Host "Sincronização concluída!" -ForegroundColor Green

# Instala dependências
Write-Host "`nInstalando dependências..." -ForegroundColor Cyan
docker compose -f "$projectDir\docker-compose.dev.yml" exec -T laravel.test composer install
docker compose -f "$projectDir\docker-compose.dev.yml" exec -T laravel.test npm install
docker compose -f "$projectDir\docker-compose.dev.yml" exec -T laravel.test npm run build

Write-Host "Pronto! Aplicação disponível em http://localhost:8005" -ForegroundColor Green
