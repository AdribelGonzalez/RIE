<?php
/*
 * Function to register a new field for calculations
 *
 * @since 2.2.28
 * @returns void
 */

function ninja_forms_register_field_calc(){
	$args = array(
		'name' => 'Calculation',
		'sidebar' => 'template_fields',
		'edit_function' => 'ninja_forms_field_calc_edit',
		'display_function' => 'ninja_forms_field_calc_display',
		'group' => 'standard_fields',
		'edit_conditional' => true,
		'edit_req' => false,
		'edit_label' => false,
		'edit_label_pos' => false,
		'edit_custom_class' => false,
		'edit_help' => false,
		//'process_field' => false,
		//'pre_process' => 'ninja_forms_field_calc_pre_process',
		'edit_options' => array(
			array(
				'type' => 'hidden',
				'name' => 'payment_field_group',
				'default' => 1,
			),
			array(
				'type' => 'hidden',
				'name' => 'payment_total',
				//'label' => 'Total',
			),
			array(
				'type' => 'hidden',
				'name' => 'payment_sub_total',
				//'label' => 'Sub Total',
			),
			array(
				'type' => 'text',
				'name' => 'calc_places',
				'label' => __( 'Number of decimal places.', 'ninja-forms' ),
				'default' => 2,
			),
		),
	);

	ninja_forms_register_field( '_calc', $args );
}

add_action( 'init', 'ninja_forms_register_field_calc' );

/*
 *
 * Function that filters the field LI label on the edit field back-end.
 *
 * @since 2.2.28
 * @returns $li_label
 */

function ninja_forms_calc_edit_label_filter( $li_label, $field_id ) {
	$field_row = ninja_forms_get_field_by_id( $field_id );
	if ( $field_row['type'] == '_calc' ) {
		if ( isset ( $field_row['data']['calc_name'] ) ) {
			$li_label = $field_row['data']['calc_name'];
		} else {
			$li_label = __( 'calc_name', 'ninja-forms' );
		}

	}
	return $li_label;
}

add_filter( 'ninja_forms_edit_field_li_label', 'ninja_forms_calc_edit_label_filter', 10, 2 );


/*
 * Function that outputs the edit options for our calculation field
 *
 * @since 2.2.28
 * @returns void
 */

function ninja_forms_field_calc_edit( $field_id, $data ){

	if ( isset ( $data['calc_name'] ) ) {
		$calc_name = $data['calc_name'];
	} else {
		$calc_name = 'calc_name';
	}

	if ( isset ( $data['default_value'] ) ) {
		$default_value = $data['default_value'];
	} else {
		$default_value = '';
	}

	if ( isset ( $data['calc_payment'] ) ) {
		$calc_payment = $data['calc_payment'];
	} else {
		$calc_payment = '';
	}

	if ( isset ( $data['calc_auto'] ) ) {
		$calc_auto = $data['calc_auto'];
	} else {
		$calc_auto = 0;
	}

	ninja_forms_edit_field_el_output($field_id, 'text', __( 'Calculation name', 'ninja-forms' ), 'calc_name', $calc_name, 'wide', '', 'widefat ninja-forms-calc-name', __( 'This is the programmatic name of your field. Examples are: my_calc, price_total, user-total.', 'ninja-forms' ));
	ninja_forms_edit_field_el_output($field_id, 'text', __( 'Default Value', 'ninja-forms' ), 'default_value', $default_value, 'wide', '', 'widefat' );
	
	echo '<hr>';
	echo '<h5>'.__( 'Display Options', 'ninja-forms' ).'</h5>';
	// Output calculation display type
	$options = array(
		array( 'name' => __( '- None', 'ninja-forms' ), 'value' => 'hidden' ),
		array( 'name' => __( 'Textbox', 'ninja-forms' ), 'value' => 'text'),
		array( 'name' => __( 'HTML', 'ninja-forms' ), 'value' => 'html'),
	);
	if ( isset ( $data['calc_display_type'] ) ) {
		$calc_display_type = $data['calc_display_type'];
	} else {
		$calc_display_type = 'text';
	}
	
	ninja_forms_edit_field_el_output($field_id, 'select', __( 'Output calculation as', 'ninja-forms' ), 'calc_display_type', $calc_display_type, 'wide', $options, 'widefat ninja-forms-calc-display');
	
	// If the calc_display_type is set to text, then we have several options to output.
	// Set the output to hidden for these options if the calc_display_type is not set to text.
	if ( $calc_display_type != 'text' ) {
		$class = 'hidden';
	} else {
		$class = '';
	}
	echo '<div id="ninja_forms_field_'.$field_id.'_clac_text_display" class="'.$class.'">';
	// Output a label input textbox.
	if ( isset ( $data['label'] ) ) {
		$label = stripslashes( $data['label'] );
	} else {
		$label = '';
	}
	ninja_forms_edit_field_el_output($field_id, 'text', __( 'Label', 'ninja-forms' ), 'label', $label, 'wide', '', 'widefat');

	// Output a label position select box.
	if ( isset ( $data['label_pos'] ) ) {
		$label_pos = $data['label_pos'];
	} else {
		$label_pos = '';
	}
	$options = array(
		array('name' => __( 'Left of Element', 'ninja-forms' ), 'value' => 'left'),
		array('name' => __( 'Above Element', 'ninja-forms' ), 'value' => 'above'),
		array('name' => __( 'Below Element', 'ninja-forms' ), 'value' => 'below'),
		array('name' => __( 'Right of Element', 'ninja-forms' ), 'value' => 'right'),
	);
	ninja_forms_edit_field_el_output($field_id, 'select', __( 'Label Position', 'ninja-forms' ), 'label_pos', $label_pos, 'wide', $options, 'widefat');
	
	// Output a disabled option checkbox.
	if( isset ( $data['calc_display_text_disabled'] ) ) {
		$calc_display_text_disabled = $data['calc_display_text_disabled'];
	} else {
		$calc_display_text_disabled = 1;
	}
	ninja_forms_edit_field_el_output($field_id, 'checkbox', __( 'Disable input?', 'ninja-forms' ), 'calc_display_text_disabled', $calc_display_text_disabled, 'thin', '', '');
	echo '</div>';

	// Set the output to hidden for the HTML RTE if the calc_display_type is not set to HTML.
	if ( $calc_display_type != 'html' ) {
		$class = 'hidden';
	} else {
		$class = '';
	}
	// Output our RTE. This is the only extra setting needed if the calc_display_type is set to HTML.
	if ( isset ( $data['calc_display_html'] ) ) {
		$calc_display_html = $data['calc_display_html'];
	} else {
		$calc_display_html = '[ninja_forms_calc]';
	}
	echo '<div id="ninja_forms_field_'.$field_id.'_clac_html_display" class="'.$class.'">';
	ninja_forms_edit_field_el_output($field_id, 'rte', '', 'calc_display_html', $calc_display_html, '', '', '', __( 'Use the following shortcode to insert the final calculation: [ninja_forms_calc]', 'ninja-forms' ) );
	echo '</div>';

	// If any option besides "none" is selected, then show our custom class and help options.
	if ( $calc_display_type == 'hidden' ) {
		$class = 'hidden';
	} else {
		$class = '';
	}

	if ( isset ( $data['class'] ) ) {
		$custom_class = $data['class'];
	} else {
		$custom_class = '';
	}

	if ( isset ( $data['show_help'] ) ) {
		$show_help = $data['show_help'];
	} else {
		$show_help = 0;
	}

	if ( isset ( $data['help_text'] ) ) {
		$help_text = $data['help_text'];
	} else {
		$help_text = '';
	}

	if( $show_help == 1 ){
		$display_span = '';
	}else{
		$display_span = 'display:none;';
	}

	echo '<div id="ninja_forms_field_'.$field_id.'_clac_extra_display" class="'.$class.'">';
	// Output our custom class textbox.
	ninja_forms_edit_field_el_output($field_id, 'text', __( 'Custom CSS Class', 'ninja-forms' ), 'class', $custom_class, 'thin', '', '');

	// Output our help text options.
	$help_desc = sprintf(__('If "help text" is enabled, there will be a question mark %s placed next to the input field. Hovering over this question mark will show the help text.', 'ninja-forms'), '<img src="'.NINJA_FORMS_URL.'/images/question-ico.gif">');
	ninja_forms_edit_field_el_output($field_id, 'checkbox', __( 'Show Help Text', 'ninja-forms' ), 'show_help', $show_help, 'wide', '', 'ninja-forms-show-help');
	?>
	<span id="ninja_forms_field_<?php echo $field_id;?>_help_span" style="<?php echo $display_span;?>">
		<?php
		ninja_forms_edit_field_el_output($field_id, 'textarea', __( 'Help Text', 'ninja-forms' ), 'help_text', $help_text, 'wide', '', 'widefat', $help_desc);
		?>
	</span>
	<?php
	echo '</div>';
	echo '<div class="description description-wide"><hr></div>';
	//echo '<h5>'.__( 'Calculation Options', 'ninja-forms' ).'</h5>';

	if ( isset ( $data['calc_method'] ) ) {
		$calc_method = $data['calc_method'];
	} else {
		$calc_method = 'auto';
	}

	switch ( $calc_method ) {
		case 'auto':
			$eq_class = 'hidden';
			$field_class = 'hidden';
			break;
		case 'fields':
			$eq_class = 'hidden';
			$field_class = '';
			break;
		case 'eq':
			$eq_class = '';
			$field_class = 'hidden';
			break;
	}
	

	if ( isset ( $data['calc_eq'] ) ) {
		$calc_eq = $data['calc_eq'];
	} else {
		$calc_eq = '';
	}

	if ( isset ( $data['calc'] ) AND $data['calc'] != '' ) {
		$calc = $data['calc'];
	} else {
		$calc = array();
	}

	$desc = '<p>'.__( 'You can enter calculation equations here using field_x where x is the ID of the field you want to use. For example, <strong>field_53 + field_28 + field_65</strong>.', 'ninja-forms' ).'</p>';
	$desc .= '<p>'.__( 'Complex equations can be created by adding parentheses: <strong>( field_45 * field_2 ) / 2</strong>.', 'ninja-forms' ).'</p>';
	$desc .= '<p>'.__( 'Please use these operators: + - * /. This is an advanced feature. Watch out for things like division by 0.', 'ninja-forms' ).'</p>';
	$options = array(
		array( 'name' => __( 'Automatically Total Calculation Values', 'ninja-forms' ), 'value' => 'auto' ),
		array( 'name' => __( 'Specify Operations And Fields (Advanced)', 'ninja-forms' ), 'value' => 'fields' ),
		array( 'name' => __( 'Use An Equation (Advanced)', 'ninja-forms' ), 'value' => 'eq' ),
	);
	ninja_forms_edit_field_el_output($field_id, 'select', __( 'Calculation Method', 'ninja-forms' ), 'calc_method', $calc_method, 'wide', $options, 'widefat ninja-forms-calc-method');
	
	?>
	
	<div class="ninja-forms-calculations <?php echo $field_class;?>">
		<div class="label">
			<?php _e( 'Field Operations', 'ninja-forms' );?> - <a href="#" name="" id="ninja_forms_field_<?php echo $field_id;?>_add_calc" class="ninja-forms-field-add-calc" rel="<?php echo $field_id;?>"><?php _e( 'Add Operation', 'ninja-forms' );?></a>
			
			<span class="spinner" style="float:left;"></span>
		</div>

		<input type="hidden" name="ninja_forms_field_<?php echo $field_id;?>[calc]" value="">
		<div id="ninja_forms_field_<?php echo $field_id;?>_calc" class="" name="">
			<?php
			$x = 0;
			foreach ( $calc as $c ) {
				ninja_forms_output_field_calc_row( $field_id, $c, $x );
			 	$x++;
			}
			?>
		</div>
	</div>
	<div class="ninja-forms-eq <?php echo $eq_class;?>">
		<?php
		ninja_forms_edit_field_el_output($field_id, 'textarea', __( 'Advanced Equation', 'ninja-forms' ), 'calc_eq', $calc_eq, 'wide', '', 'widefat', $desc);
		?>
	</div>
	<hr>
	<?php
}

/*
 * Function that outputs the display for our calculation field
 *
 * @since 2.2.28
 * @returns void
 */

function ninja_forms_field_calc_display( $field_id, $data ){
	
	if ( isset( $data['default_value'] ) ) {
		$default_value = $data['default_value'];
	} else {
		$default_value = 0;
	}

	if ( $default_value == '' ) {
		$default_value = 0;
	}

	if ( isset ( $data['calc_display_text_disabled'] ) AND $data['calc_display_text_disabled'] == 1 ) {
		$disabled = "disabled";
	} else {
		$disabled = '';
	}

	if ( isset ( $data['calc_display_type'] ) ) {
		$calc_display_type = $data['calc_display_type'];
	} else {
		$calc_display_type = 'text';
	}

	if ( isset ( $data['calc_display_html'] ) ) {
		$calc_display_html = $data['calc_display_html'];
	} else {
		$calc_display_html = '';
	}

	if ( isset ( $data['calc_method'] ) ) {
		$calc_method = $data['calc_method'];
	} else {
		$calc_method = '';
	}

	$field_class = ninja_forms_get_field_class( $field_id );

	?>
	<input type="hidden" name="ninja_forms_field_<?php echo $field_id;?>" value="<?php echo $default_value;?>" class="<?php echo $field_class;?>">
	<?php

	switch ( $calc_display_type ) {
		case 'text':
			?>
			<input type="text" id="ninja_forms_field_<?php echo $field_id;?>" name="ninja_forms_field_<?php echo $field_id;?>" value="<?php echo $default_value;?>" <?php echo $disabled;?> class="<?php echo $field_class;?>" rel="<?php echo $field_id;?>">
			<?php		
			break;
		case 'html':
			$calc_display_html = str_replace( '[ninja_forms_calc]', '<span id="ninja_forms_field_'.$field_id.'" class="'.$field_class.'" rel="'.$field_id.'">'.$default_value.'</span>', $calc_display_html );
			echo $calc_display_html;
			break;
	}
}

/*
 *
 * Function to output specific calculation options for a given field
 *
 * @param int $field_id - ID of the field being edited.
 * @param array $c - Array containing the data.
 * @param int $x - Index for this row of the calc array.
 * @since 2.2.28
 * @returns void
 */

function ninja_forms_output_field_calc_row( $field_id, $c = array(), $x = 0 ){
	global $ninja_forms_fields;
	$field_row = ninja_forms_get_field_by_id( $field_id );
	$field_type = $field_row['type'];
	$form_id = $field_row['form_id'];

	if ( isset ( $c['field'] ) ) {
		$calc_field = $c['field'];
	} else {
		$calc_field = '';
	}

	if ( isset ( $c['op'] ) ) {
		$op = $c['op'];
	} else {
		$op = '';
	}

	?>
	<div id="ninja_forms_field_<?php echo $field_id;?>_calc_row_<?php echo $x;?>" class="ninja-forms-calc-row" rel="<?php echo $x;?>">
		<a href="#" id="ninja_forms_field_<?php echo $field_id;?>_remove_calc" name="<?php echo $x;?>" rel="<?php echo $field_id;?>" class="ninja-forms-field-remove-calc">X</a>
		
		<select name="ninja_forms_field_<?php echo $field_id;?>[calc][<?php echo $x;?>][op]">
			<option value="add" <?php selected( $op, 'add' );?>>+</option>
			<option value="subtract" <?php selected( $op, 'subtract' );?>>-</option>
			<option value="multiply" <?php selected( $op, 'multiply' );?>>*</option>
			<option value="divide" <?php selected( $op, 'divide' );?>>/</option>
		</select>

		<select name="ninja_forms_field_<?php echo $field_id;?>[calc][<?php echo $x;?>][field]" class="ninja-forms-calc-select">
			<option value=""><?php _e( '- Select a Field', 'ninja-forms' );?></option>
			<?php
			// Loop through our fields and output all of our calculation fields.
			$fields = ninja_forms_get_fields_by_form_id( $form_id );
			foreach ( $fields as $field ) {
				if ( isset ( $field['data']['label'] ) ) {
					$label = $field['data']['label'];
				} else {
					$label = '';
				}
				if ( strlen ( $label ) > 15 ) {
					$label = substr ( $label, 0, 15 );
					$label .= '...';
				}
				$process_field = $ninja_forms_fields[$field['type']]['process_field'];
				if ( $field['id'] != $field_id AND $process_field ) {
					?>
					<option value="<?php echo $field['id'];?>" <?php selected( $calc_field, $field['id'] );?>><?php echo $field['id'];?> - <?php echo $label;?></option>
					<?php
				}
			}
			?>
		</select>
	</div>
	<?php
}

/*
 *
 * Function that runs during pre_processing and calcuates the value of this field.
 *
 * @since 2.2.30
 * @returns void
 */

function ninja_forms_field_calc_pre_process(){
	global $ninja_forms_processing;
	
	$form_id = $ninja_forms_processing->get_form_ID();
	$all_fields = $ninja_forms_processing->get_all_fields();
	if ( is_array ( $all_fields ) ) {
		foreach ( $all_fields as $f_id => $user_value ) {
			$field_id = $f_id;
			$field_row = ninja_forms_get_field_by_id( $field_id );
			$field_type = $field_row['type'];
			if ( $field_type == '_calc' ) {
				$field_data = $field_row['data'];
				if ( isset ( $field_data['default_value'] ) ){
					$default_value = $field_data['default_value'];
				} else {
					$default_value = 0;
				}

				$result = $default_value;

				// Figure out which method we are using to calculate this field.
				if ( isset ( $field_data['calc_method'] ) ) {
					$calc_method = $field_data['calc_method'];
				} else {
					$calc_method = 'auto';
				}

				// Get our advanced field op settings if they exist.
				if ( isset ( $field_data['calc'] ) ) {
					$calc_fields = $field_data['calc'];
				} else {
					$calc_fields = array();
				}

				// Get our calculation equation if it exists.
				if ( isset ( $field_data['calc_eq'] ) ) {
					$calc_eq = $field_data['calc_eq'];
				} else {
					$calc_eq = array();
				}

				$all_fields = $ninja_forms_processing->get_all_fields();

				// Figure out if there is a sub_total and a tax field. If there are, and this is a total field set to calc_method auto, we're using an equation, not auto.
				$tax = false;
				$sub_total = false;
				if ( is_array ( $all_fields ) ) {
					foreach ( $all_fields as $f_id => $user_value ) {
						$field = $ninja_forms_processing->get_field_settings( $f_id );
						$data = apply_filters( 'ninja_forms_field', $field['data'], $field['id'] );
						if ( $field['type'] == '_tax' ) {
							// There is a tax field; save its field_id.
							$tax = $field['id'];
						} else if ( isset ( $data['payment_sub_total'] ) AND $data['payment_sub_total'] == 1 ) {
							// There is a sub_total field; save its field_id.
							$sub_total = $field['id'];
						}
					}		
				}


				// If the tax and sub_total have been found, and this is a total field set to auto, change the calc_method and calc_eq.
				if ( $tax AND $sub_total AND isset ( $field_data['payment_total'] ) AND $field_data['payment_total'] == 1 AND $calc_method == 'auto' ) {
					$calc_method = 'eq';
					$calc_eq = 'field_'.$sub_total.' + ( field_'.$sub_total.' * field_'.$tax.' )';

					$field_settings = $ninja_forms_processing->get_field_settings( $field_id );
					$field_settings['data']['calc_method'] = $calc_method;
					$field_settings['data']['calc_eq'] = $calc_eq;
					$ninja_forms_processing->update_field_settings( $field_id, $field_settings );
				}

				// Loop through our fields and see which ones should be used for calculations.
				if ( is_array ( $all_fields ) ) {
					foreach ( $all_fields as $f_id => $user_value ) {
						$field = $ninja_forms_processing->get_field_settings( $f_id );
						$field_value = $ninja_forms_processing->get_field_value( $f_id );
						$data = $field['data'];
						if ( $f_id == $tax ) {
							$tax = ninja_forms_field_calc_value( $field['id'], $field_value, 'auto' );;
						}
						switch ( $calc_method ) {
							case 'auto': // We are automatically totalling the fields that have a calc_auto_include set to 1.
								if ( isset ( $field_data['calc_auto_include'] ) AND $field_data['calc_auto_include'] == 1 ) {
									if ( $field['type'] == '_calc' ) {
										$result = ninja_forms_calc_field_loop2( $field['id'], '', $result );
									} else {
										$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
										if ( $calc_value !== false ) {
											$result = ninja_forms_calc_evaluate( 'add', $result, $calc_value );						
										}								
									}
								}
								break;
							case 'fields': // We are performing a specific set of operations on a set of fields.
								if ( is_array ( $calc_fields ) ) {
									foreach ( $calc_fields as $c ) {
										if ( $c['field'] == $field['id'] ) {
											if ( $field['type'] == '_calc' ) {
												$result = ninja_forms_calc_field_loop2( $field['id'], '', $result );
											} else {
												$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
												if ( $calc_value !== false ) {
													$result = ninja_forms_calc_evaluate( $c['op'], $result, $calc_value );
												}
											}
										}
									}
								}
								break;
							case 'eq':
								if (preg_match("/\bfield_".$field['id']."\b/i", $calc_eq ) ) {
									if ( $field['type'] == '_calc' ) {
										$calc_value = ninja_forms_calc_field_loop2( $field['id'], $calc_eq );
									} else {
										$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
									}	
									if ( $calc_value !== false ) {
										$calc_eq = preg_replace('/\bfield_'.$field['id'].'\b/', $calc_value, $calc_eq );
									}
								}
								break;
						}					
						/*
						switch ( $calc_method ) {
							case 'auto': // We are automatically totalling the fields that have a calc_auto_include set to 1.
								if ( isset ( $data['calc_auto_include'] ) AND $data['calc_auto_include'] == 1 ) {
									$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
									if ( $calc_value !== false ) {
										$result = ninja_forms_calc_evaluate( 'add', $result, $calc_value );						
									}
								}
								break;
							case 'fields': // We are performing a specific set of operations on a set of fields.
								if ( is_array ( $calc_fields ) ) {
									foreach ( $calc_fields as $c ) {
										if ( $c['field'] == $field['id'] ) {
											$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
											if ( $calc_value !== false ) {
												$result = ninja_forms_calc_evaluate( $c['op'], $result, $calc_value );
											}
										}
									}
								}
								break;
							case 'eq':
								$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
								if ( $calc_value !== false ) {
									$calc_eq = preg_replace('/\bfield_'.$field['id'].'\b/', $calc_value, $calc_eq );
								}
								break;
						}
						*/
					}
				}
				

				if ( $calc_method == 'eq' ) {
					$eq = new eqEOS();
					$result = $eq->solveIF($calc_eq);
				}
				if ( isset ( $field_data['calc_places'] ) ) {
					$places = $field_data['calc_places'];
					$result = number_format( round( $result, $places ), $places );
				}
				//echo "RESULT: ".$result;
				//echo "<br>";
				$ninja_forms_processing->update_field_value( $field_id, $result );		
			}
		}
	}
}

add_action( 'ninja_forms_pre_process', 'ninja_forms_field_calc_pre_process', 999 );

function ninja_forms_calc_field_loop2( $field_id, $calc_eq = '', $result = '' ){
	global $ninja_forms_processing;

	$field_settings = $ninja_forms_processing->get_field_settings( $field_id );
	
	$calc_data = $field_settings['data'];

	// Figure out which method we are using to calculate this field.
	if ( isset ( $calc_data['calc_method'] ) ) {
		$calc_method = $calc_data['calc_method'];
	} else {
		$calc_method = 'auto';
	}

	// Get our advanced field op settings if they exist.
	if ( isset ( $calc_data['calc'] ) ) {
		$calc_fields = $calc_data['calc'];
	} else {
		$calc_fields = array();
	}

	// Get our calculation equation if it exists.
	if ( isset ( $calc_data['calc_eq'] ) ) {
		$calc_eq = $calc_data['calc_eq'];
	} else {
		$calc_eq = array();
	}

	$form_id = $ninja_forms_processing->get_form_ID();
	$all_fields = $ninja_forms_processing->get_all_fields();

	// Figure out if there is a sub_total and a tax field. If there are, and this is a total field set to calc_method auto, we're using an equation, not auto.
	$tax = false;
	$sub_total = false;
	foreach ( $all_fields as $f_id => $user_value ) {
		$field = $ninja_forms_processing->get_field_settings( $f_id );
		$field_value = $user_value;
		$data = $field['data'];
		if ( $field['type'] == '_tax' ) {
			// There is a tax field; save its field_id.
			$tax = $field['id'];
		} else if ( isset ( $data['payment_sub_total'] ) AND $data['payment_sub_total'] == 1 ) {
			// There is a sub_total field; save its field_id.
			$sub_total = $field['id'];
		}
	}

	// If the tax and sub_total have been found, and this is a total field set to auto, change the calc_method and calc_eq.
	if ( $tax AND $sub_total AND isset ( $calc_data['payment_total'] ) AND $calc_data['payment_total'] == 1 AND $calc_method == 'auto' ) {
		$calc_method = 'eq';
		$calc_eq = 'field_'.$sub_total.' + ( field_'.$sub_total.' * field_'.$tax.' )';
	}

	// Figure out how many calculation fields we have and run
	foreach ( $all_fields as $f_id => $user_value ) {
		$field = $ninja_forms_processing->get_field_settings( $f_id );
		$field_value = $ninja_forms_processing->get_field_value( $f_id );
		$field_data = $field['data'];
		if ( $f_id != $field_id ) {
			switch ( $calc_method ) {
				case 'auto': // We are automatically totalling the fields that have a calc_auto_include set to 1.
					if ( isset ( $field_data['calc_auto_include'] ) AND $field_data['calc_auto_include'] == 1 ) {
						if ( $field['type'] == '_calc' ) {
							$result = ninja_forms_calc_field_loop2( $field['id'], '', $result );
						} else {
							$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
							if ( $calc_value !== false ) {
								$result = ninja_forms_calc_evaluate( 'add', $result, $calc_value );						
							}								
						}
					}
					break;
				case 'fields': // We are performing a specific set of operations on a set of fields.
					if ( is_array ( $calc_fields ) ) {
						foreach ( $calc_fields as $c ) {
							if ( $c['field'] == $field['id'] ) {
								if ( $field['type'] == '_calc' ) {
									$result = ninja_forms_calc_field_loop2( $field['id'], '', $result );
								} else {
									$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
									if ( $calc_value !== false ) {
										$result = ninja_forms_calc_evaluate( $c['op'], $result, $calc_value );
									}
								}
							}
						}
					}
					break;
				case 'eq':
					if (preg_match("/\bfield_".$field['id']."\b/i", $calc_eq ) ) {
						if ( $field['type'] == '_calc' ) {
							$calc_value = ninja_forms_calc_field_loop2( $field['id'], $calc_eq );
						} else {
							$calc_value = ninja_forms_field_calc_value( $field['id'], $field_value, $calc_method );
						}
						if ( $calc_value !== false ) {
							$calc_eq = preg_replace('/\bfield_'.$field['id'].'\b/', $calc_value, $calc_eq );
						}
					}
					break;
			}
		}
	}
	if ( $calc_method == 'eq' ) {
		$eq = new eqEOS();
		$result = $eq->solveIF($calc_eq);
	}

	if ( $result == '' ) {
		$result = 0;
	}

	return $result;
}