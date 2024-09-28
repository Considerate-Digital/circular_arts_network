<div class="wrap can-bs-wrapper">
	<h2 class="border-bottom mb-3"><?php _e( 'CAN - Field Sections', 'circular-arts-network' ); ?></h2>

    	<div class="row">
    	<div class="col-sm-3">
            <div class="card">
                <h5 class="card-header">
	                <?php _e( 'Create Section', 'circular-arts-network' ); ?>
                </h5>
                <div class="card-body">
                	<p class="text-center"><?php esc_attr_e( 'Here you can create, delete or sort the sections for the listing fields.', 'circular-arts-network' ); ?></p>
                	<p class="text-center">
                		<button class="button btn-info can-create-field-section"><?php _e( 'Create New', 'circular-arts-network' ); ?></button>
                	</p>
                </div>
            </div>
    	</div>
        <div class="col-sm-9">
            <div class="card">
                <h5 class="card-header">
	                <?php _e( 'Field Sections', 'circular-arts-network' ); ?>
					<button class="btn btn-success btn-sm float-end can-save-field-section"><?php _e( 'Save Sections', 'circular-arts-network' ); ?></button>
					<button class="btn btn-danger btn-sm float-end can-reset-field-section me-2"><?php _e( 'Reset Sections', 'circular-arts-network' ); ?></button>
                </h5>
                <div class="card-body" id="field-sections-wrap">
		                <?php foreach ($field_sections as $index => $tab) { ?>
						<div class="card">
						    <div class="card-header">
						        <b><?php echo esc_attr( $tab['title'] ); ?>  - </b>  <span class="key badge bg-info"> <?php echo esc_attr( $tab['key'] ); ?> </span>
						        <span class="float-end btn btn-sm btn-outline-primary trigger-sort">
						            <i class="bi bi-arrows-move"></i>
						        </span>
						        <a href="#" class="btn btn-sm btn-outline-primary float-end trigger-toggle">
						            <i class="bi bi-arrows-expand"></i>
						        </a>
						        <a href="#" class="float-end btn btn-sm btn-outline-danger remove-field">
						            <i class="bi bi-trash3"></i>
						        </a>
						    </div>
						    <div class="card-body inside-contents">
								<div class="row mb-3">
									<label class="col-sm-4 col-form-label">
										<?php _e( 'Section Title', 'circular-arts-network' ); ?>
									</label>
									<div class="col-sm-8">
										<input type="text" class="form-control form-control-sm section_title" value="<?php echo esc_attr( $tab['title'] ); ?>">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-4 col-form-label">
										<?php _e( 'Data Name (lowercase without spaces)', 'circular-arts-network' ); ?>
									</label>
									<div class="col-sm-8">
										<input <?php echo (can_is_default_section($tab)) ? 'disabled' : ''; ?> type="text" class="form-control form-control-sm section_key" value="<?php echo esc_attr( $tab['key'] ); ?>">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-4 col-form-label">
										<?php _e( 'Icon Class or Image URL', 'circular-arts-network' ); ?>
									</label>
									<div class="col-sm-8">
										<input type="text" class="form-control form-control-sm section_icon" value="<?php echo esc_attr( $tab['icon'] ); ?>">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-sm-4 col-form-label">
										<?php _e( 'Accessibility', 'circular-arts-network' ); ?>
									</label>
									<div class="col-sm-8">
								        <select class="form-select form-select-sm section_accessibility">
								        	<?php foreach ($accessibilities as $key => $value) { ?>
												<option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $tab['accessibility'], $key, 'selected' ); ?>><?php echo esc_attr( $value ); ?></option>
								        	<?php } ?>
										</select>
									</div>
								</div>
						    </div>
						</div>
		                <?php } ?>
                </div>
            </div>			
        </div>
    </div>
</div>