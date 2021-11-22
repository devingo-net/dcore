<?php
namespace DCore\Walker;

use DCore\Theme;
use Walker_Comment;
use WP_Comment;

/**
 * Class Blog_Comment_Walker
 */

class Blog_Comment_Walker extends Walker_Comment {

    /**
     * Outputs a comment in the HTML5 format.
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function html5_comment( $comment, $depth, $args ) : void {

        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
        <?php
        Theme::getTemplatePart('single/comment-item',[
            'comment'   =>  $comment,
            'depth'     =>  $depth,
            'args'      =>  $args
        ]);

    }
}