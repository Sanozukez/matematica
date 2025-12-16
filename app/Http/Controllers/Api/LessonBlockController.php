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
        
        return response()->json([
            'lesson_id' => $lesson->id,
            'blocks' => $lesson->content['blocks'] ?? []
        ]);
    }
    
    /**
     * Salva blocos de uma lição
     * 
     * POST /api/lessons/{lesson}/blocks
     */
    public function store(Request $request, Lesson $lesson): JsonResponse
    {
        // Validação
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|uuid',
            'blocks' => 'required|array',
            'blocks.*.id' => 'required|string',
            'blocks.*.type' => 'required|string|in:paragraph,heading,image,video,code,quote,alert,list,latex,divider,table',
            'blocks.*.content' => 'nullable|string',
            'blocks.*.order' => 'required|integer|min:0',
            'blocks.*.attributes' => 'nullable|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Verifica se lesson_id corresponde
        if ($request->input('lesson_id') !== $lesson->id) {
            return response()->json([
                'message' => 'ID da lição não corresponde'
            ], 400);
        }
        
        // TODO: Adicionar autorização quando implementar policies
        
        // Salva blocos no content (JSON)
        $lesson->content = [
            'blocks' => $request->input('blocks'),
            'updated_at' => now()->toISOString()
        ];
        $lesson->save();
        
        return response()->json([
            'message' => 'Blocos salvos com sucesso',
            'lesson_id' => $lesson->id,
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
