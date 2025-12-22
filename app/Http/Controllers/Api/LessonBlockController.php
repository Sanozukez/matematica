<?php

namespace App\Http\Controllers\Api;

use App\Domain\Lesson\Models\Lesson;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * API Controller para Blocos do Editor de Lições
 * 
 * Responsável por:
 * - Salvar blocos do editor (JSON)
 * - Carregar blocos salvos
 * - Validar estrutura de blocos
 * 
 * Princípio SRP: Apenas operações de blocos do editor
 */
class LessonBlockController extends Controller
{
    /**
     * Carrega blocos de uma lição
     * 
     * GET /api/lessons/{lesson}/blocks
     */
    public function index(Lesson $lesson): JsonResponse
    {
        // Verifica se o usuário tem permissão de visualizar
        // TODO: Adicionar autorização quando implementar policies
        
        $blocksCount = count($lesson->content['blocks'] ?? []);
        $wordsCount = $this->countWords($lesson->content['blocks'] ?? []);
        
        return response()->json([
            'blocks' => $lesson->content['blocks'] ?? [],
            'lesson_title' => $lesson->title,
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'type' => $lesson->type,
                'is_active' => $lesson->is_active,
                'duration_minutes' => $lesson->duration_minutes ?? 0,
                'created_at' => $lesson->created_at?->format('d/m/Y H:i'),
                'updated_at' => $lesson->updated_at?->format('d/m/Y H:i'),
                'stats' => [
                    'blocks_count' => $blocksCount,
                    'words_count' => $wordsCount,
                ]
            ]
        ]);
    }
    
    /**
     * Conta o número de palavras em todos os blocos
     */
    private function countWords(array $blocks): int
    {
        $count = 0;
        
        foreach ($blocks as $block) {
            if ($block['type'] === 'columns' && isset($block['attributes']['columns'])) {
                foreach ($block['attributes']['columns'] as $col) {
                    foreach ($col['blocks'] ?? [] as $innerBlock) {
                        $content = $innerBlock['content'] ?? '';
                        // Garante que content é string
                        if (is_string($content)) {
                            $count += str_word_count(strip_tags($content));
                        }
                    }
                }
            } else {
                $content = $block['content'] ?? '';
                // Garante que content é string
                if (is_string($content)) {
                    $count += str_word_count(strip_tags($content));
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Salva blocos de uma lição
     * 
     * POST /api/lessons/{lesson}/blocks
     */
    public function store(Request $request, Lesson $lesson): JsonResponse
    {
        // Validação completa incluindo blocos internos de colunas
        $validator = Validator::make($request->all(), [
            'blocks' => 'required|array',
            'blocks.*.id' => 'required|string',
            'blocks.*.type' => 'required|string|in:paragraph,heading,image,video,code,quote,alert,list,latex,divider,table,columns',
            'blocks.*.content' => 'present', // Permite string, null, ou vazio
            'blocks.*.attributes' => 'present|array', // Sempre array, pode ser vazio
            'blocks.*.attributes.columns' => 'sometimes|array',
            'blocks.*.attributes.columns.*.blocks' => 'sometimes|array',
            'blocks.*.attributes.columns.*.blocks.*.id' => 'sometimes|string',
            'blocks.*.attributes.columns.*.blocks.*.type' => 'sometimes|string|in:paragraph,heading,image,video,code,quote,alert,list,latex,divider,table',
            'blocks.*.attributes.columns.*.blocks.*.content' => 'present',
            'blocks.*.attributes.columns.*.blocks.*.attributes' => 'present|array',
            'lesson_title' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            \Log::error('Validação de blocos falhou', [
                'errors' => $validator->errors()->toArray(),
                'blocks_count' => count($request->input('blocks', [])),
            ]);
            
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // TODO: Adicionar autorização quando implementar policies
        
        // Atualiza título se fornecido
        if ($request->has('lesson_title')) {
            $lesson->title = $request->input('lesson_title');
        }
        
        // Salva blocos no content (JSON) - sem lesson_id redundante
        $lesson->content = [
            'blocks' => $request->input('blocks'),
            'updated_at' => now()->toISOString()
        ];
        $lesson->save();
        
        return response()->json([
            'message' => 'Blocos salvos com sucesso',
            'blocks_count' => count($request->input('blocks'))
        ]);
    }
    
    /**
     * Exporta blocos como JSON (para backup/debug)
     * 
     * GET /api/lessons/{lesson}/blocks/export
     */
    public function export(Lesson $lesson): JsonResponse
    {
        return response()->json([
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'slug' => $lesson->slug,
                'type' => $lesson->type,
            ],
            'content' => $lesson->content,
            'exported_at' => now()->toISOString()
        ]);
    }
}
