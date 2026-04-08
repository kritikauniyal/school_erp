@props(['title', 'value', 'icon' => 'fas fa-box', 'delay' => 0])

<div class="stat-card" style="--i: {{ $delay }}">
    <div class="stat-info">
        <h3>{{ $title }}</h3>
        <div class="stat-number">{{ $value }}</div>
    </div>
    <div class="stat-icon">
        <i class="{{ $icon }}"></i>
    </div>
</div>
