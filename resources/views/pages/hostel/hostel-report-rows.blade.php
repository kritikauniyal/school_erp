@if($reportType === 'room')
    @forelse($allotments as $allotment)
        <tr>
            <td>{{ $allotment->room->room_no }}</td>
            <td>{{ $allotment->room->hostel->name }} ({{ $allotment->room->hostel->type }})</td>
            <td>{{ $allotment->student->user->name }}</td>
            <td>{{ $allotment->student->classInfo->class_name ?? '' }}-{{ $allotment->student->sectionInfo->section_name ?? '' }}</td>
            <td>{{ $allotment->student->parent->father_mobile ?? $allotment->student->mobile }}</td>
            <td>₹{{ number_format($allotment->monthly_charge, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 30px;">No occupants found for the selected filters.</td>
        </tr>
    @endforelse
@else
    @forelse($ledgerEntries as $entry)
        <tr>
            <td>{{ \Carbon\Carbon::parse($entry->date)->format('d-M-Y') }}</td>
            <td>{{ $entry->student->user->name }}</td>
            <td>{{ $entry->description }}</td>
            <td>₹{{ number_format($entry->amount, 2) }}</td>
            <td>{{ $entry->transaction_type }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align: center; padding: 30px;">No transactions found for the selected period.</td>
        </tr>
    @endforelse

    @if($ledgerEntries->count() > 0)
        <tr class="total-row">
            <td colspan="3"><strong>Total Collection</strong></td>
            <td><strong>₹{{ number_format($totals['totalCollection'], 2) }}</strong></td>
            <td></td>
        </tr>
        <tr class="total-row">
            <td colspan="3"><strong>Estimated Dues</strong></td>
            <td><strong>₹{{ number_format($totals['estimatedDues'], 2) }}</strong></td>
            <td></td>
        </tr>
    @endif
@endif
