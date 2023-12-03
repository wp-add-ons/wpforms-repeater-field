<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WPForms_Field_Repeater_Start extends WPForms_Field {
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
		$this->name  = esc_html__( 'Start Repeater', 'wpforms' );
		$this->type  = 'repeater_start';
		$this->icon  = 'fa-list-ul';
		$this->order = 540;
		$this->group = 'standard';
		$this->hooks();
	}
	/**
	 * Hooks.
	 *
	 * @since 1.7.1
	 */
	private function hooks() {
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
		<div class="wpforms-pagebreak-divider"><span class="pagebreak-label"><?php esc_html_e("Begin Repeater","wpforms-repeater-field") ?><span class="wpforms-pagebreak-title"></span></span><span class="line"></span></div>
		<?php
	}
	function add_options($type,$field){
		$output ="";
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'repeater_title',
				'value'   => esc_html__( 'Title', 'wpforms-repeater-field' ),
				'tooltip' => esc_html__( 'An optional title before each row of the repeater', 'wpforms-repeater-field' ),
			),
			false
		);
		$fld = $this->field_element(
			'text',
			$field,
			array(
				'slug'  => 'repeater_title',
				'value' => ! empty( $field['repeater_title'] ) ? esc_attr( $field['repeater_title'] ) : 'person',
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'repeater_title',
				'content' => $lbl . $fld,
			)
		);
		//
		$lbl = $this->field_element(
			'label',
			$field,
			array(
				'slug'    => 'repeater_show_number',
				'value'   => esc_html__( 'Show Index', 'wpforms-repeater-field' ),
				'tooltip' => esc_html__( 'Use the placeholder to print the current row index', 'wpforms-repeater-field' ),
			),
			false
		);
		$repeater_show_number   = ! empty( $field['repeater_show_number'] ) || wp_doing_ajax();
		$fld = $this->field_element(
			'toggle',
			$field,
			array(
				'slug'  => 'repeater_show_number',
				'value' => $repeater_show_number,
			),
			false
		);
		$output .= $this->field_element(
			'row',
			$field,
			array(
				'slug'    => 'repeater_show_number',
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
		// Label.
		$this->field_option( 'label', $field );
		$this->add_options( 'repeater', $field );
		$this->field_option( 'size', $field );
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
		$size  = ! empty( $field['size'] ) ? $field['size'] : 'medium';
		?>
		<div class="wpforms-field-repeater-start">
			<textarea class="repeater-field-header-data hidden"><div class="repeater-field-header wpforms-repeater-field-<?php echo esc_attr($size) ?>">
				<div class="repeater-field-header-title"><?php echo esc_html($field['repeater_title'] ) ?> <?php if( isset($field['repeater_show_number']) && $field['repeater_show_number'] ){ ?><span class="repeater-field-header-count">1</span><?php } ?></div>
				<div class="repeater-field-header-acctions">
					<ul>
						<li><i class="repeater-icon icon-down-open repeater-field-header-acctions-toogle" aria-hidden="true"></i></li>
						<li><i class="repeater-icon icon-cancel-1 repeater-field-header-acctions-remove" aria-hidden="true"></i></li>
					</ul>
				</div>
			</div></textarea>
		</div>
		<?php
	}
	public function format( $field_id, $field_submit, $form_data ) { 
		wpforms()->process->fields[ $field_id ] = array();
	}
}
new WPForms_Field_Repeater_Start();