@props(['title' => null, 'icon' => null])

<div class="view-card">
    @if($title)
        <div class="header-title">
            @if($icon) <i class="{{ $icon }}"></i> @endif
            <h1>{{ $title }}</h1>
        </div>
    @endif
    
    <div class="form-section">
        {{ $slot }}
    </div>
</div>
