<?php
$cta_type = get_sub_field('call_to_action_type');
$cta_class = strtolower(str_replace(' ', '-', $cta_type));
$custom_class = get_sub_field('custom_class');
$cta_preheading = get_sub_field('preheading');
$cta_heading = get_sub_field('heading');
$cta_description = get_sub_field('description');
$cta_button = get_sub_field('button_link');

// Define which tags you want to allow
$allowed_tags = [
  'br' => [],
  'strong' => [],
  'em'     => []
];
?>

<?php switch ($cta_type):
  case 'text_background_image':
?>

    <section class="cta-section cta-section--<?php echo esc_attr($cta_class); ?> <?php echo esc_attr($custom_class); ?>">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6">

            <?php if ($cta_preheading): ?>
              <span class="text-preheading"><?php echo wp_kses($cta_preheading, $allowed_tags); ?></span>
            <?php endif; ?>

            <?php if ($cta_heading): ?>
              <h3 class="section-title"><?php echo wp_kses($cta_heading, $allowed_tags); ?></h3>
            <?php endif; ?>

            <?php if ($cta_description): ?>
              <p><?php echo wp_kses($cta_description, $allowed_tags); ?></p>
            <?php endif; ?>

            <?php
            if ($cta_button):
              $button_url = $cta_button['url'];
              $button_title = $cta_button['title'];
              $button_target = $cta_button['target'] ? $cta_button['target'] : '_self';
            ?>
              <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary" target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_title); ?></a>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </section>

  <?php
    break;
  case 'custom':
  ?>

    <section class="cta-section cta-section--<?php echo esc_attr($cta_class); ?> <?php echo esc_attr($custom_class); ?>">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12 col-md-6" data-mh="col-cta">
            <div class="text-wrapper">
              <?php if ($cta_preheading): ?>
                <span class="text-preheading"><?php echo wp_kses($cta_preheading, $allowed_tags); ?></span>
              <?php endif; ?>

              <?php if ($cta_heading): ?>
                <h3 class="section-title"><?php echo wp_kses($cta_heading, $allowed_tags); ?></h3>
              <?php endif; ?>

              <?php if ($cta_description): ?>
                <p><?php echo wp_kses($cta_description, $allowed_tags); ?></p>
              <?php endif; ?>

              <?php
              if ($cta_button):
                $button_url = $cta_button['url'];
                $button_title = $cta_button['title'];
                $button_target = $cta_button['target'] ? $cta_button['target'] : '_self';
              ?>
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary" target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_title); ?></a>
              <?php endif; ?>
            </div>
          </div>

          <div class="col-12 col-md-6" data-mh="col-cta">
            <div class="img-wrapper">
              <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/images/assets/cta-calculator-thumbnail.png" />
            </div>
          </div>
        </div>
      </div>
    </section>

  <?php break;
  case 'default':
  default: ?>

    <section class="cta-section cta-section--<?php echo esc_attr($cta_class); ?> <?php echo esc_attr($custom_class); ?>">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col text-center">

            <?php if ($cta_preheading): ?>
              <span class="text-preheading"><?php echo wp_kses($cta_preheading, $allowed_tags); ?></span>
            <?php endif; ?>

            <?php if ($cta_heading): ?>
              <h3 class="section-title"><?php echo wp_kses($cta_heading, $allowed_tags); ?></h3>
            <?php endif; ?>

            <?php if ($cta_description): ?>
              <p><?php echo wp_kses($cta_description, $allowed_tags); ?></p>
            <?php endif; ?>

            <?php
            if ($cta_button):
              $button_url = $cta_button['url'];
              $button_title = $cta_button['title'];
              $button_target = $cta_button['target'] ? $cta_button['target'] : '_self';
            ?>
              <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary" target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_title); ?></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

<?php break;
endswitch; ?>