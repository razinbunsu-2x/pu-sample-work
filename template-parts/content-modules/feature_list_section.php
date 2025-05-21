<?php

use helpers\ACFHelper;

// Content Fields
$section_title       = get_sub_field('section_title');
$section_description = get_sub_field('section_description');
$feature_list        = get_sub_field('media_items');

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

<section class="feature-list-section<?php if ($custom_class): ?> <?php echo $custom_class; ?><?php endif; ?>"
  style="
        --padding-top: <?php echo esc_attr($padding_top); ?>px;
        --padding-bottom: <?php echo esc_attr($padding_bottom); ?>px;
        --padding-top-mobile: <?php echo esc_attr($padding_top_mobile); ?>px;
        --padding-bottom-mobile: <?php echo esc_attr($padding_bottom_mobile); ?>px;
        --background-color: <?php echo esc_attr($background_color); ?>;">

  <div class="container">
    <?php if (!empty($section_title) || !empty($section_description)): ?>
      <header class="section-header row justify-content-center text-center">
        <div class="col-12">
          <?php if ($section_title): ?>
            <h3 class="section-title"><?php echo wp_kses($section_title, $allowed_tags); ?></h3>
          <?php endif; ?>
          <?php if ($section_description): ?>
            <p><?php echo wp_kses_post($section_description); ?></p>
          <?php endif; ?>
        </div>
      </header>
    <?php endif; ?>

    <?php if ($feature_list) : ?>
      <div class="row justify-content-center">
        <div class="col-12 col-md-11">
          <ul class="media-list list-unstyled row align-items-center justify-content-center">
            <?php
            foreach ($feature_list as $item) :
              $media_link  = $item['media_link'];
              $media_image = $item['media_image'];

              // Extract link details if not empty
              if ($media_link) {
                $link_url    = ! empty($media_link['url'])    ? esc_url($media_link['url']) : '#';
                $link_title  = ! empty($media_link['title'])  ? esc_html($media_link['title']) : '';
                $link_target = ! empty($media_link['target']) ? esc_attr($media_link['target']) : '_self';
              }
            ?>
              <li class="col-12 col-md-6">
                <a href="<?php echo $link_url; ?>" class="media media-link justify-content-center" target="<?php echo $link_target; ?>">
                  <?php if ($media_image) : ?>
                    <div class="media-image-container">
                      <img class="media-image" src="<?php echo esc_url($media_image['url']); ?>" alt="<?php echo esc_attr($media_image['alt']); ?>">
                    </div>
                  <?php endif; ?>
                  <div class="media-body align-self-center">
                    <?php if ($link_title) : ?>
                      <h5 class="media-title"><?php echo esc_html($link_title); ?></h5>
                    <?php endif; ?>
                    <img class="media-icon-arrow" src="<?php echo get_template_directory_uri(); ?>/images/assets/feature-list-arrow-icon.png" alt="Icon Arrow">
                  </div>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>