# Editor de Lições (Fullscreen) — Plano de Excelência

## Objetivo
Construir o melhor criador de lições do mercado, inspirado no WordPress Gutenberg, com foco em usabilidade, velocidade e extensibilidade, adequado para uma plataforma estilo Udemy e futura oferta SaaS.

## Diretrizes Estratégicas
- Experiência fullscreen, limpa e focada
- Blocos modulares (SRP) e altamente extensíveis
- Um único editor para texto+vídeo+recursos (game separado)
- Performance com autosave, atalhos e preview
- Acessível, internacionalizável e pronto para colaboração futura

## Roadmap por Fases

### Fase 1 — Fundamentos de UX e Conteúdo (2 semanas)
- Barra superior: título, status, botões Salvar/Preview, atalhos (Ctrl+S)
- Sidebar direita: propriedades da lição (tipo, ordem, duração, visibilidade)
- Paleta de blocos (com busca, categorias, favoritos)
- Autosave incremental + indicador de estado
- Preview em nova aba com renderização idêntica
- Undo/Redo consistente (histórico local)

### Fase 2 — Blocos Essenciais e Mídia (2-3 semanas)
- Texto (TipTap), Títulos, Lista, Citação, Divisor
- Imagem (upload, alt, legenda, alinhamento, editor básico)
- Vídeo (YouTube/Vimeo/Bunny), tempo inicial, legendas
- Tabela (heads/rows com UX simplificada)
- Código (linguagens e copy-to-clipboard)
- LaTeX (KaTeX) com validação e preview inline

### Fase 3 — Produtividade e Templates (2 semanas)
- Templates de bloco e de página (ex.: “Aula teórica”, “Aula prática”)
- Blocos favoritos e duplicação rápida
- Snippets rápidas (ex.: “nota de atenção”, “exercício”)—com atalhos
- Multi-select de blocos e ações em lote (mover, deletar, agrupar)
- Melhorias de arrastar/soltar (indicadores e auto-scroll)

### Fase 4 — Conteúdos Avançados (2-3 semanas)
- Quiz inline (pergunta, opções, explicação)
- Galeria de imagens e carrossel
- Embed diversos (maps, iframes controlados, links enriquecidos)
- Anotações do professor (metadados não visíveis ao aluno)

### Fase 5 — Escalabilidade e SaaS (3+ semanas)
- Versionamento de conteúdo e comparação (diff de blocos)
- Colaboração em tempo real (posterior, via Yjs/TipTap collab)
- Biblioteca de blocos compartilhados por curso/organização
- Permissões granulares e auditoria
- Observabilidade: métricas de uso do editor e blocos

## Princípios de Design
- SRP rigoroso: cada bloco em sua classe, sem lógica cruzada
- Configuração via BlockRegistry e perfis (conjuntos de blocos por contexto)
- Componentização da renderização (Blade por tipo)
- Acessibilidade: foco, contraste, navegação por teclado
- Performance: carregamento preguiçoso de plugins pesados

## Perfis de Editor
- lesson-basic: texto, títulos, imagem, vídeo, citação, lista, divisor
- lesson-advanced: + tabela, código, LaTeX, quiz inline, galeria
- course-announcement: texto, título, imagem, embed

## Usabilidade Premium
- Comandos (Cmd/Ctrl+K) para “inserir bloco” e “buscar ação”
- Tooltips com atalhos, dicas contextualizadas
- Guia interativo para novos usuários
- Preview responsivo (desktop/tablet/mobile)

## Renderização no Frontend
- Componente `x-lesson-block` por tipo com Blade dedicado
- Estilos consistentes com `prose` (Tailwind Typography)
- Renderer para perfis (ex.: minimal vs acadêmico)

## Qualidade e Padrões
- Testes unitários por bloco e serviços
- Linters e formatadores (PHP CS Fixer / Pint)
- Checklist de revisão de UX por release
- Documentação viva no repositório (docs/*)

## Métricas
- Tempo médio de criação/edição
- Erros por sessão e resgates via autosave
- Blocos mais usados e abandono de fluxos

## Conclusão
Com foco em SRP, UX limpa e blocos modulares, o editor fullscreen será mais intuitivo e potente que concorrentes, pronto para evoluir a um produto SaaS robusto.
