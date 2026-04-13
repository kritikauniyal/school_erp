@extends('layouts.app')

@section('title', 'Collect Fee')

@section('page-title','Collect Fee')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Playfair+Display:ital,wght@0,700;0,800;1,700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
:root{
  --blue:#488fe4;--orange:#ff913b;--blu:#e3f0ff;--olt:#fff4ea;
  --bg:#f5f9ff;--dark:#1e293b;--muted:#5f6b7a;--sidebar:#435471;
  --sh:0 4px 18px rgba(0,20,50,.07);
  --green:#22c55e;--red:#ef4444;--yellow:#f59e0b;--purple:#7c3aed;
  --navy: #1e3a5f; --teal: #0891b2; --gold: #92400e; --gray: #475569;
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

/* RECEIPT CSS */
.a4-outer{display:flex;justify-content:center;padding: 10px; background: #f0f2f5;}
.a4{width:210mm;background:#fff;box-shadow:0 10px 44px rgba(0,0,0,.18);padding:10mm 11mm; position: relative;}
.receipt{width:100%;height:130mm;display:flex;flex-direction:column;overflow:hidden;position:relative;border-radius:6px;}
.receipt.office{border:2px solid var(--blue)}
.receipt.guardian{border:2px solid var(--teal)}
.rc-bar{padding:4px 12px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
.office .rc-bar{background:linear-gradient(90deg,var(--navy),var(--blue));border-bottom:1px solid var(--blue);}
.guardian .rc-bar{background:linear-gradient(90deg,var(--teal),#0e7490);border-bottom:1px solid var(--teal);}
.rc-bar-label{font-size:7.5pt;font-weight:800;letter-spacing:1.2px;text-transform:uppercase;color:#fff;}
.rc-bar-rcno{font-size:7pt;font-weight:700;color:rgba(255,255,255,.9);}
.rc-bar-rcno b{color:#ffe08a;font-weight:800}
.rc-hdr{padding:7px 12px 6px;display:flex;align-items:center;gap:10px;flex-shrink:0;}
.rc-logo{width:44px;height:44px;border-radius:50%;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:#fff;}
.office .rc-logo{border:2.5px solid var(--blue)}
.guardian .rc-logo{border:2.5px solid var(--teal)}
.rc-logo img{width:100%;height:100%;object-fit:cover}
.rc-school{flex:1}
.rc-sname{font-family:'Playfair Display',serif;font-size:13.5pt;font-weight:800;line-height:1.2;}
.office .rc-sname{color:var(--navy)}
.guardian .rc-sname{color:#064e63}
.rc-saddr{font-size:6.8pt;font-weight:600;color:var(--gray);margin-top:2px;line-height:1.45}
.rc-grad{width:44px;height:44px;object-fit:contain;flex-shrink:0}
.rc-session{padding:4px 12px;display:flex;flex-wrap:wrap;gap:0 20px;border-bottom:1.5px solid;flex-shrink:0;}
.office .rc-session{background:#fff;border-color:#bdd0f4}
.guardian .rc-session{background:#fff;border-color:#a5d8e6}
.rc-sess-item{font-size:7.2pt;font-weight:600;color:var(--gray)}
.rc-sess-item b{font-weight:800}
.rc-info{display:grid;grid-template-columns:1fr 1fr;flex-shrink:0;border-bottom:1.5px solid}
.office .rc-info{border-color:#bdd0f4}
.guardian .rc-info{border-color:#a5d8e6}
.rc-ic{padding:3.5px 10px;border-bottom:1px solid #dde8f8;border-right:1px solid #dde8f8;display:flex;align-items:baseline;gap:5px;line-height:1.4;background:#fff;}
.rc-ic:nth-child(even){border-right:none;}
.rc-lbl{font-size:6.8pt;font-weight:800;white-space:nowrap}
.office .rc-lbl{color:var(--blue)}
.guardian .rc-lbl{color:var(--teal)}
.rc-val{font-size:6.8pt;font-weight:700;color:var(--navy)}
.rc-tw{flex:1;overflow:hidden;padding:0 0}
.rc-tbl{width:100%;border-collapse:collapse}
.rc-tbl thead th{font-size:7pt;font-weight:800;text-transform:uppercase;letter-spacing:.5px;padding:4.5px 10px;text-align:left;color:#fff;}
.office .rc-tbl thead tr{background:linear-gradient(90deg,var(--navy),var(--blue))}
.guardian .rc-tbl thead tr{background:linear-gradient(90deg,#064e63,var(--teal))}
.rc-tbl tbody td{padding:3.5px 10px;border-bottom:1px solid #e2eaf8;font-size:7.5pt;font-weight:600;color:var(--navy);vertical-align:middle;}
.rc-tbl tfoot td{padding:4px 10px;font-size:7.8pt;font-weight:700;border-top:1px solid;}
.rc-tbl .st-row td{border-top:2px solid; color: var(--blue);}
.rc-tbl .pd-row td{color:var(--green)}
.rc-tbl .du-row td{color:var(--red)}
.pd-tag{display:inline;font-size:6pt;font-weight:800;border:1.5px solid var(--green);color:var(--green);padding:1px 5px;border-radius:3px;margin-left:4px;}
.du-tag{display:inline;font-size:6pt;font-weight:800;border:1.5px dashed var(--red);color:var(--red);padding:1px 5px;border-radius:3px;margin-left:4px;}
.rc-ftr{padding:5px 12px 6px;display:flex;align-items:flex-end;justify-content:space-between;flex-shrink:0;}
.rc-sig-line{width:80px;border-bottom:1.5px solid var(--navy);margin:0 auto 2px}
.rc-sig-lbl{font-size:6pt;font-weight:800;color:var(--navy);text-transform:uppercase;text-align:center}
.cut{display:flex;align-items:center;gap:8px;margin:6mm 0;font-size:7pt;font-weight:700;color:var(--gray);}
.cut-line{flex:1;border-top:2px dashed #93a3b8}
.receipt-modal-header{display: flex;justify-content: space-between;align-items: center;padding: 15px 20px;background: #fff;border-bottom: 1px solid #eee;position: sticky;top: 0;z-index: 100;}
.receipt-modal-title{ font-size: 1.2rem; font-weight: 700; color: var(--navy); }
.receipt-actions{ display: flex; gap: 10px; }
.receipt-btn{padding: 8px 16px;border-radius: 6px;border: none;font-weight: 600;cursor: pointer;display: flex;align-items: center;gap: 8px;}
.btn-print-receipt{ background: var(--blue); color: #fff; }
.btn-wa-receipt{ background: #25d366; color: #fff; }
.receipt-container{ max-height: 80vh; overflow-y: auto; padding: 20px; background: #f4f6f9; }

@media print{
  @page{size:A4;margin:10mm 11mm}
  body > * { display: none !important; }
  body > #receiptModal { display: block !important; position: absolute; left: 0; top: 0; }
  .receipt-modal-header { display: none !important; }
  .a4-outer { background: #fff; padding: 0; }
  .a4 { box-shadow: none; width: auto; padding: 0; }
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
                <div class="fg"><label>Class *</label><select id="classSelect" onchange="fetchSections(this.value)"><option value="">-- Select Class --</option>@foreach($globalClasses as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
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
                    <h3><i class="fas fa-calendar-alt"></i> Month-wise Fee Details</h3>
                </div>
                <div class="tw">
                    <table class="mt">
                        <thead>
                            <tr>
                                <th style="width:40px; text-align:center;"><div class="mchk" id="hallChk" onclick="toggleSelectAll()"></div></th>
                                <th>#</th><th>Month</th><th>Total Fee</th><th>Fine</th><th>Net Payable</th><th>Status</th><th>Actions</th>
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
                <div class="pm-stu" id="pmStuInfo"></div>
                <div class="ft-section">
                    <div class="ft-section-label"><i class="fas fa-tags"></i> Select Fee Type(s) to Collect</div>
                    <div class="ft-grid" id="ftGrid"></div>
                </div>
                <hr class="pm-divider">
                <div class="pm-sumrow"><span class="psr-l">Sub Total</span><span class="psr-v" id="pmSubTotal">₹0</span></div>
                <div class="pm-sumrow total-row"><span class="psr-l">Total Payable</span><span class="psr-v" id="pmTotal">₹0</span></div>
                <div class="pm-sumrow dues-row" style="border-bottom:none"><span class="psr-l">Dues After Payment</span><span class="psr-v" id="pmDuesAfter">₹0</span></div>
                <hr class="pm-divider">
                <div class="pf"><label>Pay Amount (₹)</label><input type="number" id="pmAmt" oninput="_updateDues()" placeholder="Enter amount to pay"></div>
                <div class="pf">
                    <label>Payment Mode</label>
                    <div class="pay-mode-btns">
                        <button class="pmode-btn active" id="pmModeOnline" onclick="setPayMode('Online',this)"><i class="fas fa-globe"></i> Online</button>
                        <button class="pmode-btn" id="pmModeCash" onclick="setPayMode('Cash',this)"><i class="fas fa-money-bill-wave"></i> Cash</button>
                    </div>
                    <input type="hidden" id="pmMode" value="Online">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="pf"><label>Ref / Trans No.</label><input type="text" id="pmRef" placeholder="UPI Ref / Cheque No."></div>
                    <div class="pf"><label>Remark</label><input type="text" id="pmRmk" placeholder="Optional remark"></div>
                </div>
                <button class="btn-pay-now" onclick="confirmPay()"><i class="fas fa-check-circle"></i> Pay Now</button>
            </div>
        </div>
    </div>

    <!-- RECEIPT MODAL -->
    <div id="receiptModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:999999; align-items:center; justify-content:center;">
        <div style="width: 90vw; max-width: 220mm; background: #fff; border-radius: 10px; overflow: hidden; position: relative;">
            <div class="receipt-modal-header">
                <div class="receipt-modal-title"><i class="fas fa-receipt"></i> Official Payment Receipt</div>
                <div class="receipt-actions">
                    <button class="receipt-btn btn-wa-receipt" id="waReceiptBtn" onclick="shareReceiptWithWA()"><i class="fab fa-whatsapp"></i> WhatsApp + PDF</button>
                    <button class="receipt-btn btn-print-receipt" onclick="printReceipt()"><i class="fas fa-print"></i> Print Receipt</button>
                    <button class="receipt-btn" onclick="closeReceipt()" style="background:#eee;">Close</button>
                </div>
            </div>
            <div class="receipt-container">
                <div class="a4-outer">
                    <div class="a4" id="receiptContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
const BASE_URL = "{{ url('/') }}";
let curMode='class', stu=null, monthData=null, pmMonths=[], payMode='Online';

function fetchSections(classId) {
    const sectionSelect = document.getElementById('sectionSelect');
    sectionSelect.innerHTML = '<option value="">-- Loading --</option>';
    if(!classId) { sectionSelect.innerHTML = '<option value="">-- All Sections --</option>'; return; }
    fetch(`${BASE_URL}/admin/collect-fee?get_sections=1&class_id=${classId}`)
        .then(r => r.json()).then(data => {
            let h = '<option value="">-- All Sections --</option>';
            data.forEach(s => { h += `<option value="${s.id}">${s.name}</option>`; });
            sectionSelect.innerHTML = h;
        });
}

function switchMode(m){
    curMode=m;
    document.querySelectorAll('.mcard').forEach(c=>c.classList.remove('mac'));
    const card = document.getElementById(m+'Card');
    if(card) card.classList.add('mac');
    document.getElementById('classPanel').style.display = m==='class'?'block':'none';
    document.getElementById('directPanel').style.display = m==='direct'?'block':'none';
}

function searchByClass(){
    const cid=document.getElementById('classSelect').value, sid=document.getElementById('sectionSelect').value;
    if(!cid) return Swal.fire('Error','Select a Class','error');
    Swal.showLoading();
    fetch('{{ route("admin.collect-fee.index") }}?class='+cid+'&section='+sid, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
    .then(r=>r.json()).then(res=>{
        Swal.close();
        let h='';
        res.data.forEach((s,i)=>{
            h+=`<tr><td>${i+1}</td><td>${s.sid}</td><td>${s.adm}</td><td>${s.roll}</td><td><b>${s.name}</b></td><td>${s.cls}</td><td>${s.sec}</td><td>${s.father}</td><td>${s.mob}</td><td><span class="dbadge" onclick="showDues(${s.id},'${s.name}')">₹${s.dues.toLocaleString()}</span></td><td><button class="cbtn" onclick="openPanel(${s.id})">Collect Fee</button></td></tr>`;
        });
        document.getElementById('stbodyClass').innerHTML = h;
        document.getElementById('resultsClass').style.display = 'block';
    });
}

function searchDirect(){
    const val=document.getElementById('directInput').value;
    if(!val) return Swal.fire('Wait','Enter Admission No / Name','info');
    Swal.showLoading();
    fetch('{{ route("admin.collect-fee.index") }}?keyword='+encodeURIComponent(val), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
    .then(r=>r.json()).then(res=>{
        Swal.close();
        let h='';
        res.data.forEach((s,i)=>{
            h+=`<tr><td>${i+1}</td><td>${s.sid}</td><td>${s.adm}</td><td>${s.roll}</td><td><b>${s.name}</b></td><td>${s.cls}</td><td>${s.sec}</td><td>${s.father}</td><td>${s.mob}</td><td><span class="dbadge" onclick="showDues(${s.id},'${s.name}')">₹${s.dues.toLocaleString()}</span></td><td><button class="cbtn" onclick="openPanel(${s.id})">Collect Fee</button></td></tr>`;
        });
        document.getElementById('stbodyDirect').innerHTML = h;
        document.getElementById('resultsDirect').style.display = 'block';
    });
}

function showDues(id, name){
    document.getElementById('dueStudName').innerText = name;
    document.getElementById('dueBg').classList.add('open');
    fetch(`${BASE_URL}/admin/collect-fee/details/${id}`).then(r=>r.json()).then(res=>{
        let h='', tot=0;
        res.months.forEach(m=>{ if(m.balance>0){ tot+=m.balance; h+=`<div style="display:flex;justify-content:space-between;padding:10px;border-bottom:1px solid #eee;"><span><b>${m.month}</b></span><span style="color:#ef4444;font-weight:700">₹${m.balance.toLocaleString()}</span></div>`; } });
        document.getElementById('dueBody').innerHTML = h || 'No pending dues';
        document.getElementById('dueTotAmt').innerText = tot.toLocaleString();
        document.getElementById('dueStudName').setAttribute('data-id', id);
    });
}
function closeDuePop(){ document.getElementById('dueBg').classList.remove('open'); }
function closeDueAndOpen(){ const id=document.getElementById('dueStudName').getAttribute('data-id'); closeDuePop(); openPanel(id); }

function openPanel(id){
    Swal.showLoading();
    fetch(`${BASE_URL}/admin/collect-fee/details/${id}`).then(r=>r.json()).then(res=>{
        Swal.close();
        monthData = res; stu = res.student; pmMonths = [];
        document.getElementById('stuStrip').innerHTML = `<div class="sic"><span class="sl">Name</span><span class="sv">${stu.name}</span></div><div class="sic"><span class="sl">ADM NO</span><span class="sv">${stu.adm}</span></div><div class="sic"><span class="sl">CLASS</span><span class="sv">${stu.cls} - ${stu.sec}</span></div>`;
        renderMonthTable(); document.getElementById('fpBg').classList.add('open');
    });
}
function closePanel(){ document.getElementById('fpBg').classList.remove('open'); }

function renderMonthTable(){
    let h='';
    monthData.months.forEach((m, i)=>{
        const status = m.status.toLowerCase();
        const isPaid = status==='paid';
        const chkCls = pmMonths.includes(i)?'checked-due':(isPaid?'checked-paid':'');
        
        h+=`<tr>
            <td style="text-align:center"><div class="mchk ${chkCls}" onclick="toggleRow(${i})"></div></td>
            <td>${i+1}</td>
            <td><b>${m.month}</b></td>
            <td>₹${m.fee.toLocaleString()}</td>
            <td>₹${m.fine}</td>
            <td>₹${m.net.toLocaleString()}</td>
            <td><span class="ss ${status}">${m.status.toUpperCase()}</span></td>
            <td>
                <div class="ais">
                    ${isPaid ? '' : `<button class="ib b-pay" onclick="openPay(${i})" title="Collect Fee"><i class="fa-solid fa-indian-rupee-sign"></i></button>`}
                    <button class="ib b-view" onclick="openReceipt(${i})" title="View Details"><i class="fas fa-eye"></i></button>
                    <button class="ib b-print" onclick="openReceipt(${i}, true)" title="Print"><i class="fas fa-print"></i></button>
                    <button class="ib b-wa" onclick="whatsappReceipt(${i})" title="WhatsApp"><i class="fab fa-whatsapp"></i></button>
                    <button class="ib b-tag" onclick="alert('Discount for '+monthData.months[${i}].month)" title="Discount"><i class="fas fa-tag"></i></button>
                </div>
            </td>
        </tr>`;
    });
    document.getElementById('monthBody').innerHTML = h; updateMsBar();
}

function toggleRow(i){
    if(monthData.months[i].status.toLowerCase()==='paid') return;
    const idx = pmMonths.indexOf(i); if(idx>-1) pmMonths.splice(idx,1); else pmMonths.push(i);
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
    const bar=document.getElementById('msBar');
    if(pmMonths.length>0){ bar.classList.add('visible'); document.getElementById('msCount').innerText = pmMonths.length; let sum=0; pmMonths.forEach(i=>sum+=monthData.months[i].balance); document.getElementById('msTotal').innerText = '₹'+sum; }
    else bar.classList.remove('visible');
}

function openPay(i){ pmMonths=[i]; _openPayModal(pmMonths); }
function openMultiPay(){ if(pmMonths.length>0) _openPayModal(pmMonths); }

function _openPayModal(monthIndices){
    const ftMap={};
    monthIndices.forEach(mi=>{
        monthData.months[mi].comps.forEach(c=>{ 
            if(!ftMap[c.name]) ftMap[c.name]={name:c.name, bal:0}; 
            ftMap[c.name].bal += Number(c.balance) || 0; 
        });
    });
    window._pmFtMap = ftMap;
    let sub=0, grid=document.getElementById('ftGrid');
    grid.innerHTML = Object.values(ftMap).map(f=>{
        sub += f.bal; 
        const k=f.name.replace(/[\s\/]/g,'_');
        return `<div class="ft-item selected" id="fti-${k}" onclick="toggleFt('${k}')"><div class="ft-left"><div class="ft-chk"></div><div class="ft-name">${f.name}</div></div><div class="ft-right"><div class="ft-amt">₹${f.bal.toLocaleString()}</div></div></div>`;
    }).join('');
    document.getElementById('pmSubTotal').innerText='₹'+sub.toLocaleString();
    document.getElementById('pmTotal').innerText='₹'+sub.toLocaleString();
    document.getElementById('pmAmt').value=sub;
    document.getElementById('pmBg').classList.add('open');
    _updateDues();
}

function toggleFt(k){
    document.getElementById('fti-'+k).classList.toggle('selected');
    let sub = 0; 
    Object.values(window._pmFtMap).forEach(f=>{ 
        const key = f.name.replace(/[\s\/]/g,'_');
        if(document.getElementById('fti-'+key).classList.contains('selected')) sub += Number(f.bal) || 0; 
    });
    document.getElementById('pmSubTotal').innerText='₹'+sub.toLocaleString(); 
    document.getElementById('pmTotal').innerText='₹'+sub.toLocaleString(); 
    document.getElementById('pmAmt').value=sub; 
    _updateDues();
}

function closePm(){ document.getElementById('pmBg').classList.remove('open'); }
function setPayMode(m, el){ payMode=m; document.querySelectorAll('.pmode-btn').forEach(b=>b.classList.remove('active')); el.classList.add('active'); }
function _updateDues(){ const t=Number(document.getElementById('pmTotal').innerText.replace(/[^0-9.]/g,''))||0, p=Number(document.getElementById('pmAmt').value)||0; document.getElementById('pmDuesAfter').innerText='₹'+Math.max(0,t-p).toLocaleString(); }

function confirmPay(){
    if(!stu || !stu.id) return Swal.fire('Error', 'Student session expired. Please reopen the panel.', 'error');
    
    const amt=parseFloat(document.getElementById('pmAmt').value);
    if(!amt || amt <= 0) return Swal.fire('Wait', 'Enter a valid payment amount', 'info');

    const ref = document.getElementById('pmRef').value;
    const rmk = document.getElementById('pmRmk').value;
    const mode = document.getElementById('pmMode').value || payMode;

    Swal.showLoading();
    fetch(`${BASE_URL}/admin/collect-fee/pay/${stu.id}`, {
        method:'POST', 
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({
            amount: amt, 
            payment_mode: mode, 
            reference_no: ref, 
            remark: rmk, 
            months: pmMonths 
        })
    }).then(r=>r.json()).then(res=>{
        Swal.close();
        if(res.success){
            closePm();
            Swal.fire('Success', 'Payment collected successfully', 'success').then(() => {
                showReceipt(res);
                if(curMode==='class') searchByClass(); else searchDirect();
            });
        } else {
            Swal.fire('Error', res.message || 'Payment processing failed', 'error');
        }
    }).catch(err => {
        Swal.close();
        Swal.fire('Error', 'Network error or server timeout', 'error');
        console.error(err);
    });
}

function openReceipt(i, autoPrint=false){
    const m = monthData.months[i];
    if(!m || m.status.toLowerCase()==='due') return;
    
    Swal.showLoading();
    fetch(`${BASE_URL}/admin/collect-fee/get-receipt/latest?student_id=${stu.id}`)
        .then(r=>r.json()).then(res=>{
            Swal.close();
            if(res.success) {
                showReceipt(res);
                if(autoPrint) setTimeout(()=>window.print(), 800);
            }
            else Swal.fire('Info', 'Receipt details for older payments are being archived.', 'info');
        });
}

function whatsappReceipt(i){
    const m = monthData.months[i];
    if(!m || m.status.toLowerCase()==='due') return;
    
    Swal.showLoading();
    fetch(`${BASE_URL}/admin/collect-fee/get-receipt/latest?student_id=${stu.id}`)
        .then(r=>r.json()).then(res=>{
            Swal.close();
            if(res.success) {
                renderReceiptHTML(res);
                shareReceiptWithWA();
            }
        });
}

function showReceipt(data){ renderReceiptHTML(data); document.getElementById('receiptModal').style.display='flex'; }
function closeReceipt(){ 
    document.getElementById('receiptModal').style.display='none'; 
    // Ensure the monthly list panel stays open
    document.getElementById('fpBg').classList.add('open');
}
function renderReceiptHTML(data){
    const p=data.payment, s=data.student;
    const school={
        name: '{{ env("SCHOOL_NAME", "Hazrat Ali Academy") }}',
        addr: '{{ env("SCHOOL_ADDRESS", "Chandwara Branch Muslim Club Campus, Pakki Sarai Chandwara, Muzaffarpur") }}',
        phone: '{{ env("SCHOOL_PHONE", "9102277998, 9835281616") }}',
        udise: '10140614302',
        session: '2025-2026',
        logo: 'https://decentdemo.in/school/images/school-logo.png',
        grad: 'https://cdn-icons-png.flaticon.com/128/3048/3048127.png'
    };
    const R = (n) => {
        const val = parseFloat(n) || 0;
        return '₹' + val.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    };
    const ic = (l,v)=>`<div class="rc-ic"><span class="rc-lbl">${l} :</span><span class="rc-val">${v||'—'}</span></div>`;
    const buildHalf = (t) => {
        const isOff = t==='office';
        const amtVal = parseFloat(p.amount) || 0;
        return `<div class="receipt ${t}">
            <div class="rc-bar"><span class="rc-bar-label">${isOff?'🏫  FOR OFFICE COPY':'👤  FOR GUARDIAN COPY'}</span><span class="rc-bar-rcno">Receipt No. : <b>${p.transaction_id || ('RC-'+p.id)}</b></span></div>
            <div class="rc-hdr">
                <div class="rc-logo"><img src="${school.logo}" onerror="this.src='https://via.placeholder.com/80'"></div>
                <div class="rc-school"><div class="rc-sname">${school.name}</div><div class="rc-saddr">${school.addr}<br>${school.phone}</div></div>
                <img src="${school.grad}" class="rc-grad">
            </div>
            <div class="rc-session">
                <span class="rc-sess-item">Session : <b>${school.session}</b></span>
                <span class="rc-sess-item">Payment Date : <b>${new Date(p.created_at).toLocaleDateString('en-GB')}</b></span>
                <span class="rc-sess-item">Mode : <b>${p.gateway || 'N/A'}</b></span>
            </div>
            <div class="rc-info">
                ${ic('UDISE No.', school.udise)}
                ${ic('Reg. No.', s.registration_no || 'N/A')}
                ${ic('Name', s.student_name)}
                ${ic("Father's Name", s.parent ? s.parent.father_name : 'N/A')}
                ${ic('Class', s.class_info ? s.class_info.name : (s.class_name||''))}
                ${ic('Section', s.section ? s.section.name : 'N/A')}
                ${ic('Std. ID', 'SID'+s.id)}
                ${ic('Roll No.', s.roll_no || 'N/A')}
                ${ic('Adm. No.', s.admission_no)}
                ${ic('Address', s.address || 'N/A')}
            </div>
            <div class="rc-tw">
                <table class="rc-tbl">
                    <thead><tr><th>S.No.</th><th>Particular / Description</th><th>Amount</th></tr></thead>
                    <tbody>
                        <tr><td>1</td><td>Fee Collection Month: ${new Date(p.created_at).toLocaleString('default', { month: 'long' })}</td><td>${R(amtVal)}</td></tr>
                        <tr class="emp"><td></td><td>&nbsp;</td><td></td></tr>
                        <tr class="emp"><td></td><td>&nbsp;</td><td></td></tr>
                        <tr class="emp"><td></td><td>&nbsp;</td><td></td></tr>
                    </tbody>
                    <tfoot>
                        <tr class="st-row"><td colspan="2">Sub Total</td><td>${R(amtVal)}</td></tr>
                        <tr class="pd-row"><td colspan="2">Paid &nbsp;<span class="pd-tag">PAID</span></td><td>${R(amtVal)}</td></tr>
                        <tr class="du-row"><td colspan="2">Dues &nbsp;<span class="du-tag">DUE</span></td><td>${R(0)}</td></tr>
                    </tfoot>
                </table>
            </div>
            <div class="rc-ftr">
                <div class="rc-fnote"><b>${school.name}</b> · Computer generated receipt<br>UDISE : ${school.udise} &nbsp;·&nbsp; ${school.phone}</div>
                <div><div class="rc-sig-line"></div><div class="rc-sig-lbl">Signature & Stamp</div></div>
            </div>
        </div>`;
    };
    document.getElementById('receiptContent').innerHTML = buildHalf('office') + '<div class="cut"><div class="cut-line"></div><i class="fas fa-cut"></i><span>CUT HERE &nbsp;·&nbsp; GUARDIAN COPY BELOW</span><div class="cut-line"></div></div>' + buildHalf('guardian');
}

async function shareReceiptWithWA(){
    const btn = document.getElementById('waReceiptBtn');
    btn.disabled=true; btn.innerHTML='Generating...';
    try{
        const canvas = await html2canvas(document.getElementById('receiptContent'),{scale:2});
        const pdfData = new jspdf.jsPDF('p','mm','a4');
        pdfData.addImage(canvas.toDataURL('image/jpeg',0.95),'JPEG',0,0,210,(canvas.height*210)/canvas.width);
        const file = new File([pdfData.output('blob')], 'Receipt.pdf', {type:'application/pdf'});
        if(navigator.share) await navigator.share({files:[file], text:'School Fee Receipt'});
        else window.open(URL.createObjectURL(pdfData.output('blob')),'_blank');
    }catch(e){alert('Error: '+e.message)} finally{btn.disabled=false; btn.innerHTML='WhatsApp + PDF';}
}

// Move modals to body
window.addEventListener('DOMContentLoaded', () => {
  ['dueBg', 'fpBg', 'pmBg', 'receiptModal'].forEach(id => {
    const el = document.getElementById(id); if(el) document.body.appendChild(el);
  });
});
</script>
@endpush
