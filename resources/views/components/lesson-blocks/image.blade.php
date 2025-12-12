{{-- plataforma/resources/views/components/lesson-blocks/image.blade.php --}}
@php
    $alignmentClass = match($alignment ?? 'center') {
        'left' => 'text-left',
        'right' => 'text-right',
        'center' => 'text-center',
        'wide' => 'w-full',
        default => 'text-center'
    };
@endphp

<figure class="lesson-image my-8 {{ $alignmentClass }}">
    <img 
        src="{{ Storage::url($file) }}" 
        alt="{{ $alt ?? '' }}"
        class="rounded-lg shadow-md {{ $alignment === 'wide' ? 'w-full' : 'max-w-2xl mx-auto' }}"
        loading="lazy"
    >
    @if(isset($caption) && $caption)
        <figcaption class="mt-3 text-sm text-gray-600 italic">
            {{ $caption }}
        </figcaption>
    @endif
</figure>

