{{-- Minimal preview for lessons --}}
@php
    /** @var \App\Domain\Lesson\Models\Lesson $lesson */
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $lesson->title }} • Preview</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; background:#f9fafb; color:#111827; }
        .container { max-width: 768px; margin: 40px auto; background:#fff; padding:32px; border-radius:12px; box-shadow: 0 1px 2px rgba(15,23,42,0.08); }
        h1 { font-size: 28px; margin: 0 0 16px; }
        .meta { color:#6b7280; font-size: 14px; margin-bottom: 24px; }
        .content { line-height: 1.7; }
        .badge { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; font-size:12px; border-radius:999px; background:#eef2ff; color:#3730a3; }
        .muted { color:#6b7280; }
        pre { background:#f3f4f6; padding:12px; border-radius:8px; overflow:auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="meta">
            <span class="badge">Lição</span>
            <span>•</span>
            <span class="muted">Módulo: {{ optional($lesson->module)->title ?? '-' }}</span>
        </div>
        <h1>{{ $lesson->title }}</h1>
        <div class="content">
            @if($lesson->type === 'text')
                <pre>{{ json_encode($lesson->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            @elseif($lesson->type === 'video')
                <p>Vídeo: <code>{{ $lesson->content['url'] ?? '-' }}</code></p>
            @elseif($lesson->type === 'quiz')
                <pre>{{ json_encode($lesson->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            @elseif($lesson->type === 'game')
                <pre>{{ json_encode($lesson->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            @else
                <pre>{{ json_encode($lesson->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            @endif
        </div>
    </div>
</body>
</html>
