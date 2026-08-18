<br>
<br>
<br>
<br>
<br>
<br>
<div class="footer-menu">
  <div class="container-mobile">
    <div class="footer-menu-list">
      <div class="footer-menu-item  ">
        <a href="{{ route('page.getIndex') }}">
          <div class="footer-menu-item-icon ">
            <i class="fa fa-home"></i>
          </div>
          <div class="footer-menu-item-label ">{{ trans('footer_menu.beranda') }}</div>
        </a>
      </div>
      <div class="footer-menu-item">
        <a href="{{ route('project.getIndex') }}">
          <div class="footer-menu-item-icon">
            <i class="fa fa-file-text-o"></i>
          </div>
          <div class="footer-menu-item-label">{{ trans('footer_menu.infak') }}</div>
        </a>
      </div>
      @if (auth()->user())
      <div class="footer-menu-item">
        <a href="{{ route('project.getCreate') }}">
          <div class="footer-menu-item-icon">
            <i class="fa fa-plus-square-o"></i>
          </div>
          <div class="footer-menu-item-label">{{ trans('footer_menu.galang_dana') }}</div>
        </a>
      </div>
      @endif
      <div class="footer-menu-item">
        <a href="{{ route('blog.getIndex') }}">
          <div class="footer-menu-item-icon">
            <i class="fa fa-newspaper-o"></i>
          </div>
          <div class="footer-menu-item-label">{{ trans('footer_menu.berita') }}</div>
        </a>
      </div>
      <div class="footer-menu-item">
        @if (auth()->guest())
        <a href="{{ route('page.getAkun') }}">
          <div class="footer-menu-item-icon">
            <i class="fa fa-user"></i>
          </div>
          <div class="footer-menu-item-label">{{ trans('footer_menu.akun') }}</div>
        </a>
        @else
        <a href="{{ route('page.getAkun') }}">
          <div class="footer-menu-item-icon">
            <i class="fa fa-user"></i>
          </div>
          <div class="footer-menu-item-label">{{ trans('footer_menu.akun') }}</div>
        </a>
        @endif
      </div>
    </div>
  </div>
</div>