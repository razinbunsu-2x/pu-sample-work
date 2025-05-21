<?php
$banner_type = get_sub_field('banner_type');
$banner_class = strtolower(str_replace(['_', ' '], '-', $banner_type));
$custom_class = get_sub_field('custom_class');
$banner_intro_text = get_sub_field('intro_text');
$banner_title = get_sub_field('banner_title');
$banner_description = get_sub_field('description');
$banner_highlight_text = get_sub_field('highlight_text');
$banner_link = get_sub_field('banner_link');

// Define which tags you want to allow
$allowed_tags = [
  'br' => [],
  'strong' => [],
  'em'     => []
];
?>

<?php switch ($banner_type):
  case 'image_banner':
    $banner_image = get_sub_field('image');
?>
    <section class="banner-section <?php echo esc_attr($banner_class); ?> <?php echo esc_attr($custom_class); ?>">
      <div class="container">
        <div class="row<?php if (empty($banner_link)): ?> align-items-center<?php endif; ?>">
          <div class="col-12 col-md-6">
            <?php if ($banner_intro_text): ?>
              <p class="text-intro"><?php echo wp_kses($banner_intro_text, $allowed_tags); ?></p>
            <?php endif; ?>

            <?php if ($banner_title): ?>
              <h1 class="section-title"><?php echo wp_kses($banner_title, $allowed_tags); ?> </h1>
            <?php endif; ?>

            <?php if ($banner_description): ?>
              <?php echo wp_kses_post($banner_description); ?>
            <?php endif; ?>

            <?php if ($banner_highlight_text): ?>
              <p class="text-highlight"><?php echo wp_kses($banner_highlight_text, $allowed_tags); ?></p>
            <?php endif; ?>

            <?php
            if ($banner_link):
              $link_url = $banner_link['url'];
              $link_title = $banner_link['title'];
              $link_target = $banner_link['target'] ? $banner_link['target'] : '_self';
            ?>
              <a href="<?php echo esc_url($link_url); ?>" class="btn btn-primary" target="<?php echo esc_attr($link_target); ?>">
                <?php echo esc_html($link_title); ?>
              </a>
            <?php endif; ?>

          </div>

          <?php if ($banner_image): ?>
            <div class="col-12 col-md-6">
              <img class="img-thumbnail" src="<?php echo esc_url($banner_image['url']); ?>" alt="<?php echo esc_attr($banner_image['alt']); ?>" />
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

  <?php
    break;
  case 'video_banner':
  ?>


  <?php break;
  case 'default_banner':
    // Get images from ACF
    $banner_bg_image_desktop = get_sub_field('background_image_desktop');
    $banner_bg_image_mobile  = get_sub_field('background_image_mobile');

    // Build the CSS variables string (if images are available)
    $style_vars = '';
    if ($banner_bg_image_desktop) {
      $desktop_url = esc_url($banner_bg_image_desktop['url']);
      $style_vars .= "--banner-bg-desktop: url('{$desktop_url}');";
    }
    if ($banner_bg_image_mobile) {
      $mobile_url = esc_url($banner_bg_image_mobile['url']);
      $style_vars .= "--banner-bg-mobile: url('{$mobile_url}');";
    }

    // Build array of classes for the <section>
    $classlist = [
      'banner-section',          // base class
      esc_attr($banner_class),   // from the banner_type
      esc_attr($custom_class),   // from the ACF custom_class
    ];

    // If we detect a background image, add a custom class
    if ($banner_bg_image_desktop || $banner_bg_image_mobile) {
      $classlist[] = 'has-bg';
    }
  ?>

    <section class="<?php echo implode(' ', array_filter($classlist)); ?>"
      <?php if ($style_vars) : ?>
      style="<?php echo esc_attr($style_vars); ?>"
      <?php endif; ?>>
      <div class="container">
        <div class="row">
          <div class="col-12">

            <div class="banner-content">
              <?php if ($banner_intro_text): ?>
                <p class="text-intro"><?php echo wp_kses($banner_intro_text, $allowed_tags); ?></p>
              <?php endif; ?>

              <?php if ($banner_title): ?>
                <h1 class="section-title"><?php echo wp_kses($banner_title, $allowed_tags); ?> </h1>
              <?php endif; ?>

              <?php if ($banner_description): ?>
                <?php echo wp_kses_post($banner_description); ?>
              <?php endif; ?>

              <?php if ($banner_highlight_text): ?>
                <p class="text-highlight"><?php echo wp_kses($banner_highlight_text, $allowed_tags); ?></p>
              <?php endif; ?>

              <?php
              if ($banner_link):
                $link_url = $banner_link['url'];
                $link_title = $banner_link['title'];
                $link_target = $banner_link['target'] ? $banner_link['target'] : '_self';
              ?>
                <a href="<?php echo esc_url($link_url); ?>" class="btn btn-primary" target="<?php echo esc_attr($link_target); ?>">
                  <?php echo esc_html($link_title); ?>
                </a>
              <?php endif; ?>
            </div>

          </div>

        </div>
      </div>
    </section>

<?php break;
endswitch; ?>