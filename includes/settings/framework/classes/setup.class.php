<?php if ( !defined('ABSPATH') ) {
	die;
} // Cannot access directly.
/**
 *
 * Setup Class
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 */
if ( !class_exists('CSF') ) {
	class CSF {

		// Default constants
		public static $premium  = true;
		public static $version  = '2.2.1';
		public static $dir      = '';
		public static $url      = '';
		public static $css      = '';
		public static $webfonts = [];
		public static $subsets  = [];
		public static $inited   = [];
		public static $fields   = [];
		public static $args     = [
			'admin_options'     => [],
			'customize_options' => [],
			'metabox_options'   => [],
			'nav_menu_options'  => [],
			'profile_options'   => [],
			'taxonomy_options'  => [],
			'widget_options'    => [],
			'comment_options'   => [],
			'shortcode_options' => [],
		];

		// Shortcode instances
		public static $shortcode_instances = [];

		// Initalize
		public static function init () {

			// Init action
			do_action('csf_init');

			// Set directory constants
			self::constants();

			// Include files
			self::includes();

			// Setup textdomain
			self::textdomain();

			add_action('after_setup_theme', ['CSF', 'setup']);
			add_action('init', ['CSF', 'setup']);
			add_action('switch_theme', ['CSF', 'setup']);
			add_action('admin_enqueue_scripts', ['CSF', 'add_admin_enqueue_scripts']);
			add_action('wp_enqueue_scripts', ['CSF', 'add_typography_enqueue_styles'], 80);
			add_action('wp_head', ['CSF', 'add_custom_css'], 80);
			add_filter('admin_body_class', ['CSF', 'add_admin_body_class']);

		}

		// Setup frameworks
		public static function setup () {

			// Welcome page
			self::include_plugin_file('views/welcome.php');

			// Setup admin option framework
			$params = [];
			if ( !empty(self::$args['admin_options']) ) {
				foreach ( self::$args['admin_options'] as $key => $value ) {
					if ( !empty(self::$args['sections'][$key]) && !isset(self::$inited[$key]) ) {

						$params['args']     = $value;
						$params['sections'] = self::$args['sections'][$key];
						self::$inited[$key] = true;

						CSF_Options::instance($key, $params);

						if ( !empty($value['show_in_customizer']) ) {
							$value['output_css']                   = false;
							$value['enqueue_webfont']              = false;
							self::$args['customize_options'][$key] = $value;
							self::$inited[$key]                    = null;
						}

					}
				}
			}

			// Setup customize option framework
			$params = [];
			if ( !empty(self::$args['customize_options']) ) {
				foreach ( self::$args['customize_options'] as $key => $value ) {
					if ( !empty(self::$args['sections'][$key]) && !isset(self::$inited[$key]) ) {

						$params['args']     = $value;
						$params['sections'] = self::$args['sections'][$key];
						self::$inited[$key] = true;

						CSF_Customize_Options::instance($key, $params);

					}
				}
			}

			// Setup metabox option framework
			$params = [];
			if ( !empty(self::$args['metabox_options']) ) {
				foreach ( self::$args['metabox_options'] as $key => $value ) {
					if ( !empty(self::$args['sections'][$key]) && !isset(self::$inited[$key]) ) {

						$params['args']     = $value;
						$params['sections'] = self::$args['sections'][$key];
						self::$inited[$key] = true;

						CSF_Metabox::instance($key, $params);

					}
				}
			}

			// Setup nav menu option framework
			$params = [];
			if ( !empty(self::$args['nav_menu_options']) ) {
				foreach ( self::$args['nav_menu_options'] as $key => $value ) {
					if ( !empty(self::$args['sections'][$key]) && !isset(self::$inited[$key]) ) {

						$params['args']     = $value;
						$params['sections'] = self::$args['sections'][$key];
						self::$inited[$key] = true;

						CSF_Nav_Menu_Options::instance($key, $params);

					}
				}
			}

			// Setup profile option framework
			$params = [];
			if ( !empty(self::$args['profile_options']) ) {
				foreach ( self::$args['profile_options'] as $key => $value ) {
					if ( !empty(self::$args['sections'][$key]) && !isset(self::$inited[$key]) ) {

						$params['args']     = $value;
						$params['sections'] = self::$args['sections'][$key];
						self::$inited[$key] = true;

						CSF_Profile_Options::instance($key, $params);

					}
				}
			}

			// Setup taxonomy option framework
			$params = [];
			if ( !empty(self::$args['taxonomy_options']) ) {
				$taxonomy = (isset($_GET['taxonomy'])) ? sanitize_text_field(wp_unslash($_GET['taxonomy'])) : '';
				foreach ( self::$args['taxonomy_options'] as $key => $value ) {
					if ( !empty(self::$args['sections'][$key]) && !isset(self::$inited[$key]) ) {

						$params['args']     = $value;
						$params['sections'] = self::$args['sections'][$key];
						self::$inited[$key] = true;

						CSF_Taxonomy_Options::instance($key, $params);

					}
				}
			}

			// Setup widget option framework
			if ( !empty(self::$args['widget_options']) && class_exists('WP_Widget_Factory') ) {
				$wp_widget_factory = new WP_Widget_Factory();
				foreach ( self::$args['widget_options'] as $key => $value ) {
					if ( !isset(self::$inited[$key]) ) {

						self::$inited[$key] = true;
						$wp_widget_factory->register(CSF_Widget::instance($key, $value));

					}
				}
			}

			// Setup comment option framework
			$params = [];
			if ( !empty(self::$args['comment_options']) ) {
				foreach ( self::$args['comment_options'] as $key => $value ) {
					if ( !empty(self::$args['sections'][$key]) && !isset(self::$inited[$key]) ) {

						$params['args']     = $value;
						$params['sections'] = self::$args['sections'][$key];
						self::$inited[$key] = true;

						CSF_Comment_Metabox::instance($key, $params);

					}
				}
			}

			// Setup shortcode option framework
			$params = [];
			if ( !empty(self::$args['shortcode_options']) ) {

				foreach ( self::$args['shortcode_options'] as $key => $value ) {
					if ( !empty(self::$args['sections'][$key]) && !isset(self::$inited[$key]) ) {

						$params['args']     = $value;
						$params['sections'] = self::$args['sections'][$key];
						self::$inited[$key] = true;

						CSF_Shortcoder::instance($key, $params);

					}
				}

				// Once editor setup for gutenberg and media buttons
				if ( !empty(self::$shortcode_instances) ) {
					foreach ( self::$shortcode_instances as $instance ) {
						if ( !empty($instance['show_in_editor']) ) {
							CSF_Shortcoder::once_editor_setup();
							break;
						}
					}
				}

			}

			do_action('csf_loaded');

		}

		// Create options
		public static function createOptions ($id, $args = []) {
			self::$args['admin_options'][$id] = $args;
		}

		// Create customize options
		public static function createCustomizeOptions ($id, $args = []) {
			self::$args['customize_options'][$id] = $args;
		}

		// Create metabox options
		public static function createMetabox ($id, $args = []) {
			self::$args['metabox_options'][$id] = $args;
		}

		// Create menu options
		public static function createNavMenuOptions ($id, $args = []) {
			self::$args['nav_menu_options'][$id] = $args;
		}

		// Create shortcoder options
		public static function createShortcoder ($id, $args = []) {
			self::$args['shortcode_options'][$id] = $args;
		}

		// Create taxonomy options
		public static function createTaxonomyOptions ($id, $args = []) {
			self::$args['taxonomy_options'][$id] = $args;
		}

		// Create profile options
		public static function createProfileOptions ($id, $args = []) {
			self::$args['profile_options'][$id] = $args;
		}

		// Create widget
		public static function createWidget ($id, $args = []) {
			self::$args['widget_options'][$id] = $args;
			self::set_used_fields($args);
		}

		// Create comment metabox
		public static function createCommentMetabox ($id, $args = []) {
			self::$args['comment_options'][$id] = $args;
		}

		// Create section
		public static function createSection ($id, $sections) {
			self::$args['sections'][$id][] = $sections;
			self::set_used_fields($sections);
		}

		// Set directory constants
		public static function constants () {

			// We need this path-finder code for set URL of framework
			$dirname        = str_replace('//', '/', wp_normalize_path(dirname(dirname(__FILE__))));
			$theme_dir      = str_replace('//', '/', wp_normalize_path(get_parent_theme_file_path()));
			$plugin_dir     = str_replace('//', '/', wp_normalize_path(WP_PLUGIN_DIR));
			$located_plugin = (preg_match('#' . self::sanitize_dirname($plugin_dir) . '#',
				self::sanitize_dirname($dirname))) ? true : false;
			$directory      = ($located_plugin) ? $plugin_dir : $theme_dir;
			$directory_uri  = ($located_plugin) ? WP_PLUGIN_URL : get_parent_theme_file_uri();
			$foldername     = str_replace($directory, '', $dirname);
			$protocol_uri   = (is_ssl()) ? 'https' : 'http';
			$directory_uri  = set_url_scheme($directory_uri, $protocol_uri);

			self::$dir = $dirname;
			self::$url = $directory_uri . $foldername;

		}

		// Include file helper
		public static function include_plugin_file ($file, $load = true) {

			$path     = '';
			$file     = ltrim($file, '/');
			$override = apply_filters('csf_override', 'csf-override');

			if ( file_exists(get_parent_theme_file_path($override . '/' . $file)) ) {
				$path = get_parent_theme_file_path($override . '/' . $file);
			} elseif ( file_exists(get_theme_file_path($override . '/' . $file)) ) {
				$path = get_theme_file_path($override . '/' . $file);
			} elseif ( file_exists(self::$dir . '/' . $override . '/' . $file) ) {
				$path = self::$dir . '/' . $override . '/' . $file;
			} elseif ( file_exists(self::$dir . '/' . $file) ) {
				$path = self::$dir . '/' . $file;
			}

			if ( !empty($path) && !empty($file) && $load ) {

				global $wp_query;

				if ( is_object($wp_query) && function_exists('load_template') ) {

					load_template($path, true);

				} else {

					require_once($path);

				}

			} else {

				return self::$dir . '/' . $file;

			}

		}

		// Is active plugin helper
		public static function is_active_plugin ($file = '') {
			return in_array($file, (array) get_option('active_plugins', []));
		}

		// Sanitize dirname
		public static function sanitize_dirname ($dirname) {
			return preg_replace('/[^A-Za-z]/', '', $dirname);
		}

		// Set url constant
		public static function include_plugin_url ($file) {
			return esc_url(self::$url) . '/' . ltrim($file, '/');
		}

		// Include files
		public static function includes () {

			// Helpers
			self::include_plugin_file('functions/actions.php');
			self::include_plugin_file('functions/helpers.php');
			self::include_plugin_file('functions/sanitize.php');
			self::include_plugin_file('functions/validate.php');

			// Includes free version classes
			self::include_plugin_file('classes/abstract.class.php');
			self::include_plugin_file('classes/fields.class.php');
			self::include_plugin_file('classes/admin-options.class.php');

			// Includes premium version classes
			if ( self::$premium ) {
				self::include_plugin_file('classes/customize-options.class.php');
				self::include_plugin_file('classes/metabox-options.class.php');
				self::include_plugin_file('classes/nav-menu-options.class.php');
				self::include_plugin_file('classes/profile-options.class.php');
				self::include_plugin_file('classes/shortcode-options.class.php');
				self::include_plugin_file('classes/taxonomy-options.class.php');
				self::include_plugin_file('classes/widget-options.class.php');
				self::include_plugin_file('classes/comment-options.class.php');
			}

		}

		// Maybe include a field class
		public static function maybe_include_field ($type = '') {
			if ( !class_exists('CSF_Field_' . $type) && class_exists('CSF_Fields') ) {
				self::include_plugin_file('fields/' . $type . '/' . $type . '.php');
			}
		}

		// Setup textdomain
		public static function textdomain () {
			load_textdomain('csf', self::$dir . '/languages/' . get_locale() . '.mo');
            load_theme_textdomain(THEME_TEXTDOMAIN, THEME_LANGS_DIR);
		}

		// Set all of used fields
		public static function set_used_fields ($sections) {

			if ( !empty($sections['fields']) ) {

				foreach ( $sections['fields'] as $field ) {

					if ( !empty($field['fields']) ) {
						self::set_used_fields($field);
					}

					if ( !empty($field['tabs']) ) {
						self::set_used_fields(['fields' => $field['tabs']]);
					}

					if ( !empty($field['accordions']) ) {
						self::set_used_fields(['fields' => $field['accordions']]);
					}

					if ( !empty($field['type']) ) {
						self::$fields[$field['type']] = $field;
					}

				}

			}

		}

		// Enqueue admin and fields styles and scripts
		public static function add_admin_enqueue_scripts () {

			// Loads scripts and styles only when needed
			$enqueue  = false;
			$wpscreen = get_current_screen();

			if ( !empty(self::$args['admin_options']) ) {
				foreach ( self::$args['admin_options'] as $argument ) {
					if ( substr($wpscreen->id, -strlen($argument['menu_slug'])) === $argument['menu_slug'] ) {
						$enqueue = true;
					}
				}
			}

			if ( !empty(self::$args['metabox_options']) ) {
				foreach ( self::$args['metabox_options'] as $argument ) {
					if ( in_array($wpscreen->post_type, (array) $argument['post_type']) ) {
						$enqueue = true;
					}
				}
			}

			if ( !empty(self::$args['taxonomy_options']) ) {
				foreach ( self::$args['taxonomy_options'] as $argument ) {
					if ( $wpscreen->taxonomy === $argument['taxonomy'] ) {
						$enqueue = true;
					}
				}
			}

			if ( !empty(self::$shortcode_instances) ) {
				foreach ( self::$shortcode_instances as $argument ) {
					if ( ($argument['show_in_editor'] && $wpscreen->base === 'post') || $argument['show_in_custom'] ) {
						$enqueue = true;
					}
				}
			}

			if ( !empty(self::$args['widget_options']) && ($wpscreen->id === 'widgets' || $wpscreen->id === 'customize') ) {
				$enqueue = true;
			}

			if ( !empty(self::$args['customize_options']) && $wpscreen->id === 'customize' ) {
				$enqueue = true;
			}

			if ( !empty(self::$args['nav_menu_options']) && $wpscreen->id === 'nav-menus' ) {
				$enqueue = true;
			}

			if ( !empty(self::$args['profile_options']) && ($wpscreen->id === 'profile' || $wpscreen->id === 'user-edit') ) {
				$enqueue = true;
			}

			if ( !empty(self::$args['comment_options']) && $wpscreen->id === 'comment' ) {
				$enqueue = true;
			}

			if ( $wpscreen->id === 'tools_page_csf-welcome' ) {
				$enqueue = true;
			}

			if ( !$enqueue ) {
				return;
			}

			// Check for developer mode
			$min = (self::$premium && SCRIPT_DEBUG) ? '' : '.min';

			// Admin utilities
			wp_enqueue_media();

			// Wp color picker

			wp_enqueue_style('csf-color-picker', self::include_plugin_url('assets/css/nano' . $min . '.css'), [],
				self::$version,
				'all');


			// Main style
			wp_enqueue_style('csf', self::include_plugin_url('assets/css/style' . $min . '.css'), [], self::$version,
				'all');

			// Main RTL styles
			if ( is_rtl() ) {
				wp_enqueue_style('csf-rtl', self::include_plugin_url('assets/css/style-rtl' . $min . '.css'), [],
					self::$version, 'all');
			}

			// Main scripts
			wp_enqueue_script('csf-plugins', self::include_plugin_url('assets/js/plugins' . $min . '.js'), [],
				self::$version, true);
			wp_enqueue_script('csf-color-picker', self::include_plugin_url('assets/js/pickr' . $min . '.js'), [],
				self::$version, true);
			wp_enqueue_script('csf', self::include_plugin_url('assets/js/main' . $min . '.js'), ['csf-color-picker','csf-plugins'],
				self::$version, true);

			// Main variables
			wp_localize_script('csf', 'csf_vars', [
				'color_palette' => apply_filters('csf_color_palette', []),
				'i18n'          => [
					'confirm'         => esc_html__('Are you sure?', 'csf'),
					'typing_text'     => esc_html__('Please enter %s or more characters', 'csf'),
					'searching_text'  => esc_html__('Searching...', 'csf'),
					'no_results_text' => esc_html__('No results found.', 'csf'),
				],
			]);

			// Enqueue fields scripts and styles
			$enqueued = [];

			if ( !empty(self::$fields) ) {
				foreach ( self::$fields as $field ) {
					if ( !empty($field['type']) ) {

						$classname = 'CSF_Field_' . $field['type'];
						self::maybe_include_field($field['type']);
						if ( class_exists($classname) && method_exists($classname, 'enqueue') ) {
							$instance = new $classname($field);
							if ( method_exists($classname, 'enqueue') ) {
								$instance->enqueue();
							}
							unset($instance);
						}
					}
				}
			}

			do_action('csf_enqueue');

		}

		// Add typography enqueue styles to front page
		public static function add_typography_enqueue_styles () {
			if ( !empty(self::$webfonts) ) {

				if ( !empty(self::$webfonts['enqueue']) ) {

					$query = [];
					$fonts = [];

					foreach ( self::$webfonts['enqueue'] as $family => $styles ) {
						$fonts[] = $family . (( !empty($styles)) ? ':' . implode(',', $styles) : '');
					}

					if ( !empty($fonts) ) {
						$query['family'] = implode('%7C', $fonts);
					}

					if ( !empty(self::$subsets) ) {
						$query['subset'] = implode(',', self::$subsets);
					}

					$query['display'] = 'swap';

					wp_enqueue_style('csf-google-web-fonts',
						esc_url(add_query_arg($query, '//fonts.googleapis.com/css')), [], null);

				}

				if ( !empty(self::$webfonts['async']) ) {

					$fonts = [];

					foreach ( self::$webfonts['async'] as $family => $styles ) {
						$fonts[] = $family . (( !empty($styles)) ? ':' . implode(',', $styles) : '');
					}

					wp_enqueue_script('csf-google-web-fonts',
						esc_url('//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js'), [], null);

					wp_localize_script('csf-google-web-fonts', 'WebFontConfig', ['google' => ['families' => $fonts]]);

				}

			}

		}

		// Add admin body class
		public static function add_admin_body_class ($classes) {

			if ( apply_filters('csf_fa4', false) ) {
				$classes .= 'csf-fa5-shims';
			}

			return $classes;

		}

		// Add custom css to front page
		public static function add_custom_css () {

			if ( !empty(self::$css) ) {
				echo '<style type="text/css">' . wp_strip_all_tags(self::$css) . '</style>';
			}

		}

		// Add a new framework field
		public static function field ($field = [], $value = '', $unique = '', $where = '', $parent = '') {

			// Check for unallow fields
			if ( !empty($field['_notice']) ) {

				$field_type = $field['type'];

				$field            = [];
				$field['content'] = esc_html__('Oops! Not allowed.', 'csf') . ' <strong>(' . $field_type . ')</strong>';
				$field['type']    = 'notice';
				$field['style']   = 'danger';

			}

			$depend     = '';
			$visible    = '';
			$unique     = ( !empty($unique)) ? $unique : '';
			$class      = ( !empty($field['class'])) ? ' ' . esc_attr($field['class']) : '';
			$is_pseudo  = ( !empty($field['pseudo'])) ? ' csf-pseudo-field' : '';
			$field_type = ( !empty($field['type'])) ? esc_attr($field['type']) : '';

			if ( !empty($field['dependency']) ) {

				$dependency      = $field['dependency'];
				$depend_visible  = '';
				$data_controller = '';
				$data_condition  = '';
				$data_value      = '';
				$data_global     = '';

				if ( is_array($dependency[0]) ) {
					$data_controller = implode('|', array_column($dependency, 0));
					$data_condition  = implode('|', array_column($dependency, 1));
					$data_value      = implode('|', array_column($dependency, 2));
					$data_global     = implode('|', array_column($dependency, 3));
					$depend_visible  = implode('|', array_column($dependency, 4));
				} else {
					$data_controller = ( !empty($dependency[0])) ? $dependency[0] : '';
					$data_condition  = ( !empty($dependency[1])) ? $dependency[1] : '';
					$data_value      = ( !empty($dependency[2])) ? $dependency[2] : '';
					$data_global     = ( !empty($dependency[3])) ? $dependency[3] : '';
					$depend_visible  = ( !empty($dependency[4])) ? $dependency[4] : '';
				}

				$depend .= ' data-controller="' . esc_attr($data_controller) . '"';
				$depend .= ' data-condition="' . esc_attr($data_condition) . '"';
				$depend .= ' data-value="' . esc_attr($data_value) . '"';
				$depend .= ( !empty($data_global)) ? ' data-depend-global="true"' : '';

				$visible = ( !empty($depend_visible)) ? ' csf-depend-visible' : ' csf-depend-hidden';

			}

			if ( !empty($field_type) ) {

				// These attributes has been sanitized above.
				echo '<div class="csf-field csf-field-' . $field_type . $is_pseudo . $class . $visible . '"' . $depend . '>';

				if ( !empty($field['fancy_title']) ) {
					echo '<div class="csf-fancy-title">' . $field['fancy_title'] . '</div>';
				}

				if ( !empty($field['title']) ) {
					echo '<div class="csf-title">';
					echo '<h4>' . $field['title'] . '</h4>';
					echo ( !empty($field['subtitle'])) ? '<div class="csf-subtitle-text">' . $field['subtitle'] . '</div>' : '';
					echo '</div>';
				}

				echo ( !empty($field['title']) || !empty($field['fancy_title'])) ? '<div class="csf-fieldset">' : '';

				$value = ( !isset($value) && isset($field['default'])) ? $field['default'] : $value;
				$value = (isset($field['value'])) ? $field['value'] : $value;

				self::maybe_include_field($field_type);

				$classname = 'CSF_Field_' . $field_type;

				if ( class_exists($classname) ) {
					$instance = new $classname($field, $value, $unique, $where, $parent);
					$instance->render();
				} else {
					echo '<p>' . esc_html__('Field not found!', 'csf') . '</p>';
				}

			} else {
				echo '<p>' . esc_html__('Field not found!', 'csf') . '</p>';
			}

			echo ( !empty($field['title']) || !empty($field['fancy_title'])) ? '</div>' : '';
			echo '<div class="clear"></div>';
			echo '</div>';

		}

	}

	CSF::init();
}
