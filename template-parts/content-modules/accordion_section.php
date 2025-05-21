<?php

use helpers\ACFHelper;

// Generate unique ID for this accordion instance
$accordion_id = wp_unique_id('accordion-'); // e.g. "accordion-0"

// Content Fields
$section_title        = get_sub_field('section_title');
$section_description  = get_sub_field('section_description');
$accordion_type       = get_sub_field('accordion_type');
$accordion            = get_sub_field('accordion');
$progress_circle      = get_sub_field('progress_circle');

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

<?php switch ($accordion_type):
  case 'custom':
    $hasProgressCircle = false;
    if (!empty($accordion)) {
      foreach ($accordion as $item) {
        if (isset($item['accordion_item_type']) && $item['accordion_item_type'] === 'progress_circle') {
          $hasProgressCircle = true;
          break; // no need to keep checking if we've already found one
        }
      }
    }
?>
    <section class="accordion-section<?php if ($custom_class): ?> <?php echo $custom_class; ?><?php endif; ?>"
      style="
        --padding-top: <?php echo esc_attr($padding_top); ?>px;
        --padding-bottom: <?php echo esc_attr($padding_bottom); ?>px;
        --padding-top-mobile: <?php echo esc_attr($padding_top_mobile); ?>px;
        --padding-bottom-mobile: <?php echo esc_attr($padding_bottom_mobile); ?>px;
        --background-color: <?php echo esc_attr($background_color); ?>;">
      <div class="container">

        <?php if (!empty($section_title) || !empty($section_description)): ?>
          <header class="section-header">
            <div class="row">
              <div class="col-12 col-lg-10">
                <?php if ($section_title): ?>
                  <h3 class="section-title"><?php echo wp_kses($section_title, $allowed_tags); ?></h3>
                <?php endif; ?>
                <?php if ($section_description): ?>
                  <p><?php echo wp_kses_post($section_description); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </header>
        <?php endif; ?>

        <div class="accordion-area row">
          <div class="col-12 col-md-7">
            <div id="<?php echo esc_attr($accordion_id); ?>" class="js-accordion">
              <?php
              $i = 0;
              foreach ($accordion as $item) :
                $i++;
                // Unique IDs for heading & collapse
                $heading_id  = $accordion_id . '-heading-'  . $i;
                $collapse_id = $accordion_id . '-collapse-' . $i;

                // First item expanded by default.
                $show_class     = ($i === 1) ? ' show' : '';
                $collapsed_class = ($i === 1) ? '' : ' collapsed';
                $aria_expanded  = ($i === 1) ? 'true' : 'false';
                $link = $item['accordion_link'];
                $image = $item['accordion_image'];
              ?>

                <div class="card card--accordion">
                  <div class="card-header" id="<?php echo esc_attr($heading_id); ?>">
                    <h4 class="mb-0">
                      <button
                        class="btn btn-link<?php echo esc_attr($collapsed_class); ?>"
                        data-toggle="collapse"
                        data-target="#<?php echo esc_attr($collapse_id); ?>"
                        aria-expanded="<?php echo esc_attr($aria_expanded); ?>"
                        aria-controls="<?php echo esc_attr($collapse_id); ?>">
                        <?php echo esc_html($item['accordion_title']); ?>
                      </button>
                    </h4>
                  </div>
                  <div
                    id="<?php echo esc_attr($collapse_id); ?>"
                    class="collapse<?php echo esc_attr($show_class); ?>"
                    aria-labelledby="<?php echo esc_attr($heading_id); ?>"
                    data-parent="#<?php echo esc_attr($accordion_id); ?>">
                    <div class="card-body">
                      <?php echo wp_kses_post($item['accordion_content']); ?>
                      <?php
                      if ($link) :
                        $link_url    = $link['url'];
                        $link_title  = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                      ?>
                        <a class="card-link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                          <?php echo esc_html($link_title); ?>
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div><!-- /.card -->
              <?php endforeach; ?>

            </div><!-- /#<?php echo $accordion_id; ?> -->
          </div><!-- .col-md-7 -->

          <div class="col-12 col-md-5">
            <?php
            $j = 0;
            foreach ($accordion as $item) :
              $j++;
              // Unique IDs for accordion side content
              $side_id  = $accordion_id . '-side-'  . $j;
              $accordion_item_type = $item['accordion_item_type'];
              $progress_circle = $item['accordion_progress_circle'];
              $image = $item['accordion_image'];
              $image_url = $image['url'];
              $image_alt = $image['alt'];
              $active_class = ($j === 1) ? ' active' : '';
            ?>
              <div id="<?php echo esc_attr($side_id); ?>" class="side-panel<?php echo esc_attr($active_class); ?>">

                <?php if ($accordion_item_type === 'progress_circle') : ?>
                  <?php if (!empty($progress_circle)) : ?>
                    <div class="row progress-circle-container">
                      <?php foreach ($progress_circle as $circle) :
                        $decimal_value = floatval(trim($circle['progress_value'])) / 100;
                      ?>
                        <div class="col-6">
                          <div class="progress-circle" data-value="<?php echo esc_attr($decimal_value); ?>" data-color="<?php echo esc_attr($circle['progress_color']); ?>" data-text="<?php echo esc_attr($circle['progress_description']); ?>"></div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>

                <?php if ($accordion_item_type === 'image') : ?>
                  <?php if ($image) : ?>
                    <div class="thumbnail-container d-md-block d-none">
                      <img src="<?php echo esc_url($image_url); ?>" class="img-thumbnail" alt="<?php if ($image_alt): ?><?php echo esc_attr($image_alt); ?><?php else: ?><?php echo esc_html($item['accordion_title']); ?><?php endif; ?>">
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div><!-- /#<?php echo $side_id; ?> -->

            <?php endforeach; ?>
          </div><!-- .col-md-5 -->

        </div><!-- .accordion-area .row -->
      </div><!-- .container -->
    </section><!-- .accordion-section -->
    <script>
      (function($) {
        <?php if ($hasProgressCircle): ?>
          // Progress Circle Animation
          function createProgressBar(container, value, color, additionalText) {
            const dot = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            dot.setAttribute("fill", color);
            dot.setAttribute("r", 2); // Radius 2px for 10px diameter

            const progressBar = new ProgressBar.Circle(container, {
              color: color,
              trailColor: '#C4C4C4',
              strokeWidth: 1.5,
              trailWidth: 0.5,
              duration: 2000,
              easing: 'easeInOut',
              text: {
                autoStyleContainer: false
              },
              step: function(state, circle) {
                const percentage = Math.round(circle.value() * 100);
                // Constructing the text with <sup> tag for superscript
                circle.setText(`<span class="text-percentage">${percentage}<sup>%</sup></span> <span class="text-description">${additionalText}</span>`);

                // Update dot position
                const progressPath = circle.path;
                const length = progressPath.getTotalLength();
                const point = progressPath.getPointAtLength(length * circle.value());

                dot.setAttribute("cx", point.x);
                dot.setAttribute("cy", point.y);
              }
            });

            // Append the dot to the progress bar's SVG
            progressBar.path.parentNode.appendChild(dot);

            // Start the progress bar animation
            progressBar.animate(value);

          }

          // Select each progress-circle element and extract data attributes
          $('.progress-circle').each(function() {
            const $container = $(this);
            const value = parseFloat($container.data('value'));
            const color = $container.data('color');
            const text = $container.data('text');

            // Initialize the progress bar for each container
            createProgressBar(this, value, color, text);
          });

        <?php endif; ?>
        // For each accordion container
        $('.js-accordion').each(function() {
          var $accordion = $(this);

          $accordion.on('show.bs.collapse', function(e) {
            var $sidePanelSuffix = e.target.id.replace('collapse', 'side');
            var $sidePanel = $('#' + $sidePanelSuffix);

            $accordion.parents('.accordion-area').find('.side-panel').removeClass('active');
            $sidePanel.addClass('active');
          });
        });
      })(jQuery);
    </script>
  <?php break;
  case 'basic':
  default: ?>

    <section class="accordion-section<?php if ($custom_class): ?> <?php echo $custom_class; ?><?php endif; ?>"
      style="
        --padding-top: <?php echo esc_attr($padding_top); ?>px;
        --padding-bottom: <?php echo esc_attr($padding_bottom); ?>px;
        --padding-top-mobile: <?php echo esc_attr($padding_top_mobile); ?>px;
        --padding-bottom-mobile: <?php echo esc_attr($padding_bottom_mobile); ?>px;
        --background-color: <?php echo esc_attr($background_color); ?>;">
      <div class="container">

        <?php if (!empty($section_title) || !empty($section_description)): ?>
          <header class="section-header">
            <div class="row">
              <div class="col-12 col-lg-10">
                <?php if ($section_title): ?>
                  <h3 class="section-title"><?php echo wp_kses($section_title, $allowed_tags); ?></h3>
                <?php endif; ?>
                <?php if ($section_description): ?>
                  <p><?php echo wp_kses_post($section_description); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </header>
        <?php endif; ?>

        <div class="accordion-area row">
          <div class="col-12">
            <div id="<?php echo esc_attr($accordion_id); ?>">

              <?php
              $i = 0;
              foreach ($accordion as $item) :
                $i++;
                // Unique IDs for heading & collapse
                $heading_id  = $accordion_id . '-heading-'  . $i;
                $collapse_id = $accordion_id . '-collapse-' . $i;

                $is_active_class = ($i === 1) ? ' show' : '';
                $aria_expanded   = ($i === 1) ? 'true'  : 'false';
                $collapsed_class = ($i === 1) ? ''      : ' collapsed';
                $link = $item['accordion_link'];
              ?>
                <div class="card card--accordion">
                  <div class="card-header" id="<?php echo esc_attr($heading_id); ?>">
                    <h4 class="mb-0">
                      <button
                        class="btn btn-link<?php echo esc_attr($collapsed_class); ?>"
                        data-toggle="collapse"
                        data-target="#<?php echo esc_attr($collapse_id); ?>"
                        aria-expanded="<?php echo esc_attr($aria_expanded); ?>"
                        aria-controls="<?php echo esc_attr($collapse_id); ?>">
                        <?php echo esc_html($item['accordion_title']); ?>
                      </button>
                    </h4>
                  </div>
                  <div
                    id="<?php echo esc_attr($collapse_id); ?>"
                    class="collapse<?php echo esc_attr($is_active_class); ?>"
                    aria-labelledby="<?php echo esc_attr($heading_id); ?>"
                    data-parent="#<?php echo esc_attr($accordion_id); ?>">
                    <div class="card-body">
                      <?php echo wp_kses_post($item['accordion_content']); ?>
                      <?php
                      if ($link) :
                        $link_url    = $link['url'];
                        $link_title  = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                      ?>
                        <a class="card-link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                          <?php echo esc_html($link_title); ?>
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div><!-- /#<?php echo $accordion_id; ?> -->
          </div>
        </div><!-- .accordion-area.row  -->

      </div><!-- .container -->
    </section><!-- .accordion-section -->

<?php break;
endswitch; ?>