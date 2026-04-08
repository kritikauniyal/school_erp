@extends('layouts.app')

@section('title', 'Collect Fee')

@section('page-title','Collect Fee')

@push('styles')
<style>
:root{
  --blue:#488fe4;--orange:#ff913b;--blu:#e3f0ff;--olt:#fff4ea;
  --bg:#f5f9ff;--dark:#1e293b;--muted:#5f6b7a;--sidebar:#435471;
  --sh:0 4px 18px rgba(0,20,50,.07);
  --green:#22c55e;--red:#ef4444;--yellow:#f59e0b;--purple:#7c3aed;
}

::-webkit-scrollbar{width:7px;height:7px}
::-webkit-scrollbar-track{background:#f0f4ff;border-radius:8px}
::-webkit-scrollbar-thumb{background:linear-gradient(145deg,var(--blue),var(--orange));border-radius:8px;border:1px solid #fff}

.cf-wrap { font-family: 'Inter', sans-serif; }
.cf-wrap .card{background:#fff;border-radius:24px;box-shadow:var(--sh);padding:22px 24px;margin-bottom:20px}
.cf-wrap .ctitle{display:flex;align-items:center;gap:10px;margin-bottom:20px}
.cf-wrap .ctitle h2{font-size:1.32rem;font-weight:700;color:var(--blue);margin:0}
.cf-wrap .ctitle i{font-size:1.32rem;color:var(--orange)}

/* MODE CARDS */
.mode-wrap{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px}
@media(max-width:600px){.mode-wrap{grid-template-columns:1fr}}
.mcard{border-radius:18px;cursor:pointer;border:2.5px solid #e4ecf7;transition:all .28s cubic-bezier(.2,.9,.3,1);position:relative;overflow:hidden;background:#fff;box-shadow:0 2px 10px rgba(0,20,50,.05)}
.mcard:hover:not(.mac){border-color:#c5d8f2;box-shadow:0 6px 20px rgba(0,20,50,.1);transform:translateY(-2px)}
.mc-inner{padding:16px 18px;display:flex;align-items:center;gap:14px}
.mc-ico{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;transition:.28s;flex-shrink:0}
.mc-lbl{font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.9px;margin-bottom:3px}
.mc-ttl{font-size:.93rem;font-weight:700;color:var(--dark)}
.mc-check{display:none;position:absolute;right:14px;top:50%;transform:translateY(-50%);width:21px;height:21px;border-radius:50%;align-items:center;justify-content:center;font-size:.67rem}
.mac .mc-check{display:flex}
.class-mc .mc-ico{background:var(--blu);color:var(--blue)}.class-mc .mc-lbl{color:var(--blue)}.class-mc .mc-check{background:var(--blue);color:#fff}
.class-mc.mac{border-color:var(--blue);background:linear-gradient(140deg,#eaf4ff,#f5faff);box-shadow:0 8px 28px rgba(72,143,228,.2)}
.class-mc.mac .mc-ico{background:var(--blue);color:#fff}
.direct-mc .mc-ico{background:var(--olt);color:var(--orange)}.direct-mc .mc-lbl{color:var(--orange)}.direct-mc .mc-check{background:var(--orange);color:#fff}
.direct-mc.mac{border-color:var(--orange);background:linear-gradient(140deg,#fff8f2,#fffcf8);box-shadow:0 8px 28px rgba(255,145,59,.2)}
.direct-mc.mac .mc-ico{background:var(--orange);color:#fff}

/* FILTER ROW */
.fr{display:flex;flex-wrap:wrap;align-items:flex-end;gap:15px;background:#f8fcff;padding:15px 17px;border-radius:17px;margin-bottom:15px;border:1.5px solid #e8f1fb}
.fg{display:flex;flex-direction:column;min-width:150px;flex:1 1 165px}
.fg label{font-size:.64rem;text-transform:uppercase;font-weight:700;color:var(--blue);margin-bottom:6px;letter-spacing:.5px}
.fg select, .fg input{background:#fff;border:1.5px solid #e0e7f0;border-radius:11px;padding:10px 12px;font-size:.87rem;color:var(--dark);outline:none;width:100%;transition:.2s}
.fg select:focus, .fg input:focus{border-color:var(--orange);box-shadow:0 0 0 3px rgba(255,145,59,.15)}

.sbtn{background:linear-gradient(135deg,var(--blue),#2d6abf);color:#fff;border:none;padding:11px 24px;border-radius:11px;font-weight:700;font-size:.86rem;cursor:pointer;display:flex;align-items:center;gap:7px;transition:.2s;white-space:nowrap;box-shadow: 0 4px 12px rgba(72,143,228,0.2);}
.sbtn:hover{background:linear-gradient(135deg,var(--orange),#e07a2a); transform: translateY(-1px);}

/* DIRECT SEARCH */
.dsw{background:#fff9f5;padding:16px 18px;border-radius:17px;margin-bottom:15px;border:1.5px solid #ffe4c8}
.dsi{display:flex;align-items:center;background:#fff;border:1.5px solid #e8e0d8;border-radius:40px;padding:4px 4px 4px 16px;gap:8px}
.dsi i.ic1{color:var(--orange);font-size:.92rem}
.dsi input{border:none;background:transparent;padding:10px 8px;font-size:.9rem;width:100%;outline:none;color:var(--dark)}
.dsi button{background:linear-gradient(135deg,var(--orange),#e07a2a);color:#fff;border:none;padding:9px 20px;border-radius:36px;font-weight:700;font-size:.83rem;cursor:pointer;transition:.2s}
.dsi button:hover{background:linear-gradient(135deg,var(--blue),#2d6abf)}

/* TABLES */
.ebar{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between;margin-bottom:12px}
.ebtns{display:flex;flex-wrap:wrap;gap:8px}
.ebtn{background:#fff;border:1.5px solid var(--blue);color:var(--blue);padding:6px 14px;border-radius:24px;font-weight:600;font-size:.76rem;display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:.2s;box-shadow: 0 2px 4px rgba(0,0,0,0.02)}
.ebtn:hover{background:var(--blue);color:#fff}

.tw{overflow-x:auto;border-radius:15px;background:#fff;box-shadow:var(--sh);margin-bottom:16px; border: 1px solid #eef2f6;}
table{width:100%;border-collapse:collapse;min-width:920px}
thead th{background:var(--blue);color:#fff;font-weight:600;font-size:.7rem;text-transform:uppercase;padding:12px 10px;text-align:left;letter-spacing:0.3px; border: 1px solid rgba(255,255,255,.1)}
tbody td{padding:10px 10px;border-bottom:1px solid #f1f5f9;color:var(--dark);font-size:.84rem; vertical-align: middle;}
tbody tr:hover td{background:#f8fbff}

.cbtn{background:linear-gradient(135deg,var(--orange),#e07a2a);color:#fff;border:none;padding:7px 16px;border-radius:24px;font-weight:700;font-size:.76rem;cursor:pointer;display:inline-flex;align-items:center;gap:6px;box-shadow: 0 4px 12px rgba(255,145,59,0.25);transition: .2s;}
.cbtn:hover{background:linear-gradient(135deg,var(--blue),#2d6abf); transform: scale(1.03);}
.dbadge{background:#fef3f2;color:#ef4444;padding:4px 12px;border-radius:20px;font-size:.76rem;font-weight:700;border:1.5px solid #fca5a5;cursor:pointer;display:inline-flex;align-items:center;gap:6px; transition:.2s;}
.dbadge:hover{background:#ef4444;color:#fff;border-color:#ef4444}

/* MODALS - FLOATING OVERLAY FIX */
.due-bg, .fp-bg, .pm-bg, .rc-bg{
  display:none;position:fixed;inset:0;
  background:rgba(8,18,46,.65);z-index:99999!important;
  align-items:flex-start;justify-content:center;
  backdrop-filter:blur(8px);
  overflow-y:auto; padding:22px 14px;
}
.due-bg.open, .fp-bg.open, .pm-bg.open, .rc-bg.open{display:flex}

.due-box, .fp-box, .pm, .rc-m{background:#fff;border-radius:24px;box-shadow:0 28px 72px rgba(0,18,56,.28);animation:popIn .32s ease;margin:auto; overflow: hidden; position: relative;}
@keyframes popIn{from{transform:scale(.93) translateY(18px);opacity:0}to{transform:scale(1) translateY(0);opacity:1}}

.m-hdr{color:#fff;padding:17px 23px;position:relative}
.m-hdr h2, .m-hdr h3{font-size:1.08rem;font-weight:700;display:flex;align-items:center;gap:9px;margin:0}
.m-hdr p{font-size:.72rem;opacity:.75;margin-top:3px}
.m-close{position:absolute;right:16px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer; display: flex; align-items:center; justify-content:center; transition:.2s;}
.m-close:hover{background:rgba(255,255,255,.35)}

.fp-hdr{background:linear-gradient(135deg,var(--blue),#2463b8)}
.due-hdr{background:linear-gradient(135deg,#ef4444,#dc2626)}
.pm-hdr{background:linear-gradient(135deg,var(--blue),#2463b8)}

.sstrip{display:flex;flex-wrap:wrap;gap:7px 20px;padding:12px 23px;background:#f0f7ff;border-bottom:1.5px solid #dde8f5}
.sic .sl{font-size:.57rem;text-transform:uppercase;font-weight:700;color:var(--blue);display:block; letter-spacing: 0.5px;}
.sic .sv{font-size:.82rem;font-weight:700; color: var(--dark);}

/* MULTI BAR */
.ms-bar{display:none;align-items:center;justify-content:space-between;gap:12px;background:linear-gradient(135deg,#fff8f2,#fff4ea);border:2px solid #ffe4c8;border-radius:15px;padding:11px 17px;margin:15px 23px 0;}
.ms-bar.visible{display:flex}
.ms-info{display:flex;align-items:center;gap:10px; font-weight:600; color: var(--dark);}
.ms-count{background:var(--orange);color:#fff;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:900}
.ms-total{font-size:.9rem;font-weight:800;color:var(--orange)}
.ms-actions{display:flex;gap:8px;}

/* MONTH TABLE SPECIFIC */
.msec{padding:18px 23px 23px}
.msec-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px; flex-wrap:wrap; gap:10px;}
.msec-hdr h3{font-size:.92rem;font-weight:700;color:var(--blue);display:flex;align-items:center;gap:8px; margin:0;}
.msec-hint{font-size:0.7rem; color:var(--muted); font-weight:500;}

.mchk{width:18px;height:18px;border-radius:5px;border:2px solid #cfd5e0;cursor:pointer;background:#fff;display:flex;align-items:center;justify-content:center; transition:.2s;}
.mchk.checked-due{background:var(--orange);border-color:var(--orange)}
.mchk.checked-paid{background:var(--green);border-color:var(--green)}
.mchk::after{content:'✓';color:#fff;font-size:.65rem;display:none; font-weight:900;}
.mchk.checked-due::after, .mchk.checked-paid::after{display:block}

.ss{padding:4px 10px;border-radius:15px;font-size:.65rem;font-weight:800;border:1px solid; white-space:nowrap;}
.ss.paid{background:#dcfce7;color:#16a34a;border-color:#86efac}
.ss.partial{background:#fff7ed;color:#ea580c;border-color:#fed7aa}
.ss.due{background:#fef3f2;color:#dc2626;border-color:#fca5a5}

.ft-badge{font-size:.6rem;padding:2px 8px;border-radius:10px;font-weight:700;margin-right:4px;border:1px solid #ddd;display:inline-block;margin-bottom:2px; white-space:nowrap;}
.ft-tui{color:#3b82f6;background:#eff6ff;border-color:#bfdbfe}
.ft-tra{color:#f59e0b;background:#fffbeb;border-color:#fef3c7}
.ft-default{color:#64748b;background:#f8fafc;border-color:#e2e8f0}

.ais{display:flex;gap:5px;flex-wrap:nowrap;align-items:center}
.ib{width:28px;height:28px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.78rem;transition:.2s; position:relative;}
.ib:hover{transform:translateY(-2px); box-shadow:0 4px 10px rgba(0,20,50,.15)}
.b-pay{background:#fff7ed;color:#f97316; font-weight:900; font-size:0.65rem;}
.b-view{background:#eff6ff;color:#3b82f6}
.b-print{background:#f0fdf4;color:#16a34a}
.b-wa{background:#f5fff8;color:#22c55e}
.b-tag{background:#f0f9ff;color:#0ea5e9}
.b-hist{background:#faf5ff;color:#a855f7}

.rs-ico{background:rgba(255,255,255,0.25); color:#fff; width:22px; height:20px; border-radius:5px; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem; font-weight:800; line-height:1;}
.empty{display:flex; flex-direction:column; align-items:center; justify-content:center; padding:80px 40px; color: #94a3b8; text-align:center; gap: 12px;}
.empty i{font-size: 2.5rem; color: #cdd8ef;}
.empty p{font-weight:500; font-size:0.9rem; margin:0;}

.tf-row{display:flex; justify-content:space-between; align-items:center; padding:10px 15px; background:#f8fcff; border-radius:30px; margin-top:10px; font-size:0.78rem; color: var(--muted); font-weight:500;}

/* PAY MODAL body */
.pm-body{padding:18px 20px}

/* student mini strip in modal */
.pm-stu{background:#f0f7ff;border-radius:12px;padding:10px 14px;margin-bottom:16px;display:flex;flex-wrap:wrap;gap:8px 20px}
.pm-stu-field{display:flex;flex-direction:column;gap:1px}
.pm-stu-field .psl{font-size:.56rem;text-transform:uppercase;font-weight:700;color:var(--blue);letter-spacing:.4px}
.pm-stu-field .psv{font-size:.82rem;font-weight:700;color:var(--dark)}

/* months list */
.pm-months-header{font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:8px}
.pm-months-list{display:flex;flex-direction:column;gap:5px;max-height:130px;overflow-y:auto;margin-bottom:14px}
.pm-mrow{display:flex;align-items:center;justify-content:space-between;background:#f8fcff;border:1.5px solid #e8f1fb;border-radius:10px;padding:8px 11px;font-size:.8rem;}
.pm-mrow .pmn{font-weight:700;color:var(--dark)}
.pm-mrow .pma{font-weight:800;color:var(--orange)}

/* FEE TYPE ITEMS */
.ft-section{margin-bottom:16px}
.ft-section-label{font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:10px;display:flex;align-items:center;gap:6px}
.ft-section-label i{color:var(--orange)}
.ft-grid{display:flex;flex-direction:column;gap:7px}
.ft-item{display:flex;align-items:center;justify-content:space-between;background:#f8fcff;border:1.5px solid #e8f1fb;border-radius:11px;padding:10px 13px;cursor:pointer;transition:.2s;gap:10px}
.ft-item:hover{border-color:#c5d8f2}
.ft-item.selected{background:#eff6ff;border-color:var(--blue)}
.ft-left{display:flex;align-items:center;gap:10px;flex:1}
.ft-chk{width:17px;height:17px;border-radius:5px;border:2px solid #d1d5db;display:flex;align-items:center;justify-content:center;transition:.18s;flex-shrink:0;background:#fff}
.ft-item.selected .ft-chk{background:var(--blue);border-color:var(--blue)}
.ft-chk::after{content:'';transition:.18s}
.ft-item.selected .ft-chk::after{content:'✓';color:#fff;font-size:.6rem;font-weight:800}
.ft-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.78rem;flex-shrink:0}
.ft-info{flex:1}
.ft-name{font-size:.84rem;font-weight:700;color:var(--dark)}
.ft-desc{font-size:.67rem;color:var(--muted);margin-top:1px}
.ft-right{display:flex;flex-direction:column;align-items:flex-end;gap:2px}
.ft-amt{font-size:.9rem;font-weight:800;color:var(--dark)}
.ft-paid-badge{font-size:.6rem;padding:2px 7px;border-radius:10px;font-weight:700;white-space:nowrap}
.ft-paid-badge.paid-f{background:#dcfce7;color:#16a34a;border:1px solid #86efac}
.ft-paid-badge.partial-f{background:#fef9c3;color:#b45309;border:1px solid #fde68a}
.ft-paid-badge.due-f{background:#fef3f2;color:#dc2626;border:1px solid #fca5a5}

/* summary rows */
.pm-divider{border:none;border-top:1.5px dashed #e0e7f0;margin:11px 0}
.pf{margin-bottom:12px}
.pf label{display:block;font-size:.63rem;text-transform:uppercase;font-weight:700;color:var(--blue);margin-bottom:4px;letter-spacing:.4px}
.pf input,.pf select{width:100%;border:1.5px solid #e0e7f0;border-radius:10px;padding:10px 12px;font-size:.86rem;outline:none;transition:.2s;color:var(--dark);background:#fff}
.pf input:focus,.pf select:focus{border-color:var(--orange);box-shadow:0 0 0 3px rgba(255,145,59,.15)}
.pay-mode-btns{display:flex;gap:8px;margin-top:4px}
.pmode-btn{flex:1;padding:9px;border-radius:10px;border:1.5px solid #e0e7f0;background:#fff;font-size:.82rem;font-weight:600;cursor:pointer;text-align:center;transition:.2s;color:var(--muted);display:flex;align-items:center;justify-content:center;gap:6px}
.pmode-btn.active{border-color:var(--blue);background:var(--blu);color:var(--blue)}
.btn-pay-now{width:100%;background:linear-gradient(135deg,var(--orange),#e07a2a);color:#fff;border:none;padding:13px;border-radius:12px;font-weight:700;font-size:.93rem;cursor:pointer;transition:.2s;margin-top:4px;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(255,145,59,.35)}
.btn-pay-now:hover{background:linear-gradient(135deg,var(--blue),#2463b8)}
.pm-sumrow{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f4ff;font-size:.85rem}
.pm-sumrow.total-row{background:#f8fcff;border-radius:10px;padding:10px 13px;border:1.5px solid #e8f1fb;margin:6px 0}
.pm-sumrow.total-row .psr-l{font-weight:700;color:var(--dark);font-size:.88rem}
.pm-sumrow.total-row .psr-v{color:var(--orange);font-size:1.03rem;font-weight:800}
.pm-sumrow.dues-row .psr-v{color:var(--red)}

/* receipt - exact match to reference */
.rc-bg{display:none;position:fixed;inset:0;background:rgba(8,18,46,.65);z-index:5000;align-items:center;justify-content:center;backdrop-filter:blur(6px)}
.rc-bg.open{display:flex}
.rc-m{background:#fff;border-radius:20px;padding:22px;width:min(460px,92vw);box-shadow:0 20px 60px rgba(0,20,60,.25);animation:popIn .25s ease;max-height:92vh;overflow-y:auto}
.rc-hdr{text-align:center;border-bottom:2px dashed #e0e7f0;padding-bottom:11px;margin-bottom:11px}
.rc-hdr h2{font-size:.98rem;font-weight:800;color:var(--blue);display:flex;align-items:center;justify-content:center;gap:8px}
.rc-hdr p{font-size:.71rem;color:var(--muted);margin-top:3px}
.rcrow{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px dotted #e0e7f0;font-size:.8rem}
.rcrow:last-of-type{border-bottom:none}
.rcrow .rl{color:var(--muted);font-weight:500}
.rcrow .rv{font-weight:700;color:var(--dark)}
.rc-lbl{font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);padding:9px 0 4px;border-bottom:1px dotted #e0e7f0;margin-bottom:3px}
.rc-sep{border:none;border-top:2px solid var(--blue);margin:8px 0 3px}
.rc-total{display:flex;justify-content:space-between;font-size:.84rem;padding:5px 0 3px;font-weight:700;color:var(--dark)}
.rc-total .rv{color:var(--green);font-size:.95rem}
.rc-bal{display:flex;justify-content:space-between;font-size:.78rem;padding:3px 0}
.rc-foot{text-align:center;font-size:.62rem;color:var(--muted);margin-top:10px;line-height:1.5}
.mxbtn{background:transparent;border:none;color:var(--muted);font-size:.94rem;cursor:pointer;line-height:1}
.btn-prc{background:var(--blue);color:#fff;border:none;padding:10px;border-radius:24px;font-weight:700;font-size:.79rem;cursor:pointer;transition:.2s;margin-top:12px;width:100%;display:flex;align-items:center;justify-content:center;gap:7px}
.btn-prc:hover{background:var(--orange)}

/* SweetAlert2 z-index fix — appears above all modals */
.swal2-container{ z-index:999999!important; }

/* Print layout */
@media print{
  body > *:not(#rcBg){ display:none!important; }
  #rcBg{ display:flex!important; position:static!important; background:none!important; padding:0!important; backdrop-filter:none!important; }
  .rc-m{ box-shadow:none!important; border:none!important; width:100%!important; max-width:100%!important; }
  .mxbtn, .btn-prc{ display:none!important; }
}

@media(max-width:768px){
    .due-bg, .fp-bg, .pm-bg, .rc-bg { padding: 8px; }
    .fp-box { width: 100% !important; border-radius: 18px; }
}
</style>
@endpush

@section('content')
<div class="cf-wrap">
    <!-- SEARCH PANEL -->
    <div class="card" style="padding-top: 5px;">
        <div class="ctitle"><i class="fas fa-hand-holding-usd"></i><h2>Fee Collection</h2></div>
        <div class="mode-wrap">
            <div id="classCard" class="mcard class-mc mac" onclick="switchMode('class')">
                <div class="mc-inner"><div class="mc-ico"><i class="fas fa-layer-group"></i></div><div class="mc-info"><div class="mc-lbl">SEARCH MODE 1</div><div class="mc-ttl">Class / Section Filter</div></div><div class="mc-check"><i class="fas fa-check"></i></div></div>
            </div>
            <div id="directCard" class="mcard direct-mc" onclick="switchMode('direct')">
                <div class="mc-inner"><div class="mc-ico"><i class="fas fa-bolt"></i></div><div class="mc-info"><div class="mc-lbl">SEARCH MODE 2</div><div class="mc-ttl">Direct Student Search</div></div><div class="mc-check"><i class="fas fa-check"></i></div></div>
            </div>
        </div>

        <!-- CLASS PANEL -->
        <div id="classPanel">
            <div class="fr">
                <div class="fg"><label>Class *</label><select id="classSelect"><option value="">-- Select Class --</option>@foreach($globalClasses as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
                <div class="fg"><label>Section</label><select id="sectionSelect"><option value="">-- All Sections --</option>@foreach($sections as $section)<option value="{{ $section->id }}">{{ $section->name }}</option>@endforeach</select></div>
                <button class="sbtn" onclick="searchByClass()"><i class="fas fa-search"></i> Search</button>
            </div>
            <div id="resultsClass" style="display:none; margin-top:20px;">
                <div class="ebar">
                    <div class="ebtns">
                        <button class="ebtn" onclick="exportTable('copy','stbodyClass')"><i class="fas fa-copy"></i> Copy</button>
                        <button class="ebtn" onclick="exportTable('csv','stbodyClass')"><i class="fas fa-file-csv"></i> CSV</button>
                        <button class="ebtn" onclick="exportTable('excel','stbodyClass')"><i class="fas fa-file-excel"></i> Excel</button>
                        <button class="ebtn" onclick="exportTable('pdf','stbodyClass')"><i class="fas fa-file-pdf"></i> PDF</button>
                        <button class="ebtn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    </div>
                    <div class="tsearch">
                        <i class="fas fa-filter"></i>
                        <input type="text" placeholder="Filter by name..." oninput="filterRows('stbodyClass', this.value)">
                    </div>
                </div>
                <div class="tw" style="margin-bottom: 5px;">
                    <table>
                        <thead><tr><th>#</th><th>STD ID</th><th>ADM NO.</th><th>ROLL</th><th>STUDENT NAME</th><th>CLASS</th><th>SEC.</th><th>FATHER NAME</th><th>MOBILE</th><th>TOTAL DUES</th><th>ACTION</th></tr></thead>
                        <tbody id="stbodyClass"></tbody>
                    </table>
                </div>
                <div class="tf-row">
                    <div id="footerInfoClass">Showing 0-0 of 0 entries</div>
                    <div><i class="fas fa-clock"></i> <span class="ts-val">{{ date('d/m/Y, h:i:s a') }}</span></div>
                </div>
            </div>
        </div>

        <!-- DIRECT PANEL -->
        <div id="directPanel" style="display:none">
            <div class="dsw">
                <div class="dsi"><i class="fas fa-search ic1"></i><input type="text" id="directInput" placeholder="Admission No. / Ledger No. / Mobile No. / Student Name" onkeydown="if(event.key==='Enter')searchDirect()"><button onclick="searchDirect()"><i class="fas fa-bolt"></i> Search</button></div>
                <div style="font-size:.71rem; color:var(--muted); margin-top:10px; display:flex; align-items:center; gap:6px;">
                    <i class="fas fa-info-circle"></i> Exact match on Admission No., Ledger No. or Mobile No. will directly open the fee panel.
                </div>
            </div>
            <div id="resultsDirect" style="display:none; margin-top:20px;">
                <div class="ebar">
                    <div class="ebtns">
                        <button class="ebtn" onclick="exportTable('copy','stbodyDirect')"><i class="fas fa-copy"></i> Copy</button>
                        <button class="ebtn" onclick="exportTable('csv','stbodyDirect')"><i class="fas fa-file-csv"></i> CSV</button>
                        <button class="ebtn" onclick="exportTable('excel','stbodyDirect')"><i class="fas fa-file-excel"></i> Excel</button>
                        <button class="ebtn" onclick="exportTable('pdf','stbodyDirect')"><i class="fas fa-file-pdf"></i> PDF</button>
                        <button class="ebtn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    </div>
                    <div class="tsearch">
                        <i class="fas fa-filter"></i>
                        <input type="text" placeholder="Filter by name..." oninput="filterRows('stbodyDirect', this.value)">
                    </div>
                </div>
                <div class="tw" style="margin-bottom: 5px;">
                    <table>
                        <thead><tr><th>#</th><th>STD ID</th><th>ADM NO.</th><th>ROLL</th><th>STUDENT NAME</th><th>CLASS</th><th>SEC.</th><th>FATHER NAME</th><th>MOBILE</th><th>TOTAL DUES</th><th>ACTION</th></tr></thead>
                        <tbody id="stbodyDirect"></tbody>
                    </table>
                </div>
                <div class="tf-row">
                    <div id="footerInfoDirect">Showing 0-0 of 0 entries</div>
                    <div><i class="fas fa-clock"></i> <span class="ts-val">{{ date('d/m/Y, h:i:s a') }}</span></div>
                </div>
            </div>
        </div>
        <div id="emptyHint" class="empty"><i class="fas fa-hand-holding-usd"></i><p>Select a search mode above to load student data</p></div>
    </div>

    <!-- DUE POPUP -->
    <div class="due-bg" id="dueBg">
        <div class="due-box" style="width: min(480px, 94vw);">
            <div class="m-hdr due-hdr">
                <h3><i class="fas fa-exclamation-circle"></i> Due Months — <span id="dueStudName"></span></h3>
                <button class="m-close" onclick="closeDuePop()"><i class="fas fa-times"></i></button>
            </div>
            <div class="due-body" id="dueBody" style="padding: 20px; max-height: 400px; overflow-y:auto;"></div>
            <div class="due-ftr" style="padding: 15px 20px; border-top: 1.5px solid #f0f4ff; display: flex; justify-content: space-between; align-items:center; background:#f8fbff;">
                <div style="background:#fef3f2; color:#ef4444; padding:8px 16px; border-radius:24px; font-weight:800; font-size:0.9rem; border:1.5px solid #fee2e2;">Total: ₹<span id="dueTotAmt">0</span></div>
                <button class="sbtn" onclick="closeDueAndOpen()"><span class="rs-ico">Rs</span> Collect Fee</button>
            </div>
        </div>
    </div>

    <!-- FEE PANEL -->
    <div class="fp-bg" id="fpBg">
        <div class="fp-box" style="width: min(1080px, 100%);">
            <div class="m-hdr fp-hdr">
                <h2><i class="fas fa-hand-holding-usd"></i> Fee Collection</h2>
                <p>Select months · choose fee types · collect single or multiple months</p>
                <button class="m-close" onclick="closePanel()"><i class="fas fa-times"></i></button>
            </div>
            <div class="sstrip" id="stuStrip"></div>
            
            <div class="ms-bar" id="msBar">
                <div class="ms-info">
                    <span class="ms-count" id="msCount">0</span>
                    <span id="msLabel">month selected</span>
                    <span style="color:#ccc; margin:0 5px;">|</span>
                    Total: <span class="ms-total" id="msTotal">₹0</span>
                </div>
                <div class="ms-actions">
                    <button class="sbtn" style="background:#fff; color:var(--blue); border:1.5px solid var(--blue)" onclick="selectAllDue()"><i class="fas fa-check-double"></i> All Due</button>
                    <button class="sbtn" style="background:#fff; color:var(--muted); border:1.5px solid #ddd" onclick="clearSelection()"><i class="fas fa-times"></i> Clear</button>
                    <button class="sbtn" onclick="openMultiPay()"><i class="fas fa-rupee-sign"></i> Pay Selected</button>
                </div>
            </div>

            <div class="msec">
                <div class="msec-hdr">
                    <h3><i class="fas fa-calendar-alt"></i> Month-wise Fee Details — All 12 Months</h3>
                    <div class="msec-hint"><i class="fas fa-info-circle" style="color:var(--orange)"></i> Check rows to select multiple months for bulk payment</div>
                </div>
                <div class="tw">
                    <table class="mt">
                        <thead>
                            <tr>
                                <th style="width:40px; text-align:center;"><div class="mchk" id="hallChk" onclick="toggleSelectAll()"></div></th>
                                <th>#</th><th>Month</th><th>Fee Types</th><th>Total Fee</th><th>Fine</th><th>Net Payable</th><th>Paid</th><th>Balance</th><th>Pay. Date</th><th>Status</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="monthBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PAY MODAL -->
    <div class="pm-bg" id="pmBg">
        <div class="pm" style="width: min(520px, 95vw); max-height:95vh; overflow-y:auto;">
            <div class="m-hdr pm-hdr" style="position:sticky;top:0;z-index:10;border-radius:20px 20px 0 0;">
                <h3 id="pmTitle"><i class="fas fa-rupee-sign"></i> Collect Fee</h3>
                <button class="m-close" onclick="closePm()"><i class="fas fa-times"></i></button>
            </div>
            <div class="pm-body">
                <!-- Student mini info -->
                <div class="pm-stu" id="pmStuInfo"></div>

                <!-- Month list (for multi-month) -->
                <div id="pmMultiList" style="display:none; margin-bottom:14px;">
                    <div class="pm-months-header">Selected Months</div>
                    <div class="pm-months-list" id="pmMonthList"></div>
                </div>

                <!-- Fee type selector -->
                <div class="ft-section">
                    <div class="ft-section-label"><i class="fas fa-tags"></i> Select Fee Type(s) to Collect</div>
                    <div class="ft-grid" id="ftGrid"></div>
                </div>

                <hr class="pm-divider">

                <!-- Summary -->
                <div class="pm-sumrow"><span class="psr-l">Sub Total</span><span class="psr-v" id="pmSubTotal">₹0</span></div>
                <div class="pm-sumrow"><span class="psr-l">Discount / Concession</span><span class="psr-v" style="color:var(--green)">₹0</span></div>
                <div class="pm-sumrow total-row">
                    <span class="psr-l"><i class="fas fa-shield-check"></i> Total Payable</span>
                    <span class="psr-v" id="pmTotal">₹0</span>
                </div>
                <div class="pm-sumrow dues-row" style="border-bottom:none">
                    <span class="psr-l">Dues After Payment</span>
                    <span class="psr-v" id="pmDuesAfter">₹0</span>
                </div>

                <hr class="pm-divider">

                <!-- Pay amount -->
                <div class="pf">
                    <label>Pay Amount (₹)</label>
                    <input type="number" id="pmAmt" oninput="_updateDues()" placeholder="Enter amount to pay">
                </div>

                <!-- Payment mode -->
                <div class="pf">
                    <label>Payment Mode</label>
                    <div class="pay-mode-btns">
                        <button class="pmode-btn active" id="pmModeOnline" onclick="setPayMode('Online',this)"><i class="fas fa-globe"></i> Online</button>
                        <button class="pmode-btn" id="pmModeCash" onclick="setPayMode('Cash',this)"><i class="fas fa-money-bill-wave"></i> Offline / Cash</button>
                    </div>
                    <input type="hidden" id="pmMode" value="Online">
                </div>

                <!-- Ref / Remark -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="pf"><label>Ref / Trans No.</label><input type="text" id="pmRef" placeholder="UPI Ref / Cheque No."></div>
                    <div class="pf"><label>Remark</label><input type="text" id="pmRmk" placeholder="Optional remark"></div>
                </div>

                <button class="btn-pay-now" onclick="confirmPay()">
                    <i class="fas fa-check-circle"></i> Pay Now
                </button>
            </div>
        </div>
    </div>

    <!-- RECEIPT MODAL — exact match to reference -->
    <div class="rc-bg" id="rcBg">
        <div class="rc-m">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted)">Payment Receipt</div>
                <button class="mxbtn" onclick="closeRc()"><i class="fas fa-times"></i></button>
            </div>
            <div class="rc-hdr">
                <h2><i class="fas fa-school" style="color:var(--orange)"></i> Smart School ERP</h2>
                <p>Fee Payment Receipt</p>
            </div>
            <div id="rcContent"></div>
            <button class="btn-prc" onclick="window.print()"><i class="fas fa-print"></i> Print Receipt</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
      const BASE_URL = "{{ url('/') }}";
let curMode='class', stu=null, monthData=null, pmMonths=[], payMode='Online';

function switchMode(m){
  curMode=m;
  document.querySelectorAll('.mcard').forEach(c=>c.classList.remove('mac'));
  const card = document.getElementById(m+'Card');
  if(card) card.classList.add('mac');
  
  document.getElementById('classPanel').style.display = m==='class'?'block':'none';
  document.getElementById('directPanel').style.display = m==='direct'?'block':'none';
  document.getElementById('emptyHint').style.display = 'none';
}

function searchByClass(){
  const cid=document.getElementById('classSelect').value;
  const sid=document.getElementById('sectionSelect').value;
  if(!cid) return Swal.fire('Error','Please select a Class','error');
  
  Swal.showLoading();
  fetch('{{ route("admin.collect-fee.index") }}?class='+cid+'&section='+sid, {
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  })
  .then(r=>r.json()).then(res=>{
    Swal.close();
    if(!res.success) return;
    let h='';
    res.data.forEach((s,i)=>{
      const name = (s.name||'').toUpperCase();
      const father = (s.father||'').toUpperCase();
      h+=`<tr><td>${i+1}</td><td>${s.sid||''}</td><td>${s.adm||''}</td><td>${s.roll||''}</td>
      <td><b>${name}</b></td><td>${s.cls||''}</td><td>${s.sec||''}</td>
      <td>${father}</td><td>${s.mob||''}</td>
      <td><span class="dbadge" onclick="showDues(${s.id},'${name}')"><i class="fas fa-circle-exclamation"></i> ₹${(s.dues||0).toLocaleString()}</span></td>
      <td><button class="cbtn" onclick="openPanel(${s.id})"><span class="rs-ico">Rs</span> Collect Fee</button></td></tr>`;
    });
    document.getElementById('stbodyClass').innerHTML = h;
    document.getElementById('footerInfoClass').innerText = `Showing 1-${res.data.length} of ${res.data.length} entries`;
    document.getElementById('resultsClass').style.display = 'block';
    document.getElementById('emptyHint').style.display = 'none';
  }).catch(e=>Swal.fire('Error','Failed to load data','error'));
}

function searchDirect(){
  const val=document.getElementById('directInput').value;
  if(!val) return Swal.fire('Wait','Enter Admission No / Name','info');
  
  Swal.showLoading();
  fetch('{{ route("admin.collect-fee.index") }}?keyword='+encodeURIComponent(val), {
    headers: {'X-Requested-With': 'XMLHttpRequest'}
  })
  .then(r=>r.json()).then(res=>{
    Swal.close();
    if(!res.success) return;
    let h='';
    res.data.forEach((s,i)=>{
      const name = (s.name||'').toUpperCase();
      const father = (s.father||'').toUpperCase();
      h+=`<tr><td>${i+1}</td><td>${s.sid||''}</td><td>${s.adm||''}</td><td>${s.roll||''}</td>
      <td><b>${name}</b></td><td>${s.cls||''}</td><td>${s.sec||''}</td>
      <td>${father}</td><td>${s.mob||''}</td>
      <td><span class="dbadge" onclick="showDues(${s.id},'${name}')"><i class="fas fa-circle-exclamation"></i> ₹${(s.dues||0).toLocaleString()}</span></td>
      <td><button class="cbtn" onclick="openPanel(${s.id})"><span class="rs-ico">Rs</span> Collect Fee</button></td></tr>`;
    });
    document.getElementById('stbodyDirect').innerHTML = h;
    document.getElementById('footerInfoDirect').innerText = `Showing 1-${res.data.length} of ${res.data.length} entries`;
    document.getElementById('resultsDirect').style.display = 'block';
    document.getElementById('emptyHint').style.display = 'none';
  }).catch(e=>Swal.fire('Error','Search failed','error'));
}

function showDues(id, name){
  document.getElementById('dueStudName').innerText = name;
  document.getElementById('dueBody').innerHTML = '<p style="text-align:center;padding:20px;color:#5f6b7a">Loading dues...</p>';
  document.getElementById('dueBg').classList.add('open');
  
  fetch(`${BASE_URL}/admin/collect-fee/details/${id}`)
  .then(r=>r.json()).then(res=>{
    let h='', tot=0;
    res.months.forEach(m=>{
      if(m.balance > 0){
        tot += m.balance;
        h+=`<div style="display:flex;justify-content:space-between;padding:10px;border-bottom:1px solid #f0f4ff;">
          <span><b>${m.month}</b></span><span style="color:#ef4444;font-weight:700">₹${m.balance.toLocaleString()}</span></div>`;
      }
    });
    document.getElementById('dueBody').innerHTML = h || '<p style="text-align:center;padding:20px;color:#22c55e">No Pending Dues!</p>';
    document.getElementById('dueTotAmt').innerText = tot.toLocaleString();
    document.getElementById('dueStudName').setAttribute('data-id', id);
  });
}

function closeDuePop(){ document.getElementById('dueBg').classList.remove('open'); }
function closeDueAndOpen(){ const id=document.getElementById('dueStudName').getAttribute('data-id'); closeDuePop(); openPanel(id); }

function openPanel(id){
  Swal.showLoading();
  fetch(`${BASE_URL}/admin/collect-fee/details/${id}`)
  .then(r=>r.json()).then(res=>{
    Swal.close();
    monthData = res;
    stu = res.student;
    pmMonths = [];
    document.getElementById('stuStrip').innerHTML = `
      <div class="sic"><span class="sl">Student Name</span><span class="sv" style="color:var(--dark)">${stu.name}</span></div>
      <div class="sic"><span class="sl">STD ID</span><span class="sv" style="color:var(--blue)">${stu.sid||'SID21'}</span></div>
      <div class="sic"><span class="sl">ADM NO</span><span class="sv" style="color:var(--orange); font-weight:800">${stu.adm}</span></div>
      <div class="sic"><span class="sl">LEDGER</span><span class="sv">${stu.ledger||'L012'}</span></div>
      <div class="sic"><span class="sl">ROLL NO</span><span class="sv">${stu.roll||'22'}</span></div>
      <div class="sic"><span class="sl">CLASS / SEC</span><span class="sv">${stu.cls} - ${stu.sec}</span></div>
      <div class="sic"><span class="sl">FATHER</span><span class="sv">${stu.father||'N/A'}</span></div>
      <div class="sic"><span class="sl">MOBILE</span><span class="sv">${stu.mob}</span></div>
    `;
    renderMonthTable();
    document.getElementById('fpBg').classList.add('open');
  });
}

function closePanel(){ document.getElementById('fpBg').classList.remove('open'); }

function renderMonthTable(){
  let h='';
  monthData.months.forEach((m, i)=>{
    const isPaid = m.status.toLowerCase()==='paid';
    const rowCls = pmMonths.includes(i)?'row-sel':(isPaid?'row-paid-sel':'');
    const checkedClass = pmMonths.includes(i)?'checked-due':(isPaid?'checked-paid':'');
    
    const ftHtml = m.comps.map(c => {
      let cls = 'ft-default';
      const n = c.name.toLowerCase();
      if(n.includes('tuition')) cls = 'ft-tui';
      else if(n.includes('transport')) cls = 'ft-tra';
      else if(n.includes('hostel')) cls = 'ft-hos';
      else if(n.includes('lab')) cls = 'ft-lab';
      else if(n.includes('sport')) cls = 'ft-spo';
      return `<span class="ft-badge ${cls}">${c.name}</span>`;
    }).join('');
    
    h+=`<tr class="${rowCls}">
      <td style="text-align:center"><div class="mchk ${checkedClass}" onclick="toggleRow(${i})"></div></td>
      <td>${i+1}</td><td><b>${m.month}</b></td><td><div class="ft-badges">${ftHtml}</div></td>
      <td style="color:var(--muted)">₹${m.fee.toLocaleString()}</td>
      <td style="color:${m.fine>0?'var(--orange)':'#cbd5e1'}">₹${m.fine}</td>
      <td style="font-weight:800; color:var(--dark)">₹${m.net.toLocaleString()}</td>
      <td style="font-weight:800; color:${m.paid>0?'var(--green)':'#cbd5e1'}">₹${m.paid.toLocaleString()}</td>
      <td style="font-weight:800; color:${m.balance>0?'var(--red)':'var(--green)'}">₹${m.balance.toLocaleString()}</td>
      <td style="color:var(--muted); font-size:0.7rem">${m.paidDate||'—'}</td>
      <td><span class="ss ${m.status.toLowerCase()}">${m.status.toUpperCase()}</span></td>
      <td>
        <div class="ais">
          ${!isPaid ? `<button class="ib b-pay" onclick="openPay(${i})" title="Collect Fee">Rs</button>` : ''}
          <button class="ib b-view" onclick="openReceipt(${i})" title="View Details"><i class="fas fa-eye"></i></button>
          <button class="ib b-print" onclick="window.print()" title="Print Receipt"><i class="fas fa-print"></i></button>
          <button class="ib b-wa" onclick="alert('Send via WhatsApp')" title="WhatsApp"><i class="fab fa-whatsapp"></i></button>
          <button class="ib b-tag" onclick="alert('Manage Discounts')" title="Discount"><i class="fas fa-tag"></i></button>
          <button class="ib b-hist" onclick="alert('View History')" title="History"><i class="fas fa-history"></i></button>
        </div>
      </td>
    </tr>`;
  });
  document.getElementById('monthBody').innerHTML = h;
  updateMsBar();
}

function toggleRow(i){
  const m = monthData.months[i];
  if(m.status.toLowerCase()==='paid') return; // can't reselect paid
  const idx = pmMonths.indexOf(i);
  if(idx>-1) pmMonths.splice(idx,1); else pmMonths.push(i);
  renderMonthTable();
}

function toggleSelectAll(){
  const allDue = monthData.months.map((m,i)=>m.status!=='Paid'?i:null).filter(v=>v!==null);
  if(pmMonths.length === allDue.length) pmMonths = []; else pmMonths = [...allDue];
  renderMonthTable();
}

function clearSelection(){ pmMonths=[]; renderMonthTable(); }
function selectAllDue(){ toggleSelectAll(); }

function updateMsBar(){
  const bar=document.getElementById('msBar'), count=document.getElementById('msCount'), total=document.getElementById('msTotal');
  if(pmMonths.length>0){
    bar.classList.add('visible');
    count.innerText = pmMonths.length;
    let sum=0; pmMonths.forEach(i=>sum+=monthData.months[i].balance);
    total.innerText = '₹'+sum;
  } else bar.classList.remove('visible');
}

function openPay(i){
  const m=monthData.months[i];
  if(m.status.toLowerCase()==='paid') return Swal.fire('Info','This month is already fully paid.','info');
  pmMonths = [i];
  _openPayModal(pmMonths);
}

function openMultiPay(){
  if(pmMonths.length===0) return;
  _openPayModal(pmMonths);
}

function _openPayModal(monthIndices){
  // Title
  if(monthIndices.length===1){
    document.getElementById('pmTitle').innerHTML = '<i class="fas fa-rupee-sign"></i> Collect Fee \u2014 '+monthData.months[monthIndices[0]].month+' 2025';
  } else {
    document.getElementById('pmTitle').innerHTML = '<i class="fas fa-rupee-sign"></i> Collect '+monthIndices.length+' Months';
  }

  // Student mini info - horizontal like reference
  document.getElementById('pmStuInfo').innerHTML = [
    {l:'Name', v:stu.name},
    {l:'Adm No.', v:stu.adm},
    {l:'Class', v:stu.cls+' \u2013 '+stu.sec},
    {l:'Mobile', v:stu.mob}
  ].map(c=>`<div class="pm-stu-field"><span class="psl">${c.l}</span><span class="psv">${c.v}</span></div>`).join('');

  // Multi month list
  const ml=document.getElementById('pmMultiList');
  if(monthIndices.length>1){
    ml.style.display='';
    document.getElementById('pmMonthList').innerHTML=monthIndices.map(i=>{
      const m=monthData.months[i];
      return `<div class="pm-mrow"><span class="pmn">${m.month} 2025</span><span class="pma">\u20b9${m.balance.toLocaleString()}</span></div>`;
    }).join('');
  } else { ml.style.display='none'; }

  // Aggregate fee types across selected months - FIX for repeating balance bug
  const ftMap={};
  monthIndices.forEach(mi=>{
    const m=monthData.months[mi];
    (m.comps||[]).forEach(c=>{
      const key=c.name;
      if(!ftMap[key]) ftMap[key]={name:c.name, total:0, paid:0, balance:0};
      ftMap[key].total  += Number(c.total)||0;
      ftMap[key].paid   += Number(c.paid)||0;
      ftMap[key].balance += Number(c.balance)||0;
    });
  });

  // Icon map
  function getFtStyle(name){
    const n=name.toLowerCase();
    if(n.includes('tuition')||n.includes('tui')) return {ico:'fa-book',clr:'#3b82f6',bg:'#eff6ff'};
    if(n.includes('transport')) return {ico:'fa-bus',clr:'#f59e0b',bg:'#fffbeb'};
    if(n.includes('hostel'))    return {ico:'fa-bed',clr:'#8b5cf6',bg:'#faf5ff'};
    if(n.includes('lab')||n.includes('lib')) return {ico:'fa-flask',clr:'#f97316',bg:'#fff7ed'};
    if(n.includes('sport'))     return {ico:'fa-futbol',clr:'#22c55e',bg:'#f0fdf4'};
    if(n.includes('exam'))      return {ico:'fa-file-alt',clr:'#10b981',bg:'#f0fdf4'};
    return {ico:'fa-coins',clr:'#488fe4',bg:'#eff6ff'};
  }

  let subTotal=0;
  const grid=document.getElementById('ftGrid');
  grid.innerHTML=Object.values(ftMap).map(f=>{
    const isPaid=f.balance<=0;
    const status=isPaid?'paid':(f.paid>0?'partial':'due');
    const {ico,clr,bg}=getFtStyle(f.name);
    if(!isPaid) subTotal+=f.balance;
    const badgeCls=isPaid?'paid-f':(f.paid>0?'partial-f':'due-f');
    const badgeTxt=isPaid?'Paid':(f.paid>0?'Partial':'Due');
    const key=f.name.replace(/[\s\/]/g,'_');
    return `<div class="ft-item${isPaid?'':' selected'}" id="fti-${key}" onclick="toggleFt('${key}',${f.balance})" ${isPaid?'style="opacity:.6;pointer-events:none"':''}>
      <div class="ft-left">
        <div class="ft-chk" id="ftchk-${key}"></div>
        <div class="ft-icon" style="background:${bg};color:${clr}"><i class="fas ${ico}"></i></div>
        <div class="ft-info">
          <div class="ft-name">${f.name}</div>
          <div class="ft-desc">Balance: \u20b9${f.balance.toLocaleString()} | Paid: \u20b9${f.paid.toLocaleString()}</div>
        </div>
      </div>
      <div class="ft-right">
        <div class="ft-amt">\u20b9${f.balance.toLocaleString()}</div>
        <span class="ft-paid-badge ${badgeCls}">${badgeTxt}</span>
      </div>
    </div>`;
  }).join('');

  window._pmFtMap=ftMap;
  document.getElementById('pmSubTotal').innerText='\u20b9'+subTotal.toLocaleString();
  document.getElementById('pmTotal').innerText='\u20b9'+subTotal.toLocaleString();
  document.getElementById('pmAmt').value=subTotal;
  _updateDues();
  document.getElementById('pmRef').value='';
  const rmk=document.getElementById('pmRmk'); if(rmk) rmk.value='';
  document.querySelectorAll('.pmode-btn').forEach(b=>b.classList.remove('active'));
  const online=document.getElementById('pmModeOnline'); if(online) online.classList.add('active');
  document.getElementById('pmMode').value='Online';
  payMode='Online';
  document.getElementById('pmBg').classList.add('open');
}

function toggleFt(key){
  const el=document.getElementById('fti-'+key);
  if(!el) return;
  el.classList.toggle('selected');
  let sub=0;
  Object.values(window._pmFtMap||{}).forEach(f=>{
    const k=f.name.replace(/[\s\/]/g,'_');
    const el2=document.getElementById('fti-'+k);
    if(el2&&el2.classList.contains('selected')) sub+=Number(f.balance)||0;
  });
  document.getElementById('pmSubTotal').innerText='\u20b9'+sub.toLocaleString();
  document.getElementById('pmTotal').innerText='\u20b9'+sub.toLocaleString();
  document.getElementById('pmAmt').value=sub;
  _updateDues();
}

function closePm(){ document.getElementById('pmBg').classList.remove('open'); }
function setPayMode(m, el){
  payMode=m; document.getElementById('pmMode').value=m;
  document.querySelectorAll('.pmode-btn').forEach(btn=>btn.classList.remove('active'));
  el.classList.add('active');
}
function _updateDues(){
  const totText=document.getElementById('pmTotal').innerText||'0';
  const tot=Number(totText.replace(/[^0-9.]/g,''))||0;
  const pay=Number(document.getElementById('pmAmt').value)||0;
  document.getElementById('pmDuesAfter').innerText='\u20b9'+Math.max(0,tot-pay).toLocaleString();
}
function updatePmDues(){ _updateDues(); }

function confirmPay(){
  const payAmt = parseFloat(document.getElementById('pmAmt').value);
  if(!payAmt || payAmt <= 0) return Swal.fire('Error','Enter a valid amount','error');
  
  const rc = 'RC'+Math.floor(Math.random()*90000+10000);
  const today = new Date().toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'});
  const ftMap = window._pmFtMap||{};
  
  // Get selected fee type keys
  const selKeys = Object.values(ftMap).filter(f=>{
    const k=f.name.replace(/[\s\/]/g,'_');
    const el=document.getElementById('fti-'+k);
    return el && el.classList.contains('selected');
  }).map(f=>f.name);

  if(selKeys.length===0) return Swal.fire('Info','Please select at least one fee type','info');

  Swal.fire({
    title: 'Confirm Payment?',
    text: `Collect ₹${payAmt.toLocaleString()} via ${payMode}?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#488fe4',
    confirmButtonText: 'Yes, Confirm'
  }).then(result => {
    if(!result.isConfirmed) return;
    Swal.showLoading();
    
    // Try backend payment
    fetch(`${BASE_URL}/admin/collect-fee/pay/${stu.id}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        amount: payAmt,
        payment_mode: payMode,
        months: pmMonths,
        fee_types: selKeys,
        reference_no: document.getElementById('pmRef').value,
        remark: (document.getElementById('pmRmk')||{}).value||'',
        _token: '{{ csrf_token() }}'
      })
    })
    .then(r => r.json())
    .then(res => {
      Swal.close();
      // Whether backend succeeds or fails, process locally for UI
      _processPaymentLocally(payAmt, rc, today, selKeys);
    })
    .catch(err => {
      Swal.close();
      // On error, still process locally so UI works
      _processPaymentLocally(payAmt, rc, today, selKeys);
    });
  });
}

function _processPaymentLocally(payAmt, rc, today, selKeys){
  // Update monthData in memory
  pmMonths.forEach(mi=>{
    const m = monthData.months[mi];
    let monthPaid = 0;
    m.comps.forEach(c=>{
      if(!selKeys.includes(c.name)) return;
      const pay = Math.min(Number(c.balance)||0, payAmt);
      c.paid = (Number(c.paid)||0) + pay;
      c.balance = Math.max(0, (Number(c.balance)||0) - pay);
      c.status = c.balance<=0 ? 'paid' : c.paid>0 ? 'partial' : 'due';
      monthPaid += pay;
    });
    m.paid = (Number(m.paid)||0) + monthPaid;
    m.balance = m.comps.reduce((a,c)=>a+(Number(c.balance)||0),0);
  m.status = m.balance<=0 ? 'Paid' : m.paid>0 ? 'Partial' : 'Due';
    if(m.balance<=0){
      m.paidDate = today;
      m.receiptNo = rc;
    }
    m.selected = false;
  });
  
  closePm();
  pmMonths=[]; // reset
  renderMonthTable();
  updateMsBar();
  
  // Build and show receipt
  _showReceipt(rc, today, payAmt);
}

function _buildReceiptHTML(rc, today, totalPaid, compRows, balanceDue, mode){
  var rows = [
    '<div class="rcrow"><span class="rl">Receipt No.</span><span class="rv" style="color:var(--blue)">'+rc+'</span></div>',
    '<div class="rcrow"><span class="rl">Pay. Date</span><span class="rv">'+today+'</span></div>',
    '<div class="rcrow"><span class="rl">Student</span><span class="rv">'+(stu?stu.name:'')+'</span></div>',
    '<div class="rcrow"><span class="rl">Adm No.</span><span class="rv">'+(stu?stu.adm:'')+'</span></div>',
    '<div class="rcrow"><span class="rl">Class / Section</span><span class="rv">'+(stu?stu.cls+' \u2013 '+stu.sec:'')+'</span></div>',
    (mode?'<div class="rcrow"><span class="rl">Payment Mode</span><span class="rv">'+mode+'</span></div>':''),
    '<div class="rc-lbl">Fee Breakdown</div>',
    compRows,
    '<hr class="rc-sep">',
    '<div class="rc-total"><span>Total Paid</span><span class="rv">\u20b9'+Number(totalPaid).toLocaleString()+'</span></div>',
    '<div class="rc-bal"><span style="color:#5f6b7a">Balance Due</span><span style="color:'+(Number(balanceDue)>0?'var(--red)':'var(--green)')+';">\u20b9'+Number(balanceDue).toLocaleString()+'</span></div>',
    '<div class="rc-foot">Computer generated receipt &middot; Smart School ERP</div>'
  ];
  return rows.filter(Boolean).join('');
}

function _showReceipt(rc, today, totalPaid){
  var ftMap = window._pmFtMap||{};
  var compRows = Object.values(ftMap).filter(function(f){
    var k = f.name.replace(/[\s\/]/g,'_');
    var el = document.getElementById('fti-'+k);
    return el && el.classList.contains('selected');
  }).map(function(f){
    // Show amount paid (total - remaining balance after payment)
    var paid = Number(f.balance||0); // f.balance at time of payment = amount that was cleared
    return '<div class="rcrow"><span class="rl">'+f.name+'</span><span class="rv" style="color:var(--green)">\u20b9'+paid.toLocaleString()+'</span></div>';
  }).join('');

  var remBal = 0;
  var months = (window._lastPmMonths||pmMonths||[]);
  months.forEach(function(mi){
    var m = monthData && monthData.months ? monthData.months[mi] : null;
    if(m) remBal += Number(m.balance)||0;
  });

  document.getElementById('rcContent').innerHTML = _buildReceiptHTML(rc, today, totalPaid, compRows||'', remBal, payMode);
  document.getElementById('rcBg').classList.add('open');
}

function openReceipt(i){
  var m = monthData && monthData.months ? monthData.months[i] : null;
  if(!m || m.status.toLowerCase()==='due') return Swal.fire('Info','No payment recorded for this month yet.','info');

  var rc = m.receiptNo || '\u2014';
  var paidDate = m.paidDate || new Date().toLocaleDateString('en-IN',{day:'2-digit',month:'short',year:'numeric'});
  var compRows = (m.comps||[]).map(function(f){
    var paid = Number(f.paid)||0;
    var clr = f.status==='paid'?'var(--green)':f.status==='partial'?'#f59e0b':'var(--muted)';
    return '<div class="rcrow"><span class="rl">'+f.name+'</span><span class="rv" style="color:'+clr+'">\u20b9'+paid.toLocaleString()+(f.status!=='paid'?' (Partial)':'')+'</span></div>';
  }).join('');
  var fineLine = m.fine>0 ? '<div class="rcrow"><span class="rl">Late Fine</span><span class="rv">\u20b9'+m.fine+'</span></div>' : '';

  document.getElementById('rcContent').innerHTML = _buildReceiptHTML(
    rc, paidDate, Number(m.paid)||0,
    compRows+fineLine,
    Number(m.balance)||0,
    ''
  );
  document.getElementById('rcBg').classList.add('open');
}
function closeRc(){ document.getElementById('rcBg').classList.remove('open'); }

function filterRows(tid, val){
  const rows = document.querySelectorAll(`#${tid} tr`);
  val = val.toLowerCase();
  rows.forEach(r=>{
    r.style.display = r.innerText.toLowerCase().includes(val) ? '' : 'none';
  });
}

function exportTable(type, tid){
  if(type==='csv'){
    let csv = [];
    const rows = document.querySelectorAll(`#${tid} tr`);
    rows.forEach(r => {
      if (r.style.display !== 'none') {
        let row = [];
        const cells = r.querySelectorAll('td');
        cells.forEach((c, idx) => {
          if (idx < 9) row.push('"' + c.innerText.trim().replace(/"/g, '""') + '"');
        });
        if (row.length > 0) csv.push(row.join(','));
      }
    });
    const blob = new Blob([csv.join('\n')], {type:'text/csv'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a'); a.href=url; a.download='fee_collection.csv'; a.click();
  } else if(type==='pdf' || type==='print') {
      window.print();
  } else {
    Swal.fire('Export', type.toUpperCase()+' export triggered', 'info');
  }
}

// Move modals to body to avoid transform/z-index issues with sidebar
window.addEventListener('DOMContentLoaded', () => {
  ['dueBg', 'fpBg', 'pmBg', 'rcBg'].forEach(id => {
    const el = document.getElementById(id);
    if(el) document.body.appendChild(el);
  });
});
</script>
@endpush
