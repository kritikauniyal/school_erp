<div class="desk-topbar">
    <div class="pg-title">
        <div class="pg-icon"><i class="@yield('page_icon', 'fas fa-chart-pie')"></i></div>
        <div class="pg-text">
            <h1 style="color: var(--blue); font-size: 2rem; font-weight: 800;">
                @yield('title', 'Dashboard')
            </h1>
            <div class="pg-sub">
                <i class="far fa-calendar-alt"></i>
                <span>{{ date('d M Y') }}</span>
                <span style="font-size: .3rem; opacity: 0.5;"><i class="fas fa-circle"></i></span>
                <span>Smart School ERP</span>
            </div>
        </div>
    </div>
    <div class="desk-right">
        @yield('page_actions')
        <a href="#" class="d-btn" title="Search"><i class="fas fa-search"></i></a>
        <a href="#" class="d-btn" title="Notifications"><i class="fas fa-bell"></i><span class="notif-dot"></span></a>
        
        <div class="dropdown">
            <div class="user-chip dd-toggle" id="userChipDesktop">
                <div class="uc-av">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</div>
                <div>
                    <div class="uc-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="uc-role">{{ Auth::user()->role_name ?? 'Super Admin' }}</div>
                </div>
                <i class="fas fa-chevron-down" style="font-size:.6rem; opacity:.4; margin-left:8px"></i>
            </div>
            <div class="dropdown-menu" id="userMenuDesktop">
                <div style="padding:10px 12px; border-bottom:1px solid var(--border); margin-bottom:5px">
                    <div style="font-size: .8rem; font-weight:700; color:var(--txt1)">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div style="font-size: .65rem; color:var(--txt3)">{{ Auth::user()->email ?? 'admin@example.com' }}</div>
                </div>
                <a href="#" class="dd-item"><i class="fas fa-user-circle"></i> Profile Setting</a>
                <a href="#" class="dd-item"><i class="fas fa-lock"></i> Change Password</a>
                <div class="dd-divider"></div>
                <form action="{{ route('logout') }}" method="POST" id="logoutForm-desk">
                    @csrf
                    <button type="submit" class="dd-item danger" style="width:100%; border:none; background:none; cursor:pointer">
                        <i class="fas fa-power-off"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

