<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title') - Smart School ERP</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/erp.css') }}">
@stack('styles')
</head>
<body>

<div class="overlay" id="overlay"></div>

<div class="app">

  @include('partials.sidebar')

  <!-- ========== MAIN WRAPPER ========== -->
  <div class="main-wrap">

    <header class="topnav" id="topnav">
      <div class="tn-left">
        <button class="hamburger" id="hamburger"><i class="fas fa-bars"></i></button>
        <div>
          <div class="tn-title">@yield('title', 'Dashboard')</div>
          <div class="tn-sub"><i class="far fa-calendar-alt"></i> {{ date('d M Y') }}</div>
        </div>
      </div>
      <div class="tn-right">
        <a href="#" class="tn-btn"><i class="fas fa-search"></i></a>
        <a href="#" class="tn-btn"><i class="fas fa-bell"></i><span class="notif-dot"></span></a>
        <div class="dropdown">
          <div class="tn-avatar dd-toggle" id="userChipMobile">
            {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
          </div>
          <div class="dropdown-menu" id="userMenuMobile">
            <div style="padding:10px 12px; border-bottom:1px solid var(--border); margin-bottom:5px">
                <div style="font-size: .8rem; font-weight:700; color:var(--txt1)">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div style="font-size: .65rem; color:var(--txt3)">{{ Auth::user()->role_name ?? 'Super Admin' }}</div>
            </div>
            <a href="#" class="dd-item"><i class="fas fa-user-circle"></i> Profile</a>
            <form action="{{ route('logout') }}" method="POST" id="logoutForm-mob">
                @csrf
                <button type="submit" class="dd-item danger" style="width:100%; border:none; background:none; cursor:pointer">
                    <i class="fas fa-power-off"></i> Logout
                </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <div class="page-inner">

      @include('partials.header')

      {{-- ========== PAGE CONTENT ========== --}}
      @yield('content')

      <div style="text-align:right;font-size:.78rem;color:var(--txt3);padding-top:16px;border-top:1px solid var(--border);margin-top:24px">
        <strong style="color:var(--orange)">Smart School ERP</strong> &nbsp;·&nbsp; Powered by Decent Web Services LLP
      </div>

    </div><!-- /page-inner -->
  </div><!-- /main-wrap -->

</div><!-- /app -->

<script>
(function(){
  var sidebar = document.getElementById('sidebar'),
      overlay = document.getElementById('overlay'),
      hamburger = document.getElementById('hamburger'),
      sbClose = document.getElementById('sbClose');

  function openSB(){ sidebar.classList.add('open'); overlay.classList.add('on'); document.body.style.overflow='hidden'; }
  function closeSB(){ sidebar.classList.remove('open'); overlay.classList.remove('on'); document.body.style.overflow=''; }

  if(hamburger) hamburger.addEventListener('click', openSB);
  if(sbClose)   sbClose.addEventListener('click',  closeSB);
  if(overlay)   overlay.addEventListener('click',  closeSB);
  window.addEventListener('resize', function(){ if(window.innerWidth>768) closeSB(); });

  /* Collapsible sections */
  document.querySelectorAll('.section-title').forEach(function(t){
    t.addEventListener('click', function(){
      var sub = document.getElementById(this.getAttribute('data-target'));
      if(!sub) return;
      var wasOpen = sub.classList.contains('open');
      document.querySelectorAll('.submenu').forEach(function(s){ s.classList.remove('open'); });
      document.querySelectorAll('.section-title').forEach(function(x){ x.classList.remove('open'); });
      if(!wasOpen){ sub.classList.add('open'); this.classList.add('open'); }
    });
  });

  /* Dropdown toggles */
  document.querySelectorAll('.dd-toggle').forEach(function(btn){
    btn.addEventListener('click', function(e){
      e.stopPropagation();
      var menu = this.nextElementSibling;
      var isOpen = menu.classList.contains('show');
      document.querySelectorAll('.dropdown-menu').forEach(function(m){ m.classList.remove('show'); });
      if(!isOpen) menu.classList.add('show');
    });
  });
  document.addEventListener('click', function(){
    document.querySelectorAll('.dropdown-menu').forEach(function(m){ m.classList.remove('show'); });
  });
})();
</script>

<!-- Internal Libraries -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

@stack('scripts')
</body>
</html>
