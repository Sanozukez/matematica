 <script setup>
/**
 * Componente Editor.js
 * 
 * Editor de blocos para criação de conteúdo de lições
 * Suporta: texto, imagens, código, fórmulas LaTeX, tabelas, etc.
 */
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import ImageTool from '@editorjs/image';
import CodeTool from '@editorjs/code';
import Quote from '@editorjs/quote';
import Warning from '@editorjs/warning';
import Delimiter from '@editorjs/delimiter';
import Table from '@editorjs/table';
import InlineCode from '@editorjs/inline-code';
import MathTool from './MathTool.js';

const props = defineProps({
    /** Dados iniciais do editor (JSON) */
    modelValue: {
        type: [Object, String],
        default: null,
    },
    /** ID único do editor */
    editorId: {
        type: String,
        default: 'editorjs',
    },
    /** Placeholder quando vazio */
    placeholder: {
        type: String,
        default: 'Comece a escrever sua lição...',
    },
    /** Se o editor está desabilitado */
    disabled: {
        type: Boolean,
        default: false,
    },
    /** URL para upload de imagens */
    uploadUrl: {
        type: String,
        default: '/api/admin/upload/image',
    },
    /** CSRF Token */
    csrfToken: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

const editorRef = ref(null);
let editor = null;

/**
 * Configuração das ferramentas do Editor.js
 */
const getTools = () => ({
    header: {
        class: Header,
        config: {
            placeholder: 'Título da seção',
            levels: [2, 3, 4],
            defaultLevel: 2,
        },
        inlineToolbar: true,
    },
    list: {
        class: List,
        inlineToolbar: true,
        config: {
            defaultStyle: 'unordered',
        },
    },
    image: {
        class: ImageTool,
        config: {
            endpoints: {
                byFile: props.uploadUrl,
                byUrl: props.uploadUrl + '?byUrl=1',
            },
            field: 'image',
            types: 'image/png, image/jpeg, image/gif, image/webp',
            captionPlaceholder: 'Legenda da imagem',
            buttonContent: 'Selecionar imagem',
            additionalRequestHeaders: {
                'X-CSRF-TOKEN': props.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        },
    },
    code: {
        class: CodeTool,
        config: {
            placeholder: 'Digite seu código aqui...',
        },
    },
    math: {
        class: MathTool,
    },
    quote: {
        class: Quote,
        inlineToolbar: true,
        config: {
            quotePlaceholder: 'Digite a citação',
            captionPlaceholder: 'Autor da citação',
        },
    },
    warning: {
        class: Warning,
        inlineToolbar: true,
        config: {
            titlePlaceholder: 'Atenção!',
            messagePlaceholder: 'Informação importante...',
        },
    },
    delimiter: Delimiter,
    table: {
        class: Table,
        inlineToolbar: true,
        config: {
            rows: 2,
            cols: 3,
        },
    },
    inlineCode: {
        class: InlineCode,
    },
});

/**
 * Inicializa o editor
 */
const initEditor = async () => {
    if (editor) {
        await editor.destroy();
    }

    // Parse dados iniciais
    let initialData = null;
    if (props.modelValue) {
        if (typeof props.modelValue === 'string') {
            try {
                initialData = JSON.parse(props.modelValue);
            } catch (e) {
                initialData = null;
            }
        } else {
            initialData = props.modelValue;
        }
    }

    editor = new EditorJS({
        holder: props.editorId,
        tools: getTools(),
        data: initialData,
        placeholder: props.placeholder,
        readOnly: props.disabled,
        onChange: async () => {
            const data = await editor.save();
            emit('update:modelValue', data);
            emit('change', data);
        },
        i18n: {
            messages: {
                ui: {
                    blockTunes: {
                        toggler: {
                            'Click to tune': 'Clique para configurar',
                            'or drag to move': 'ou arraste para mover',
                        },
                    },
                    inlineToolbar: {
                        converter: {
                            'Convert to': 'Converter para',
                        },
                    },
                    toolbar: {
                        toolbox: {
                            Add: 'Adicionar',
                        },
                    },
                },
                toolNames: {
                    Text: 'Texto',
                    Heading: 'Título',
                    List: 'Lista',
                    Warning: 'Aviso',
                    Quote: 'Citação',
                    Code: 'Código',
                    Delimiter: 'Separador',
                    Table: 'Tabela',
                    Image: 'Imagem',
                    'Inline Code': 'Código inline',
                    Math: 'Fórmula',
                },
                tools: {
                    warning: {
                        Title: 'Título',
                        Message: 'Mensagem',
                    },
                    image: {
                        Caption: 'Legenda',
                        'Select an Image': 'Selecionar imagem',
                        'With border': 'Com borda',
                        'Stretch image': 'Expandir imagem',
                        'With background': 'Com fundo',
                    },
                    list: {
                        Unordered: 'Lista com marcadores',
                        Ordered: 'Lista numerada',
                    },
                    header: {
                        'Heading 2': 'Título 2',
                        'Heading 3': 'Título 3',
                        'Heading 4': 'Título 4',
                    },
                },
                blockTunes: {
                    delete: {
                        Delete: 'Excluir',
                    },
                    moveUp: {
                        'Move up': 'Mover para cima',
                    },
                    moveDown: {
                        'Move down': 'Mover para baixo',
                    },
                },
            },
        },
    });

    await editor.isReady;
};

/**
 * Salva o conteúdo do editor
 */
const save = async () => {
    if (editor) {
        return await editor.save();
    }
    return null;
};

/**
 * Limpa o editor
 */
const clear = async () => {
    if (editor) {
        await editor.clear();
    }
};

// Expõe métodos para o componente pai
defineExpose({ save, clear });

onMounted(() => {
    initEditor();
});

onBeforeUnmount(async () => {
    if (editor) {
        await editor.destroy();
        editor = null;
    }
});

// Observa mudanças no modelValue externo
watch(() => props.modelValue, (newValue, oldValue) => {
    // Só reinicializa se o valor mudou significativamente de fora
    if (JSON.stringify(newValue) !== JSON.stringify(oldValue) && !editor?.configuration?.holder) {
        initEditor();
    }
}, { deep: true });
</script>

<template>
    <div class="editor-js-wrapper">
        <div
            :id="editorId"
            ref="editorRef"
            class="editor-js-container"
            :class="{ 'editor-disabled': disabled }"
        />
    </div>
</template>

<style>
/* Importa CSS do KaTeX */
@import 'katex/dist/katex.min.css';

.editor-js-wrapper {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    min-height: 400px;
}

.editor-js-container {
    padding: 1rem;
}

.editor-disabled {
    opacity: 0.6;
    pointer-events: none;
}

/* Estilos customizados para o Editor.js */
.ce-block__content {
    max-width: 100%;
}

.ce-toolbar__content {
    max-width: 100%;
}

.codex-editor__redactor {
    padding-bottom: 100px !important;
}

/* Estilo para blocos de código */
.ce-code__textarea {
    font-family: 'Fira Code', 'Monaco', 'Consolas', monospace;
    font-size: 14px;
    background: #1e293b;
    color: #e2e8f0;
    border-radius: 0.5rem;
}

/* Estilo para avisos */
.cdx-warning {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
}

/* Estilo para citações */
.cdx-quote {
    border-left: 4px solid #6366f1;
    background: #eef2ff;
}

/* Estilos para o Math Tool */
.math-tool {
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
}

.math-tool__label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151;
}

.math-tool__input {
    width: 100%;
    padding: 0.75rem;
    font-family: 'Fira Code', monospace;
    font-size: 14px;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background: #fff;
    resize: vertical;
}

.math-tool__input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.math-tool__checkbox-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.75rem 0;
    font-size: 14px;
    color: #6b7280;
}

.math-tool__preview {
    margin-top: 1rem;
    padding: 1.5rem;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    text-align: center;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.math-tool__preview--error {
    border-color: #fca5a5;
    background: #fef2f2;
}

.math-tool__placeholder {
    color: #9ca3af;
    font-style: italic;
}

.math-tool__error {
    color: #ef4444;
    font-size: 14px;
}

.math-tool__help {
    padding: 0.5rem 1rem;
    font-size: 12px;
    color: #6366f1;
    background: transparent;
    border: 1px solid #6366f1;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s;
}

.math-tool__help:hover {
    background: #6366f1;
    color: #fff;
}

/* Modal de ajuda */
.math-help-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.math-help-modal__content {
    background: #fff;
    padding: 2rem;
    border-radius: 0.75rem;
    max-width: 500px;
    max-height: 80vh;
    overflow-y: auto;
}

.math-help-modal__content h3 {
    margin: 0 0 1rem;
    font-size: 1.25rem;
}

.math-help-modal__content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.math-help-modal__content td {
    padding: 0.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.math-help-modal__content td:first-child {
    font-weight: 500;
    color: #374151;
}

.math-help-modal__content td:last-child {
    font-family: monospace;
    color: #6366f1;
}

.math-help-modal__content code {
    display: block;
    padding: 0.75rem;
    background: #f1f5f9;
    border-radius: 0.375rem;
    font-size: 14px;
    overflow-x: auto;
}

.math-help-modal__close {
    margin-top: 1rem;
    padding: 0.75rem 1.5rem;
    background: #6366f1;
    color: #fff;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    font-weight: 500;
}

.math-help-modal__close:hover {
    background: #4f46e5;
}
</style>

