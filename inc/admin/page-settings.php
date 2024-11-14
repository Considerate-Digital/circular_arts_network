<?php 
/**
** Settings Page Template
*/

/*
**========== Direct Access Restriction =========== 
*/
if( ! defined('ABSPATH' ) ){ exit; }

?>
<div class="wrap can-bs-wrapper">
	<h2 class="border-bottom mb-3">
		<?php _e( 'Circular Arts Network - Settings', 'circular-arts-network' ); ?> <span class="badge badge-sm bg-info">v<?php echo CAN_VERSION; ?></span>
	</h2>
	<div class="row">
		<div class="col-sm-3">
			<div class="list-group wcp-tabs-menu">
				<?php $all_fields_settings = $this->admin_settings_fields();
					foreach ($all_fields_settings as $panel) { ?>
						<a href="#<?php echo esc_attr( $panel['panel_name'] ); ?>" role="button" class="list-group-item">
							<?php echo (isset($panel['icon'])) ? $panel['icon'] : '' ; ?>
							<?php echo esc_attr( $panel['panel_title'] ); ?>
						</a>
				<?php } ?>
			</div>			
		</div>
		<div class="col-sm-9">
			<form id="can-settings-form" class="form-horizontal">
				<input type="hidden" name="action" value="wcp_rem_save_settings">
				<?php $all_fields_settings = $this->admin_settings_fields();
					foreach ($all_fields_settings as $panel) { ?>
						<div class="card panel-settings" id="<?php echo esc_attr( $panel['panel_name'] ); ?>">
							<div class="card-header">
								<b><?php echo (isset($panel['icon'])) ? $panel['icon'] : '' ; ?> <?php echo esc_attr( $panel['panel_title'] ); ?></b>
							</div>
							<div class="card-body">
								<?php foreach ($panel['fields'] as $field) {
									echo $this->render_setting_field($field);
								} ?>
							</div>
						</div>
				<?php } ?>
				<div class="text-right mt-2">
					<span class="wcp-progress" style="display:none;"><?php _e( 'Please Wait...', 'circular-arts-network' ); ?></span>					
					<input class="btn btn-success float-end" type="submit" value="<?php _e( 'Save Settings', 'circular-arts-network' ); ?>">
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>
