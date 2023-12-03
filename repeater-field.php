<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Pagebreak field.
 *
 * @since 1.0.0
 */
class WPForms_Field_Repeater extends WPForms_Field {
	/**
	 * Pages information.
	 *
	 * @since 1.3.7
	 *
	 * @var array|bool
	 */
	protected $pagebreak;
	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Define field type information.
		$this->name  = esc_html__( 'End Repeater', 'wpforms' );
		$this->type  = 'repeater';
		$this->icon  = 'fa-list-ul';
		$this->order = 550;
		$this->group = 'standard';
		$this->hooks();

	}
	/**
	 * Hooks.
	 *
	 * @since 1.7.1
	 */
	private function hooks() {
		add_action( 'wpforms_frontend_js', array( $this, 'frontend_js' ) );
		add_action( 'wpforms_frontend_css', array( $this, 'frontend_css' ) );
		add_filter("wpforms_process_before_form_data",array($this,"wpforms_process_before_form_data"),10,2);
		
	}
	function wpforms_process_before_form_data($post_content,$entry){
		$zo = false;
		$new_datas = array();

		// version wp form 1.7.1.2
		if( isset($post_content["fields"])) {
			foreach($post_content["fields"] as $id => $field ){
				if($field["type"] == "repeater_start"){
					$zo = true;
					continue;
				}
				if($field["type"] == "repeater"){
					$new_datas[] = $field;
					$zo = false;
					continue;
				}
				if( $zo  ){	
					if( isset($field["required"])){
						unset($post_content["fields"][$id]["required"]);
					}
				}
				
			}
		}
		
		return $post_content;
	}
	function frontend_js($forms){
		if (
			wpforms()->frontend->assets_global() ||
			true === wpforms_has_field_type( 'repeater', $forms, true )
		) {
			wp_enqueue_script(
				'wpforsm_repeater',
				WPFORMS_REPEATER_FIELD_PLUGIN_URL . 'libs/wp_repeater.js',
				array("jquery"),
				"1.1.2"
			);
		}
	}
	function frontend_css($forms){
		if (
			wpforms()->frontend->assets_global() ||
			true === wpforms_has_field_type( 'repeater', $forms, true )
		) {
			wp_enqueue_style(
				'repeater_icon',
				WPFORMS_REPEATER_FIELD_PLUGIN_URL . 'libs/css/repeatericons.css',
				array()
			);
			wp_enqueue_style(
				'wpforsm_repeater',
				WPFORMS_REPEATER_FIELD_PLUGIN_URL . 'libs/wp_repeater.css',
				array()
			);
		}
	}
	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field
	 */
	public function field_preview( $field ) {
		?>
		<div class="wpforms-pagebreak-divider"><span class="pagebreak-label"><?php esc_html_e("End Repeater","wpforms-repeater-field") ?><span class="wpforms-pagebreak-title"></span></span><span class="line"></span></div>
		<?php
	}
	function add_options($type,$field){
		$check = get_option( '_redmuber_item_46490403');
		$text_pro ="";
		if($check != "ok"){
			$text_pro = esc_html__("Pro version","rednumber");
		}
		$output ="";
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'repeater_add_button',
				'value'   => esc_html__( 'Add button text', 'wpforms-repeater-field' ),
			),
			false
		);
		$fld = $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'repeater_add_button',
				'value' => ! empty( $field['repeater_add_button'] ) ? esc_attr( $field['repeater_add_button'] ) : 'Add more...',
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'repeater_add_button',
				'content' => $lbl . $fld,
			)
		);
		//
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'repeater_initial_rows',
				'value'   => esc_html__( 'Initial Rows', 'wpforms-repeater-field' ),
				'tooltip' => esc_html__( 'The number of rows at start, if empty no rows will be created', 'wpforms-repeater-field' ),
			),
			false
		);
		if($check != "ok"){ 
			$fld = '<input type="text" disabled="disabled" value="1 '.$text_pro.'" />';
		}else{
			$fld = $this->field_element(
				'text',
				$field,
				array(
					'slug'  => 'repeater_initial_rows',
					'value' => ! empty( $field['repeater_initial_rows'] ) ? esc_attr( $field['repeater_initial_rows'] ) : '1',
				),
				false
			);
		}
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'repeater_initial_rows',
				'content' => $lbl . $fld,
			)
		);
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'repeater_limit',
				'value'   => esc_html__( 'Limit', 'wpforms-repeater-field' ),
				'tooltip' => esc_html__( 'Max number of rows applicable by the user, leave empty for no limit', 'wpforms-repeater-field' ),
			),
			false
		);
		if($check != "ok"){ 
			$fld = '<input type="text" disabled="disabled" value="3 '.$text_pro.'" />';
		}else{
			$fld = $this->field_element(
				'text',
				$field,
				array(
					'slug'  => 'repeater_limit',
					'value' => ! empty( $field['repeater_limit'] ) ? esc_attr( $field['repeater_limit'] ) : '10',
				),
				false
			);
		}
		
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'repeater_limit',
				'content' => $lbl . $fld,
			)
		);
        echo wp_kses_post( $output );        
    }
	public function field_options( $field ) {
		/*
		 * Basic field options.
		 */
		// Options open markup.
		$args = array(
			'markup' => 'open',
		);
		$this->field_option( 'basic-options', $field, $args );
		$this->field_option( 'label', $field );
		$this->add_options( 'repeater', $field );
		// Options close markup.
		$args = array(
			'markup' => 'close',
		);
		$this->field_option( 'basic-options', $field, $args );
		
	}
	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field      Field data and settings.
	 * @param array $field_atts Field attributes.
	 * @param array $form_data  Form data and settings.
	 */
	public function field_display( $field, $field_atts, $form_data ) {
		$primary = $field['properties']['inputs']['primary'];
		$input = sprintf(
			'<input type="hidden" %s %s>',
			wpforms_html_attributes( $primary['id'], array("wpforms-field-repeater-data"), $primary['data'], $primary['attr'] ),
			$primary['required']
		); 
		$repeater_add_button    = ! empty( $field['repeater_add_button'] ) ? $field['repeater_add_button'] : 'Add more...';
		$limit    = ! empty( $field['repeater_limit'] ) ? $field['repeater_limit'] : '3';
		$initial_rows    = ! empty( $field['repeater_initial_rows'] ) ? $field['repeater_initial_rows'] : '1';
		?>
		<div class="repeater-field-warp-item-data" data-initial_rows="<?php echo esc_attr($initial_rows) ?>" data-limit="<?php echo esc_attr($limit) ?>">
			<div class="repeater-field-warp-item">
			</div>
			<div class="repeater-field-footer"><a href='#' class='wpforms-repeater-field-button-add' ><?php echo esc_html($repeater_add_button) ?></a></div>
			<?php
			$allowed_html = array(
			    'input' => array(
			        'type'      => array(),
			        'name'      => array(),
			        'value'     => array(),
			        'id'   => array(),
			        'class'   => array(),
			        'aria-errormessage'   => array(),
			    ),
			);
			echo wp_kses( $input,$allowed_html); 
			?>
			<textarea class="wpforms-field-repeater-data-html hidden"></textarea>
		</div>
		<?php
	}
	public function format( $field_id, $field_submit, $form_data ) {
		if ( is_array( $field_submit ) ) {
			$field_submit = array_filter( $field_submit );
			$field_submit = implode( "\r\n", $field_submit );
		}
		$name = $this->get_lb_by_id($field_id,$form_data["fields"]);
		$datas = json_decode($field_submit,true);
		$datas_submit = map_deep( $_POST["wpforms"],"sanitize_text_field");
		if(!wp_verify_nonce( $datas_submit["nonce"], "wpforms::form_{$form_data["id"]}")){
			die('Security check'); 
		}
		$value = "";
		foreach( $datas["id"] as $name_id ){
			foreach( $datas["fields"] as $field ){
				preg_match('/wpforms\[fields\]\[([0-9]+?)\]/',$field,$matches);
				$id_field = $matches[1];
				$lb = $this->get_lb_by_id($id_field,$form_data["fields"]);
				wpforms()->process->fields[ $id_field ] = array();
				$vl = $datas_submit["fields"][$id_field."_".$name_id];
				if( is_array($vl) ){
					$vl = implode(", ",$vl);
				}
				$value .= $lb .': ' . $vl."\r";
			}
			$value .="\r";
		}
		
		wpforms()->process->fields[ $field_id ] = array(
			'name'  => $name,
			'value' => $value,
			'id'    => absint( $field_id ),
			'type'  => $this->type,
		);
	}
	function get_lb_by_id($id,$form_data){
		$lb = '#'.$id;
		foreach( $form_data as $field ){
			if($id == $field["id"] ){
				$lb = ! empty( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '#'.$field["id"];
				break;
			}
		}
		return $lb;
	}
	public function html_email_value( $val, $field, $form_data = array(), $context = '' ) {
		if ( empty( $field['value'] ) || $field['type'] !== $this->type ) {
			return $val;
		}
		return $field['value'];
	}
}
new WPForms_Field_Repeater();
