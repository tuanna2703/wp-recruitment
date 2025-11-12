<?php 
    /* get categories */
        $taxo = 'category';
        $_category = array();
        if(!isset($atts['cat']) || $atts['cat']==''){
            $terms = get_terms($taxo);
            foreach ($terms as $cat){
                $_category[] = $cat->term_id;
            }
        } else {
            $_category  = explode(',', $atts['cat']);
        }
        $atts['categories'] = $_category;
?>
<div class="cms-grid-wraper cms-grid-blog1 cms-latest-news-layout2 <?php echo esc_attr($atts['template']);?>" id="<?php echo esc_attr($atts['html_id']);?>">
    <?php if($atts['filter']=="true" and $atts['layout']=='masonry'):?>
        <div class="cms-grid-filter">
            <ul class="cms-filter-category list-unstyled list-inline">
                <li><a class="active" href="#" data-group="all">All</a></li>
                <?php foreach($atts['categories'] as $category):?>
                    <?php $term = get_term( $category, $taxo );?>
                    <li><a href="#" data-group="<?php echo esc_attr('category-'.$term->slug);?>">
                            <?php echo esc_html($term->name);?>
                        </a>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endif;?>
    <div class="row <?php echo esc_attr($atts['grid_class']);?>">
        <?php
        $posts = $atts['posts'];
        $size = ($atts['layout']=='basic')?'thumbnail':'medium'; ?>
        <?php while($posts->have_posts()){
            $posts->the_post();
            $groups = array();
            $groups[] = '"all"';
            foreach(cmsGetCategoriesByPostID(get_the_ID(),$taxo) as $category){
                $groups[] = '"category-'.$category->slug.'"';
            }
            ?>
            <div class="cms-grid-item <?php echo esc_attr($atts['item_class']);?>" data-groups='[<?php echo implode(',', $groups);?>]'>
                <div class="cms-latestnews-item-inner">
                    <div class="cms-latestnews-holder">
                        <div class="cms-latestnews-terms"><?php the_terms( get_the_ID(), 'category', '', ', ' ); ?></div>
                        <div class="cms-latestnews-meta">
                            <h3 class="cms-latestnews-title">
                                <a title="<?php echo get_the_title(get_the_ID()); ?>" href="<?php echo get_permalink(get_the_ID()); ?>"><?php echo get_the_title(get_the_ID()); ?></a>
                            </h3>
                            <div class="cms-latestnews-date"><?php echo get_the_date(); ?></div>
                        </div>
                        <a class="cms-latestnews-more" title="<?php echo get_the_title(get_the_ID()); ?>" href="<?php echo get_permalink(get_the_ID()); ?>"><?php echo esc_html__('Read Full Story', 'wp-recruitment'); ?><i class="zmdi zmdi-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <?php } ?>
    </div>
</div>