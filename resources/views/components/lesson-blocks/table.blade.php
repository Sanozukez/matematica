{{-- plataforma/resources/views/components/lesson-blocks/table.blade.php --}}
<div class="lesson-table my-8 overflow-x-auto">
    @if(isset($caption) && $caption)
        <h4 class="text-lg font-semibold mb-3 text-gray-800">{{ $caption }}</h4>
    @endif
    
    <table class="min-w-full divide-y divide-gray-300 border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-gray-900 border-b border-gray-300">
                        {{ $header['text'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            @foreach($rows as $row)
                <tr class="hover:bg-gray-50 transition">
                    @foreach($row['cells'] as $cell)
                        <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                            {{ $cell['value'] ?? '' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

