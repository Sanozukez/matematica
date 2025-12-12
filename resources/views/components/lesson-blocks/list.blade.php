{{-- plataforma/resources/views/components/lesson-blocks/list.blade.php --}}
{{-- 
    Renderiza uma lista criada com TipTap Editor
    Suporta tanto o formato antigo (items array) quanto o novo (content do TipTap)
--}}
@php
    $html = '';
    
    // Formato antigo: items array (compatibilidade)
    if (isset($items) && is_array($items) && count($items) > 0) {
        $tag = match($style ?? 'bullet') {
            'numbered' => 'ol',
            'bullet' => 'ul',
            default => 'ul'
        };
        $listClass = match($style ?? 'bullet') {
            'numbered' => 'list-decimal',
            'bullet' => 'list-disc',
            default => 'list-disc'
        };
        $html = "<{$tag} class=\"lesson-list {$listClass} ml-6 my-4 space-y-2\">";
        foreach ($items as $item) {
            $itemContent = is_array($item) ? ($item['content'] ?? '') : $item;
            $html .= "<l