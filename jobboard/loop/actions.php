<?php
/**
 * The Template for displaying loop job actions
 *
 * This template can be overridden by copying it to yourtheme/jobboard/loop/actions.php.
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

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$job_listing_style = recruitment_get_opt('job_listing_style', 'job-listing-classic');
if ($job_listing_style == 'job-listing-classic' || $job_listing_style == 'job-listing-modern') { ?>
	<div class="loop-actions col-lg-3 col-md-12 col-sm-12 col-xs-12">
		<?php do_action('jobboard_loop_actions'); ?>
	</div>
<?php } ?>

<?php if ($job_listing_style == 'job-listing-outline') { ?>
	<div class="loop-content"><?php echo wp_trim_words(strip_tags(strip_shortcodes(get_the_content())), 28); ?></div>
	<div class="loop-actions">
		<?php
		if (class_exists('JB_Basket')):
			$j_id = get_the_ID();
			$user_id = get_current_user_id();
			$in_basket = false;
			$post_ids = !empty($_COOKIE['jobboard-basket']) ? $_COOKIE['jobboard-basket'] : array(0);
			if (JB()->job->get_row($user_id, $j_id) || in_array($j_id, $post_ids))
				$in_basket = true;
			?>
			<div class="loop-basket-save">
				<button class="grid-jobs-basket-add" data-id="<?php the_ID(); ?>" <?php if ($in_basket): ?>
						style="display: none;" <?php endif; ?>>
					<i class="fa fa-plus icon"></i>
					<i class="fa fa-spinner jobboard-loading" style="display: none;"></i>
					<span><?php echo esc_html__('Save', 'wp-recruitment'); ?></span>
				</button>
				<button class="grid-jobs-basket-delete" data-id="<?php the_ID(); ?>" <?php if (!$in_basket): ?>
						style="display: none;" <?php endif; ?>>
					<i class="fa fa-minus icon"></i>
					<i class="fa fa-spinner jobboard-loading" style="display: none;"></i>
					<span><?php echo esc_html__('Remove', 'wp-recruitment'); ?></span>
				</button>
			</div>
			<?php
		endif;
		?>
		<a class="loop-view-job"
			href="<?php the_permalink(); ?>"><?php echo esc_html__('View Job', 'wp-recruitment'); ?></a>
	</div>
<?php } ?>