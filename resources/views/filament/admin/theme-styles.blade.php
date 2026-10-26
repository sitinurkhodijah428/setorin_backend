<style>
    /* ==========================================================================
       Setor.in / Rumah Hijau - Theme Styling (Dark Green Sidebar & Clean White Cards)
       ========================================================================== */

    /* 1. SIDEBAR DEEP FOREST GREEN */
    aside.fi-sidebar,
    .fi-sidebar-header,
    .fi-sidebar-nav,
    .fi-sidebar {
        background-color: #0f3e2b !important;
        border-right: 1px solid #0b3223 !important;
    }

    .fi-sidebar-header {
        background-color: #0f3e2b !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    /* ALL SIDEBAR TEXT & ICONS -> PURE CRISP WHITE */
    .fi-sidebar-nav .fi-sidebar-item-button,
    .fi-sidebar-nav .fi-sidebar-item-label,
    .fi-sidebar-nav .fi-sidebar-item-button span,
    .fi-sidebar-nav a,
    .fi-sidebar-nav li span {
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
    }

    .fi-sidebar-nav .fi-sidebar-item-icon,
    .fi-sidebar-nav .fi-sidebar-item-button svg {
        color: #ffffff !important;
        opacity: 0.95 !important;
        width: 1.25rem !important;
        height: 1.25rem !important;
    }

    /* Sidebar Group Headings */
    .fi-sidebar-group-label,
    .fi-sidebar-group-label span {
        color: #d1fae5 !important; /* soft bright white-mint */
        font-size: 0.6875rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.06em !important;
        text-transform: uppercase !important;
        opacity: 0.9 !important;
    }

    /* Sidebar Button Hover */
    .fi-sidebar-nav .fi-sidebar-item-button {
        border-radius: 0.65rem !important;
        margin: 2px 0 !important;
        transition: background-color 0.15s ease !important;
    }

    .fi-sidebar-nav .fi-sidebar-item-button:hover {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }

    /* Active Sidebar Navigation Item (Dashboard) */
    .fi-sidebar-item.fi-active .fi-sidebar-item-button,
    .fi-sidebar-item-active .fi-sidebar-item-button {
        background-color: #1e563e !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2) !important;
    }

    .fi-sidebar-item.fi-active .fi-sidebar-item-button .fi-sidebar-item-icon,
    .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
        color: #ffffff !important;
    }

    /* 2. BRAND LOGO IN SIDEBAR HEADER */
    .sj-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .sj-brand-ico {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.35rem;
        height: 2.35rem;
        border-radius: 0.75rem;
        background: #a7f3d0;
        color: #0f5132;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    .sj-brand-name {
        font-weight: 800;
        font-size: 1.15rem;
        line-height: 1.15;
        color: #ffffff !important;
        letter-spacing: -0.01em;
    }
    .sj-brand-sub {
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: #bbf7d0 !important;
    }

    /* 3. SIDEBAR IMPACT CARD */
    .sj-impact {
        margin: 1.25rem 0.75rem;
        padding: 1.1rem;
        border-radius: 1rem;
        background: rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s ease;
    }
    .sj-impact:hover {
        transform: translateY(-2px);
    }
    .sj-impact-title {
        font-size: 0.625rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        color: #bef264 !important; /* bright lime */
        text-transform: uppercase;
    }
    .sj-impact-val {
        font-size: 1.35rem;
        font-weight: 800;
        color: #ffffff !important;
        margin: 0.35rem 0 0.2rem 0;
        letter-spacing: -0.02em;
    }
    .sj-impact-note {
        font-size: 0.7rem;
        line-height: 1.35;
        color: #e2e8f0 !important;
    }

    /* 4. TOPBAR & USER MENU */
    .fi-topbar {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .sj-user {
        display: flex;
        flex-direction: column;
        text-align: right;
        margin-right: 0.65rem;
        line-height: 1.2;
    }
    .sj-user-name {
        font-size: 0.875rem;
        font-weight: 700;
        color: #0f172a;
    }
    .sj-user-role {
        font-size: 0.7rem;
        font-weight: 500;
        color: #64748b;
    }

    /* 5. MAIN BACKGROUND */
    .fi-main {
        background-color: #f8fafc !important;
    }

    /* 6. STATS OVERVIEW CARDS: PERSEGI (SQUARE-LIKE), SEJAJAR, TIDAK MAKAN TEMPAT */
    @media (min-width: 1024px) {
        /* Susun 5 kartu sejajar dalam 1 baris */
        .fi-wi-stats-overview-stats-ctn,
        .fi-wi-stats-overview > div {
            display: grid !important;
            grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
            gap: 1rem !important;
        }
    }

    .fi-wi-stats-overview-stat {
        background-color: #ffffff !important;
        border-radius: 1.15rem !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        box-shadow: 0 2px 8px -2px rgba(0, 0, 0, 0.04) !important;
        padding: 1.15rem 1.25rem !important;
        min-height: 125px !important;
        max-height: 145px !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        transition: transform 0.18s ease, box-shadow 0.18s ease !important;
    }

    .fi-wi-stats-overview-stat:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 20px -4px rgba(0, 0, 0, 0.08) !important;
    }

    /* Angka & Label Kartu Ringkas & Tegas */
    .fi-wi-stats-overview-stat-value {
        font-size: 1.75rem !important;
        font-weight: 800 !important;
        letter-spacing: -0.03em !important;
        color: #0f172a !important;
        line-height: 1.15 !important;
        margin: 0.35rem 0 !important;
    }

    .fi-wi-stats-overview-stat-label {
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        color: #64748b !important;
    }

    .fi-wi-stats-overview-stat-description {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
    }

    /* Sembunyikan canvas sparkline jika ada agar tidak membuat kartu panjang */
    .fi-wi-stats-overview-stat-chart,
    .fi-wi-stats-overview-stat canvas {
        display: none !important;
        height: 0 !important;
    }

    /* 7. GRAFIK, KOMPOSISI & TABEL TETAP SESUAI STANDAR MOCKUP */
    .fi-section,
    .fi-wi-table {
        background-color: #ffffff !important;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        box-shadow: 0 2px 10px -2px rgba(0, 0, 0, 0.03) !important;
        overflow: hidden !important;
    }

    .fi-ta-header-cell {
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.05em !important;
        color: #64748b !important;
        text-transform: uppercase !important;
    }

    .fi-ta-row {
        transition: background-color 0.12s ease !important;
    }
    .fi-ta-row:hover {
        background-color: #f8fafc !important;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: rgba(15, 62, 43, 0.25);
        border-radius: 9999px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(15, 62, 43, 0.45);
    }
</style>
