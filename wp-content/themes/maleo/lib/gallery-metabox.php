<?php 
/*
 * Thanks to Easy Gallery Metabox Plugin
 * http://wordpress.org/plugins/easy-image-gallery/
 */

function ps_image_gallery_add_meta_box() {
        add_meta_box( 'prodigystudio_post_gallery', __('Post Gallery Images', 'prodigystudio' ), 'ps_image_gallery_metabox', 'post', 'normal', 'high' );       
        add_meta_box( 'prodigystudio_portfolio_gallery', __('if Gallery Images', 'prodigystudio' ), 'ps_image_gallery_metabox', 'portfolio', 'normal', 'high' );
}
    add_action( 'add_meta_boxes', 'ps_image_gallery_add_meta_box' );

function ps_image_gallery_metabox() {
        global $post; ?>

    <div id="gallery_images_container" class="ps_image_gallery">
        <ul class="gallery_images">
            <?php
            $image_gallery = get_post_meta( $post->ID, '_ps_image_gallery', true );
            $attachments = array_filter( explode( ',', $image_gallery ) );
             if ( $attachments )
            foreach ( $attachments as $attachment_id ) {
                echo '<li class="image attachment details" data-attachment_id="' . $attachment_id . '"><div class="attachment-preview"><div class="thumbnail">
                            ' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '</div>
                            <a href="#" class="delete check" title="' . __( 'Remove image', 'prodigystudio' ) . '"><div class="media-modal-icon"></div></a>
                        </div></li>';
        }
?>
        </ul>
        <input type="hidden" id="image_gallery" name="image_gallery" value="<?php echo esc_attr( $image_gallery ); ?>" />
        <?php wp_nonce_field( 'ps_image_gallery', 'ps_image_gallery' ); ?>

    </div>

    <p class="add_gallery_images hide-if-no-js">
        <input type="button" class="ps_gallery_btn button" value="<?php _e( 'Add images', 'prodigystudio' ); ?>">
    </p>
    
  <?php /* Props to WooCommerce for the following JS code */ ?>

    <script type="text/javascript">
        jQuery(document).ready(function($){

              // Uploading files
            var image_gallery_frame;
            var $image_gallery_ids = $('#image_gallery');
            var $gallery_images = $('#gallery_images_container ul.gallery_images');

            jQuery('.add_gallery_images').on( 'click', '.ps_gallery_btn', function( event ) {

                var $el = $(this);
                var attachment_ids = $image_gallery_ids.val();

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( image_gallery_frame ) {
                    image_gallery_frame.open();
                    return;
                }

                // Create the media frame.
                image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                    // Set the title of the modal.
                    title: '<?php _e( 'Add Images to Gallery', 'prodigystudio' ); ?>',
                    button: {
                        text: '<?php _e( 'Add to gallery', 'prodigystudio' ); ?>',
                    },
                    multiple: true
                });

                // When an image is selected, run a callback.
                image_gallery_frame.on( 'select', function() {

                    var selection = image_gallery_frame.state().get('selection');

                    selection.map( function( attachment ) {

                        attachment = attachment.toJSON();

                        if ( attachment.id ) {
                            attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

                             $gallery_images.append('\
                                <li class="image attachment details" data-attachment_id="' + attachment.id + '">\
                                    <div class="attachment-preview">\
                                        <div class="thumbnail">\
                                            <img src="' + attachment.url + '" />\
                                        </div>\
                                       <a href="#" class="delete check" title="<?php _e( 'Remove image', 'prodigystudio' ); ?>"><div class="media-modal-icon"></div></a>\
                                    </div>\
                                </li>');

                        }

                    } );

                    $image_gallery_ids.val( attachment_ids );
                });

                // Finally, open the modal.
                image_gallery_frame.open();
            });

            // Image ordering
            $gallery_images.sortable({
                items: 'li.image',
                cursor: 'move',
                scrollSensitivity:40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: 'eig-metabox-sortable-placeholder',
                start:function(event,ui){
                    ui.item.css('background-color','#f6f6f6');
                },
                stop:function(event,ui){
                    ui.item.removeAttr('style');
                },
                update: function(event, ui) {
                    var attachment_ids = '';

                    $('#gallery_images_container ul li.image').css('cursor','default').each(function() {
                        var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                        attachment_ids = attachment_ids + attachment_id + ',';
                    });

                    $image_gallery_ids.val( attachment_ids );
                }
            });

            // Remove images
            $('#gallery_images_container').on( 'click', 'a.delete', function() {

                $(this).closest('li.image').remove();

                var attachment_ids = '';

                $('#gallery_images_container ul li.image').css('cursor','default').each(function() {
                    var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $image_gallery_ids.val( attachment_ids );

                return false;
            } );

        });
    </script>

    <?php }
function ps_image_gallery_save_post( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    $post_types = array ('page', 'post');

    // check user permissions
    if ( isset( $_POST[ 'post_type' ] ) && !array_key_exists( $_POST[ 'post_type' ], $post_types ) ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
    }
    else {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    }

    if ( ! isset( $_POST[ 'ps_image_gallery' ] ) || ! wp_verify_nonce( $_POST[ 'ps_image_gallery' ], 'ps_image_gallery' ) )
        return;

    if ( isset( $_POST[ 'image_gallery' ] ) && !empty( $_POST[ 'image_gallery' ] ) ) {

        $attachment_ids = sanitize_text_field( $_POST['image_gallery'] );

        // turn comma separated values into array
        $attachment_ids = explode( ',', $attachment_ids );

        // clean the array
        $attachment_ids = array_filter( $attachment_ids  );

        // return back to comma separated list with no trailing comma. This is common when deleting the images
        $attachment_ids =  implode( ',', $attachment_ids );

        update_post_meta( $post_id, '_ps_image_gallery', $attachment_ids );
    } else {
        delete_post_meta( $post_id, '_ps_image_gallery' );
    }

    do_action( 'ps_image_gallery_save_post', $post_id );
}
add_action( 'save_post', 'ps_image_gallery_save_post' );

