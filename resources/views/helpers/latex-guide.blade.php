<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guia de LaTeX - Matemática</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section h2 {
            color: #667eea;
            font-size: 1.8rem;
            margin-bottom: 20px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        
        .section h3 {
            color: #764ba2;
            font-size: 1.3rem;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        
        .example {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            overflow-x: auto;
        }
        
        .code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            margin: 15px 0;
            overflow-x: auto;
        }
        
        .code-label {
            color: #667eea;
            font-weight: bold;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .result {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        
        .card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }
        
        .card h4 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #667eea;
            color: white;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .tip {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        
        .tip strong {
            color: #ff9800;
        }
        
        .warning {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        
        .warning strong {
            color: #f44336;
        }
        
        footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ddd;
        }
        
        a {
            color: #667eea;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        .btn-close {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .btn-close:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.6);
        }

        @media (max-width: 768px) {
            header h1 {
                font-size: 1.8rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .btn-close {
                padding: 10px 16px;
                font-size: 0.9rem;
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📐 Guia LaTeX para Matemática</h1>
            <p>Aprenda a escrever fórmulas matemáticas profissionais</p>
        </header>
        
        <div class="content">
            <!-- INTRODUÇÃO -->
            <div class="section">
                <h2>O que é LaTeX?</h2>
                <p>LaTeX é um sistema de typografia profissional especialmente poderoso para escrever fórmulas matemáticas. É o padrão internacional em documentos científicos e acadêmicos.</p>
                <p style="margin-top: 15px;">Neste editor, você pode usar LaTeX para renderizar fórmulas matemáticas belas e profissionais.</p>
            </div>
            
            <!-- OPERAÇÕES BÁSICAS -->
            <div class="section">
                <h2>Operações Básicas</h2>
                
                <h3>Adição e Subtração</h3>
                <div class="grid">
                    <div>
                        <div class="code-label">Código:</div>
                        <div class="code">2 + 3 - 1</div>
                    </div>
                    <div>
                        <div class="code-label">Resultado:</div>
                        <div class="result">2 + 3 - 1 = 4</div>
                    </div>
                </div>
                
                <h3>Multiplicação e Divisão</h3>
                <div class="grid">
                    <div>
                        <div class="code-label">Multiplicação:</div>
                        <div class="code">2 \times 3 = 6</div>
                    </div>
                    <div>
                        <div class="code-label">Divisão:</div>
                        <div class="code">\frac{10}{2} = 5</div>
                    </div>
                </div>
            </div>
            
            <!-- FRAÇÕES -->
            <div class="section">
                <h2>Frações</h2>
                <p>Use <code>\frac{numerador}{denominador}</code> para criar frações:</p>
                
                <div class="grid">
                    <div>
                        <div class="code-label">Simples:</div>
                        <div class="code">\frac{1}{2}</div>
                    </div>
                    <div>
                        <div class="code-label">Complexa:</div>
                        <div class="code">\frac{x^2 + 1}{x - 1}</div>
                    </div>
                </div>
            </div>
            
            <!-- POTÊNCIAS E RAÍZES -->
            <div class="section">
                <h2>Potências e Raízes</h2>
                
                <h3>Potências (Expoentes)</h3>
                <div class="grid">
                    <div>
                        <div class="code-label">Exemplo:</div>
                        <div class="code">x^2 + y^3</div>
                    </div>
                    <div>
                        <div class="code-label">Potência múltipla:</div>
                        <div class="code">x^{2n+1}</div>
                    </div>
                </div>
                
                <h3>Raízes Quadradas e Cúbicas</h3>
                <div class="grid">
                    <div>
                        <div class="code-label">Raiz quadrada:</div>
                        <div class="code">\sqrt{9} = 3</div>
                    </div>
                    <div>
                        <div class="code-label">Raiz cúbica:</div>
                        <div class="code">\sqrt[3]{8} = 2</div>
                    </div>
                </div>
            </div>
            
            <!-- SUBSCRITOS -->
            <div class="section">
                <h2>Subscritos (Índices)</h2>
                <p>Use <code>_</code> (underscore) para subscritos:</p>
                
                <div class="grid">
                    <div>
                        <div class="code-label">Simples:</div>
                        <div class="code">x_1 + x_2</div>
                    </div>
                    <div>
                        <div class="code-label">Múltiplos caracteres:</div>
                        <div class="code">a_{ij}</div>
                    </div>
                </div>
            </div>
            
            <!-- LETRAS GREGAS -->
            <div class="section">
                <h2>Letras Gregas</h2>
                <p>As letras gregas são muito usadas em matemática:</p>
                
                <table>
                    <tr>
                        <th>Letra</th>
                        <th>Código</th>
                        <th>Letra</th>
                        <th>Código</th>
                    </tr>
                    <tr>
                        <td>α (alfa)</td>
                        <td><code>\alpha</code></td>
                        <td>β (beta)</td>
                        <td><code>\beta</code></td>
                    </tr>
                    <tr>
                        <td>γ (gama)</td>
                        <td><code>\gamma</code></td>
                        <td>δ (delta)</td>
                        <td><code>\delta</code></td>
                    </tr>
                    <tr>
                        <td>π (pi)</td>
                        <td><code>\pi</code></td>
                        <td>μ (mi)</td>
                        <td><code>\mu</code></td>
                    </tr>
                    <tr>
                        <td>σ (sigma)</td>
                        <td><code>\sigma</code></td>
                        <td>ω (ômega)</td>
                        <td><code>\omega</code></td>
                    </tr>
                </table>
            </div>
            
            <!-- FUNÇÕES TRIGONOMÉTRICAS -->
            <div class="section">
                <h2>Funções Trigonométricas</h2>
                
                <div class="grid">
                    <div>
                        <div class="code-label">Seno:</div>
                        <div class="code">\sin(x)</div>
                    </div>
                    <div>
                        <div class="code-label">Cosseno:</div>
                        <div class="code">\cos(\theta)</div>
                    </div>
                </div>
                
                <div class="grid">
                    <div>
                        <div class="code-label">Tangente:</div>
                        <div class="code">\tan(x)</div>
                    </div>
                    <div>
                        <div class="code-label">Logaritmo:</div>
                        <div class="code">\log(x)</div>
                    </div>
                </div>
            </div>
            
            <!-- SOMATÓRIOS E PRODUTOS -->
            <div class="section">
                <h2>Somatórios e Produtos</h2>
                
                <h3>Somatório (Σ)</h3>
                <div class="grid">
                    <div>
                        <div class="code-label">Simples:</div>
                        <div class="code">\sum x_i</div>
                    </div>
                    <div>
                        <div class="code-label">Com limites:</div>
                        <div class="code">\sum_{i=1}^{n} x_i</div>
                    </div>
                </div>
                
                <h3>Produtório (Π)</h3>
                <div class="grid">
                    <div>
                        <div class="code-label">Simples:</div>
                        <div class="code">\prod x_i</div>
                    </div>
                    <div>
                        <div class="code-label">Com limites:</div>
                        <div class="code">\prod_{i=1}^{n} x_i</div>
                    </div>
                </div>
            </div>
            
            <!-- INTEGRAIS -->
            <div class="section">
                <h2>Integrais</h2>
                
                <div class="grid">
                    <div>
                        <div class="code-label">Integral indefinida:</div>
                        <div class="code">\int x^2 \, dx</div>
                    </div>
                    <div>
                        <div class="code-label">Integral definida:</div>
                        <div class="code">\int_0^1 x^2 \, dx</div>
                    </div>
                </div>
            </div>
            
            <!-- MATRIZES -->
            <div class="section">
                <h2>Matrizes</h2>
                
                <div class="code-label">Matriz 2x2:</div>
                <div class="code">
\begin{pmatrix}
  a & b \\
  c & d
\end{pmatrix}
                </div>
                
                <div class="tip">
                    <strong>💡 Dica:</strong> Use <code>pmatrix</code> para parênteses, <code>bmatrix</code> para colchetes, <code>vmatrix</code> para barras (determinante).
                </div>
            </div>
            
            <!-- EQUAÇÕES LINEARES -->
            <div class="section">
                <h2>Sistema de Equações</h2>
                
                <div class="code-label">Exemplo:</div>
                <div class="code">
\begin{cases}
  x + y = 5 \\
  2x - y = 4
\end{cases}
                </div>
            </div>
            
            <!-- DICAS E BOAS PRÁTICAS -->
            <div class="section">
                <h2>Dicas e Boas Práticas</h2>
                
                <div class="tip">
                    <strong>💡 Use chaves { } para agrupamento:</strong>
                    <p>Quando você tem múltiplos caracteres em expoentes ou subscritos, sempre use chaves: <code>x^{10}</code> em vez de <code>x^10</code></p>
                </div>
                
                <div class="tip">
                    <strong>💡 Espaçamento em modo matemático:</strong>
                    <p>Use <code>\,</code> para pequenos espaços e <code>\quad</code> para espaços maiores.</p>
                </div>
                
                <div class="warning">
                    <strong>⚠️ Cuidado com caracteres especiais:</strong>
                    <p>Caracteres como <code>$, %, &, #, _, {, }</code> têm significado especial. Se precisa usá-los literalmente, use barra invertida: <code>\$, \%, \&</code></p>
                </div>
                
                <div class="tip">
                    <strong>💡 Alinhamento de múltiplas linhas:</strong>
                    <p>Use <code>align*</code> para alinhar várias equações pela igualdade:</p>
                    <div class="code">
\begin{align*}
  y &= mx + b \\
  y &= 2x + 3
\end{align*}
                    </div>
                </div>
            </div>
            
            <!-- SÍMBOLOS COMUNS -->
            <div class="section">
                <h2>Símbolos Comuns</h2>
                
                <table>
                    <tr>
                        <th>Símbolo</th>
                        <th>Código</th>
                        <th>Símbolo</th>
                        <th>Código</th>
                    </tr>
                    <tr>
                        <td>≠</td>
                        <td><code>\neq</code></td>
                        <td>≤</td>
                        <td><code>\leq</code></td>
                    </tr>
                    <tr>
                        <td>≥</td>
                        <td><code>\geq</code></td>
                        <td>±</td>
                        <td><code>\pm</code></td>
                    </tr>
                    <tr>
                        <td>÷</td>
                        <td><code>\div</code></td>
                        <td>×</td>
                        <td><code>\times</code></td>
                    </tr>
                    <tr>
                        <td>∞</td>
                        <td><code>\infty</code></td>
                        <td>≈</td>
                        <td><code>\approx</code></td>
                    </tr>
                    <tr>
                        <td>∈</td>
                        <td><code>\in</code></td>
                        <td>∉</td>
                        <td><code>\notin</code></td>
                    </tr>
                </table>
            </div>
            
            <!-- REFERÊNCIAS -->
            <div class="section">
                <h2>Referências Úteis</h2>
                <ul style="margin-left: 20px; line-height: 2;">
                    <li><a href="https://www.overleaf.com/learn/latex" target="_blank">📚 Overleaf LaTeX Documentation</a> - Documentação completa e profissional</li>
                    <li><a href="https://en.wikibooks.org/wiki/LaTeX/Mathematics" target="_blank">📖 WikiBooks - LaTeX Mathematics</a> - Guia detalhado sobre matemática em LaTeX</li>
                    <li><a href="https://www.overleaf.com/learn/latex/Mathematical_expressions" target="_blank">🧮 Mathematical Expressions</a> - Expressões matemáticas</li>
                </ul>
            </div>
        </div>
        
        <footer>
            <p>Guia LaTeX para Matemática • Criado para auxiliar no aprendizado de fórmulas profissionais</p>
        </footer>
    </div>
    
    <button class="btn-close" onclick="window.close()">← Voltar</button>
</body>
</html>
