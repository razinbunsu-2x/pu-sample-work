<?php

function render_all_flexible_content($post_id = null)
{
  // If no post_id is specified, use the current post's ID
  if (!$post_id) {
    $post_id = get_the_ID();
  }

  // Get all field objects for the given post
  $fields = get_field_objects($post_id);

  if ($fields) {
    foreach ($fields as $field_name => $field) {
      // Check if this field is a flexible content field
      if (isset($field['type']) && $field['type'] === 'flexible_content') {

        // Check if there are rows for this flexible content field
        if (have_rows($field_name, $post_id)) {
          while (have_rows($field_name, $post_id)) {
            the_row();

            // Get the layout name (e.g., "banner_section", "text_block", etc.)
            $layout = get_row_layout();

            // Load the template corresponding to the layout name.
            // For example, if $layout = 'banner_section', it looks for:
            // template-parts/content-modules/banner_section.php
            get_template_part('template-parts/content-modules/' . $layout);
          }
        }
      }
    }
  }
}
