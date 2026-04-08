@props(['title', 'subtitle' => 'Smart School ERP', 'icon' => 'fas fa-circle'])

<div class="desk-topbar">
    <div class="pg-title">
        <div class="pg-icon"><i class="{{ $icon }}"></i></div>
        <div class="pg-text">
            <h1>{{ $title }}</h1>
            <div class="pg-sub">
                <i class="far fa-calendar-alt"></i>
                <span>{{ date('d M Y') }}</span>
                <span style="font-size:.3rem"><i class="fas fa-circle"></i></span>
                <span>{{ $subtitle }}</span>
            </div>
        </div>
    </div>
    <div class="desk-right">
        {{ $slot }}
        <a href="#" class="d-btn" title="Search"><i class="fas fa-search"></i></a>
        <a href="#" class="d-btn" title="Notifications"><i class="fas fa-bell"></i><span class="notif-dot"></span></a>
        <div class="user-chip">
            <div class="uc-av">{{ substr(auth()->user()->name ?? 'AD', 0, 2) }}</div>
            <div>
                <div class="uc-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="uc-role">{{ auth()->user()->role ?? 'Super Admin' }}</div>
            </div>
        </div>
    </div>
</div>
