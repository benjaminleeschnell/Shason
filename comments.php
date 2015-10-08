<?php
/**
 * @package WordPress
 * @subpackage Reales
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>

        <h2 class="comments-title">
            <?php echo number_format_i18n(get_comments_number()) . ' ' . __('Comments', 'reales'); ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                    'style'       => 'ol',
                    'callback'    => 'reales_comment',
                    'short_ping'  => true,
                ) );
            ?>
        </ol>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
        <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
            <div class="nav-previous pull-left"><?php previous_comments_link( '<span class="fa fa-angle-left"></span> ' . __( 'Older Comments', 'reales' ) ); ?></div>
            <div class="nav-next pull-right"><?php next_comments_link( __( 'Newer Comments' . ' <span class="fa fa-angle-right"></span>', 'reales' ) ); ?></div>
            <div class="clearfix"></div>
        </nav>
        <?php endif; ?>

        <?php if ( ! comments_open() ) : ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'reales' ); ?></p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $required_text = '  ';

    $args = array(
        'id_form'           => 'commentform',
        'id_submit'         => 'submit',
        'title_reply'       => __( 'Leave a Reply','reales' ),
        'title_reply_to'    => __( 'Leave a Reply to %s','reales' ),
        'cancel_reply_link' => __( 'Cancel Reply','reales' ),
        'label_submit'      => __( 'Post Comment','reales' ),

        'comment_notes_before' => '<p class="comment-notes">' .
            __( 'Your email address will not be published.  ', 'reales' ) . ( $req ? esc_html($required_text) : '' ) .
        '</p>',

        'comment_field' =>  '<div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'.
            '<div class="form-group">'.
                '<textarea id="comment" class="form-control" name="comment" rows="5" aria-required="true" placeholder="'. __( 'Comment', 'reales' ) .'"></textarea>' .
            '</div>'.
        '</div></div>',

        'fields' => apply_filters( 'comment_form_default_fields', array(
            'author' => '<div class="row"><div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">' .
                '<div class="form-group">'.
                    '<input id="author" name="author" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . '  placeholder="' . __( 'Name', 'reales' ) . '"/>'.
                '</div>'.
            '</div>',

            'email' => '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">' .
                '<div class="form-group">'.
                    '<input id="email" name="email" type="text" class="form-control"  value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' placeholder="' . __( 'Email', 'reales' ) . '" />'.
                '</div>'.
            '</div></div>',

            'url' => '<div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'.
                '<div class="form-group">'.
                    '<input id="url" name="url" type="text" class="form-control"  value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="'. __( 'Website', 'reales' ) .'"/>'.
                '</div>'.
            '</div></div>'
        ) )
    );

    comment_form($args); ?>

</div>
