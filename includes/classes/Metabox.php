<?php

/**
 *
 *
 * @category   Classes
 * @version    1.0.0
 * @since      1.0.0
 */

namespace DCore;

/**
 * Class Metabox
 *
 * @package DCore
 */
class Metabox {
	public static function addControl (string $id, array $args) : void {
		$defaults = [
			'id'            => $id,
			'label'         => '',
			'type'          => 'text',
			'default'       => '',
			'value'         => '',
			'condition'     => [],
			'outer_classes' => [],
			'inner_classes' => [],
		];

		$args                  = array_merge($defaults, $args);
		$args['outer_classes'] = array_merge($args['outer_classes'], [
			'custom-metabox-' . $id
		]);

		$args['value'] = empty($args['value']) ? $args['default'] : $args['value'];
		$args['name']  = $args['name'] ?? $id;

		$conditionsAttr = '';
		if ( !empty($args['condition']) ) {
			$conditionsAttr = 'data-condition="' . esc_attr(json_encode($args['condition'])) . '"';
		}

		?>

        <p class="custom-metabox-item <?= implode(' ', $args['outer_classes']) ?>" <?= $conditionsAttr ?>>
            <label>
				<?= $args['label'] ?>
            </label>
			<?php
			if ( method_exists(self::class, '_' . $args['type']) ) {
				echo '<span class="inner widefat">';
				self::{'_' . $args['type']}($args);
				echo '</p>';
			}
			?>
        </p>
		<?php
	}

	private static function _text (array $args) : void {
		$args['input_type'] = $args['input_type'] ?? 'text';
		echo '<input type="' . esc_attr($args['input_type']) . '" name="' . esc_attr($args['name']) . '" value="' . esc_attr($args['value']) . '" class="widefat ' . esc_attr(implode(' ',
				$args['inner_classes'])) . '" />';
	}

	private static function _checkbox (array $args) : void {
		$args['checkbox_label'] = $args['checkbox_label'] ?? '';
		echo '<label><input type="checkbox" name="' . esc_attr($args['name']) . '" class="checkbox ' . esc_attr(implode(' ',
				$args['inner_classes'])) . '" ' . ($args['value'] === 'on' ? 'checked' : '') . '/> ' . $args['checkbox_label'] . '</label>';
	}

	private static function _imageUpload (array $args) : void {
		$imageUrl = '';
		if ( is_numeric($args['value']) ) {
			$imageUrl = wp_get_attachment_image_url($args['value'], 'thumbnail');
			if ( !is_string($imageUrl) ) {
				$imageUrl = '';
			}
		}
		?>
        <input type="button" class="button dc-upload-btn" value="<?= __('Upload', THEME_TEXTDOMAIN) ?>">
        <img class="item-preview" src="<?= $imageUrl ?>" alt=""/>
        <span class="remove-item" title="<?= __('Remove', THEME_TEXTDOMAIN) ?>">X</span>

        <input type="hidden" class="image-upload-input"
               name="<?= esc_attr($args['name']) ?>" value="<?= esc_attr($args['value']) ?>">
		<?php
	}

	private static function _select (array $args) : void {
		$options = $args['options'] ?? [];
		if ( empty($options) ) {
			return;
		}
		echo '<select name="' . esc_attr($args['name']) . '" class="widefat ' . esc_attr(implode(' ',
				$args['inner_classes'])) . '">';

		foreach ( $options as $value => $label ) {
			echo '<option value="' . esc_attr($value) . '" ' . ($args['value'] === $value ? 'selected' : '') . '>';
			echo esc_html($label);
			echo '</option>';
		}

		echo '</select>';

	}
}