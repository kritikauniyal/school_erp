@extends('layouts.app')

@section('title', 'Collect Fee')

@section('page-title','Collect Fee')

@push('styles')
<style>
:root{
  --blue:#488fe4;--orange:#ff913b;--blu:#e3f0ff;--olt:#fff4ea;
  --bg:#f5f9ff;--dark:#1e293b;--muted:#5f6b7a;--sidebar:#435471;
  --sh:0 4px 18px rgba(0,20,50,.07);
  --green:##22c55e;--red:#ef4444;--yellow:#f59e0b;--purple:#7c3aed;
}

.cf-wrap { font-family: 'Inter', sans-serif; }
.cf-wrap .card{background:#fff;border-radius:22px;box-shadow:0 4px 18px rgba(0,20,50,.07);padding:22px 24px;margin-bottom:20px}
.cf-wrap .ctitle{display:flex;align-items:center;gap:10px;margin-bottom:20px}
.cf-wrap .ctitle h2{font-size:1.32rem;font-weight:700;color:#488fe4;margin:0}
.cf-wrap .ctitle i{font-size:1.32rem;color:#ff913b}

/* MODE CARDS */
.mode-wrap{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px}
@media(max-width:600px){.mode-wrap{grid-template-columns:1fr}}
.mcard{border-radius:18px;cursor:pointer;border:2.5px solid #e4ecf7;transition:all .28s cubic-bezier(.2,.9,.3,1);position:relative;overflow:hidden;background:#fff;box-shadow:0 2px 10px rgba(0,20,50,.05)}
.mcard:hover:not(.mac){border-color:#c5d8f2;box-shadow:0 6px 20px rgba(0,20,50,.1);transform:translateY(-2px)}
.mc-inner{padding:16px 18px;display:flex;align-items:center;gap:14px}
.mc-ico{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;transition:.28s;flex-shrink:0}
.mc-lbl{font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.9px;margin-bottom:3px}
.mc-ttl{font-size:.93rem;font-weight:700;color:#1e293b}
.mc-check{display:none;position:absolute;right:14px;top:50%;transform:translateY(-50%);width:21px;height:21px;border-radius:50%;align-items:center;justify-content:center;font-size:.67rem}
.mac .mc-check{display:flex}
.class-mc .mc-ico{background:#e3f0ff;color:#488fe4}.class-mc .mc-lbl{color:#488fe4}.class-mc .mc-check{background:#488fe4;color:#fff}
.class-mc.mac{border-color:#488fe4;background:linear-gradient(140deg,#eaf4ff,#f5faff);box-shadow:0 8px 28px rgba(72,143,228,.2)}
.class-mc.mac .mc-ico{background:#488fe4;color:#fff}
.direct-mc .mc-ico{background:#fff4ea;color:#ff913b}.direct-mc .mc-lbl{color:#ff913b}.direct-mc .mc-check{background:#ff913b;color:#fff}
.direct-mc.mac{border-color:#ff913b;background:linear-gradient(140deg,#fff8f2,#fffcf8);box-shadow:0 8px 28px rgba(255,145,59,.2)}
.direct-mc.mac .mc-ico{background:#ff913b;color:#fff}

/* FILTER ROW */
.fr{display:flex;flex-wrap:wrap;align-items:flex-end;gap:20px;background:#fff;padding:20px 24px;border-radius:17px;margin-bottom:20px;border:1.5px solid #ecf2f8}
.fg{display:flex;flex-direction:column;min-width:150px;flex:1 1 200px}
.fg label{font-size:.68rem;text-transform:uppercase;font-weight:800;color:#488fe4;margin-bottom:8px;letter-spacing:.8px}
.fg select, .fg input{background:#fff;border:1.5px solid #e2e8f0;border-radius:30px;padding:12px 18px;font-size:.85rem;color:#1e293b;outline:none;transition:.2s; -webkit-appearance: none;}
.fg select{background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath fill='%23488fe4' d='M201.4 342.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 274.7 86.6 137.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z'/%3E%3C/svg%3E") no-repeat right 16px center/12px;}
.sbtn{background:linear-gradient(135deg,#488fe4,#2d6abf);color:#fff;border:none;padding:12px 32px;border-radius:30px;font-weight:700;font-size:.88rem;cursor:pointer;display:flex;align-items:center;gap:8px;transition:.2s;white-space:nowrap; box-shadow: 0 4px 12px rgba(72,143,228,0.3);}
.sbtn:hover{transform: translateY(-1px); box-shadow: 0 6px 18px rgba(72,143,228,0.4);}

/* DIRECT SEARCH */
.dsw{background:#fff9f5;padding:16px 18px;border-radius:17px;margin-bottom:15px;border:1.5px solid #ffe4c8}
.dsi{display:flex;align-items:center;background:#fff;border:1.5px solid #e8e0d8;border-radius:40px;padding:4px 4px 4px 16px;gap:8px}
.dsi input{border:none;background:transparent;padding:10px 8px;font-size:.9rem;width:100%;outline:none}
.dsi button{background:linear-gradient(135deg,#ff913b,#e07a2a);color:#fff;border:none;padding:9px 20px;border-radius:36px;font-weight:700;font-size:.83rem;cursor:pointer}

/* EXPORT + TABLE */
.tbar{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:18px;flex-wrap:wrap}
.tb-btns{display:flex;gap:7px;flex-wrap:wrap}
.ex-btn{background:#fff;color:#488fe4;border:1.5px solid #488fe4;padding:6px 16px;border-radius:30px;font-size:0.75rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:7px;transition:.2s;box-shadow: 0 2px 5px rgba(0,0,0,0.02)}
.ex-btn i{font-size: 0.8rem;}
.ex-btn:hover{background:#488fe4;color:#fff}

.tb-search{position:relative;flex:1;max-width:240px;display:flex;align-items:center}
.tb-search i{position:absolute;left:15px;color:#94a3b8;font-size:0.9rem}
.tb-search input{width:100%;border:1px solid #e2e8f0;border-radius:30px;padding:9px 15px 9px 40px;font-size:0.82rem;outline:none;background:#fff;color:#1e293b;transition:.2s}
.tb-search input:focus{border-color:#488fe4;box-shadow:0 0 0 3px rgba(72,143,228,0.1)}
.tb-search input::placeholder{color:#94a3b8;font-weight:400}

.tw{overflow-x:auto;border-radius:12px;background:#fff;box-shadow:0 4px 18px rgba(0,20,50,.07);margin-bottom:20px; border: 1px solid #eef2f6;}
table{width:100%;border-collapse:collapse;min-width:980px}
thead th{background:#488fe4;color:#fff;font-weight:600;font-size:.72rem;text-transform:uppercase;padding:15px 12px;text-align:left; letter-spacing: 0.3px;}
tbody tr:hover{background-color: #f8fbff;}
tbody td{padding:14px 12px;border-bottom:1px solid #f1f5f9;color:#1e293b;font-size:.82rem}

.cbtn{background:linear-gradient(135deg,#ff913b,#f57c00);color:#fff;border:none;padding:8px 18px;border-radius:30px;font-weight:700;font-size:.76rem;cursor:pointer;display:inline-flex;align-items:center;gap:7px;box-shadow: 0 4px 12px rgba(255,145,59,0.25);transition: .2s;}
.cbtn:hover{transform: translateY(-1px); box-shadow: 0 6px 15px rgba(255,145,59,0.35);}
.dbadge{background:#fff1f0;color:#ef4444;padding:6px 12px;border-radius:30px;font-size:.78rem;font-weight:700;border:1px solid #ffccc7;cursor:pointer;display:inline-flex;align-items:center;gap:6px}
.dbadge i{font-size: 0.85rem;}
.dbadge:hover{background: #ffccc7;}

/* MODALS */
.due-bg, .fp-bg, .pm-bg, .rc-bg{display:none;position:fixed;inset:0;background:rgba(8,18,46,.6);z-index:3000;align-items:center;justify-content:center;backdrop-filter:blur(6px);overflow-y:auto}
.due-bg.open, .fp-bg.open, .pm-bg.open, .rc-bg.open{display:flex}
.due-box, .fp-box, .pm, .rc-m{background:#fff;border-radius:22px;box-shadow:0 28px 72px rgba(0,18,56,.28);animation:popIn .32s ease;margin:auto}
@keyframes popIn{from{transform:scale(.93) translateY(18px);opacity:0}to{transform:scale(1) translateY(0);opacity:1}}
.m-hdr{color:#fff;padding:17px 23px;position:relative}
.m-hdr h2, .m-hdr h3{font-size:1.08rem;font-weight:700;display:flex;align-items:center;gap:9px;margin:0}
.m-close{position:absolute;right:16px;top:16px;background:rgba(255,255,255,.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer}
.fp-hdr, .pm-hdr{background:linear-gradient(135deg,#488fe4,#2463b8)}

.sstrip{display:flex;flex-wrap:wrap;gap:7px 20px;padding:11px 23px;background:#f0f7ff;border-bottom:1.5px solid #dde8f5}
.sic .sl{font-size:.57rem;text-transform:uppercase;font-weight:700;color:#488fe4;display:block}
.sic .sv{font-size:.82rem;font-weight:700}

.ms-bar{display:none;align-items:center;justify-content:space-between;gap:12px;background:linear-gradient(135deg,#fff8f2,#fff4ea);border:2px solid #ffe4c8;border-radius:15px;padding:11px 17px;margin:11px 23px}
.ms-bar.visible{display:flex}
.ms-count-pill{background:#fff;border:1px solid #ffcc99;border-radius:30px;padding:5px 15px;display:flex;align-items:center;gap:8px}
.ms-count-circle{background:#ff913b;color:#fff;width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:900}
.ms-total{font-size:.9rem;font-weight:800;color:#ff913b}

.mchk{width:18px;height:18px;border-radius:50%;border:2px solid #cfd5e0;cursor:pointer;background:#fff;display:flex;align-items:center;justify-content:center}
.mchk.checked-due{background:#ff913b;border-color:#ff913b}
.mchk.checked-paid{background:#22c55e;border-color:#22c55e}
.mchk::after{content:'✓';color:#fff;font-size:.65rem;display:none}
.mchk.checked-due::after, .mchk.checked-paid::after{display:block}

.ss{padding:3px 10px;border-radius:15px;font-size:.65rem;font-weight:800;border:1px solid}
.ss.paid{background:#dcfce7;color:#16a34a;border-color:#86efac}
.ss.partial{background:#fff7ed;color:#ea580c;border-color:#fed7aa}
.ss.due{background:#fef3f2;color:#dc2626;border-color:#fca5a5}

/* PM MODAL SPECIFIC */
.pm-stu-mini{display:grid;grid-template-columns:repeat(4,1fr);gap:15px;background:#f8fafc;padding:15px;border-radius:14px;margin-bottom:20px;border:1px solid #edf2f7}
.pm-sm-box .pm-sm-lbl{font-size:.58rem;text-transform:uppercase;font-weight:700;color:#5f6b7a;display:block;margin-bottom:2px}
.pm-sm-box .pm-sm-val{font-size:.82rem;font-weight:700;color:#1e293b}

.pm-checklist{display:flex;flex-direction:column;gap:10px;margin-bottom:20px}
.pm-citem{display:flex;align-items:center;padding:12px 15px;background:#fff;border:1.5px solid #edf2f7;border-radius:14px;transition:.2s;gap:15px}
.pm-citem:hover{border-color:#488fe4;background:#fcfdfe}
.pm-c-info{display:flex;align-items:center;gap:12px;flex:1}
.pm-c-ico{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.9rem}
.pm-c-name{font-size:.88rem;font-weight:700;color:#1e293b}
.pm-c-tag{font-size:.68rem;color:#5f6b7a;font-weight:500}
.pm-c-amt{font-size:.92rem;font-weight:800;color:#1e293b}

.pm-mode-toggle{display:grid;grid-template-columns:1fr 1fr;gap:10px;background:#f1f5f9;padding:4px;border-radius:12px}
.pm-mode-btn{border:none;background:transparent;padding:10px;border-radius:10px;font-size:.82rem;font-weight:700;color:#5f6b7a;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:.2s}
.pm-mode-btn.active{background:#fff;color:#488fe4;box-shadow:0 3px 8px rgba(0,0,0,.08)}

.pm-pay-btn{background:linear-gradient(135deg,#ff913b,#f57c00);color:#fff;border:none;width:100%;padding:15px;border-radius:14px;font-weight:800;font-size:.95rem;cursor:pointer;box-shadow:0 6px 20px rgba(255,145,59,.3);transition:.2s}
.pm-pay-btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(255,145,59,.4)}
.ft-badge{font-size:.65rem;padding:3px 10px;border-radius:20px;font-weight:700;margin-right:4px;border:1px solid #ddd;display:inline-block;margin-bottom:2px}
.ft-tui{color:#3b82f6;background:#eff6ff;border-color:#bfdbfe}
.ft-tra{color:#f59e0b;background:#fffbeb;border-color:#fef3c7}
.ft-hos{color:#8b5cf6;background:#f5f3ff;border-color:#ddd6fe}
.ft-lab{color:#f97316;background:#fff7ed;border-color:#ffedd5}
.ft-spo{color:#10b981;background:#ecfdf5;border-color:#d1fae5}
.ft-default{color:#64748b;background:#f8fafc;border-color:#e2e8f0}
.ais{display:flex;gap:5px;flex-wrap:nowrap}
.ib{width:28px;height:28px;border-radius:8px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.78rem;transition:.2s}
.ib:hover{transform:translateY(-1px);filter:brightness(0.95)}
.b-pay{background:#fff7ed;color:#f97316;font-weight:900;font-size:0.6rem;padding:0 5px}
.b-view{background:#eff6ff;color:#3b82f6}
.b-print{background:#f0fdf4;color:##22c55e}
.b-wa{background:#f0fdf4;color:##22c55e}
.b-tag{background:#f0f9ff;color:#0ea5e9}
.b-hist{background:#faf5ff;color:#a855f7}

.tf-row i{margin-right: 5px;}
.rs-ico{background:rgba(255,255,255,0.25); color:#fff; width:22px; height:20px; border-radius:5px; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem; font-weight:800; line-height:1;}
.empty{display:flex; flex-direction:column; align-items:center; justify-content:center; padding:100px 40px; color: #94a3b8; text-align:center; gap: 15px;}
.empty p{font-weight:500; font-size:0.95rem; margin:0;}
.rs-ico{background:rgba(255,255,255,0.25); color:#fff; width:22px; height:20px; border-radius:5px; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem; font-weight:800; line-height:1;}
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
                <div class="tbar">
                    <div class="tb-btns">
                        <button class="ex-btn" onclick="exportTable('copy','stbodyClass')"><i class="fas fa-copy"></i> Copy</button>
                        <button class="ex-btn" onclick="exportTable('csv','stbodyClass')"><i class="fas fa-file-csv"></i> CSV</button>
                        <button class="ex-btn" onclick="exportTable('excel','stbodyClass')"><i class="fas fa-file-excel"></i> Excel</button>
                        <button class="ex-btn" onclick="exportTable('pdf','stbodyClass')"><i class="fas fa-file-pdf"></i> PDF</button>
                        <button class="ex-btn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    </div>
                    <div class="tb-search">
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
                <div class="dsi"><i class="fas fa-search ic1" style="color:#ff913b"></i><input type="text" id="directInput" placeholder="Admission No. / Ledger No. / Mobile No. / Student Name" onkeydown="if(event.key==='Enter')searchDirect()"><button onclick="searchDirect()"><i class="fas fa-bolt"></i> Search</button></div>
            </div>
            <div id="resultsDirect" style="display:none; margin-top:20px;">
                <div class="tbar">
                    <div class="tb-btns">
                        <button class="ex-btn" onclick="exportTable('copy','stbodyDirect')"><i class="fas fa-copy"></i> Copy</button>
                        <button class="ex-btn" onclick="exportTable('csv','stbodyDirect')"><i class="fas fa-file-csv"></i> CSV</button>
                        <button class="ex-btn" onclick="exportTable('excel','stbodyDirect')"><i class="fas fa-file-excel"></i> Excel</button>
                        <button class="ex-btn" onclick="exportTable('pdf','stbodyDirect')"><i class="fas fa-file-pdf"></i> PDF</button>
                        <button class="ex-btn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    </div>
                    <div class="tb-search">
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
        <div id="emptyHint" class="empty"><i class="fas fa-hand-holding-usd" style="font-size:2.5rem; color:#cdd8ef;"></i><p>Select a search mode above to load student data</p></div>
    </div>

    <!-- DUE POPUP -->
    <div class="due-bg" id="dueBg">
        <div class="due-box" style="width: min(480px, 94vw);">
            <div class="m-hdr" style="background:linear-gradient(135deg,#ef4444,#dc2626);"><h3><i class="fas fa-exclamation-circle"></i> Due Months — <span id="dueStudName"></span></h3><button class="m-close" onclick="closeDuePop()"><i class="fas fa-times"></i></button></div>
            <div class="due-body" id="dueBody" style="padding: 15px; max-height: 400px; overflow-y:auto;"></div>
            <div class="due-ftr" style="padding: 15px; border-top: 1.5px solid #f0f4ff; display: flex; justify-content: space-between; align-items:center;">
                <div style="background:#fef3f2; color:#ef4444; padding:7px 14px; border-radius:24px; font-weight:800; font-size:0.86rem;">Total: ₹<span id="dueTotAmt">0</span></div>
                <button class="sbtn" onclick="closeDueAndOpen()"><span class="rs-ico">Rs</span> Collect Fee</button>
            </div>
        </div>
    </div>

    <!-- FEE PANEL -->
    <div class="fp-bg" id="fpBg">
        <div class="fp-box" style="width: min(1200px, 98%);">
            <div class="m-hdr fp-hdr">
                <h2><i class="fas fa-hand-holding-usd"></i> Fee Collection</h2>
                <p>Select months · choose fee types · collect single or multiple months</p>
                <button class="m-close" onclick="closePanel()"><i class="fas fa-times"></i></button>
            </div>
            <div class="sstrip" id="stuStrip" style="display:flex; gap:25px; padding:12px 23px; background:#f8fafc; border-bottom:1.5px solid #edf2f7; font-size:0.7rem; overflow-x:auto; white-space:nowrap;"></div>
            
            <div class="ms-bar" id="msBar">
                <div class="ms-info">
                    <div class="ms-count-pill">
                        <span class="ms-count-circle" id="msCount">0</span>
                        <span style="font-size:0.75rem; color:#666;">month selected</span>
                    </div>
                    <span style="color:#ccc; margin:0 5px;">|</span>
                    <span style="font-size:0.8rem; color:#666;">Total:</span>
                    <span class="ms-total" id="msTotal">₹0</span>
                </div>
                <div class="ms-actions" style="display:flex; gap:8px;">
                    <button class="sbtn" style="background:#fff; color:#488fe4; border:1.5px solid #488fe4" onclick="selectAllDue()"><i class="fas fa-check-double"></i> All Due</button>
                    <button class="sbtn" style="background:#fff; color:#5f6b7a; border:1.5px solid #ddd" onclick="clearSelection()"><i class="fas fa-times"></i> Clear</button>
                    <button class="sbtn" style="background:linear-gradient(135deg,#ff913b,#e07a2a);" onclick="openMultiPay()"><i class="fas fa-rupee-sign"></i> Pay Selected</button>
                </div>
            </div>

            <div style="padding: 0 23px 20px;">
                <div style="font-size:0.85rem; font-weight:700; color:#488fe4; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-calendar-alt"></i> Month-wise Fee Details — All 12 Months
                    <span style="margin-left:auto; font-size:0.7rem; color:#ff913b; font-weight:600;"><i class="fas fa-info-circle"></i> Check rows to select multiple months for bulk payment</span>
                </div>
                <div class="tw">
                    <table>
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
        <div class="pm" style="width: min(520px, 95vw);">
            <div class="m-hdr pm-hdr">
                <h3><i class="fas fa-rupee-sign"></i> Collect Fee — <span id="pmMonthTitle"></span></h3>
                <button class="m-close" onclick="closePm()"><i class="fas fa-times"></i></button>
            </div>
            <div class="pm-body" style="padding: 20px;">
                <div class="pm-stu-mini" id="pmStuMini">
                    <!-- Dynamic -->
                </div>
                
                <div style="font-size:0.65rem; font-weight:800; color:#ff913b; text-transform:uppercase; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                    <i class="fas fa-list-check"></i> Select Fee Type(s) to Collect
                </div>
                
                <div class="pm-checklist" id="pmChecklist">
                    <!-- Dynamic Items -->
                </div>

                <div class="pm-summary" style="border-top: 1.5px dashed #e2e8f0; padding:15px 0 0;">
                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#5f6b7a; margin-bottom:8px;"><span>Sub Total</span><span id="pmSubTotal" style="font-weight:700">₹0</span></div>
                    <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#5f6b7a; margin-bottom:12px;"><span>Discount / Concession</span><span style="color:#22c55e; font-weight:700">₹0</span></div>
                    <div style="padding:14px 18px; border-radius:14px; background:#f0f9ff; border:1px solid #bae6fd; display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-weight:800; font-size:0.9rem; color:#488fe4;"><i class="fas fa-shield-check"></i> Total Payable</span>
                        <span id="pmTotal" style="color:#488fe4; font-size:1.15rem; font-weight:900;">₹0</span>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-top:20px;">
                    <div class="fg">
                        <label>Pay Amount (₹)</label>
                        <div style="position:relative">
                            <span style="position:absolute; left:16px; top:50%; transform:translateY(-50%); color:#5f6b7a; font-weight:700">₹</span>
                            <input type="number" id="pmAmt" style="padding-left:32px; font-weight:800; color:#1e293b; font-size:1.05rem;" oninput="updatePmDues()">
                        </div>
                    </div>
                    <div class="fg">
                        <label>Dues After Payment</label>
                        <div style="padding:12px; font-weight:800; color:#ef4444; font-size:1.05rem; border:1.5px solid #fee2e2; border-radius:30px; background:#fff; text-align:right" id="pmDuesAfter">₹0</div>
                    </div>
                </div>

                <div class="fg" style="margin-top:15px;">
                    <label>Payment Mode</label>
                    <div class="pm-mode-toggle">
                        <button class="pm-mode-btn active" onclick="setPayMode('Online', this)"><i class="fas fa-globe"></i> Online</button>
                        <button class="pm-mode-btn" onclick="setPayMode('Cash', this)"><i class="fas fa-money-bill-wave"></i> Offline / Cash</button>
                    </div>
                    <input type="hidden" id="pmMode" value="Online">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-top:15px;">
                    <div class="fg">
                        <label>Ref / Trans No.</label>
                        <input type="text" id="pmRef" placeholder="UPI Ref / Cheque No. (optional)" style="border-radius:10px">
                    </div>
                    <div class="fg">
                        <label>Remark</label>
                        <input type="text" id="pmRemark" placeholder="Optional remark" style="border-radius:10px">
                    </div>
                </div>

                <button class="pm-pay-btn" style="margin-top:25px;" onclick="confirmPay()">
                    <i class="fas fa-check-circle"></i> Pay Now
                </button>
            </div>
        </div>
    </div>

    <!-- RECEIPT MODAL -->
    <div class="rc-bg" id="rcBg">
        <div class="rc-m" style="width: min(500px, 95vw); padding:0;">
            <div style="padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.75rem; font-weight: 800; color:#5f6b7a; text-transform: uppercase;">Payment Receipt</span>
                <button style="background:none; border:none; color:#5f6b7a; cursor:pointer; font-size:1.1rem;" onclick="closeRc()"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding: 25px; text-align: center;">
                <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:5px;">
                    <div style="width:24px; height:24px; background:#ff913b; border-radius:5px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:0.7rem;"><i class="fas fa-graduation-cap"></i></div>
                    <h2 style="font-size: 1.15rem; color:#1e293b; margin:0; font-weight:800;">Smart School ERP</h2>
                </div>
                <p style="font-size: 0.7rem; color:#5f6b7a; margin-bottom:20px;">Fee Payment Receipt</p>
                
                <div style="text-align: left; background: #fcfdfe; border: 1px solid #f0f4f8; border-radius:12px; padding: 15px;">
                    <div id="rcDetailsGrid" style="display: grid; grid-template-columns: 1fr; gap: 8px; margin-bottom: 15px;"></div>
                    
                    <div style="font-size: 0.65rem; font-weight: 800; color:#488fe4; border-bottom: 1px dashed #e2e8f0; padding-bottom: 5px; margin-bottom: 10px;">FEE BREAKDOWN</div>
                    <div id="rcBreakdown" style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 15px;"></div>
                    
                    <div style="display:flex; justify-content:space-between; align-items:center; padding-top: 10px; border-top: 1.5px solid #e2e8f0;">
                        <span style="font-weight: 800; font-size: 0.85rem;">Total Paid</span>
                        <span id="rcTotalPaid" style="font-weight: 900; font-size: 1.1rem; color:#22c55e;">₹0</span>
                    </div>
                </div>
                
                <button class="sbtn" style="width: 100%; margin-top: 20px; background: #488fe4; padding: 14px;" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>
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
      <div class="sic"><span class="sl">STUDENT NAME</span><span class="sv" style="color:#1e293b">${stu.name}</span></div>
      <div class="sic"><span class="sl">STD ID</span><span class="sv" style="color:#488fe4">${stu.sid||'SID21'}</span></div>
      <div class="sic"><span class="sl">ADM NO</span><span class="sv" style="color:#1e293b">${stu.adm}</span></div>
      <div class="sic"><span class="sl">LEDGER NO.</span><span class="sv" style="color:#1e293b">${stu.ledger||'L012'}</span></div>
      <div class="sic"><span class="sl">ROLL NO</span><span class="sv" style="color:#1e293b">${stu.roll||'22'}</span></div>
      <div class="sic"><span class="sl">CLASS / SEC</span><span class="sv" style="color:#1e293b">${stu.cls} - ${stu.sec}</span></div>
      <div class="sic"><span class="sl">FATHER</span><span class="sv" style="color:#1e293b">${stu.father||'N/A'}</span></div>
      <div class="sic"><span class="sl">MOBILE</span><span class="sv" style="color:#1e293b">${stu.mob}</span></div>
    `;
    renderMonthTable();
    document.getElementById('fpBg').classList.add('open');
  });
}

function closePanel(){ document.getElementById('fpBg').classList.remove('open'); }

function renderMonthTable(){
  let h='';
  monthData.months.forEach((m, i)=>{
    const isPaid = m.status==='paid';
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
    h+=`<tr>
      <td style="text-align:center"><div class="mchk ${checkedClass}" onclick="toggleRow(${i})"></div></td>
      <td>${i+1}</td><td><b>${m.month}</b></td><td>${ftHtml}</td>
      <td style="color:#64748b">₹${m.fee.toLocaleString()}</td>
      <td style="color:${m.fine>0?'#ff913b':'#cbd5e1'}">₹${m.fine}</td>
      <td style="font-weight:800; color:#1e293b">₹${m.net.toLocaleString()}</td>
      <td style="font-weight:800; color:${m.paid>0?'#22c55e':'#cbd5e1'}">₹${m.paid.toLocaleString()}</td>
      <td style="font-weight:800; color:${m.balance>0?'#ef4444':'#22c55e'}">₹${m.balance.toLocaleString()}</td>
      <td style="color:#64748b; font-size:0.75rem">${m.paidDate||'-'}</td>
      <td>
        <span class="ss ${m.status.toLowerCase()}" style="display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-weight:700;">
          <i class="fas ${m.status.toLowerCase()==='paid'?'fa-check-circle':(m.status.toLowerCase()==='partial'?'fa-circle-dot':'fa-circle-xmark')}"></i> 
          ${m.status.toUpperCase()}
        </span>
      </td>
      <td>
        <div class="ais">
          ${m.status.toLowerCase() !== 'paid' ? `<button class="ib b-pay" onclick="openPay(${i})" title="Collect Fee">Rs</button>` : ''}
          <button class="ib b-view" onclick="openReceipt(${i})" title="View Details"><i class="fas fa-eye"></i></button>
          <button class="ib b-print" onclick="window.print()" title="Print Receipt"><i class="fas fa-print"></i></button>
          <button class="ib b-wa" onclick="alert('Send via WhatsApp')" title="WhatsApp"><i class="fab fa-whatsapp"></i></button>
          <button class="ib b-tag" onclick="alert('Manage Discounts')" title="Discount/Tags"><i class="fas fa-tag"></i></button>
          <button class="ib b-hist" onclick="alert('View History')" title="Payment History"><i class="fas fa-history"></i></button>
        </div>
      </td>
    </tr>`;
  });
  document.getElementById('monthBody').innerHTML = h;
  updateMsBar();
}

function toggleRow(i){
  if(monthData.months[i].status==='Paid') return;
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
  if(m.status==='paid') return Swal.fire('Info','This month is already fully paid.','info');
  pmMonths = [i];
  openMultiPay();
}

function openMultiPay(){
  if(pmMonths.length===0) return;
  const list=document.getElementById('pmChecklist'), title=document.getElementById('pmMonthTitle');
  let h='', sub=0;
  pmMonths.sort((a,b)=>a-b);
  
  if(pmMonths.length === 1) {
    title.innerText = monthData.months[pmMonths[0]].month + ' 2025';
  } else {
    title.innerText = pmMonths.length + ' Months';
  }
  
  // Collect all components from selected months
  const allComps = [];
  pmMonths.forEach(idx => {
    const m = monthData.months[idx];
    sub += m.balance;
    m.comps.forEach(c => {
      // Find if this fee type already in list (for multi-month grouping)
      const existing = allComps.find(x => x.name === c.name);
      if(existing) {
        existing.total += c.total;
        existing.paid += c.paid;
        existing.balance += c.balance;
      } else {
        allComps.push({...c});
      }
    });
  });

  allComps.forEach(c => {
    let ico = 'fa-calendar-alt', clr = '#488fe4';
    const n = c.name.toLowerCase();
    if(n.includes('tui')) { ico = 'fa-book'; clr = '#3b82f6'; }
    else if(n.includes('trans')) { ico = 'fa-bus'; clr = '#f59e0b'; }
    else if(n.includes('host')) { ico = 'fa-bed'; clr = '#8b5cf6'; }
    else if(n.includes('lab') || n.includes('lib')) { ico = 'fa-flask'; clr = '#f97316'; }
    else if(n.includes('regis')) { ico = 'fa-id-card'; clr = '#10b981'; }
    
    const status = c.balance <= 0 ? 'paid' : (c.paid > 0 ? 'partial' : 'due');
    const statusText = status.toUpperCase();

    h += `<div class="pm-citem">
      <div class="mchk ${c.balance <= 0 ? 'checked-paid' : 'checked-due'}" onclick="event.stopPropagation()"></div>
      <div class="pm-c-info">
        <div class="pm-c-ico" style="background:${clr}"><i class="fas ${ico}"></i></div>
        <div>
          <div class="pm-c-name">${c.name}</div>
          <div class="pm-c-tag">Balance: ₹${c.balance.toLocaleString()} | Paid: ₹${c.paid.toLocaleString()}</div>
        </div>
      </div>
      <div class="pm-c-val">
        <div class="pm-c-amt">₹${c.balance.toLocaleString()}</div>
        <span class="ss ${status}" style="padding:2px 8px; font-size:0.55rem; margin-top:3px; display:block; text-align:center">${statusText}</span>
      </div>
    </div>`;
  });
  
  list.innerHTML = h;
  document.getElementById('pmSubTotal').innerText = '₹' + sub.toLocaleString();
  document.getElementById('pmTotal').innerText = '₹' + sub.toLocaleString();
  document.getElementById('pmAmt').value = sub;
  updatePmDues();
  
  document.getElementById('pmStuMini').innerHTML = `
    <div class="pm-sm-box"><div class="pm-sm-lbl">NAME</div><div class="pm-sm-val">${stu.name}</div></div>
    <div class="pm-sm-box"><div class="pm-sm-lbl">ADM NO.</div><div class="pm-sm-val">${stu.adm}</div></div>
    <div class="pm-sm-box"><div class="pm-sm-lbl">CLASS</div><div class="pm-sm-val">${stu.cls} - ${stu.sec}</div></div>
    <div class="pm-sm-box"><div class="pm-sm-lbl">MOBILE</div><div class="pm-sm-val">${stu.mob}</div></div>
  `;
  
  document.getElementById('pmBg').classList.add('open');
}

function closePm(){ document.getElementById('pmBg').classList.remove('open'); }
function setPayMode(m, el){
  payMode=m; document.getElementById('pmMode').value = m;
  document.querySelectorAll('.pm-mode-btn').forEach(btn=>btn.classList.remove('active'));
  el.classList.add('active');
}

function updatePmDues(){
  const totText = document.getElementById('pmTotal').innerText || '0';
  const tot = Number(totText.replace(/[^0-9.]/g, "")) || 0;
  const pay = Number(document.getElementById('pmAmt').value) || 0;
  const dues = Math.max(0, tot - pay);
  document.getElementById('pmDuesAfter').innerText = '₹' + dues.toLocaleString();
}

function confirmPay(){
  if(!stu || !stu.id) return Swal.fire('Error','Student data missing','error');
  const payAmt = document.getElementById('pmAmt').value;
  if(!payAmt || payAmt <= 0) return Swal.fire('Error','Enter a valid amount','error');
  
  Swal.fire({
    title: 'Confirm Payment?',
    text: `You are about to collect ₹${Number(payAmt).toLocaleString()} via ${payMode}`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#488fe4',
    confirmButtonText: 'Yes, Confirm'
  }).then(result => {
    if(result.isConfirmed){
      Swal.showLoading();
      
      const payload = {
        amount: payAmt,
        payment_mode: payMode,
        reference_no: document.getElementById('pmRef').value,
        remark: document.getElementById('pmRemark').value,
        _token: '{{ csrf_token() }}'
      };
      
       fetch(`${BASE_URL}/fee/fee-collection/${stu.id}/pay`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(res => {
        if(res.success){
          Swal.fire('Paid!', 'Payment processed successfully', 'success');
          closePm();
          openPanel(stu.id); // Refresh student panel
          if(curMode==='class') searchByClass(); else searchDirect(); // Refresh background list
        } else {
          Swal.fire('Failed', res.message || 'Payment failed', 'error');
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Server communication error', 'error');
      });
    }
  });
}

function openReceipt(i){
  const m=monthData.months[i];
  if(m.status==='due') return Swal.fire('Wait','No payment record for this month','info');
  
  const grid=document.getElementById('rcDetailsGrid'), breakdown=document.getElementById('rcBreakdown');
  grid.innerHTML = `
    <div style="display:flex;justify-content:space-between"><span>Student:</span><b>${stu.name}</b></div>
    <div style="display:flex;justify-content:space-between"><span>Adm No:</span><b>${stu.adm}</b></div>
    <div style="display:flex;justify-content:space-between"><span>Month:</span><b>${m.month}</b></div>
    <div style="display:flex;justify-content:space-between"><span>Date:</span><b>${m.paidDate||'-'}</b></div>
  `;
  
  let bh='';
  m.comps.forEach(f=>bh+=`<div style="display:flex;justify-content:space-between;font-size:0.8rem"><span>${f.name}</span><span>₹${f.total.toLocaleString()}</span></div>`);
  if(m.fine>0) bh+=`<div style="display:flex;justify-content:space-between;font-size:0.8rem;color:#ff913b"><span>Fine</span><span>₹${m.fine}</span></div>`;
  breakdown.innerHTML = bh;
  
  document.getElementById('rcTotalPaid').innerText = '₹'+m.paid.toLocaleString();
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
</script>
@endpush
