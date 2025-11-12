<?php
/**
 * The Template for displaying media upload file.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/fields/fields/media.php.
 *
 * HOWEVER, on occasion JobBoard will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		FOX
 * @package 	JobBoard/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if(!is_array($value)){
    $attachment_id = (int) $value;
    $value = array('id' => $attachment_id);
}
$editable = isset($editable)?$editable:true;
$attachment = false;
if(!empty($value['id'])){
    $attachment = get_post($value['id']);
}
?>

<div class="file type-<?php echo esc_attr($input); ?>">
    <?php if($attachment): ?>
        <div class="file-thumb" title="<?php echo esc_attr(sprintf(esc_attr__('Uploaded : %s', 'wp-recruitment'), $attachment->post_date)); ?>">
            <?php if(strpos($attachment->post_mime_type, 'image') !== false){ ?>
                <img src="<?php echo esc_url(wp_get_attachment_thumb_url($attachment->ID)); ?>" alt="<?php echo esc_attr($attachment->post_title); ?>">
            <?php } else { ?>
                <i class="<?php echo esc_attr(jb_get_file_icon($attachment->post_mime_type)); ?>"></i>
            <?php } ?>
        </div>
        <div class="file-info">
            <span class="file-name"><?php echo esc_html($attachment->post_title); ?></span>
            <?php if($editable): ?>
            <span class="file-date"><?php echo sprintf(esc_html__('Uploaded %s', 'wp-recruitment'), jb_get_timeago(strtotime($attachment->post_date))); ?></span>
            <?php endif; ?>
        </div>
    <?php elseif($input == 'image'): ?>
        <div class="file-thumb"><?php jb_the_placeholder_image('100x100'); ?></div>
        <div class="file-info">
            <span class="file-name"><?php esc_html_e('No image !', 'wp-recruitment'); ?></span>
        </div>
    <?php else:?>
        <div class="file-thumb"><i class="fa fa-file-o" aria-hidden="true"></i></div>
        <div class="file-info">
            <span class="file-name"><?php esc_html_e('No file !', 'wp-recruitment'); ?></span>
        </div>
    <?php endif;?>
</div>
<?php
if($editable):
?>
<input id="<?php echo esc_attr($id); ?>" type="file" class="field-media" name="<?php echo esc_attr($name); ?>" data-type="<?php echo esc_attr($types); ?>" data-type-notice="<?php echo sprintf(esc_attr__('File format not supported, you can only upload the following "%s" file.', 'wp-recruitment'), $types); ?>" data-size="<?php echo esc_attr($size); ?>" data-size-notice="<?php echo sprintf(esc_attr__('You can not upload files larger than %sKb', 'wp-recruitment'), $size); ?>">
<button type="button" class="file-select"><?php echo esc_html($button); ?></button>
<?php
elseif($attachment && $input != 'image'):
?>
    <a href="<?php echo esc_url(wp_get_attachment_url($attachment->ID)); ?>" target="_blank" class="button download btn file-select">
        <i class="fa fa-download"></i>
        <?php esc_html_e( 'Download', 'wp-recruitment' ) ?>
    </a>
<?php
endif;
?>