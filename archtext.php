<?php
/*
	Plugin Name: Arch Text
	Plugin URI: http://pjdecocq.nl
	Description: Create an Arch text effect and give options like font-size.
	Version: 1.0
	Author: Paul de Cocq
	Author URI: http://pjdecocq.nl
*/

	defined('ABSPATH') or die('File cannot be accessed directly!');

	if( ! class_exists('Archtext')) {
		
		class Archtext {
			/**
			 * Specify the plugin name 
			 * @var string
			 */
			protected $tag = 'archtext';
			 
			/**
			 * Specify an user friendly name
			 * @var string
			 */
			protected $name = 'Arch Text';
			
			/**
			 * Specifiy the version number
			 * @var string
			 */
			protected $version = '1.0';
			
			/**
			 * List of options to determine plugin behaviour. Filled with Defaults
			 * @var array
			 */
			protected $options = array(
				'fromSize'	=> 50,
				'toSize'	  => 20,
				'split'		  => 'yes'
			);
			 
			public function __construct() {
				
				add_shortcode( $this->tag, array( &$this, 'shortcode' ) );
				
			}
			
			public function shortcode( $atts, $content = null ) {
				
				extract( shortcode_atts( array(
					'fromSize' 	=> false,
					'toSize'	=> false,
					'split'		=> false,
				), $atts) );
				
				// First check if $atts is array
				if ( is_array($atts) ) {
					if ( array_key_exists('fromsize', $atts) ) {
					    if( is_numeric($atts['fromsize']) ) {
						    $this->options['fromSize'] = esc_attr( $atts['fromsize'] );
					    }
					}
					
					if ( array_key_exists('tosize', $atts) ) {
						if( is_numeric($atts['tosize']) ) {
							$this->options['toSize'] = esc_attr( $atts['tosize'] );
						}
					}
					
					if ( array_key_exists('split', $atts) ) {
						$this->options['split'] = esc_attr( $atts['split'] );
					}
				}
				// Load in needed scripts en styles
				$this->_enqueue();				
				
				$classes = array(
					$this->tag
				);
				if ( !empty($class) ) {
					$classes[] = esc_attr( $class );
				}
				
				ob_start();
				?>
				<span class="<?php echo $this->tag; ?>">
					<?php echo $content; ?>
				</span>
				<?php
				return ob_get_clean();
			}

			protected function _enqueue() {
				
				$plugin_path = plugin_dir_url(__FILE__);
				if ( !wp_style_is( $this->tag, 'enqueued' ) ) {
					wp_enqueue_style(
						$this->tag,
						$plugin_path . 'archtext.css',
						array(),
						$this->version
					);
				}
				
				if ( !wp_script_is( $this->tag, 'enqueued' ) ) {
					wp_enqueue_script('jquery');
					wp_enqueue_script(
						'jquery-' . $this->tag,
						$plugin_path . 'archtext.min.js',
						array('jquery'),
						$this->version
					);
					
					wp_register_script(
						$this->tag,
						$plugin_path . 'archtext.js',
						array( 'jquery-' . $this->tag ),
						$this->version
					);
					
					$options = array_merge( array(
						'selector'	=> '.' . $this->tag
					), $this->options);
					wp_localize_script( $this->tag, $this->tag, $options);
					wp_enqueue_script( $this->tag );
				}
				
			}


		}
		
		new Archtext;

	}

?>
