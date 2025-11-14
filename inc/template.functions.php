<?php
/**
 * Get theme option based on its id.
 *
 * @param string $opt_id Required. the option id.
 * @param mixed $default Optional. Default if the option is not found or not yet saved.
 *                         If not set, false will be used
 *
 * @return mixed
 */
function recruitment_get_opt($opt_id, $default = false)
{
    $opt_name = 'opt_theme_options';
    if (empty($opt_name)) {
        return $default;
    }
    global ${$opt_name};
    if (!isset(${$opt_name}) || !isset(${$opt_name}[$opt_id])) {
        $options = get_option($opt_name);
    } else {
        $options = ${$opt_name};
    }
    if (
        !isset($options) ||
        !isset($options[$opt_id]) ||
        $options[$opt_id] === ''
    ) {
        return $default;
    }
    if (is_array($options[$opt_id]) && is_array($default)) {
        foreach ($options[$opt_id] as $key => $value) {
            if (isset($default[$key]) && $value === '') {
                $options[$opt_id][$key] = $default[$key];
            }
        }
    }
    return $options[$opt_id];
}

/**
 * Get Header Layout
 */
function recruitment_header()
{
    global $opt_meta_options;
    $header_layout = recruitment_get_opt('header_layout', 1);

    if (!empty($opt_meta_options['custom_header'])) {
        $header_layout = $opt_meta_options['header_layout'];
    }

    /* load custom header template. */
    get_template_part('inc/header/header', $header_layout);
}

/**
 * Get Header Layout
 */
function recruitment_footer()
{
    global $opt_meta_options;
    $footer_layout = recruitment_get_opt('footer_layout', 1);

    if (!empty($opt_meta_options['custom_footer'])) {
        $footer_layout = $opt_meta_options['footer_layout'];
    }

    /* load custom footer template. */
    get_template_part('inc/footer/footer', $footer_layout);
}

/**
 * Get Theme Logo
 */
function recruitment_header_logo()
{
    $main_logo = recruitment_get_opt('main_logo', [
        'url' => get_template_directory_uri() . '/assets/images/logo.png'
    ]);
    echo '<a href="' . esc_url(home_url('/')) . '"><img alt="' . esc_html__('Logo', 'wp-recruitment') . '" src="' . esc_url($main_logo['url']) . '"></a>';
}

/**
 * Get Logo Footer
 */
function recruitment_logo_footer()
{
    $logo_footer = recruitment_get_opt('logo_footer', [
        'url' => get_template_directory_uri() . '/assets/images/logo-footer.png'
    ]);
    echo esc_url($logo_footer['url']);
}

/**
 * get header class.
 */
function recruitment_header_class($class = '')
{
    $menu_sticky = recruitment_get_opt('menu_sticky', false);

    if ($menu_sticky == '1') {
        $class .= ' sticky-desktop';
    }

    echo esc_attr($class);
}

/**
 * Main navigation.
 */
function recruitment_header_navigation_primary()
{
    global $opt_meta_options;

    /* main nav. */
    if (has_nav_menu('primary')) {
        wp_nav_menu([
            'menu_class' => 'nav-menu menu-main-menu',
            'theme_location' => 'primary',
            'menu' => is_page() && !empty($opt_meta_options['header_menu']) ? $opt_meta_options['header_menu'] : '',
            'walker' => class_exists('HeroMenuWalker') ? new HeroMenuWalker() : '',
        ]);
    } else { ?>
        <div class="new-item-menu"><a
                href="<?php echo get_admin_url(); ?>nav-menus.php"><?php esc_html_e('Add Navigation Menu', 'wp-recruitment'); ?></a>
        </div>
    <?php }
}

/**
 * Main navigation - Left
 */
function recruitment_header_navigation_left()
{
    global $opt_meta_options;

    /* main nav. */
    wp_nav_menu([
        'menu_class' => 'nav-menu menu-main-menu nav-menu-left',
        'theme_location' => 'pr_menu_left',
        'menu' => is_page() && !empty($opt_meta_options['header_menu']) ? $opt_meta_options['header_menu'] : '',
        'walker' => class_exists('HeroMenuWalker') ? new HeroMenuWalker() : '',
    ]);
}

/**
 * Main navigation - Right
 */
function recruitment_header_navigation_right()
{
    global $opt_meta_options;

    /* main nav. */
    wp_nav_menu([
        'menu_class' => 'nav-menu menu-main-menu nav-menu-right',
        'theme_location' => 'pr_menu_right',
        'menu' => is_page() && !empty($opt_meta_options['header_menu']) ? $opt_meta_options['header_menu'] : '',
        'walker' => class_exists('HeroMenuWalker') ? new HeroMenuWalker() : '',
    ]);
}

/**
 * get page title layout
 */
function recruitment_page_title()
{
    if (function_exists('is_jb_profile') && is_jb_profile()) {
        return;
    }

    global $opt_meta_options, $jobboard_options;

    if (class_exists('JB_Map') && function_exists('is_jb_jobs') && is_jb_jobs() && !empty($jobboard_options['page-jobs'])) {
        recruitment_loop_page_content();
        return;
    }

    $job_single_feature = recruitment_get_opt('job_single_feature', 'default');
    $bg_page_title = recruitment_get_opt('bg_page_title', [
        'url' => get_template_directory_uri() . '/assets/images/bg-page-title.jpg'
    ]);
    if (function_exists('is_jb_job') && is_jb_job()) {
        if ($job_single_feature == 'job-feature' && has_post_thumbnail()) {
            $bg_page_title['url'] = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
        recruitment_single_job_page_title($bg_page_title['url']);
        return;
    }

    $post_layout = recruitment_get_opt('post_layout', 'layout1');
    $page_title_layout = recruitment_get_opt('page_title_layout', '1');
    $page_title_align = recruitment_get_opt('page_title_align', 'center');


    if (isset($opt_meta_options['post_layout_custom']) && $opt_meta_options['post_layout_custom'] != 'themeoption') {
        $post_layout = $opt_meta_options['post_layout_custom'];
    }

    if (is_page() && !empty($opt_meta_options['custom_page_title'])) {
        $page_title_layout = $opt_meta_options['page_title_layout'];
    }

    /* Title Align */
    if (is_page() && !empty($opt_meta_options['page_pagetitle_align'])) {
        $page_title_align = $opt_meta_options['page_pagetitle_align'];
    }

    /* BG image */
    if (is_page() && !empty($opt_meta_options['page_bg_page_title']['url'])) {
        $bg_page_title['url'] = $opt_meta_options['page_bg_page_title']['url'];
    }

    /* Custom layout from page. */
    if (!is_post_type_archive('jb-events') && !is_post_type_archive('jb-resources') && !is_singular('jb-events')) {
        if ($page_title_layout != '') {
            switch ($page_title_layout) {
                case '1':
                    if ($post_layout != 'layout2') { ?>
                        <div id="cms-page-title" class="page-title text-<?php echo esc_attr($page_title_align); ?>"
                            style="background-image: url(<?php echo esc_url($bg_page_title['url']); ?>)">
                            <div class="container">
                                <div class="row">
                                    <div class="cms-page-title-inner col-md-12">
                                        <h1><?php recruitment_get_page_title(); ?></h1>
                                        <?php recruitment_page_sub_title(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    break;
            }
        }
    }
}

/**
 * Get sub page title.
 *
 * @author CMSSuperHeroes Team
 */
function recruitment_page_sub_title()
{
    global $opt_meta_options; ?>
    <?php if (!empty($opt_meta_options['page_title_sub'])) { ?>
        <span class="subtitle"> <?php echo esc_attr($opt_meta_options['page_title_sub']); ?></span>
    <?php } elseif (!empty($opt_meta_options['post_subtitle'])) { ?>
        <span class="subtitle"> <?php echo esc_attr($opt_meta_options['post_subtitle']); ?></span>
    <?php }
}

/**
 * page title
 */
function recruitment_get_page_title()
{

    global $opt_meta_options;

    if (!is_archive()) {
        /* page. */
        if (is_page()):
            if (function_exists('is_jb_endpoint_url') && is_jb_endpoint_url()) {
                jb_page_title();
            } elseif (!empty($opt_meta_options['page_title_text'])) {
                echo esc_html($opt_meta_options['page_title_text']);
            } else {
                the_title();
            }
        elseif (is_front_page()):
            esc_html_e('Blog', 'wp-recruitment');
            /* search */
        elseif (is_search()):
            printf(esc_html__('Search Results for: %s', 'wp-recruitment'), '<span>' . get_search_query() . '</span>');
            /* 404 */
        elseif (is_404()):
            esc_html_e('404', 'wp-recruitment');
            /* other */
        else:
            the_title();
        endif;
    } else {
        /* category. */
        if (is_category()):
            single_cat_title();
        elseif (is_tag()):
            /* tag. */
            single_tag_title();
            /* author. */
        elseif (is_author()):
            printf(esc_html__('Author: %s', 'wp-recruitment'), '<span class="vcard">' . get_the_author() . '</span>');
            /* date */
        elseif (is_day()):
            printf(esc_html__('Day: %s', 'wp-recruitment'), '<span>' . get_the_date() . '</span>');
        elseif (is_month()):
            printf(esc_html__('Month: %s', 'wp-recruitment'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'wp-recruitment')) . '</span>');
        elseif (is_year()):
            printf(esc_html__('Year: %s', 'wp-recruitment'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'wp-recruitment')) . '</span>');
            /* post format */
        elseif (is_tax('post_format', 'post-format-aside')):
            esc_html_e('Asides', 'wp-recruitment');
        elseif (is_tax('post_format', 'post-format-gallery')):
            esc_html_e('Galleries', 'wp-recruitment');
        elseif (is_tax('post_format', 'post-format-image')):
            esc_html_e('Images', 'wp-recruitment');
        elseif (is_tax('post_format', 'post-format-video')):
            esc_html_e('Videos', 'wp-recruitment');
        elseif (is_tax('post_format', 'post-format-quote')):
            esc_html_e('Quotes', 'wp-recruitment');
        elseif (is_tax('post_format', 'post-format-link')):
            esc_html_e('Links', 'wp-recruitment');
        elseif (is_tax('post_format', 'post-format-status')):
            esc_html_e('Statuses', 'wp-recruitment');
        elseif (is_tax('post_format', 'post-format-audio')):
            esc_html_e('Audios', 'wp-recruitment');
        elseif (is_tax('post_format', 'post-format-chat')):
            esc_html_e('Chats', 'wp-recruitment');
            /* woocommerce */
        elseif (function_exists('is_woocommerce') && is_woocommerce()):
            woocommerce_page_title();
        elseif (function_exists('is_jb_taxonomy') && is_jb_taxonomy()):
            jb_the_page_title();
        elseif (function_exists('is_jb_search') && is_jb_search()):
            jb_the_page_title();
        else:
            if (is_post_type_archive('jobboard-post-jobs')) {
                echo get_the_title(jb_page_id('jobs'));
            } else {
                the_title();
            }
        endif;
    }
}

/**
 * Breadcrumb
 *
 * @since Recruitment 1.0.9
 */
function recruitment_get_bread_crumb($separator = '')
{
    global $post;

    echo '<ul class="breadcrumbs">';
    $params['link_none'] = '';

    /* category */
    if (is_category()) {
        $category = get_the_category();
        $ID = $category[0]->cat_ID;
        echo is_wp_error($cat_parents = get_category_parents($ID, true, '', false)) ? '' : '<li>' . wp_kses_post($cat_parents) . '</li>';
    }
    /* tax */
    if (is_tax()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $link = get_term_link($term);

        if (is_wp_error($link)) {
            echo sprintf('<li>%s</li>', $term->name);
        } else {
            echo sprintf('<li><a href="%s" title="%s">%s</a></li>', $link, $term->name, $term->name);
        }
    }
    /* home */

    /* page not front_page */
    if (is_page() && !is_front_page()) {
        $parents = array();
        $parent_id = $post->post_parent;
        while ($parent_id):
            $page = get_post($parent_id);
            if ($params["link_none"]) {
                $parents[] = get_the_title($page->ID);
            } else {
                $parents[] = '<li><a href="' . esc_url(get_permalink($page->ID)) . '" title="' . esc_attr(get_the_title($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a></li>' . $separator;
            }
            $parent_id = $page->post_parent;
        endwhile;
        $parents = array_reverse($parents);
        echo join('', $parents);
        echo '<li>' . esc_html(get_the_title()) . '</li>';
    }
    /* single */
    if (is_single()) {
        $categories_1 = get_the_category($post->ID);
        if ($categories_1):
            foreach ($categories_1 as $cat_1):
                $cat_1_ids[] = $cat_1->term_id;
            endforeach;
            $cat_1_line = implode(',', $cat_1_ids);
        endif;
        if (isset($cat_1_line) && $cat_1_line) {
            $categories = get_categories(array(
                'include' => $cat_1_line,
                'orderby' => 'id'
            ));
            if ($categories):
                foreach ($categories as $cat):
                    $cats[] = '<li><a href="' . esc_url(get_category_link($cat->term_id)) . '" title="' . esc_attr($cat->name) . '">' . esc_html($cat->name) . '</a></li>';
                endforeach;
                echo join('', $cats);
            endif;
        }
        echo '<li>' . get_the_title() . '</li>';
    }
    /* tag */
    if (is_tag()) {
        echo '<li>' . "Tag: " . single_tag_title('', false) . '</li>';
    }
    /* search */
    if (is_search()) {
        echo '<li>' . esc_html__("Search", 'wp-recruitment') . '</li>';
    }
    /* date */
    if (is_year()) {
        echo '<li>' . get_the_time('Y') . '</li>';
    }
    /* 404 */
    if (is_404()) {
        echo '<li>' . esc_html__("404 - Page not Found", 'wp-recruitment') . '</li>';
    }
    /* archive */
    if (is_archive() && is_post_type_archive()) {
        $title = post_type_archive_title('', false);
        echo '<li>' . esc_html($title) . '</li>';
    }
    echo "</ul>";
}

/**
 * Display an optional post detail.
 */
function recruitment_post_detail()
{
    ?>
    <ul class="post-details">
        <li class="detail-author">
            <?php echo get_avatar(get_the_author_meta('ID'), 'full'); ?>     <?php the_author_posts_link(); ?>
        </li>
        <li class="detail-comment"><a href="<?php the_permalink(); ?>"><i
                    class="zmdi zmdi-comment-more"></i><?php echo comments_number('0', '1', '%'); ?><?php esc_html_e(' Comments', 'wp-recruitment'); ?>
            </a></li>
        <li class="detail-date"><a
                href="<?php echo get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>"><i
                    class="zmdi zmdi-calendar-alt"></i><?php the_date(); ?></a></li>

        <?php if (has_category()): ?>
            <li class="detail-terms"><?php the_terms(get_the_ID(), 'category', '', ', '); ?></li>
        <?php endif; ?>

        <?php if (is_sticky()): ?>
            <li class="detail-sticky"><i class='fa fa-thumb-tack'></i><?php echo esc_html__('Sticky', 'wp-recruitment'); ?></li>
        <?php endif; ?>
    </ul>
    <?php
}
function recruitment_post_detail_l2()
{
    ?>
    <ul class="entry-details">
        <li class="detail-author">
            <?php echo get_avatar(get_the_author_meta('ID'), 'full'); ?>     <?php the_author_posts_link(); ?>
        </li>
        <li class="detail-date">
            <a href="<?php echo get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')); ?>">
                <i class="zmdi zmdi-calendar-alt"></i>
                <?php the_date(); ?>
            </a>
        </li>
        <?php if (has_category()): ?>
            <li class="detail-terms"><?php the_terms(get_the_ID(), 'category', '', ', '); ?></li>
        <?php endif; ?>
    </ul>
    <?php
}

/**
 * Display an optional post video.
 */
function recruitment_post_video()
{

    global $opt_meta_options, $wp_embed;

    /* no video. */
    if (empty($opt_meta_options['opt-video-type'])) {
        recruitment_post_thumbnail();

        return;
    }

    if ($opt_meta_options['opt-video-type'] == 'local' && !empty($opt_meta_options['otp-video-local']['id'])) {

        $video = wp_get_attachment_metadata($opt_meta_options['otp-video-local']['id']);

        echo do_shortcode('[video width="' . esc_attr($opt_meta_options['otp-video-local']['width']) . '" height="' . esc_attr($opt_meta_options['otp-video-local']['height']) . '" ' . $video['fileformat'] . '="' . esc_url($opt_meta_options['otp-video-local']['url']) . '" poster="' . esc_url($opt_meta_options['otp-video-thumb']['url']) . '"][/video]');

    } elseif ($opt_meta_options['opt-video-type'] == 'youtube' && !empty($opt_meta_options['opt-video-youtube'])) {

        echo do_shortcode($wp_embed->run_shortcode('[embed]' . esc_url($opt_meta_options['opt-video-youtube']) . '[/embed]'));

    } elseif ($opt_meta_options['opt-video-type'] == 'vimeo' && !empty($opt_meta_options['opt-video-vimeo'])) {

        echo do_shortcode($wp_embed->run_shortcode('[embed]' . esc_url($opt_meta_options['opt-video-vimeo']) . '[/embed]'));

    }
}

/**
 * Display an optional post audio.
 */
function recruitment_post_audio()
{
    global $opt_meta_options;

    /* no audio. */
    if (empty($opt_meta_options['otp-audio']['id'])) {
        recruitment_post_thumbnail();

        return;
    }

    $audio = wp_get_attachment_metadata($opt_meta_options['otp-audio']['id']);

    echo do_shortcode('[audio ' . $audio['fileformat'] . '="' . esc_url($opt_meta_options['otp-audio']['url']) . '"][/audio]');
}

/**
 * Display an optional post gallery.
 */
function recruitment_post_gallery()
{
    global $opt_meta_options;

    /* no audio. */
    if (empty($opt_meta_options['opt-gallery'])) {
        recruitment_post_thumbnail();

        return;
    }

    $array_id = explode(",", $opt_meta_options['opt-gallery']);

    ?>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php $i = 0; ?>
            <?php foreach ($array_id as $image_id): ?>
                <?php
                $attachment_image = wp_get_attachment_image_src($image_id, 'full', false);
                if ($attachment_image[0] != ''): ?>
                    <div class="item <?php if ($i == 0) {
                        echo 'active';
                    } ?>">
                        <img style="width:100%;" data-src="holder.js" src="<?php echo esc_url($attachment_image[0]); ?>" />
                    </div>
                    <?php $i++; endif; ?>
            <?php endforeach; ?>
        </div>
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="fa fa-angle-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="fa fa-angle-right"></span>
        </a>
    </div>
    <?php
}

/**
 * Display an optional post quote.
 */
function recruitment_post_quote()
{
    global $opt_meta_options;

    if (empty($opt_meta_options['opt-quote-content'])) {
        recruitment_post_thumbnail();

        return;
    }

    $opt_meta_options['opt-quote-title'] = !empty($opt_meta_options['opt-quote-title']) ? '<span>' . esc_html($opt_meta_options['opt-quote-title']) . '</span>' : '';

    echo '<blockquote>' . esc_html($opt_meta_options['opt-quote-content']) . wp_kses_post($opt_meta_options['opt-quote-title']) . '</blockquote>';
}

/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index
 * views, or a div element when on single views.
 */
/**
 * Date: 6.12.2018
 * Editer: TuanNA
 */
function recruitment_post_thumbnail()
{
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }
    $size = 'recruitment_blog_size1';

    // if (has_post_thumbnail() && !post_password_required() && !is_attachment() && wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
    //     $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false);
    //     $thumbnail = get_the_post_thumbnail(get_the_ID(), $size);
    // else:
    //     $thumbnail_url[0] = get_template_directory_uri() . '/assets/images/no-image.jpg';
    //     $thumbnail = '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/no-image.jpg') . '" alt="' . get_the_title() . '" />';
    // endif;
    $thumbnail = the_post_thumbnail($size);
    echo '<div class="post-thumbnail">' . $thumbnail . '</div>';
}

function recruitment_post_details_thumbnail()
{
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }
    $size = 'recruitment_blog_size1';

    if (has_post_thumbnail() && !post_password_required() && !is_attachment() && wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false);
        $thumbnail = get_the_post_thumbnail(get_the_ID(), $size);
    else:
        $thumbnail_url[0] = get_template_directory_uri() . '/assets/images/no-image.jpg';
        $thumbnail = '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/no-image.jpg') . '" alt="' . get_the_title() . '" />';
    endif;
    echo '<div class="post-thumbnail">' . $thumbnail . '</div>';
}

function recruitment_post_details_thumbnail_full()
{
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }
    $size = 'full';

    if (has_post_thumbnail() && !post_password_required() && !is_attachment() && wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false);
        $thumbnail = get_the_post_thumbnail(get_the_ID(), $size);
    else:
        $thumbnail_url[0] = get_template_directory_uri() . '/assets/images/no-image.jpg';
        $thumbnail = '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/no-image.jpg') . '" alt="' . get_the_title() . '" />';
    endif;
    echo '<div class="post-thumbnail">' . $thumbnail . '</div>';
}

function recruitment_post_thumbnail_2column()
{
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }
    $size = 'recruitment_blog_size2';

    if (has_post_thumbnail() && !post_password_required() && !is_attachment() && wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false)):
        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size, false);
        $thumbnail = get_the_post_thumbnail(get_the_ID(), $size);
    else:
        $thumbnail_url[0] = get_template_directory_uri() . '/assets/images/no-image.jpg';
        $thumbnail = '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/no-image.jpg') . '" alt="' . get_the_title() . '" />';
    endif;
    echo '<div class="post-thumbnail">' . $thumbnail . '</div>';
}

function recruitment_footer_top()
{
    $footer_top_column = intval(recruitment_get_opt('footer-top-column', 4));

    /* Footer Top */
    if ($footer_top_column <= 0) {
        return;
    }

    $_class = "";

    switch ($footer_top_column) {
        case 2:
            $_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
            break;
        case 3:
            $_class = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
            break;
        case 4:
            $_class = 'col-lg-3 col-md-3 col-sm-6 col-xs-12';
            break;
        case 6:
            $_class = 'col-lg-2 col-md-2 col-sm-6 col-xs-12';
            break;
    }

    for ($i = 1; $i <= $footer_top_column; $i++) {
        if (is_active_sidebar('sidebar-footer-top-' . $i)) {
            echo '<div class="cms-footer-top-item text-center-xs text-center-sm ' . esc_html($_class) . '">';
            dynamic_sidebar('sidebar-footer-top-' . $i);
            echo "</div>";
        }
    }
}

/**
 * Footer - Back To Top
 **/
function recruitment_back_to_top()
{
    $footer_button_back_to_top = recruitment_get_opt('footer_button_back_to_top', '1');
    if ($footer_button_back_to_top == '1') { ?>
        <div id="back_to_top" class="back_to_top"><span
                class="go_up"><?php echo esc_html__('Back to Top', 'wp-recruitment'); ?><i
                    class="fa fa-chevron-circle-up"></i></span></div>
    <?php }
}

/* Archive - Feature Post */
function recruitment_feature_post()
{
    global $post;

    wp_register_script('owl-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', ['jquery'], '1.0', true);
    wp_register_script('owl-carousel-cms', get_template_directory_uri() . '/assets/js/owl.carousel.cms.js', ['owl-carousel'], '1.0', true);
    wp_enqueue_style('owl-carousel-cms', get_template_directory_uri() . '/assets/css/owl.carousel.css');
    wp_enqueue_script('owl-carousel');
    $cms_carousel['blog-feature-wrap'] = array(
        'margin' => 0,
        'loop' => 'true',
        'mouseDrag' => 'true',
        'nav' => 'true',
        'dots' => 'false',
        'autoplay' => 'false',
        'autoplayTimeout' => 2000,
        'smartSpeed' => 1200,
        'autoplayHoverPause' => 'false',
        'navText' => array(
            '<i class="zmdi zmdi-caret-left-circle"></i>',
            '<i class="zmdi zmdi-caret-right-circle"></i>'
        ),
        'responsive' => array(
            0 => array(
                "items" => 1,
            ),
            768 => array(
                "items" => 1,
            ),
            992 => array(
                "items" => 1,
            ),
            1200 => array(
                "items" => 1,
            )
        )
    );
    wp_localize_script('owl-carousel-cms', "cmscarousel", $cms_carousel);
    wp_enqueue_script('owl-carousel-cms');

    $query = new WP_Query(array('posts_per_page' => 1, 'post_type' => 'post', 'post_status' => 'future')); ?>
    <div id="blog-feature-wrap" class="cms-carousel">
        <?php if ($query->have_posts()) {
            while ($query->have_posts()):
                $query->the_post();
                if (has_post_thumbnail($post->ID)) {
                    $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                } else {
                    $thumbnail = '' . get_template_directory_uri() . '/assets/images/no-image.jpg';
                }
                ?>

                <div class="blog-feature-inner" style="background-image: url(<?php echo esc_url($thumbnail); ?>);">
                    <span class="top-feature"><?php echo esc_html__('Featured', 'wp-recruitment'); ?></span>
                    <div class="entry-content">
                        <div class="entry-date"><?php the_date(); ?></div>
                        <?php the_title('<h1 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h1>'); ?>
                    </div>
                </div>
            <?php endwhile;
        } ?>
    </div>
    <?php wp_reset_postdata();
}

/**
 * List socials share for post.
 *
 * @since Recruitment 1.0.9
 */
function recruitment_get_socials_share()
{
    ?>
    <ul class="post-social-shared">
        <li><span><?php esc_html_e('Share', "wp-recruitment"); ?></span></li>
        <li><a title="Facebook" data-placement="top" data-rel="tooltip" target="_blank"
                href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><span class="share-box"><i
                        class="fa fa-facebook"></i></span></a></li>
        <li><a title="Twitter" data-placement="top" data-rel="tooltip" target="_blank"
                href="https://twitter.com/home?status=<?php esc_html_e('Check out this article', "wp-recruitment"); ?>:%20<?php the_title(); ?>%20-%20<?php the_permalink(); ?>"><span
                    class="share-box"><i class="fa fa-twitter"></i></span></a></li>
        <li><a title="Google Plus" data-placement="top" data-rel="tooltip" target="_blank"
                href="https://plus.google.com/share?url=<?php the_permalink(); ?>"><span class="share-box"><i
                        class="fa fa-google-plus"></i></span></a></li>
    </ul>
    <?php
}

/**
 * List socials share for post.
 *
 * @since Recruitment 1.0.9
 */
function recruitment_get_socials_share_job()
{
    ?>
    <ul>
        <li><a title="Facebook" data-placement="top" data-rel="tooltip" target="_blank"
                href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><span class="share-box"><i
                        class="fa  fa-facebook-square"></i></span></a></li>
        <li>
            <a title="LinkedIn" data-placement="top" data-rel="tooltip" target="_blank"
                href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>">
                <span class="share-box">
                    <i class="fa fa-linkedin-square"></i>
                </span>
            </a>
        </li>
        <li><a title="Twitter" data-placement="top" data-rel="tooltip" target="_blank"
                href="https://twitter.com/home?status=<?php esc_html_e('Check out this article', "wp-recruitment"); ?>:%20<?php the_title(); ?>%20-%20<?php the_permalink(); ?>"><span
                    class="share-box"><i class="fa fa-twitter-square"></i></span></a></li>
        <li><a title="Google Plus" data-placement="top" data-rel="tooltip" target="_blank"
                href="https://plus.google.com/share?url=<?php the_permalink(); ?>"><span class="share-box"><i
                        class="fa fa-google-plus-square"></i></span></a></li>
        <?php
        $top_bar_email = recruitment_get_opt('top_bar_email', '');
        if (!empty($top_bar_email)) {
            ?>
            <li>
                <a
                    href="mailto:<?php echo wp_kses_post($top_bar_email); ?>?subject=<?php echo get_the_title(); ?>&amp;body=Check out this site <?php echo esc_url(get_permalink()); ?>">
                    <i class="fa fa-envelope-square"></i>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php
}

/*
 * Top Social
 * 
 */
function recruitment_top_social()
{
    $cms_top_social = recruitment_get_opt('top_bar_social', [
        'enabled' => array(
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'linkedin' => 'inkedin',
            'google' => 'Google',
        ),
    ]);
    ?>
    <ul class="social-top">
        <?php
        if (is_array($cms_top_social) && isset($cms_top_social['enabled']) && is_array($cms_top_social['enabled'])):
            $social_facebook_url = recruitment_get_opt('social_facebook_url', '#');
            $social_twitter_url = recruitment_get_opt('social_twitter_url', '#');
            $social_inkedin_url = recruitment_get_opt('social_inkedin_url', '#');
            $social_rss_url = recruitment_get_opt('social_rss_url', '#');
            $social_instagram_url = recruitment_get_opt('social_instagram_url', '#');
            $social_google_url = recruitment_get_opt('social_google_url', '#');
            $social_skype_url = recruitment_get_opt('social_skype_url', '#');
            $social_pinterest_url = recruitment_get_opt('social_pinterest_url', '#');
            $social_vimeo_url = recruitment_get_opt('social_vimeo_url', '#');
            $social_youtube_url = recruitment_get_opt('social_youtube_url', '#');
            $social_yelp_url = recruitment_get_opt('social_yelp_url', '#');
            $social_tumblr_url = recruitment_get_opt('social_tumblr_url', '#');
            foreach ($cms_top_social as $key => $value) {
                switch ($key) {

                    case 'facebook':
                        echo '<li><a href="' . esc_url($social_facebook_url) . '"><i class="fa fa-facebook"></i></a></li>';
                        break;

                    case 'twitter':
                        echo '<li><a href="' . esc_url($social_twitter_url) . '"><i class="fa fa-twitter"></i></a></li>';
                        break;

                    case 'linkedin':
                        echo '<li><a href="' . esc_url($social_inkedin_url) . '"><i class="fa fa-linkedin"></i></a></li>';
                        break;

                    case 'rss':
                        echo '<li><a href="' . esc_url($social_rss_url) . '"><i class="fa fa-rss"></i></a></li>';
                        break;

                    case 'instagram':
                        echo '<li><a href="' . esc_url($social_instagram_url) . '"><i class="fa fa-instagram"></i></a></li>';
                        break;

                    case 'google':
                        echo '<li><a href="' . esc_url($social_google_url) . '"><i class="fa fa-google-plus"></i></a></li>';
                        break;

                    case 'skype':
                        echo '<li><a href="' . esc_url($social_skype_url) . '"><i class="fa fa-skype"></i></a></li>';
                        break;

                    case 'pinterest':
                        echo '<li><a href="' . esc_url($social_pinterest_url) . '"><i class="fa fa-pinterest"></i></a></li>';
                        break;

                    case 'vimeo':
                        echo '<li><a href="' . esc_url($social_vimeo_url) . '"><i class="fa fa-vimeo"></i></a></li>';
                        break;

                    case 'youtube':
                        echo '<li><a href="' . esc_url($social_youtube_url) . '"><i class="fa fa-youtube"></i></a></li>';
                        break;

                    case 'yelp':
                        echo '<li><a href="' . esc_url($social_yelp_url) . '"><i class="fa fa-yelp"></i></a></li>';
                        break;

                    case 'tumblr':
                        echo '<li><a href="' . esc_url($social_tumblr_url) . '"><i class="fa fa-tumblr"></i></a></li>';
                        break;

                }
            }
        endif;
        ?>
    </ul>
    <?php
}

/* Page Call To Action */

function recruitment_page_cta()
{
    global $opt_meta_options;
    $post_layout = recruitment_get_opt('post_layout', 'layout1');
    $show_call_to_action = recruitment_get_opt('show_call_to_action', false);
    $cta_title = recruitment_get_opt('cta_title', '');
    $cta_desc = recruitment_get_opt('cta_desc', '');
    $cta_button_url = recruitment_get_opt('cta_button_url', '');
    $cta_button_text = recruitment_get_opt('cta_button_text', '');

    if (isset($opt_meta_options['post_layout_custom']) && $opt_meta_options['post_layout_custom'] != 'themeoption') {
        $post_layout = $opt_meta_options['post_layout_custom'];
    }

    if (!empty($opt_meta_options['page_call_to_action'])) {

        $show_call_to_action = $opt_meta_options['page_show_call_to_action'];

        if (!empty($opt_meta_options['page_cta_title'])) {
            $cta_title = $opt_meta_options['page_cta_title'];
        }

        if (!empty($opt_meta_options['page_cta_decs'])) {
            $cta_desc = $opt_meta_options['page_cta_decs'];
        }

        if (!empty($opt_meta_options['page_cta_button_url'])) {
            $cta_button_url = $opt_meta_options['page_cta_button_url'];
        }

        if (!empty($opt_meta_options['page_cta_button_text'])) {
            $cta_button_text = $opt_meta_options['page_cta_button_text'];
        }

    }

    if ($show_call_to_action == '1') { ?>
        <?php if ($post_layout != 'layout2') { ?>
            <div class="page-cta-wrap">
                <div class="row">
                    <div class="container">
                        <div class="col-lg-12">
                            <h3 class="cta-title"><?php echo esc_html($cta_title); ?></h3>
                            <div class="cta-desc"><?php echo esc_html($cta_desc); ?></div>
                            <a href="<?php echo esc_url($cta_button_url); ?>"
                                class="cta-more btn btn-outline-white size-large btn-lg"><?php echo esc_html($cta_button_text); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php }
}

function cms_page_full_width()
{
    global $opt_meta_options;

    $page_full_width = '';
    /* chect content full width */
    if (is_page() && isset($opt_meta_options['page_full_width']) && $opt_meta_options['page_full_width']) {
        /* full width */
        $page_full_width = "no-container";
    } else {
        /* boxed */
        $page_full_width = "container";
    }

    echo apply_filters('cms_page_full_width', $page_full_width);
}

/* Blog Layout */
function recruitment_blog_sidebar()
{
    global $opt_meta_options;
    $blog_sidebar = recruitment_get_opt('blog_sidebar', 'right-sidebar');

    if (is_page() && !empty($opt_meta_options['enable_sidebar'])) {
        $blog_sidebar = $opt_meta_options['page_sidebar'];
    }

    return 'is-' . esc_attr($blog_sidebar);
}

function recruitment_blog_class()
{
    global $opt_meta_options;
    $blog_sidebar = recruitment_get_opt('blog_sidebar', 'right-sidebar');

    $_class = "col-xs-12 col-sm-8 col-md-9 col-lg-9";

    if (is_page() && !empty($opt_meta_options['enable_sidebar'])) {
        $blog_sidebar = $opt_meta_options['page_sidebar'];
    }

    if ($blog_sidebar == 'no-sidebar') {
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-12 content-full-width";
    }

    echo esc_attr($_class);
}

function recruitment_single_post_sidebar()
{
    $single_sidebar = recruitment_get_opt('single_sidebar', 'right-sidebar');
    return 'is-' . esc_attr($single_sidebar);
}

function recruitment_single_post_class()
{
    $single_sidebar = recruitment_get_opt('single_sidebar', 'right-sidebar');
    $_class = "col-xs-12 col-sm-8 col-md-9 col-lg-9";
    if ($single_sidebar == 'no-sidebar') {
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-12 content-full-width";
    }

    echo esc_attr($_class);
}

/* Page Layout */

function recruitment_page_sidebar()
{
    global $opt_meta_options;

    $_sidebar = 'right-sidebar';

    if (isset($opt_meta_options['page_sidebar'])) {
        $_sidebar = $opt_meta_options['page_sidebar'];
    }

    return 'is-' . esc_attr($_sidebar);
}

function recruitment_page_class()
{
    global $opt_meta_options;

    $_class = "col-xs-12 col-sm-8 col-md-9 col-lg-9";

    if (isset($opt_meta_options['page_sidebar']) && $opt_meta_options['page_sidebar'] == 'no-sidebar') {
        $_class = "col-xs-12 col-sm-12 col-md-12 col-lg-12";
    }

    echo esc_attr($_class);
}

function recruitment_post_comment()
{
    $post_comment = recruitment_get_opt('post_comment', 'show');
    if ($post_comment == 'show') {
        if (comments_open() || get_comments_number()):
            comments_template();
        endif;
    }
}

/**
 * Loading.
 *
 * @author CMSSuperHeroes Team
 */
function recruitment_get_page_loading()
{
    if (recruitment_get_opt('page_loadding', false) == '1') {
        echo '<div id="cms-loadding"><div class="cms-loader"></div></div>';
    }
}

function recruitment_side()
{ ?>
    <div class="cms-side-options hide">
        <a href="#" class="cms-side-item"><i class="zmdi zmdi-shopping-cart-plus"></i>
            <span class="cms-side-hover"><?php echo esc_html__('Purchase', 'wp-recruitment'); ?>
    </div>
    </a>
    </div>
<?php }

/**
 * Social Team.
 *
 * @author Socail Team
 */

function recruitment_social_team()
{
    global $opt_meta_options;

    if (isset($opt_meta_options['team_link1']) && isset($opt_meta_options['team_icon1'])) { ?>
        <div class="social-item">
            <a class="<?php echo esc_attr($opt_meta_options['team_icon1']); ?>"
                href="<?php echo esc_url($opt_meta_options['team_link1']); ?>"></a>
        </div>
    <?php }
    if (!empty($opt_meta_options['team_link2']) && !empty($opt_meta_options['team_icon2'])) { ?>
        <div class="social-item">
            <a class="<?php echo esc_attr($opt_meta_options['team_icon2']); ?>"
                href="<?php echo esc_url($opt_meta_options['team_link2']); ?>"></a>
        </div>
    <?php }
    if (!empty($opt_meta_options['team_link3']) && !empty($opt_meta_options['team_icon3'])) { ?>
        <div class="social-item">
            <a class="<?php echo esc_attr($opt_meta_options['team_icon3']); ?>"
                href="<?php echo esc_url($opt_meta_options['team_link3']); ?>"></a>
        </div>
    <?php }
    if (!empty($opt_meta_options['team_link4']) && !empty($opt_meta_options['team_icon4'])) { ?>
        <div class="social-item">
            <a class="<?php echo esc_attr($opt_meta_options['team_icon4']); ?>"
                href="<?php echo esc_url($opt_meta_options['team_link4']); ?>"></a>
        </div>
    <?php }
}

add_filter('body_class', 'recruitment_body_class');
function recruitment_body_class($classes)
{
    $classes[] = recruitment_get_opt('job_listing_style', 'job-listing-classic');

    $single_job_layout = '';
    $single_job_layout = (isset($_GET['single-job-layout'])) ? trim($_GET['single-job-layout']) : recruitment_get_opt('single_job_layout', 'single-job-classic');
    if ($single_job_layout == 'single-job-classic') {
        $classes[] = 'single-job-classic';
    } else {
        $classes[] = 'single-job-modern';
    }

    return $classes;
}

/**
 * Generate unique id with specific length
 *
 * @since  1.0
 * 
 * @param  integer $length Default to 6
 * @return string
 */
function recruitment_generate_uniqueid($length = 6)
{
    return substr(md5(microtime()), rand(0, 26), $length);
}

add_filter('cpt_dev_mode', 'recruitment_cpt_dev_mode');
if (!function_exists('recruitment_cpt_dev_mode')) {
    function recruitment_cpt_dev_mode()
    {
        return defined('DEV_MODE') && DEV_MODE == true;
    }
}

/*
 *  Dashboard Configurations
 */
if (!function_exists('recruitment_cpt_dashboard_config')) {
    add_filter('cpt_dashboard_config', 'recruitment_cpt_dashboard_config');
    function recruitment_cpt_dashboard_config()
    {
        return [
            'documentation_link' => 'https://cmssuperheroes.gitbook.io/recruitment-wordpress-theme/',
            'ticket_link' => 'https://cmssuperheroes.ticksy.com/',
            'video_tutorial_link' => 'https://www.youtube.com/c/CMSSuperheroes',
            'demo_link' => 'http://demo.cmssuperheroes.com/themeforest/wp-recruitment/',
        ];
    }
}