<?php
/**
 * @name : Default Header
 * @package : CMSSuperHeroes
 * @author : Fox
 */

$footer_custom_width_column = recruitment_get_opt('footer_custom_width_column', '1');
$footer_top_column = recruitment_get_opt('footer-top-column', 4);
$cms_copyright = recruitment_get_opt('cms-copyright', '');
?>
<footer id="colophon" class="site-footer cms-footer1 <?php if ($footer_custom_width_column == '1') {
    echo 'width_column_change';
} ?>">
    <?php if (is_active_sidebar('sidebar-footer-top-1')): ?>
        <div id="cms-footer-top" class="footer-column<?php echo esc_attr($footer_top_column); ?>">
            <div class="container">
                <div class="row">
                    <?php recruitment_footer_top(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div id="cms-footer-bottom">
        <div class="no-container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center-xs">
                    <div class="logo-footer">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php recruitment_logo_footer(); ?>"
                                alt="Logo Footer" /></a>
                    </div>
                    <div class="cms-copyright">
                        <?php if (!empty($cms_copyright)) {
                            echo wp_kses_post($cms_copyright);
                        } else { ?>
                            <div>© <?php echo date("Y"); ?>. Theme by <a
                                    href="https://themeforest.net/user/cmssuperheroes/portfolio"><?php echo esc_html('CMSSuperheroes'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right text-center-xs">
                    <div class="cms-footer-backtop">
                        <?php recruitment_back_to_top(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer><!-- .site-footer -->