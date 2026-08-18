<footer>
  <div class="container">
    <div class="footer-links-box">
      <div class="row">
        @foreach($menu_footer as $menu)
          <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="footer-links">
              <div>
                <b>{{ $menu->title }}</b>
              </div>
              @foreach($menu->children->sortBy('position') as $child)
                <div>
                  <a href="{{ $child->link }}">{{ $child->title }}</a>
                </div>
              @endforeach
            </div>
          </div>
        @endforeach
          <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="footer-links socmed">
              <div>
                <b>Ikuti Kami</b>
              </div>
              <div>
                <img src="{{ url('img/facebook.png') }}">
                <a target="_blank" href="{{ $settings['FACEBOOK_LINK'] }}"><span>Facebook</span></a>
              </div>
              <div>
                <img src="{{ url('img/twitter.png') }}">
                <a target="_blank" href="{{ $settings['TWITTER_LINK'] }}"><span>Twitter</span></a>
              </div>
              <div>
                <img src="{{ url('img/instagram.png') }}">
                <a target="_blank" href="{{ $settings['INSTAGRAM_LINK'] }}"><span>Instagram</span></a>
              </div>
              <div>
                <img src="{{ url('img/whatsapp.png') }}">
                <a target="_blank" href="{{ $settings['WA_LINK'] }}?text={{ $settings['WA_DEFAULT_MSG'] }}"><span>WhatsApp</span></a>
              </div>
              <div>
                <img src="{{ url('img/youtube.png') }}" style="width: auto;height: 12px;">
                <a target="_blank" href="{{ $settings['YOUTUBE_LINK'] }}"><span>Youtube</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="foot">
        Copyright © {{ date('Y') }} {{ $settings['WEB_TITLE'] }}. All rights reserved.
      </div>
    </div>
  </footer>
