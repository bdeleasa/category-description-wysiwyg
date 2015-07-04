<?php
/**
 * Plugin Name: Category Description WYSIWYG
 * Description: Adds a TinyMCE WYSIWYG editor to the category description fields.
 * Author: Brianna Deleasa
 * Author URI: http://briannadeleasa.com
 * Version: 1.0
 * License: GPL2
 */

// remove the html filtering
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );


add_filter( 'edit_category_form_fields', 'cat_description' );
/**
 * Outputs the new taxonomy description field as a TinyMCE
 * editor instead of a plain textarea.
 *
 * @param $tag
 * @return none
 */
function cat_description($tag) {
    ?>
    <table class="form-table">
        <tr class="form-field">
            <th scope="row" valign="top"><label for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
            <td>
                <?php
                $settings = array(
                    'wpautop' => true,
                    'media_buttons' => true,
                    'quicktags' => true,
                    'textarea_rows' => '15',
                    'textarea_name' => 'description'
                );

                // We need to use html_entity_decode on the content before wp_editor,
                // otherwise TinyMCEwill convert all of our HTML tags into HTML entities
                // and you'll actually see the HTML tags in the WYSIWYG editor as text.
                $content = html_entity_decode( $tag->description );

                wp_editor( wp_kses_post( $content, ENT_QUOTES, 'UTF-8'), 'cat_description', $settings);
                ?>
                <br />
                <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
            </td>
        </tr>
    </table>
<?php
}


add_action('admin_head', 'remove_default_category_description');
/**
 * Use jQuery to remove the old description field.
 *
 * @param none
 * @return none
 */
function remove_default_category_description() {

    global $current_screen;

    if ( $current_screen->id == 'edit-category' )
    {
        ?>
        <script type="text/javascript">
            jQuery(function($) {
                $('textarea#description').closest('tr.form-field').remove();
            });
        </script>
    <?php
    }

}