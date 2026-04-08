@forelse($fines as $index => $fine)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td class="class-list">
            @php
                $classData = $fine->classes;
            @endphp
            
            @if(is_array($classData) && count($classData) > 0)
                {{ implode(', ', $classData) }}
            @elseif(!empty($classData))
                {{ $classData }}
            @else
                <span class="text-muted small">No class selected</span>
            @endif
        </td>
        <td>{{ $fine->month }}</td>
        <td>{{ $fine->from_date->format('d-M-Y') }}</td>
        <td>{{ $fine->to_date->format('d-M-Y') }}</td>
        <td>₹{{ number_format($fine->amount, 0) }}</td>
        <td>
            <i class="fas fa-trash delete-icon text-danger" 
               onclick="deleteFine({{ $fine->id }})" 
               title="Delete"></i>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-4 text-muted">No fine settings found.</td>
    </tr>
@endforelse
