// plataforma/resources/js/Components/Editor/MathTool.js

/**
 * Plugin de Fórmulas Matemáticas para Editor.js
 * 
 * Permite inserir e editar fórmulas LaTeX
 * Renderiza usando KaTeX para melhor performance
 */

import katex from 'katex';

export default class MathTool {
    /**
     * Configuração do plugin
     */
    static get toolbox() {
        return {
            title: 'Fórmula',
            icon: '<svg width="17" height="15" viewBox="0 0 17 15" xmlns="http://www.w3.org/2000/svg"><text x="0" y="12" font-size="14" font-weight="bold">∑</text></svg>',
        };
    }

    /**
     * Construtor
     */
    constructor({ data, api, config, readOnly }) {
        this.api = api;
        this.readOnly = readOnly;
        this.data = {
            latex: data.latex || '',
            displayMode: data.displayMode !== undefined ? data.displayMode : true,
        };

        this.wrapper = null;
        this.input = null;
        this.preview = null;
    }

    /**
     * Renderiza o bloco
     */
    render() {
        this.wrapper = document.createElement('div');
        this.wrapper.classList.add('math-tool');

        if (this.readOnly) {
            this._renderPreview();
        } else {
            this._renderEditor();
        }

        return this.wrapper;
    }

    /**
     * Renderiza modo de edição
     */
    _renderEditor() {
        // Container do input
        const inputContainer = document.createElement('div');
        inputContainer.classList.add('math-tool__input-container');

        // Label
        const label = document.createElement('label');
        label.textContent = 'Fórmula LaTeX:';
        label.classList.add('math-tool__label');

        // Input
        this.input = document.createElement('textarea');
        this.input.classList.add('math-tool__input');
        this.input.placeholder = 'Ex: \\frac{-b \\pm \\sqrt{b^2-4ac}}{2a}';
        this.input.value = this.data.latex;
        this.input.rows = 3;

        // Checkbox display mode
        const checkboxContainer = document.createElement('div');
        checkboxContainer.classList.add('math-tool__checkbox-container');

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = 'displayMode-' + Math.random().toString(36).substr(2, 9);
        checkbox.checked = this.data.displayMode;
        checkbox.addEventListener('change', () => {
            this.data.displayMode = checkbox.checked;
            this._updatePreview();
        });

        const checkboxLabel = document.createElement('label');
        checkboxLabel.htmlFor = checkbox.id;
        checkboxLabel.textContent = 'Exibir em bloco (centralizado)';

        checkboxContainer.appendChild(checkbox);
        checkboxContainer.appendChild(checkboxLabel);

        // Preview
        this.preview = document.createElement('div');
        this.preview.classList.add('math-tool__preview');

        // Botão de ajuda
        const helpButton = document.createElement('button');
        helpButton.type = 'button';
        helpButton.classList.add('math-tool__help');
        helpButton.textContent = '? Ajuda LaTeX';
        helpButton.addEventListener('click', () => this._showHelp());

        // Event listeners
        this.input.addEventListener('input', () => {
            this.data.latex = this.input.value;
            this._updatePreview();
        });

        // Monta o DOM
        inputContainer.appendChild(label);
        inputContainer.appendChild(this.input);
        inputContainer.appendChild(checkboxContainer);
        inputContainer.appendChild(helpButton);
        
        this.wrapper.appendChild(inputContainer);
        this.wrapper.appendChild(this.preview);

        // Renderiza preview inicial
        this._updatePreview();
    }

    /**
     * Renderiza modo somente leitura
     */
    _renderPreview() {
        this.preview = document.createElement('div');
        this.preview.classList.add('math-tool__preview', 'math-tool__preview--readonly');
        this.wrapper.appendChild(this.preview);
        this._updatePreview();
    }

    /**
     * Atualiza o preview da fórmula
     */
    _updatePreview() {
        if (!this.preview) return;

        if (!this.data.latex.trim()) {
            this.preview.innerHTML = '<span class="math-tool__placeholder">A fórmula aparecerá aqui...</span>';
            return;
        }

        try {
            this.preview.innerHTML = katex.renderToString(this.data.latex, {
                displayMode: this.data.displayMode,
                throwOnError: false,
                errorColor: '#ef4444',
            });
            this.preview.classList.remove('math-tool__preview--error');
        } catch (error) {
            this.preview.innerHTML = `<span class="math-tool__error">Erro: ${error.message}</span>`;
            this.preview.classList.add('math-tool__preview--error');
        }
    }

    /**
     * Mostra ajuda de LaTeX
     */
    _showHelp() {
        const helpContent = `
            <div class="math-help">
                <h3>Exemplos de LaTeX</h3>
                <table>
                    <tr><td>Fração:</td><td>\\frac{a}{b}</td></tr>
                    <tr><td>Raiz quadrada:</td><td>\\sqrt{x}</td></tr>
                    <tr><td>Potência:</td><td>x^2 ou x^{10}</td></tr>
                    <tr><td>Índice:</td><td>x_1 ou x_{n}</td></tr>
                    <tr><td>Soma:</td><td>\\sum_{i=1}^{n}</td></tr>
                    <tr><td>Integral:</td><td>\\int_{a}^{b}</td></tr>
                    <tr><td>Pi:</td><td>\\pi</td></tr>
                    <tr><td>Delta:</td><td>\\Delta</td></tr>
                    <tr><td>Infinito:</td><td>\\infty</td></tr>
                    <tr><td>Diferente:</td><td>\\neq</td></tr>
                    <tr><td>Menor igual:</td><td>\\leq</td></tr>
                    <tr><td>Maior igual:</td><td>\\geq</td></tr>
                </table>
                <p><strong>Equação de 2º grau:</strong></p>
                <code>x = \\frac{-b \\pm \\sqrt{b^2-4ac}}{2a}</code>
            </div>
        `;

        // Cria modal simples
        const modal = document.createElement('div');
        modal.classList.add('math-help-modal');
        modal.innerHTML = `
            <div class="math-help-modal__content">
                ${helpContent}
                <button type="button" class="math-help-modal__close">Fechar</button>
            </div>
        `;

        modal.querySelector('.math-help-modal__close').addEventListener('click', () => {
            modal.remove();
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.remove();
        });

        document.body.appendChild(modal);
    }

    /**
     * Salva os dados do bloco
     */
    save() {
        return {
            latex: this.data.latex,
            displayMode: this.data.displayMode,
        };
    }

    /**
     * Valida os dados
     */
    validate(savedData) {
        return savedData.latex.trim() !== '';
    }
}

