
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

 <tr class="form-field term-group-wrap">
   <th scope="row">
     <label for="category-image-id"><?php esc_html_e( 'Icon or Image', 'circular-arts-network' ); ?></label>
   </th>
   <td>
    <?php
      $saved_image = get_term_meta ( $term->term_id, 'circartsnet_category_image', true );
      $saved_icon = get_term_meta ( $term->term_id, 'circartsnet_category_icon', true );
    ?>
     <div class="can-bs-wrapper">
      <div class="card p-3 mb-3">
        <div class="row text-center">
          <div class="col-sm-6">
            <h4><?php esc_html_e('Icon', 'circular-arts-network'); ?></h4>
              <select class="can-iconpicker" id="can-iconpicker" name="circartsnet_category_icon">
                <option value=""><?php esc_html_e( 'No icon', 'circular-arts-network' ) ?></option>
                <?php
                  $icons = circartsnet_get_icons_list();
                  foreach ($icons as $iconClass) {
                    $selected = ($saved_icon == $iconClass) ? 'selected' : '' ;
                    echo esc_html("<option {$selected}>{$iconClass}</option>");
                  }
                ?>
              </select>
          </div>
          <div class="col-sm-6">
            <h4><?php esc_html_e('Image', 'circular-arts-network'); ?></h4>
              <input type="hidden" id="category-image-id" name="circartsnet_category_image" class="custom_media_url" value="">
              <div id="category-image-wrapper">
                <?php if ( $saved_image ) {
                  echo wp_get_attachment_image ( $saved_image, 'thumbnail' );
                  echo '<i class="bi bi-x-circle"></i>';
                } ?>
              </div>
              <div class="can-image-upload">
                <i class="bi bi-upload"></i>
              </div>
          </div>
        </div>
      </div>
     </div>
   </td>
 </tr>
