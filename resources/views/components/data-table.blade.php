@props(['id' => null])

<div class="table-card">
    <div class="table-wrapper">
        <table @if($id) id="{{ $id }}" @endif>
            {{ $slot }}
        </table>
    </div>
</div>
