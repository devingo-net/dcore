<?php

/**
 *
 *
 * @category   classes
 * @version    1.1.0
 * @since      1.0.0
 */

namespace DCore\Features;

use WP_Comment;

/**
 * Class Advanced_Reviews
 *
 * @package DCore\Features
 */
class Advanced_Reviews {
	public static string $likesMetaKey    = 'comment_likes';
	public static string $disLikesMetaKey = 'comment_dislikes';

	/**
	 * add input fields
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function printInputs (array $args) : array {
		if ( !is_singular('product') ) {
			return $args;
		}

		if ( getShopOptions('product-comment-chips', '1') === '1' ) {
			$args['comment'] .= PHP_EOL . '<div class="comment-chips positive-rates"><input class="chips-input" data-name="positive-rate" data-label="' . __('Positive rates',
					THEME_TEXTDOMAIN) . '" type="hidden" value=""></div>';
			$args['comment'] .= PHP_EOL . '<div class="comment-chips negative-rates"><input class="chips-input" data-name="negative-rate" data-label="' . __('Negative rates',
					THEME_TEXTDOMAIN) . '" type="hidden" value=""></div>';
		}

		if ( is_user_logged_in() && getShopOptions('product-comment-attachments', '1') === '1' ) {
			$args['comment'] .= PHP_EOL . '<div id="comment-attachments"><input type="file" name="attachments[]" multiple></div>';
		}

		if ( getShopOptions('product-comment-as-unknown', '1') === '1' ) {
			$args['comment'] .= PHP_EOL . '<p class="comment-as-unknown">
<input id="wp-comment-as-unknown" name="wp-comment-as-unknown" type="checkbox" value="yes">
<label for="wp-comment-as-unknown">' . __('Send as an unknown user?', THEME_TEXTDOMAIN) . '</label>
</p>';
		}

		return $args;
	}

	/**
	 * save comment meta
	 *
	 * @param $commentID
	 */
	public function saveMeta ($commentID) : void {
		if ( isset($_POST['positive-rate']) && is_array($_POST['positive-rate']) && !empty($_POST['positive-rate']) ) {
			foreach ( $_POST['positive-rate'] as $key => $value ) {
				if ( empty(str_replace(' ', '', $value)) ) {
					unset($_POST['positive-rate'][$key]);
				}
			}
			if ( !empty($_POST['positive-rate']) ) {
				add_comment_meta($commentID, 'positive-rate', $_POST['positive-rate']);
			}
		}
		if ( isset($_POST['negative-rate']) && is_array($_POST['negative-rate']) && !empty($_POST['negative-rate']) ) {
			foreach ( $_POST['negative-rate'] as $key => $value ) {
				if ( empty(str_replace(' ', '', $value)) ) {
					unset($_POST['negative-rate'][$key]);
				}
			}
			if ( !empty($_POST['negative-rate']) ) {
				add_comment_meta($commentID, 'negative-rate', $_POST['negative-rate']);
			}
		}

		if ( isset($_POST['attachments']) && is_array($_POST['attachments']) ) {
			$attachments = $_POST['attachments'];
			foreach ( $attachments as $index => $attachment ) {
				if ( empty($attachment) || !is_numeric($attachment) ) {
					unset($attachments[$index]);
				} else {
					$attachments[$index] /= 2;
				}
			}

			if ( !empty($attachments) ) {
				add_comment_meta($commentID, 'comment_attachments', $attachments);
			}
		}

		if ( isset($_POST['wp-comment-as-unknown']) && getShopOptions('product-comment-as-unknown', '1') === '1' ) {
			wp_update_comment([
				'comment_ID'           => $commentID,
				'comment_author'       => __('Unknown', THEME_TEXTDOMAIN),
				'comment_author_email' => 'not@show.email'
			]);
		}
	}

	/**
	 * @param string      $commentText
	 * @param \WP_Comment $comment
	 * @param array       $args
	 *
	 * @return string
	 */
	public static function displayChips (string $commentText, $comment, array $args) : string {
		if ( !($comment instanceof WP_Comment) ) {
			return $commentText;
		}
		$positiveChip = get_comment_meta($comment->comment_ID, 'positive-rate', true);
		$negativeRate = get_comment_meta($comment->comment_ID, 'negative-rate', true);

		if ( empty($positiveChip) && empty($negativeRate) ) {
			return $commentText;
		}

		$chipsRender = '';
		if ( !empty($positiveChip) && is_array($positiveChip) ) {
			$chipsRender = '<div class="comment-rates positive-rates">';
			$chipsRender .= '<ul>';
			foreach ( $positiveChip as $item ) {
				if ( empty(str_replace(" ", '', $item)) ) {
					continue;
				}
				$chipsRender .= '<li>' . esc_html($item) . '</li>';
			}
			$chipsRender .= '</ul>';
			$chipsRender .= '</div>';
		}

		if ( !empty($negativeRate) && is_array($negativeRate) ) {
			$chipsRender .= '<div class="comment-rates negative-rates">';
			$chipsRender .= '<ul>';
			foreach ( $negativeRate as $item ) {
				if ( empty(str_replace(" ", '', $item)) ) {
					continue;

				}
				$chipsRender .= '<li>' . esc_html($item) . '</li>';
			}
			$chipsRender .= '</ul>';
			$chipsRender .= '</div>';
		}

		if ( !empty($chipsRender) ) {
			$chipsRender = '<div class="comment-rates-outer">' . $chipsRender . '</div>';
		}


		return $commentText . PHP_EOL . $chipsRender;
	}

	/**
	 * @param string      $commentText
	 * @param \WP_Comment $comment
	 * @param array       $args
	 *
	 * @return string
	 */
	public static function displayAttachments (string $commentText, $comment, array $args) : string {
		if ( !($comment instanceof WP_Comment) ) {
			return $commentText;
		}
		$commentAttachments = get_comment_meta($comment->comment_ID, 'comment_attachments', true);

		if ( !empty($commentAttachments) && is_array($commentAttachments) ) {
			$commentAttachmentsHTML = '<ul class="comment-attachments" uk-lightbox>';
			foreach ( $commentAttachments as $commentAttachment ) {
				$attachmentLink = wp_get_attachment_url($commentAttachment);
				if ( empty($attachmentLink) ) {
					continue;
				}

				$commentAttachmentsHTML .= '<li>';
				if ( $comment->comment_approved === "1" || is_admin() ) {
					$commentAttachmentsHTML .= '<a href="' . $attachmentLink . '">';
					$commentAttachmentsHTML .= wp_get_attachment_image($commentAttachment, 'thumbnail');
					$commentAttachmentsHTML .= '</a>';
				}
				if ( is_admin() ) {
					$commentAttachmentsHTML .= '<button type="button" data-comment-id="' . $comment->comment_ID . '" data-attach-id="' . $commentAttachment . '" class="button remove-item">حذف</button>';
				}
				$commentAttachmentsHTML .= '</li>';
			}
			$commentAttachmentsHTML .= '</ul>';


		} else {
			$commentAttachmentsHTML = '';
		}


		return $commentText . PHP_EOL . $commentAttachmentsHTML;
	}

	/**
	 * comment likes count
	 *
	 * @param \WP_Comment $comment
	 *
	 * @return int
	 */
	public static function getCommentLikesCount (WP_Comment $comment) : int {
		$count = get_comment_meta($comment->comment_ID, self::$likesMetaKey, true);

		return is_numeric($count) ? (int) $count : 0;
	}

	/**
	 * comment dislikes count
	 *
	 * @param \WP_Comment $comment
	 *
	 * @return int
	 */
	public static function getCommentDisLikesCount (WP_Comment $comment) : int {
		$count = get_comment_meta($comment->comment_ID, self::$disLikesMetaKey, true);

		return is_numeric($count) ? (int) $count : 0;
	}

	/**
	 * display comment like & dislike buttons
	 *
	 * @param string      $commentText
	 * @param \WP_Comment $comment
	 * @param array       $args
	 *
	 * @return string
	 */
	public static function displayCommentLike (string $commentText, $comment, array $args) : string {
		if ( !($comment instanceof WP_Comment) ) {
			return $commentText;
		}
		if ( is_admin() || getShopOptions('product-comment-likes', '1') !== '1' ) {
			return $commentText;
		}
		$commentLike = '<div class="comment-likes-outer">';
		$commentLike .= '<p class="section-title">' . __('Was this comment helpful to you? ',
				THEME_TEXTDOMAIN) . '</p>';
		$commentLike .= '<button class="like-button like" data-comment="' . $comment->comment_ID . '" type="button" aria-label="' . __('Like comment',
				THEME_TEXTDOMAIN) . '"><i class="far fa-thumbs-up"></i><span>' . __('Like',
				THEME_TEXTDOMAIN) . '</span><b class="count">' . self::getCommentLikesCount($comment) . '</b></button>';
		$commentLike .= '<button class="like-button dislike" data-comment="' . $comment->comment_ID . '" type="button" aria-label="' . __('Dislike comment',
				THEME_TEXTDOMAIN) . '"><i class="far fa-thumbs-down"></i><span>' . __('Dislike',
				THEME_TEXTDOMAIN) . '</span><b class="count">' . self::getCommentDisLikesCount($comment) . '</b></button>';
		$commentLike .= '</div>';

		return $commentText . PHP_EOL . $commentLike;
	}

	/**
	 * upload comment attachments
	 */
	public function uploadAttachments () : void {
		if ( !isset($_FILES['attachments'], $_POST['nonce']) || !is_user_logged_in() || !wp_verify_nonce($_POST['nonce'],
				'ajax-nonce') ) {
			wp_send_json_error([
				'message' => __('You are not allowed to submit requests!', THEME_TEXTDOMAIN)
			], 400);
		}

		if ( !function_exists('wp_handle_upload') ) {
			require_once(ABSPATH . 'wp-admin/includes/image.php');
		}

		$upload_overrides = [
			'test_form' => false,
			'mimes'     => [
				'png'          => 'image/png',
				'jpg|jpeg|jpe' => 'image/jpeg'
			]
		];
		$files            = $_FILES['attachments'];
		if ( !isset($files['size'][0]) || $files['size'][0] > 2000000 ) {
			wp_send_json_error([
				'message' => __('File size is not allowed!', THEME_TEXTDOMAIN)
			], 400);
		}

		if ( isset($files['name'][0]) ) {
			$fileType = explode('.', $files['name'][0]);
			$fileName = 'comment_' . md5($files['name'][0]) . '.' . $fileType[count($fileType) - 1];
			$file     = [
				'name'     => $fileName,
				'type'     => $files['type'][0],
				'tmp_name' => $files['tmp_name'][0],
				'error'    => $files['error'][0],
				'size'     => $files['size'][0]
			];

			$movefile = wp_handle_upload($file, $upload_overrides);

			if ( isset($movefile['url']) ) {
				$insertImage = wp_insert_attachment([
					'guid'           => $movefile['url'],
					'post_mime_type' => $movefile['type'],
					'post_title'     => preg_replace('/\.[^.]+$/', '', $fileName),
					'post_content'   => '',
					'post_status'    => 'inherit'
				], $movefile['file'], 0, true);

				if ( is_numeric($insertImage) && !is_wp_error($insertImage) ) {
					$attach_data = wp_generate_attachment_metadata($insertImage, $movefile['file']);
					wp_update_attachment_metadata($insertImage, $attach_data);
					wp_send_json((int) $insertImage * 2);
				}
			}
		}
		wp_send_json_error([
			'message' => __('An error occurred!', THEME_TEXTDOMAIN)
		], 400);
	}

	/**
	 * remove comment attachment
	 */
	public function removeAttachment () : void {
		$attachID = $_POST['attach'] ?? '0';
		$comment  = $_POST['comment'] ?? '0';
		if ( $attachID !== '0' && $comment !== '0' ) {
			$getCommentAttachments = get_comment_meta($comment, 'comment_attachments', true);
			if ( ($key = array_search($attachID, $getCommentAttachments, false)) !== false ) {
				unset($getCommentAttachments[$key]);
			}
			if ( update_comment_meta($comment, 'comment_attachments', $getCommentAttachments) ) {
				wp_delete_attachment($attachID, true);
				wp_send_json([
					'success' => true,
					'message' => __('Item deleted!', THEME_TEXTDOMAIN)
				]);
			}
		}
		wp_send_json_error(['message' => __('An error occurred!', THEME_TEXTDOMAIN)], 400);
	}

	/**
	 * add comment like
	 *
	 * @param $id
	 *
	 * @return int
	 */
	public function commentAddLike ($id) : int {
		$likes = get_comment_meta($id, self::$likesMetaKey, true);
		if ( empty($likes) ) {
			$likes = 0;
		} else {
			$likes = (int) $likes;
		}
		$likes++;
		update_comment_meta($id, self::$likesMetaKey, $likes);

		return $likes;
	}

	/**
	 * add comment dislike
	 *
	 * @param $id
	 *
	 * @return int
	 */
	public function commentAddDislike ($id) : int {
		$likes = get_comment_meta($id, self::$disLikesMetaKey, true);
		if ( empty($likes) ) {
			$likes = 0;
		} else {
			$likes = (int) $likes;
		}
		$likes++;
		update_comment_meta($id, self::$disLikesMetaKey, $likes);

		return $likes;
	}

	/**
	 * comment rates
	 */
	public function commentRates () : void {
		$result    = [
			'status' => false,
			'count'  => 0
		];
		$commentID = $_POST['commentID'] ?? '';
		$rateType  = $_POST['rateType'] ?? 'like';
		if ( is_numeric($commentID) ) {
			if ( $rateType === "like" ) {
				$count = $this->commentAddLike($commentID);
			} else {
				$count = $this->commentAddDislike($commentID);
			}
			$result['status'] = true;
			$result['count']  = $count;
		}
		wp_send_json($result);
	}

	/**
	 * init all hooks
	 */
	public static function init () : void {
		$class = new self();
		add_filter('comment_form_fields', [$class, 'printInputs']);
		add_action('comment_post', [$class, 'saveMeta']);
		add_filter('comment_text', [self::class, 'displayAttachments'], 10, 3);
		add_filter('comment_text', [self::class, 'displayChips'], 11, 3);
		add_filter('comment_text', [self::class, 'displayCommentLike'], 12, 3);
		add_action('wp_ajax_CommentUploadAttachments', [$class, 'uploadAttachments']);
		add_action('wp_ajax_nopriv_CommentUploadAttachments', [$class, 'uploadAttachments']);
		add_action('wp_ajax_commentRates', [$class, 'commentRates']);
		add_action('wp_ajax_nopriv_commentRates', [$class, 'commentRates']);
		add_action('wp_ajax_CommentRemoveAttachment', [$class, 'removeAttachment']);
	}
}