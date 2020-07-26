<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Post_Submitter
 * @subpackage Post_Submitter/public/partials
 */
?>

<div class="clear"></div>
<?php if ( $post_info['is_logged_in'] === false ): ?>
    <div class="alert-message">
        <div class="alert">
            <span id="closebtn" class="closebtn">×</span>
			<?php echo esc_html__( 'You are not authrorized to view the form', 'post-submitter' ); ?>
        </div>
    </div>
<?php else: ?>
    <div id="post" class="container post">
        <div class="row">
            <h2><?php echo esc_html__( 'Submit Post', 'post-submitter' ); ?></h2>
            <div class="post-form">
                <form name="post" id="post-form" action="" method="post" enctype="multipart/form-data">
                    <label for="post_title"><?php echo esc_html__( 'Post Title', 'post-submitter' ); ?></label>
                    <input type="text" maxlength="65535" id="post_title" name="post_title"
                           placeholder="<?php echo esc_html__( 'Enter post title', 'post-submitter' ); ?>">

                    <label for="post_title"><?php echo esc_html__( 'Custom Post Types', 'post-submitter' ); ?></label>
					<?php echo $post_info['select']; ?>

                    <label for="description"><?php echo esc_html__( 'Description', 'post-submitter' ); ?></label>
                    <textarea id="description" name="description"
                              placeholder="<?php echo esc_html__( 'Enter description', 'post-submitter' ); ?>"
                              style="height:200px"></textarea>

                    <label for="excerpt"><?php echo esc_html__( 'Excerpt', 'post-submitter' ); ?></label>
                    <textarea maxlength="65535" id="excerpt" name="excerpt"
                              placeholder="<?php echo esc_html__( 'Enter excerpt', 'post-submitter' ); ?>"
                              style="height:100px"></textarea>
                    <label for="featured-image"><?php echo esc_html__( 'Featured Image', 'post-submitter' ); ?></label>
                    <input id="file" type="file" name="featured_image" accept="image/*"/>


                    <input id="submit" class="btn" type="submit" value="Submit"/>

                    <div id="loading">
                        <img src="<?php echo POST_SUBMITTER_PLUGIN_URL ?>public/images/loading.gif">
                    </div>
                    <div id="notify"></div>

					<?php //honeypot ?>
                    <input id="field" type="text" name="field">
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
