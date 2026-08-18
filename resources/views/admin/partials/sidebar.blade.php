@php
  $route = Route::currentRouteName();
@endphp

<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ url('/') }}">Rumah Tas Lucu</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ url('/') }}">RTL</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="{{ Str::contains($route, 'admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
           <i class="fas fa-house-damage"></i> <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a class="nav-link" href="https://us5.admin.mailchimp.com/" target="_blank">
           <i class="fas fa-envelope"></i> <span>Dashboard Kirim Email</span>
        </a>
      </li>
      <li>
        <a class="nav-link" href="https://dashboard.tawk.to/" target="_blank">
           <i class="fas fa-comments"></i> <span>Dashboard Chat CS</span>
        </a>
      </li>

      <li class="menu-header">Master</li>
      @if (isPermittedOneOf(['admin.users.index', 'admin.customers.index', 'admin.suppliers.index', 'admin.couriers.index']))
      <li class="nav-item dropdown">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i> <span>Akun</span></a>
        <ul class="dropdown-menu">
          @if (isPermitted('admin.users.index'))
          <li class="{{ Str::contains($route, 'admin.users') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
              <i class="fas fa-user"></i> <span>User</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.customers.index'))
          <li class="{{ Str::contains($route, 'admin.customers') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.customers.index') }}">
              <i class="fas fa-user"></i> <span>Customer</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.suppliers.index'))
          <li class="{{ Str::contains($route, 'admin.suppliers') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.suppliers.index') }}">
              <i class="fas fa-user-tie"></i> <span>Supplier</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.couriers.index'))
          <li class="{{ Str::contains($route, 'admin.couriers') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.couriers.index') }}">
              <i class="fas fa-user-clock"></i> <span>Kurir</span>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      @if (isPermittedOneOf(['admin.lookbooks.index', 'admin.colors.index', 'admin.categories.index', 'admin.products.index']))
      <li class="nav-item dropdown">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-boxes"></i> <span>Produk</span></a>
        <ul class="dropdown-menu">
          @if (isPermitted('admin.lookbooks.index'))
          <li class="{{ Str::contains($route, 'admin.lookbooks') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.lookbooks.index') }}">
              <i class="fas fa-book-reader"></i> <span>Lookbook</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.colors.index'))
          <li class="{{ Str::contains($route, 'admin.colors') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.colors.index') }}">
              <i class="fas fa-palette"></i> <span>Warna</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.categories.index'))
          <li class="{{ Str::contains($route, 'admin.categories') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
              <i class="fas fa-box"></i> <span>Kategori</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.products.index'))
          <li class="{{ Str::contains($route, 'admin.products') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.products.index') }}">
              <i class="fas fa-shopping-bag"></i> <span>Produk</span>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      @if (isPermittedOneOf(['admin.coupons.index', 'admin.sliders.index']))
      <li class="nav-item dropdown">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-tags"></i> <span>Promo</span></a>
        <ul class="dropdown-menu">
          @if (isPermitted('admin.coupons.index'))
          <li class="{{ Str::contains($route, 'admin.coupons') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.coupons.index') }}">
              <i class="fas fa-tag"></i> <span>Kupon</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.sliders.index'))
          <li class="{{ Str::contains($route, 'admin.sliders') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.sliders.index') }}">
              <i class="fas fa-images"></i> <span>Banner</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.rewards.index'))
          <li class="{{ Str::contains($route, 'admin.rewards') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.rewards.index') }}">
              <i class="fas fa-gift"></i> <span>Hadiah</span>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      @if (isPermittedOneOf([
        'admin.blog-categories.index',
        'admin.accounting-categories.index',
        'admin.blogs.index',
        'admin.banks.index',
        'admin.pages.index',
        'admin.menus.index',
        'admin.about.index',
        'admin.faq.index',
        'admin.terms_and_agreements.index',
        'admin.stores.index',
        'admin.notification.index',
      ]))
      <li class="nav-item dropdown">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-pen-square"></i> <span>Konten Web</span></a>
        <ul class="dropdown-menu">
          @if (isPermitted('admin.blog-categories.index'))
          <li class="{{ Str::contains($route, 'admin.blog-categories') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.blog-categories.index') }}">
              <i class="fas fa-file-alt"></i> <span>Kategori Artikel</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.accounting-categories.index'))
          <li class="{{ Str::contains($route, 'admin.accounting-categories') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.accounting-categories.index') }}">
              <i class="fas fa-money-bill-alt"></i> <span>Kategori Accounting</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.blogs.index'))
          <li class="{{ Str::contains($route, 'admin.blogs') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.blogs.index') }}">
              <i class="fas fa-newspaper"></i> <span>Artikel</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.banks.index'))
          <li class="{{ Str::contains($route, 'admin.banks') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.banks.index') }}">
              <i class="fas fa-university"></i> <span>Bank</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.pages.index'))
          <li class="{{ Str::contains($route, 'admin.pages') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.pages.index') }}">
              <i class="fas fa-file"></i> <span>Halaman</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.menus.index'))
          <li class="{{ Str::contains($route, 'admin.menus') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.menus.index') }}">
              <i class="fas fa-bars"></i> <span>Menu</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.about.index'))
          <li class="{{ Str::contains($route, 'admin.about') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.about.index') }}">
              <i class="fas fa-bars"></i> <span>Tentang Kami</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.faq.index'))
          <li class="{{ Str::contains($route, 'admin.faq') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.faq.index') }}">
              <i class="fas fa-bars"></i> <span>FAQs</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.terms_and_agreements.index'))
          <li class="{{ Str::contains($route, 'admin.terms_and_agreements') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.terms_and_agreements.index') }}">
              <i class="fas fa-bars"></i> <span>Syarat & Ketentuan</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.stores.index'))
          <li class="{{ Str::contains($route, 'admin.stores') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.stores.index') }}">
              <i class="fas fa-bars"></i> <span>Toko</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.other-tab-photos.index'))
          <li class="{{ Str::contains($route, 'admin.other-tab-photos') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.other-tab-photos.index') }}">
              <i class="fas fa-bars"></i> <span>Foto Tab Lainnya</span>
            </a>
          </li>
          @endif
          @if (isPermitted('admin.notification.index'))
          <li class="{{ Str::contains($route, 'admin.notification') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.notification.index') }}">
              <i class="fas fa-bars"></i> <span>Notifikasi</span>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      @if (isPermitted('admin.customer-points.index'))
      <li class="{{ Str::contains($route, 'admin.customer-points') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.customer-points.index') }}">
            <i class="fas fa-fire"></i> <span>Poin Customer</span>
        </a>
      </li>
      @endif

      <li class="menu-header">Transaksi</li>
      @if (isPermitted('admin.deposits.index'))
      <li class="{{ Str::contains($route, 'admin.deposits') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.deposits.index') }}">
           <i class="fas fa-money-bill-wave"></i> <span>Deposit</span>
        </a>
      </li>
      @endif
      <!-- @if (isPermitted('admin.accountings.index'))
      <li class="{{ Str::contains($route, 'admin.accountings') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.accountings.index') }}">
           <i class="fas fa-money-bill-wave"></i> <span>Accounting</span>
        </a>
      </li>
      @endif -->
      @if (isPermitted('admin.courier-cost-expenses.index'))
      <li class="{{ Str::contains($route, 'admin.courier-cost-expenses') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.courier-cost-expenses.index') }}">
           <i class="fas fa-truck"></i> <span>Pengeluaran Ongkir</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.expenses.index'))
      <li class="{{ Str::contains($route, 'admin.expenses') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.expenses.index') }}">
           <i class="fas fa-arrow-left"></i> <span>Pengeluaran</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.offline-sales.index'))
      <li class="{{ Str::contains($route, 'admin.offline-sales.') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.offline-sales.index') }}">
           <i class="fas fa-shopping-basket"></i> <span>Penjualan Offline</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.online-sales.index'))
      <li class="{{ Str::contains($route, 'admin.online-sales.') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.online-sales.index') }}">
           <i class="fas fa-shopping-basket"></i> <span>Penjualan Online</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.shopee-sales.index'))
      <li class="{{ Str::contains($route, 'admin.shopee-sales.') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.shopee-sales.index') }}">
           <i class="fas fa-shopping-basket"></i> <span>Penjualan Shopee</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.salesreturns.index'))
      <li class="{{ Str::contains($route, 'admin.salesreturns.') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.salesreturns.index') }}">
           <i class="fas fa-hands"></i> <span>Pengembalian</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.receivings.index'))
      <li class="{{ Str::contains($route, 'admin.receivings') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.receivings.index') }}">
           <i class="fas fa-hand-holding"></i> <span>Penerimaan (Kulakan)</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.deliveries.index'))
      <li class="{{ Str::contains($route, 'admin.deliveries') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.deliveries.index') }}">
           <i class="fas fa-box"></i> <span>Pengiriman</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.transfer-stocks.index'))
      <li class="{{ Str::contains($route, 'admin.transfer-stocks') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.transfer-stocks.index') }}">
           <i class="fas fa-arrows-alt-h"></i> <span>Transfer Stok</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.stock-adjustments.index'))
      <li class="{{ Str::contains($route, 'admin.stock-adjustments') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.stock-adjustments.index') }}">
           <i class="fas fa-warehouse"></i> <span>Penyesuaian Stok</span>
        </a>
      </li>
      @endif

      <li class="menu-header">Laporan</li>
      @if (isPermitted('admin.reports.wholesale-costs'))
      <li class="{{ Str::contains($route, 'admin.reports.wholesale-costs') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.wholesale-costs') }}">
           <i class="fas fa-chart-line"></i> <span>Biaya Kulak</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.stocks'))
      <!-- <li class="{{ Str::contains($route, 'admin.reports.stocks') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.stocks') }}">
           <i class="fas fa-chart-line"></i> <span>Stok Keluar/Masuk</span>
        </a>
      </li> -->
      @endif
      @if (isPermitted('admin.reports.realtime-stocks'))
      <li class="{{ Str::contains($route, 'admin.reports.realtime-stocks') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.realtime-stocks') }}">
           <i class="fas fa-chart-line"></i> <span>Stok Realtime</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.global-sales'))
      <li class="{{ Str::contains($route, 'admin.reports.global-sales') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.global-sales') }}">
           <i class="fas fa-chart-line"></i> <span>Penjualan Global</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.warehouse-offline-sales'))
      <li class="{{ Str::contains($route, 'admin.reports.warehouse-offline-sales') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.warehouse-offline-sales') }}">
           <i class="fas fa-chart-line"></i> <span>Penjualan Offline Gudang</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.store-offline-sales'))
      <li class="{{ Str::contains($route, 'admin.reports.store-offline-sales') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.store-offline-sales') }}">
           <i class="fas fa-chart-line"></i> <span>Penjualan Offline Toko</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.online-sales'))
      <li class="{{ Str::contains($route, 'admin.reports.online-sales') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.online-sales') }}">
           <i class="fas fa-chart-line"></i> <span>Penjualan Online</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.gross-sales'))
      <li class="{{ Str::contains($route, 'admin.reports.gross-sales') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.gross-sales') }}">
           <i class="fas fa-chart-line"></i> <span>Pendapatan Kotor</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.net-sales'))
      <li class="{{ Str::contains($route, 'admin.reports.net-sales') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.net-sales') }}">
           <i class="fas fa-chart-line"></i> <span>Pendapatan Bersih</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.per-method-sales'))
      <li class="{{ Str::contains($route, 'admin.reports.per-method-sales') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.per-method-sales') }}">
           <i class="fas fa-chart-line"></i> <span>Penjualan Per Metode</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.unpaid-order'))
      <li class="{{ Str::contains($route, 'admin.reports.unpaid-order') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.unpaid-order') }}">
           <i class="fas fa-chart-line"></i> <span>Belum Dibayar</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.courier-costs'))
      <li class="{{ Str::contains($route, 'admin.reports.courier-costs') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.courier-costs') }}">
           <i class="fas fa-chart-line"></i> <span>Pembayaran Ongkir</span>
        </a>
      </li>
      @endif
      @if (isPermitted('admin.reports.expenses'))
      <li class="{{ Str::contains($route, 'admin.reports.expenses') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.reports.expenses') }}">
           <i class="fas fa-chart-line"></i> <span>Pengeluaran</span>
        </a>
      </li>
      @endif

      <li class="menu-header">Pengaturan</li>
      @if (isPermitted('admin.settings.index'))
      <li class="{{ Str::contains($route, 'admin.settings') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.settings.index') }}">
           <i class="fas fa-cog"></i> <span>Pengaturan</span>
        </a>
      </li>
      @endif
    </ul>
  </aside>
</div>
