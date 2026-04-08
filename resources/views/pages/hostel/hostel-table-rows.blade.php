@forelse($allotments as $index => $allotment)
    <tr>
        <td>{{ $allotments->firstItem() + $index }}</td>
        <td>{{ $allotment->allotment_no }}</td>
        <td>{{ $allotment->room->room_no }} ({{ $allotment->room->hostel->name }})</td>
        <td>
            {{ $allotment->student->classInfo->class_name ?? '' }}-{{ $allotment->student->sectionInfo->section_name ?? '' }}-{{ $allotment->student->roll_no ?? '' }}
        </td>
        <td>{{ $allotment->student->registration_no }}</td>
        <td>{{ $allotment->student->user->name }}</td>
        <td>{{ $allotment->student->parent->father_name ?? '' }}</td>
        <td>{{ $allotment->student->parent->father_mobile ?? $allotment->student->mobile }}</td>
        <td>{{ \Carbon\Carbon::parse($allotment->allotment_date)->format('d-M-Y') }}</td>
        <td>{{ $allotment->discharge_date ? \Carbon\Carbon::parse($allotment->discharge_date)->format('d-M-Y') : '-' }}</td>
        <td>₹{{ number_format($allotment->monthly_charge, 2) }}</td>
        <td>
            <span class="status-badge" style="background: {{ $allotment->status == 'alloted' ? 'var(--blue-light)' : '#ffeaea' }}; color: {{ $allotment->status == 'alloted' ? 'var(--primary-blue)' : '#e53e3e' }};">
                {{ ucfirst($allotment->status) }}
            </span>
        </td>
        <td class="action-links">
            @if($allotment->status == 'alloted')
                <span class="edit-link" 
                    data-id="{{ $allotment->id }}"
                    data-student="{{ $allotment->student->user->name }}"
                    data-class="{{ $allotment->student->classInfo->class_name ?? '' }}"
                    data-section="{{ $allotment->student->sectionInfo->section_name ?? '' }}"
                    data-reg="{{ $allotment->student->registration_no }}"
                    data-father="{{ $allotment->student->parent->father_name ?? '' }}"
                    data-room="{{ $allotment->room_id }}"
                    data-charge="{{ $allotment->monthly_charge }}"
                    data-date="{{ \Carbon\Carbon::parse($allotment->allotment_date)->format('Y-m-d') }}"
                    data-remarks="{{ $allotment->remarks }}">Edit</span>
                <span class="stop-link" data-id="{{ $allotment->id }}" style="color: #e53e3e;">Stop</span>
            @else
                <span style="color: #94a3b8; text-decoration: none; cursor: default;">Discharged</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="13" style="text-align: center; padding: 40px; color: var(--text-muted);">
            <i class="fas fa-bed" style="font-size: 2rem; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
            No hostel allocations found.
        </td>
    </tr>
@endforelse
