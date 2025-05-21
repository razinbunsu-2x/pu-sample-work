<?php

use helpers\ACFHelper;

// Content Fields
$slider_type     = get_sub_field('slider_type');
$slider_items    = get_sub_field('slider_items');
$slider_class    = strtolower(str_replace(['_', ' '], '-', $slider_type));
$heading         = get_sub_field('heading');
$link            = get_sub_field('link');
$slider_settings = get_sub_field('slider_settings');
$hide_dots       = $slider_settings['hide_dots'] ?? false;
$hide_arrows     = $slider_settings['hide_arrows'] ?? false;

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

<?php switch ($slider_type):
  case 'resources':
    $args = array(
      'post_type' => 'post',
      'posts_per_page' => 6,
    );
    $resources_posts_query = new WP_Query($args);

    // ✅ Override post IDs for CTA
    $register_now_ids = [14588, 14586];
?>
    <section class="slider-section slider-section--<?php echo esc_attr($slider_class); ?><?php if ($custom_class): ?> <?php echo $custom_class; ?><?php endif; ?>"
      style="
        --padding-top: <?php echo esc_attr($padding_top); ?>px;
        --padding-bottom: <?php echo esc_attr($padding_bottom); ?>px;
        --padding-top-mobile: <?php echo esc_attr($padding_top_mobile); ?>px;
        --padding-bottom-mobile: <?php echo esc_attr($padding_bottom_mobile); ?>px;
        --background-color: <?php echo esc_attr($background_color); ?>;">
      <div class="container">
        <?php if (!empty($heading)) : ?>
          <h3 class="section-title text-center">
            <?php echo wp_kses($heading, $allowed_tags); ?>
          </h3>
        <?php endif; ?>
        <div class="resources-carousel">
          <?php
          while ($resources_posts_query->have_posts()) : $resources_posts_query->the_post();
            // Get the post ID
            $post_id = get_the_ID();
            // Get the post thumbnail ID
            $thumbnail_id = get_post_thumbnail_id();
            // Get the alt text of the post thumbnail
            $alt_text = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

            // ✅ Custom CTA
            $cta_text = 'Read More';
            if (has_category('whitepapers-guides', $post_id)) {
              $cta_text = 'Download';
            } elseif (has_category('podcasts', $post_id)) {
              $cta_text = 'Listen Now';
            } elseif (has_category('webinars', $post_id)) {
              $cta_text = 'Watch Now';
            }

            // ✅ Override CTA for specific post IDs
            if (!empty($register_now_ids) && in_array($post_id, $register_now_ids)) {
              $cta_text = 'Register Now';
            }

            // ✅ Use get_post_class
            $card_classes = implode(' ', get_post_class('card card-article', $post_id));
          ?>
            <div class="<?php echo esc_attr($card_classes); ?>">
              <!-- Display the post thumbnail -->
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', ['class' => 'card-img-top', 'alt' => esc_attr($alt_text)]); ?>
              <?php else : ?>
                <img class="card-img-top" src="<?php echo get_template_directory_uri(); ?>/images/assets/default-placeholder-search.webp" alt="Process Unity" />
              <?php endif; ?>
              <div class="card-body" data-mh="card-body">
                <!-- Display the post categories as links using get_the_term_list() -->
                <?php
                $category_list = get_the_term_list(get_the_ID(), 'category', '', ', ', '');
                if (!empty($category_list)) :
                ?>
                  <p class="card-category">
                    <?php echo $category_list; ?>
                  </p>
                <?php endif; ?>
                <!-- Display the post title -->
                <?php if (!empty(get_the_title())) : ?>
                  <h3 class="card-title">
                    <?php echo wp_kses(wp_trim_words(get_the_title(), 12, '...'), $allowed_tags); ?>
                  </h3>
                <?php endif; ?>
                <!-- Display the post content -->
                <?php if (!empty(get_the_content())) : ?>
                  <p class="card-text">
                    <?php echo esc_html(wp_trim_words(get_the_content(), 16, '..')); ?>
                  </p>
                <?php endif; ?>
              </div>
              <!-- Display the post link -->
              <?php if (!empty(get_permalink())) : ?>
                <div class="card-footer">
                  <a class="card-link" href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html($cta_text); ?></a>
                </div>
              <?php endif; ?>
            </div><!-- .card-article -->
          <?php
          endwhile;
          wp_reset_postdata();
          ?>
        </div>
        <?php
        if ($link):
          $button_url = $link['url'];
          $button_title = $link['title'];
          $button_target = $link['target'] ? $link['target'] : '_self';
        ?>
          <div class="button-container text-center">
            <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary" target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_title); ?></a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php
    break;
  case 'testimonials': ?>

    <section class="slider-section slider-section--<?php echo esc_attr($slider_class); ?><?php if ($custom_class): ?> <?php echo $custom_class; ?><?php endif; ?>"
      style="
        --padding-top: <?php echo esc_attr($padding_top); ?>px;
        --padding-bottom: <?php echo esc_attr($padding_bottom); ?>px;
        --padding-top-mobile: <?php echo esc_attr($padding_top_mobile); ?>px;
        --padding-bottom-mobile: <?php echo esc_attr($padding_bottom_mobile); ?>px;
        --background-color: <?php echo esc_attr($background_color); ?>;">
      <div class="container">
        <?php if (!empty($heading)) : ?>
          <h3 class="section-title text-center">
            <?php echo wp_kses($heading, $allowed_tags); ?>
          </h3>
        <?php endif; ?>

        <div class="testimonial-carousel">

          <div class="card card--testimonial" data-mh="card-testimonial">
            <div class="card-header" data-mh="card-header-testimonial">
              <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0NCIgaGVpZ2h0PSIzNCIgdmlld0JveD0iMCAwIDQ0IDM0IiBmaWxsPSJub25lIj4KPGcgb3BhY2l0eT0iMC40IiBjbGlwLXBhdGg9InVybCgjY2xpcDA4MTIwXzMzMDMpIj4KPHBhdGggZD0iTTAuODc1IDE5LjA5NTdDMC44NzUgNS45MDkxNCA3LjQ0NjgxIDAuNjI1OTE0IDE5LjM0NDggMC40NTQxMDJMMjAuNjMzNCA2LjY4MjI5QzEzLjc2MDkgNy40MTI0OSAxMC40MTA2IDEwLjk3NzYgMTAuODQwMSAxNi42MDQ0SDE3LjAyNTNWMzIuNDU0MUgwLjg3NVYxOS4wOTU3Wk0yMy40MjQxIDE5LjA5NTdDMjMuNDI0MSA1LjkwOTE0IDI5Ljk5NTkgMC42MjU5MTQgNDEuODkzOSAwLjQ1NDEwMkw0My4xODI1IDYuNjgyMjlDMzYuMzEgNy40MTI0OSAzMi45MTY3IDEwLjk3NzYgMzMuMzg5MiAxNi42MDQ0SDM5LjU3NDVWMzIuNDU0MUgyMy40MjQxVjE5LjA5NTdaIiBmaWxsPSIjNkI3RDg1Ii8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDA4MTIwXzMzMDMiPgo8cmVjdCB3aWR0aD0iNDQiIGhlaWdodD0iMzMiIGZpbGw9IndoaXRlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDAuMDQxMDE1NikiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4=" alt="SVG Image">
              <div class="card-logo">
                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/images/assets/g2-logo.png" alt="G2 Logo">
              </div>
            </div>
            <div class="card-body" data-mh="card-body-testimonial">
              <h4 class="card-title">An absolute game changer for our organization.</h4>
              <p class="card-text">ProcessUnity has been an absolute game-changer for our organization. Their best-in-class teams (Engineering, Product, Project) facilitated seamless customization and implementation of our VRM process. True partners!</p>
              <p class="card-text--source">G2 Review</p>
            </div>
          </div>

          <div class="card card--testimonial" data-mh="card-testimonial">
            <div class="card-header" data-mh="card-header-testimonial">
              <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0NCIgaGVpZ2h0PSIzNCIgdmlld0JveD0iMCAwIDQ0IDM0IiBmaWxsPSJub25lIj4KPGcgb3BhY2l0eT0iMC40IiBjbGlwLXBhdGg9InVybCgjY2xpcDA4MTIwXzMzMDMpIj4KPHBhdGggZD0iTTAuODc1IDE5LjA5NTdDMC44NzUgNS45MDkxNCA3LjQ0NjgxIDAuNjI1OTE0IDE5LjM0NDggMC40NTQxMDJMMjAuNjMzNCA2LjY4MjI5QzEzLjc2MDkgNy40MTI0OSAxMC40MTA2IDEwLjk3NzYgMTAuODQwMSAxNi42MDQ0SDE3LjAyNTNWMzIuNDU0MUgwLjg3NVYxOS4wOTU3Wk0yMy40MjQxIDE5LjA5NTdDMjMuNDI0MSA1LjkwOTE0IDI5Ljk5NTkgMC42MjU5MTQgNDEuODkzOSAwLjQ1NDEwMkw0My4xODI1IDYuNjgyMjlDMzYuMzEgNy40MTI0OSAzMi45MTY3IDEwLjk3NzYgMzMuMzg5MiAxNi42MDQ0SDM5LjU3NDVWMzIuNDU0MUgyMy40MjQxVjE5LjA5NTdaIiBmaWxsPSIjNkI3RDg1Ii8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDA4MTIwXzMzMDMiPgo8cmVjdCB3aWR0aD0iNDQiIGhlaWdodD0iMzMiIGZpbGw9IndoaXRlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDAuMDQxMDE1NikiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4=" alt="SVG Image">
              <div class="card-logo card-logo--gartner">
                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/images/assets/gartner-logo.png" alt="Gartner Logo">
              </div>
            </div>
            <div class="card-body" data-mh="card-body-testimonial">
              <h4 class="card-title">Highly flexible to meet company’s risk management needs.</h4>
              <p class="card-text">ProcessUnity is highly configurable and flexible enough to meet our company's third-party risk management needs. The team is responsive and has built a strong user community to share ideas and improvements.</p>
              <p class="card-text--source">Procurement, Gartner Review</p>
            </div>
          </div>

          <div class="card card--testimonial" data-mh="card-testimonial">
            <div class="card-header" data-mh="card-header-testimonial">
              <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0NCIgaGVpZ2h0PSIzNCIgdmlld0JveD0iMCAwIDQ0IDM0IiBmaWxsPSJub25lIj4KPGcgb3BhY2l0eT0iMC40IiBjbGlwLXBhdGg9InVybCgjY2xpcDA4MTIwXzMzMDMpIj4KPHBhdGggZD0iTTAuODc1IDE5LjA5NTdDMC44NzUgNS45MDkxNCA3LjQ0NjgxIDAuNjI1OTE0IDE5LjM0NDggMC40NTQxMDJMMjAuNjMzNCA2LjY4MjI5QzEzLjc2MDkgNy40MTI0OSAxMC40MTA2IDEwLjk3NzYgMTAuODQwMSAxNi42MDQ0SDE3LjAyNTNWMzIuNDU0MUgwLjg3NVYxOS4wOTU3Wk0yMy40MjQxIDE5LjA5NTdDMjMuNDI0MSA1LjkwOTE0IDI5Ljk5NTkgMC42MjU5MTQgNDEuODkzOSAwLjQ1NDEwMkw0My4xODI1IDYuNjgyMjlDMzYuMzEgNy40MTI0OSAzMi45MTY3IDEwLjk3NzYgMzMuMzg5MiAxNi42MDQ0SDM5LjU3NDVWMzIuNDU0MUgyMy40MjQxVjE5LjA5NTdaIiBmaWxsPSIjNkI3RDg1Ii8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDA4MTIwXzMzMDMiPgo8cmVjdCB3aWR0aD0iNDQiIGhlaWdodD0iMzMiIGZpbGw9IndoaXRlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDAuMDQxMDE1NikiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4=" alt="SVG Image">
              <div class="card-logo">
                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/images/assets/g2-logo.png" alt="G2 Logo">
              </div>
            </div>
            <div class="card-body" data-mh="card-body-testimonial">
              <h4 class="card-title">Recommendation for ProcessUnity.</h4>
              <p class="card-text">ProcessUnity is a highly configurable system that has allowed us to mold the systems to our needs. It has enhanced our ability to understand risk and to focus errors towards the highest risks. The manual TPRM workload has been automated and reporting has enabled us to quickly and easily summarize data and provide clear communications to senior management and the board.</p>
              <p class="card-text--source">G2 Review</p>
            </div>
          </div>

          <div class="card card--testimonial" data-mh="card-testimonial">
            <div class="card-header" data-mh="card-header-testimonial">
              <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0NCIgaGVpZ2h0PSIzNCIgdmlld0JveD0iMCAwIDQ0IDM0IiBmaWxsPSJub25lIj4KPGcgb3BhY2l0eT0iMC40IiBjbGlwLXBhdGg9InVybCgjY2xpcDA4MTIwXzMzMDMpIj4KPHBhdGggZD0iTTAuODc1IDE5LjA5NTdDMC44NzUgNS45MDkxNCA3LjQ0NjgxIDAuNjI1OTE0IDE5LjM0NDggMC40NTQxMDJMMjAuNjMzNCA2LjY4MjI5QzEzLjc2MDkgNy40MTI0OSAxMC40MTA2IDEwLjk3NzYgMTAuODQwMSAxNi42MDQ0SDE3LjAyNTNWMzIuNDU0MUgwLjg3NVYxOS4wOTU3Wk0yMy40MjQxIDE5LjA5NTdDMjMuNDI0MSA1LjkwOTE0IDI5Ljk5NTkgMC42MjU5MTQgNDEuODkzOSAwLjQ1NDEwMkw0My4xODI1IDYuNjgyMjlDMzYuMzEgNy40MTI0OSAzMi45MTY3IDEwLjk3NzYgMzMuMzg5MiAxNi42MDQ0SDM5LjU3NDVWMzIuNDU0MUgyMy40MjQxVjE5LjA5NTdaIiBmaWxsPSIjNkI3RDg1Ii8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDA4MTIwXzMzMDMiPgo8cmVjdCB3aWR0aD0iNDQiIGhlaWdodD0iMzMiIGZpbGw9IndoaXRlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDAuMDQxMDE1NikiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4=" alt="SVG Image">
              <div class="card-logo card-logo--gartner">
                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/images/assets/gartner-logo.png" alt="Gartner Logo">
              </div>
            </div>
            <div class="card-body" data-mh="card-body-testimonial">
              <h4 class="card-title">ProcessUnity's Configurability Meets Company's Risk Management Demand.</h4>
              <p class="card-text">ProcessUnity is highly configurable and flexible enough to meet our company's third-party risk management needs. The team is responsive and has built a strong user community to share ideas and improvements</p>
              <p class="card-text--source">Procurement, Gartner Review</p>
            </div>
          </div>

          <div class="card card--testimonial" data-mh="card-testimonial">
            <div class="card-header" data-mh="card-header-testimonial">
              <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0NCIgaGVpZ2h0PSIzNCIgdmlld0JveD0iMCAwIDQ0IDM0IiBmaWxsPSJub25lIj4KPGcgb3BhY2l0eT0iMC40IiBjbGlwLXBhdGg9InVybCgjY2xpcDA4MTIwXzMzMDMpIj4KPHBhdGggZD0iTTAuODc1IDE5LjA5NTdDMC44NzUgNS45MDkxNCA3LjQ0NjgxIDAuNjI1OTE0IDE5LjM0NDggMC40NTQxMDJMMjAuNjMzNCA2LjY4MjI5QzEzLjc2MDkgNy40MTI0OSAxMC40MTA2IDEwLjk3NzYgMTAuODQwMSAxNi42MDQ0SDE3LjAyNTNWMzIuNDU0MUgwLjg3NVYxOS4wOTU3Wk0yMy40MjQxIDE5LjA5NTdDMjMuNDI0MSA1LjkwOTE0IDI5Ljk5NTkgMC42MjU5MTQgNDEuODkzOSAwLjQ1NDEwMkw0My4xODI1IDYuNjgyMjlDMzYuMzEgNy40MTI0OSAzMi45MTY3IDEwLjk3NzYgMzMuMzg5MiAxNi42MDQ0SDM5LjU3NDVWMzIuNDU0MUgyMy40MjQxVjE5LjA5NTdaIiBmaWxsPSIjNkI3RDg1Ii8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDA4MTIwXzMzMDMiPgo8cmVjdCB3aWR0aD0iNDQiIGhlaWdodD0iMzMiIGZpbGw9IndoaXRlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDAuMDQxMDE1NikiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4=" alt="SVG Image">
              <div class="card-logo">
                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/images/assets/g2-logo.png" alt="G2 Logo">
              </div>
            </div>
            <div class="card-body" data-mh="card-body-testimonial">
              <h4 class="card-title">A life-changing solution to vendor risk and compliance challenges.</h4>
              <p class="card-text">It is most helpful that ProcessUnity is highly configurable; therefore, you can utilize the out of the box option and build out the platform to fit your company's needs.</p>
              <p class="card-text--source">G2 Review</p>
            </div>
          </div>

          <div class="card card--testimonial" data-mh="card-testimonial">
            <div class="card-header" data-mh="card-header-testimonial">
              <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0NCIgaGVpZ2h0PSIzNCIgdmlld0JveD0iMCAwIDQ0IDM0IiBmaWxsPSJub25lIj4KPGcgb3BhY2l0eT0iMC40IiBjbGlwLXBhdGg9InVybCgjY2xpcDA4MTIwXzMzMDMpIj4KPHBhdGggZD0iTTAuODc1IDE5LjA5NTdDMC44NzUgNS45MDkxNCA3LjQ0NjgxIDAuNjI1OTE0IDE5LjM0NDggMC40NTQxMDJMMjAuNjMzNCA2LjY4MjI5QzEzLjc2MDkgNy40MTI0OSAxMC40MTA2IDEwLjk3NzYgMTAuODQwMSAxNi42MDQ0SDE3LjAyNTNWMzIuNDU0MUgwLjg3NVYxOS4wOTU3Wk0yMy40MjQxIDE5LjA5NTdDMjMuNDI0MSA1LjkwOTE0IDI5Ljk5NTkgMC42MjU5MTQgNDEuODkzOSAwLjQ1NDEwMkw0My4xODI1IDYuNjgyMjlDMzYuMzEgNy40MTI0OSAzMi45MTY3IDEwLjk3NzYgMzMuMzg5MiAxNi42MDQ0SDM5LjU3NDVWMzIuNDU0MUgyMy40MjQxVjE5LjA5NTdaIiBmaWxsPSIjNkI3RDg1Ii8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDA4MTIwXzMzMDMiPgo8cmVjdCB3aWR0aD0iNDQiIGhlaWdodD0iMzMiIGZpbGw9IndoaXRlIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDAuMDQxMDE1NikiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4=" alt="SVG Image">
              <div class="card-logo card-logo--gartner">
                <img class="img-fluid" src="<?php echo get_template_directory_uri(); ?>/images/assets/gartner-logo.png" alt="Gartner Logo">
              </div>
            </div>
            <div class="card-body" data-mh="card-body-testimonial">
              <h4 class="card-title">Been using ProcessUnity's Vendor Risk Management product for years and continuing!</h4>
              <p class="card-text">This SaaS solution is easy to implement and maintain. We started out with a manual process, which took many people resources and hours, so we decided to look for automation, which provided us time to focus on other priorities, and no more manual follow-ups!</p>
              <p class="card-text--source">IT Security and Risk Management, Gartner Review</p>
            </div>
          </div>

        </div>

        <?php
        if ($link):
          $button_url = $link['url'];
          $button_title = $link['title'];
          $button_target = $link['target'] ? $link['target'] : '_self';
        ?>
          <div class="button-container text-center">
            <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary" target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_title); ?></a>
          </div>
        <?php endif; ?>
      </div>
    </section>

  <?php
    break;
  case 'announcement': ?>

    <section class="slider-section slider-section--<?php echo esc_attr($slider_class); ?><?php if ($custom_class): ?> <?php echo $custom_class; ?><?php endif; ?>"
      style="
        --padding-top: <?php echo esc_attr($padding_top); ?>px;
        --padding-bottom: <?php echo esc_attr($padding_bottom); ?>px;
        --padding-top-mobile: <?php echo esc_attr($padding_top_mobile); ?>px;
        --padding-bottom-mobile: <?php echo esc_attr($padding_bottom_mobile); ?>px;
        --background-color: transparent;">

      <div class="container">
        <div class="announcement-carousel">
          <?php
          foreach ($slider_items as $card) :
            // Sub-fields
            $card_image       = $card['item_image'];
            $card_title       = $card['item_title'];
            $card_description = $card['item_description'];
            $card_link        = $card['item_link'];
          ?>
            <div class="card card--announcement">
              <?php if ($card_image): ?>
                <div class="card-header">
                  <img class="img-fluid" src="<?php echo esc_url($card_image['url']); ?>" alt="<?php echo esc_attr($card_image['alt']); ?>" />
                </div>
              <?php endif; ?>
              <div class="card-body">
                <?php if ($card_title): ?>
                  <h4 class="card-title"><?php echo wp_kses_post($card_title); ?></h4>
                <?php endif; ?>
                <?php if ($card_description): ?>
                  <div class="card-text"><?php echo wp_kses_post($card_description); ?></div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <?php
                if ($card_link):
                  $card_link_url = $card_link['url'];
                  $card_link_title = $card_link['title'];
                  $card_link_target = $card_link['target'] ? $card_link['target'] : '_self';
                ?>
                  <a href="<?php echo esc_url($card_link_url); ?>" class="btn btn-primary" target="<?php echo esc_attr($card_link_target); ?>">
                    <?php echo esc_html($card_link_title); ?>
                  </a>
                <?php endif; ?>
              </div>
            </div><!-- .card-announcement -->
          <?php endforeach; ?>
        </div><!-- .announcement-carousel -->
      </div><!-- .container -->
    </section>

<?php break;
endswitch; ?>

<?php if ($slider_type === 'resources'): ?>
  <script>
    (function($) {
      $('.resources-carousel').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 2,
        dots: <?php echo $hide_dots ? 'false' : 'true'; ?>,
        arrows: <?php echo $hide_arrows ? 'false' : 'true'; ?>,
        responsive: [{
            breakpoint: 991.98,
            settings: {
              dots: true,
              arrows: true,
              slidesToShow: 2,
              slidesToScroll: 2,
            }
          },
          {
            breakpoint: 575.98,
            settings: {
              dots: true,
              arrows: true,
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    })(jQuery);
  </script>
<?php elseif ($slider_type === 'testimonials'): ?>
  <script>
    (function($) {
      $('.testimonial-carousel').slick({
        infinite: true,
        slidesToShow: 2,
        slidesToScroll: 2,
        dots: true,
        arrows: true,
        responsive: [{
            breakpoint: 991.98,
            settings: {
              dots: true,
              arrows: true,
              slidesToShow: 2,
              slidesToScroll: 1,
            }
          },
          {
            breakpoint: 575.98,
            settings: {
              dots: true,
              arrows: true,
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    })(jQuery);
  </script>
<?php elseif ($slider_type === 'announcement'): ?>
  <script>
    (function($) {
      $('.announcement-carousel').slick({
        autoplay: true,
        autoplaySpeed: 4000,
        speed: 1000,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        arrows: false,
        fade: true,
        cssEase: 'linear'
      });
    })(jQuery);
  </script>
<?php endif; ?>