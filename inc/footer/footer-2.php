<?php
/**
 * @name : Default Header
 * @package : CMSSuperHeroes
 * @author : Fox
 */
global $opt_meta_options;
$footer_custom_width_column = recruitment_get_opt('footer_custom_width_column', '1');
$footer_top_column = recruitment_get_opt('footer-top-column', 4);
$cms_copyright = recruitment_get_opt('cms-copyright', '');
$payment_logo = recruitment_get_opt('payment_logo', []);
?>
<footer id="colophon" class="site-footer cms-footer2 <?php if ($footer_custom_width_column == '1') {
    echo 'width_column_change';
} ?>">
    <?php if (is_active_sidebar('sidebar-footer-top-1')): ?>
        <div id="cms-footer-top" class="footer-column<?php echo esc_attr($footer_top_column); ?>">
            <div class="container <?php if (!empty($opt_meta_options['custom_footer'] && !empty($opt_meta_options['footer_full_width']))) {
                echo 'footer-full-width';
            } ?>">
                <div class="row">
                    <?php recruitment_footer_top(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div id="cms-footer-bottom">
        <div class="no-container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 text-center-xs text-center-sm">
                    <div class="cms-copyright">
                        <?php if (is_array($payment_logo) && count($payment_logo) > 0) { ?>
                            <span class="cms-footer-payment">
                                <?php
                                foreach ($payment_logo as $payment) {
                                    if (!is_array($payment) || !isset($payment['url'], $payment['image']))
                                        continue;
                                    ?>
                                    <a href="<?php echo esc_url($payment['url']); ?>">
                                        <img src="<?php echo esc_url($payment['image']); ?>"
                                            alt="<?php echo esc_attr($payment['title']); ?>" />
                                    </a>
                                    <?php
                                }
                                ?>
                            </span>
                        <?php } ?>
                        <?php if (!empty($cms_copyright)) {
                            echo wp_kses_post($cms_copyright);
                        } else { ?>
                            <div>© <?php echo date("Y"); ?>. Theme by <a
                                    href="https://themeforest.net/user/cmssuperheroes/portfolio"><?php echo esc_html('CMSSuperheroes'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (is_active_sidebar('footer_bottom_right')): ?>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 text-right text-center-xs text-center-sm">
                        <div class="cms-footer-bottom-col">
                            <?php dynamic_sidebar('footer_bottom_right'); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</footer><!-- .site-footer -->