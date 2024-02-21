 <tr class="form-field term-group-wrap">
   <th scope="row">
     <label for="category-image-id"><?php _e( 'Icon or Image', 'ultimate-classified-listings' ); ?></label>
   </th>
   <td>
    <?php
      $saved_image = get_term_meta ( $term->term_id, 'ucl_category_image', true );
      $saved_icon = get_term_meta ( $term->term_id, 'ucl_category_icon', true );
    ?>
     <div class="uclwp-bs-wrapper">
      <div class="card p-3 mb-3">
        <div class="row text-center">
          <div class="col-sm-6">
            <h4><?php _e('Icon', 'ultimate-classified-listings'); ?></h4>
              <select class="ucl-iconpicker" id="ucl-iconpicker" name="ucl_category_icon">
                <option value=""><?php _e( 'No icon', 'ultimate-classified-listings' ) ?></option>
                <?php
                  $icons = uclwp_get_icons_list();
                  foreach ($icons as $iconClass) {
                    $selected = ($saved_icon == $iconClass) ? 'selected' : '' ;
                    echo "<option {$selected}>{$iconClass}</option>";
                  }
                ?>
              </select>
          </div>
          <div class="col-sm-6">
            <h4><?php _e('Image', 'ultimate-classified-listings'); ?></h4>
              <input type="hidden" id="category-image-id" name="ucl_category_image" class="custom_media_url" value="">
              <div id="category-image-wrapper">
                <?php if ( $saved_image ) {
                  echo wp_get_attachment_image ( $saved_image, 'thumbnail' );
                  echo '<i class="bi bi-x-circle"></i>';
                } ?>
              </div>
              <div class="ucl-image-upload">
                <i class="bi bi-upload"></i>
              </div>
          </div>
        </div>
      </div>
     </div>
   </td>
 </tr>