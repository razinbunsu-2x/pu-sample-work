<?php

namespace helpers;

class ACFHelper
{
  /**
   * Get ACF field value with fallback default
   *
   * @param array|string $field_source - ACF group array (for get_sub_field group) or field name
   * @param string $field_name - The ACF field key inside the group
   * @param mixed $default - Default value if field is empty
   * @param bool $is_sub_field - Whether to use get_sub_field() (true) or get_field() (false)
   * @return int|string - Sanitized value or default
   */
  public static function get_padding_value($field_source, $field_name, $default, $is_sub_field = false)
  {
    // If $field_source is an array (group field), extract the child field
    if (is_array($field_source) && isset($field_source[$field_name])) {
      $value = $field_source[$field_name];
    } else {
      // Otherwise, get field normally
      $value = $is_sub_field ? get_sub_field($field_name) : get_field($field_name);
    }

    // Return sanitized value or default
    return (isset($value) && is_numeric($value) && $value !== '') ? intval($value) : $default;
  }
}
