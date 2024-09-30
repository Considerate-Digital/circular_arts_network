<div class="wrap wcp-main-wrap can-bs-wrapper">
    <h2 class="border-bottom mb-3"><?php _e( 'CAN - Fields Builder', 'circular-arts-network' ); ?></h2>

    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <span class="glyphicon glyphicon-info-sign"></span>
                <?php _e( 'Drag and Drop the fields from Field Types into the Active Fields area.', 'circular-arts-network' ); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="card">
                <h5 class="card-header">
                    <?php _e( 'Field Types', 'circular-arts-network' ); ?>
                </h5>
                <div class="card-body">
                    <div class="hard-coded-list">
                        <?php foreach ($field_types as $field_name => $field_label) { ?>
                        <div class="card" data-type="<?php echo esc_attr( $field_name ); ?>">
                            <div class="card-header">
                                <?php $this->render_fields_builder_field_heading('', $field_label); ?>
                            </div>
                            <div class="card-body inside-contents">
                                <?php
                                    foreach ($fields_data as $field) {
                                        $this->render_fields_builder_field($field, array('type' => $field_name));
                                    }
                                    do_action( 'can_after_drag_drop_property_field', array('type' => $field_name) );
                                ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>  
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="card">
                <h5 class="card-header">
                    <?php _e( 'Active Fields', 'circular-arts-network' ); ?>
                    <button class="btn btn-success btn-sm float-end can-save-settings"><?php _e( 'Save Settings', 'circular-arts-network' ); ?></button>
                    <button class="btn btn-danger btn-sm me-2 float-end can-reset-settings"><?php _e( 'Reset Fields', 'circular-arts-network' ); ?></button>
                </h5>
                <div class="card-body">
                    <div class="form-meta-setting form-horizontal">
                        <?php
                            if(isset($saved_fields) && is_array($saved_fields)) {
                                foreach ($saved_fields as $data) {
                                    $field_label = $field_types[$data['type']];?>
                                    <div class="card" data-type="<?php echo esc_attr( $data['type'] ); ?>">
                                        <div class="card-header">
                                            <?php $this->render_fields_builder_field_heading($data['title'], $field_label); ?>
                                        </div>
                                        <div class="card-body inside-contents">
                                            <?php
                                                foreach ($fields_data as $field) {
                                                    $this->render_fields_builder_field($field, $data);
                                                }
                                                do_action( 'can_after_drag_drop_property_field', $data );
                                            ?>
                                        </div>
                                    </div>
                                <?php }
                            } else {
                                include CAN_PATH.'/inc/arrays/listing-fields.php';
                                $fields = $inputFields;
                                foreach ($fields as $data) {
                                    $field_label = $field_types[$data['type']]; ?>
                                    <div class="card" data-type="<?php echo esc_attr( $data['type'] ); ?>">
                                        <div class="card-header title">
                                            <?php $this->render_fields_builder_field_heading($data['title'], $field_label); ?>
                                        </div>
                                        <div class="card-body inside-contents">
                                            <?php
                                                foreach ($fields_data as $field) {
                                                    $this->render_fields_builder_field($field, $data);
                                                }
                                                do_action( 'can_after_drag_drop_property_field', $data );
                                            ?>
                                        </div>
                                    </div>
                                <?php }
                            }
                        ?>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>