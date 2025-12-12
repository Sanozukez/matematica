# Manual do Criador de Conteúdo

## 📚 Plataforma Matemática - Guia de Criação de Lições

---

## 🎯 Visão Geral

Este manual explica como criar e organizar conteúdo educacional na plataforma.

### Hierarquia do Conteúdo

```
📁 Curso (ex: "Matemática Básica")
   └── 📂 Módulo (ex: "Números Naturais")
       └── 📄 Lição (ex: "O que são números?")
```

---

## 🏗️ Criando um Curso

1. Acesse **Conteúdo → Cursos**
2. Clique em **Criar Curso**
3. Preencha:
   - **Título**: Nome do curso
   - **Slug**: URL amigável (gerado automaticamente)
   - **Descrição**: Apresentação do curso
   - **Nível**: Básico, Fundamental, Médio ou Avançado
   - **Ícone**: Emoji representativo (ex: 🧮)
   - **Gamificado**: Marque se terá mini jogos

---

## 📦 Criando um Módulo

1. Acesse **Conteúdo → Módulos** ou edite um curso
2. Clique em **Criar Módulo**
3. Vincule ao **Curso** correspondente
4. Preencha título, descrição e ordem

---

## 📝 Criando uma Lição

### Tipos de Lição

| Tipo | Uso | Exemplo |
|------|-----|---------|
| **Texto** | Conteúdo escrito com imagens e fórmulas | Explicação de conceitos |
| **Vídeo** | Aula em vídeo | Video-aula no YouTube |
| **Quiz** | Perguntas de múltipla escolha | Avaliação de conhecimento |
| **Mini Jogo** | Atividade interativa gamificada | Jogo de contagem |

---

## ✍️ Editor de Texto (Editor.js)

O editor de texto usa **blocos** que você pode adicionar, reordenar e editar.

### Como Usar

1. Clique no **+** à esquerda para adicionar um bloco
2. Escolha o tipo de bloco
3. Digite o conteúdo
4. Arraste para reordenar

### Blocos Disponíveis

#### 📄 Texto
Parágrafo normal. Use a barra de formatação para:
- **Negrito** (Ctrl+B)
- *Itálico* (Ctrl+I)
- [Links](url)
- `código inline`

#### 📌 Título
Cria títulos de seção (H2, H3, H4).

#### 📋 Lista
Lista com marcadores ou numerada.

#### 🖼️ Imagem
- Clique em "Selecionar imagem" para upload
- Ou cole uma URL de imagem
- Adicione legenda

#### 📐 Fórmula Matemática (LaTeX)
Escreva fórmulas usando sintaxe LaTeX.

**Exemplos comuns:**

| Resultado | LaTeX |
|-----------|-------|
| Fração | `\frac{a}{b}` |
| Raiz quadrada | `\sqrt{x}` |
| Potência | `x^2` ou `x^{10}` |
| Índice | `x_1` ou `x_{n}` |
| Soma | `\sum_{i=1}^{n}` |
| Integral | `\int_{a}^{b}` |
| Pi | `\pi` |
| Delta | `\Delta` |
| Infinito | `\infty` |
| Diferente | `\neq` |
| Menor/igual | `\leq` |
| Maior/igual | `\geq` |
| Multiplicação | `\times` ou `\cdot` |
| Divisão | `\div` |

**Equação de 2º grau:**
```latex
x = \frac{-b \pm \sqrt{b^2-4ac}}{2a}
```

**Teorema de Pitágoras:**
```latex
a^2 + b^2 = c^2
```

**Fração mista:**
```latex
1\frac{1}{2} = \frac{3}{2}
```

#### 💻 Código
Bloco para código de programação.

#### ⚠️ Aviso
Destaque para informações importantes.

#### 💬 Citação
Citações de autores ou referências.

#### ➖ Separador
Linha horizontal para dividir seções.

#### 📊 Tabela
Tabela com linhas e colunas.

---

## 🎬 Lições em Vídeo

### Provedores Suportados

- **YouTube**: Cole a URL do vídeo (ex: `https://youtube.com/watch?v=XXXXX`)
- **Bunny Stream**: Cole a URL do player Bunny
- **Vimeo**: Cole a URL do Vimeo
- **URL Direta**: Link direto para arquivo .mp4

### Dicas para Vídeos

1. Use vídeos curtos (5-15 min)
2. Adicione timestamps importantes na descrição
3. Mencione pontos-chave na descrição

---

## ❓ Lições de Quiz

### Criando Perguntas

1. Clique em **Adicionar Pergunta**
2. Digite a pergunta
3. Adicione opções de resposta (2-5)
4. Marque a(s) correta(s)
5. Adicione explicação (opcional mas recomendado)

### Boas Práticas

- Escreva perguntas claras e objetivas
- Evite pegadinhas
- A explicação deve ensinar, não apenas dizer "correto/incorreto"
- Varie a posição da resposta correta

---

## 🎮 Mini Jogos

### Tipos Disponíveis

| Tipo | Descrição |
|------|-----------|
| 🔢 Contagem | Contar objetos |
| 🎯 Associação | Conectar itens correspondentes |
| 📊 Ordenação | Colocar em ordem |
| 🧩 Quebra-cabeça | Montar peças |
| 🧠 Memória | Encontrar pares |
| ✋ Arrastar e Soltar | Organizar elementos |

### Configurações

- **Dificuldade**: Fácil, Médio, Difícil
- **Tempo Limite**: Opcional
- **Pontos**: Quanto o aluno ganha ao completar

---

## 🏆 Sistema de Badges

Badges são conquistas que o aluno ganha ao completar módulos.

### Criando uma Badge

1. Acesse **Gamificação → Badges**
2. Crie a badge com nome, ícone e pontos
3. Associe a um módulo (opcional)
4. Configure pré-requisitos (outras badges necessárias)

### Skill Tree

As badges formam uma árvore de habilidades onde:
- Algumas badges desbloqueiam outras
- O aluno visualiza seu progresso
- Motiva a conclusão de módulos

---

## 💡 Dicas Gerais

### Organização

- Mantenha lições curtas e focadas
- Use ordem lógica nos módulos
- Comece do básico e avance gradualmente

### Engajamento

- Alterne entre tipos de lição (texto, vídeo, quiz)
- Use imagens e fórmulas para ilustrar
- Inclua quizzes para fixação

### Acessibilidade

- Use linguagem clara
- Adicione legendas em imagens
- Estruture bem os títulos

---

## 🆘 Suporte

Dúvidas? Entre em contato com o administrador da plataforma.

