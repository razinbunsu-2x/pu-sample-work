<?php

use helpers\ACFHelper;

// Content Fields
$video_type   = get_sub_field('video_type');
$video_class  = strtolower(str_replace(['_', ' '], '-', $video_type));
$video_source = get_sub_field('video_source');
$video_id     = get_sub_field('video_id');
$video_image  = get_sub_field('video_thumbnail');

// Styling Fields
$custom_class          = sanitize_text_field(get_sub_field('custom_class'));
$padding               = get_sub_field('section_padding');
$padding_top           = ACFHelper::get_padding_value($padding, 'padding_top', 120, true);
$padding_bottom        = ACFHelper::get_padding_value($padding, 'padding_bottom', 80, true);
$padding_top_mobile    = ACFHelper::get_padding_value($padding, 'mobile_padding_top', 120, true);
$padding_bottom_mobile = ACFHelper::get_padding_value($padding, 'mobile_padding_bottom', 80, true);
$background_color      = sanitize_hex_color(get_sub_field('section_background_color')) ?: '#ffffff';

// Define which tags you want to allow
$allowed_tags = [
  'br' => [],
  'strong' => [],
  'em'     => []
];
?>

<?php switch ($video_type):
  case 'fullwidth':
?>

    <section class="video-section video-section--<?php echo esc_attr($video_class); ?><?php if ($custom_class): ?> <?php echo $custom_class; ?><?php endif; ?>"
      style="
  --padding-top: <?php echo esc_attr($padding_top); ?>px;
  --padding-bottom: <?php echo esc_attr($padding_bottom); ?>px;
  --padding-top-mobile: <?php echo esc_attr($padding_top_mobile); ?>px;
  --padding-bottom-mobile: <?php echo esc_attr($padding_bottom_mobile); ?>px;
  --background-color: <?php echo esc_attr($background_color); ?>;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-10">
            <?php
            // Safety checks & sanitization
            $video_source = $video_source ? esc_attr($video_source) : 'wistia';
            $video_id     = $video_id ? esc_attr($video_id) : '';
            $image_url    = !empty($video_image['url']) ? esc_url($video_image['url']) : '';
            if ($video_id) : ?>
              <div
                class="video-wrapper embed-responsive"
                data-video-id="<?php echo $video_id; ?>"
                data-video-provider="<?php echo $video_source; ?>">
                <div
                  class="video-thumbnail"
                  style="background-image: url('<?php echo $image_url; ?>');">
                  <div class="play-button"></div>
                </div><!-- .video-thumbnail -->
              </div><!-- .video-wrapper -->
            <?php endif; ?>
          </div><!-- .col -->
        </div><!-- .row -->
      </div><!-- .container -->
    </section>

  <?php
    break;
  case 'two_column':
    $video_title = get_sub_field('section_title');
    $video_description = get_sub_field('section_description');
  ?>

    <section class="video-section video-section--<?php echo esc_attr($video_class); ?><?php if ($custom_class): ?> <?php echo $custom_class; ?><?php endif; ?>"
      style="
    --padding-top: <?php echo esc_attr($padding_top); ?>px;
    --padding-bottom: <?php echo esc_attr($padding_bottom); ?>px;
    --padding-top-mobile: <?php echo esc_attr($padding_top_mobile); ?>px;
    --padding-bottom-mobile: <?php echo esc_attr($padding_bottom_mobile); ?>px;
    --background-color: <?php echo esc_attr($background_color); ?>;">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6">
            <?php if ($video_title): ?>
              <h3><?php echo wp_kses($video_title, $allowed_tags); ?></h3>
            <?php endif; ?>

            <?php if ($video_description): ?>
              <?php echo wp_kses_post($video_description); ?>
            <?php endif; ?>
          </div>
          <div class="col-12 col-md-6">

            <?php
            // Safety checks & sanitization
            $video_source = $video_source ? esc_attr($video_source) : 'wistia';
            $video_id     = $video_id ? esc_attr($video_id) : '';
            $image_url    = !empty($video_image['url']) ? esc_url($video_image['url']) : '';
            if ($video_id) : ?>
              <div
                class="video-wrapper embed-responsive"
                data-video-id="<?php echo $video_id; ?>"
                data-video-provider="<?php echo $video_source; ?>">
                <div
                  class="video-thumbnail"
                  style="background-image: url('<?php echo $image_url; ?>');">
                  <div class="play-button"></div>
                </div><!-- .video-thumbnail -->
              </div><!-- .video-wrapper -->
            <?php endif; ?>

          </div>
        </div>
      </div>
    </section>

<?php break;
endswitch; ?>

<?php if ($video_source === 'wistia') : ?>
  <script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
<?php endif; ?>
<script>
  (function($) {
    $(document).ready(function() {
      // Bind click to the thumbnail instead of the entire wrapper.
      $('.video-thumbnail').on('click', function(e) {
        e.stopPropagation(); // Prevent click from bubbling up.
        var $wrapper = $(this).closest('.video-wrapper');

        // If already playing, do nothing.
        if ($wrapper.hasClass('playing')) {
          return;
        }
        $wrapper.addClass('playing');

        var videoId = $wrapper.data('video-id');
        var provider = ($wrapper.data('video-provider') || 'wistia').toLowerCase();

        if (provider === 'youtube') {
          var iframeUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
          var iframeAttrs = {
            src: iframeUrl,
            frameborder: 0,
            allow: 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture',
            allowfullscreen: '',
            "class": "embed-responsive-item"
          };
          var $iframe = $('<iframe>', iframeAttrs).css({
            width: '100%',
            height: '100%'
          });
          $wrapper.addClass('embed-responsive-16by9').empty().append($iframe);
        } else if (provider === 'wistia') {
          var $wistiaDiv = $('<div>', {
            id: 'wistia_' + videoId,
            "class": "wistia_embed wistia_async_" + videoId + " embed-responsive-item"
          }).css({
            width: '100%',
            height: '100%'
          });
          $wrapper.addClass('embed-responsive-16by9').empty().append($wistiaDiv);
          window._wq = window._wq || [];
          _wq.push({
            id: videoId,
            onReady: function(video) {
              video.unmute();
              video.play();
            }
          });
        }
      });
    });
  })(jQuery);
</script>