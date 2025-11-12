<?php if(class_exists('JobBoard')) {
    $i = 0 ; $num = $content->post_count - 1;
    $specialism = get_terms(array('taxonomy' => 'jobboard-tax-specialisms', 'hide_empty' => true, 'number' => 4));
    $html_id = cmsHtmlID('cms-recent-jobs');
    $atts['html_id'] = $html_id;
    if($layout == 1) { ?>
        <div id="<?php echo esc_attr($atts['html_id']);?>" class="jb-carousel-wrap cms-recent-jobs <?php echo esc_html($atts['job_style']); ?> <?php echo esc_html($atts['custom_class']); ?>">
            <div class="jb-carousel-meta">
                <h3><?php echo esc_html($atts['title']); ?></h3>
                <ul class="jb-carousel-filter">
                    <li><a href="#"><?php echo esc_html__('All', 'wp-recruitment'); ?></a></li>
                    <?php foreach ($specialism as $term ): ?>
                        <li>
                            <a href="#jobboard-tax-specialisms-<?php echo esc_attr($term->slug); ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div id="cms-jobs-wrapper">
                <div class="jb-jobs-body jobs-carousel">
                    <?php while ($content->have_posts()) : $content->the_post(); ?>

                        <?php if($i % 4 == 0){ echo "<div class='jb-carousel-items'>"; } ?>

                        <?php 
                            if(class_exists('JobBoard')) {
                                jb_get_template_part( 'content', 'jobs' ); 
                            }
                        ?>

                        <?php if($i % 4 == 3 || $i == $num){ echo "</div>"; } ?>

                    <?php $i++; endwhile; ?>

                    <?php wp_reset_postdata(); ?>
                </div>
                <div class="jb-jobs-footer clearfix">
                    <a class="btn view-all-job" href="<?php echo jb_page_permalink('jobs'); ?>"><?php echo esc_html__('All Jobs', 'wp-recruitment'); ?></a>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div id="<?php echo esc_attr($atts['html_id']);?>" class="cms-jb-carousel-layout2 <?php echo esc_html($atts['custom_class']); ?>">
            <div class="cms-jb-carousel-head">
                <h3>
                    <?php echo esc_html($atts['title']); ?>
                </h3>
            </div>
            <?php while ($content->have_posts()) : $content->the_post(); ?>

                <?php if($i % 4 == 0){ echo "<div class='jb-carousel-items'>"; } ?>
                    
                <?php if(class_exists('JobBoard')) { ?>
                    <div class="cms-jb-item">
                        <div class="loop-holder">
                            <h3 class="loop-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <?php
                            if(class_exists('JB_Deadline')):
                                $deadline = get_post_meta(get_the_ID(), '_deadline', true);
                                $days = 0;
                                $text = '';
                                if(!empty($deadline)) {
                                    $deadline = strtotime($deadline);
                                    $today = strtotime(date("Y-m-d H:i:s"));
                                    $secs = $deadline - $today;
                                    $days = (float)$secs / 86400;
                                }
                                $ending_time = (int) jb_get_option('ending-time-notification');
                                if($ending_time > 0 && $days > 0){
                                    $text = $days <= $ending_time ? 'Ending Soon' : '';
                                }
                                elseif(!empty($deadline) && $days <= 0){
                                    $text = 'Expired';
                                }
                                if(!empty($text)):
                            ?>
                            <div class="btn-ending">
                                <span><?php echo esc_html($text); ?></span>
                            </div>
                            <?php
                                endif;
                            endif;
                            ?>
                        </div>
                        <div class="loop-meta">
                            <?php if (!$hide_location): ?>
                                <div class="loop-locations"><?php jb_job_the_locations(); ?></div>
                            <?php endif; ?>
                            <?php if (!$hide_salary): ?>
                                <div class="loop-salary"><?php jb_job_the_salary(); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="loop-type"><?php jb_job_the_types(); ?></div>
                        <div class="loop-content"><?php echo wp_trim_words(strip_tags(strip_shortcodes(get_the_content())),28); ?></div>
                        <div class="loop-actions">
                            <?php
                            if(class_exists('JB_Basket')):
                                $j_id = get_the_ID();
                                $user_id = get_current_user_id();
                                $in_basket = false;
                                $post_ids = !empty($_COOKIE['jobboard-basket']) ? $_COOKIE['jobboard-basket'] : array(0);
                                if(JB()->job->get_row( $user_id, $j_id ) || in_array($j_id, $post_ids))
                                    $in_basket = true;
                            ?>
                            <div class="loop-basket-save">
                                <button class="grid-jobs-basket-add" data-id="<?php the_ID(); ?>" <?php if($in_basket): ?> style="display: none;" <?php endif; ?> >
                                    <i class="fa fa-plus icon"></i>
                                    <i class="fa fa-spinner jobboard-loading" style="display: none;"></i>
                                    <span
                                    ><?php echo esc_html__('Save', 'wp-recruitment'); ?></span>
                                </button>
                                <button class="grid-jobs-basket-delete" data-id="<?php the_ID(); ?>" <?php if(!$in_basket): ?> style="display: none;" <?php endif; ?> >
                                    <i class="fa fa-minus icon"></i>
                                    <i class="fa fa-spinner jobboard-loading" style="display: none;"></i>
                                    <span><?php echo esc_html__('Remove', 'wp-recruitment'); ?></span>
                                </button>
                            </div>
                            <?php
                            endif;
                            ?>
                            <a class="loop-view-job" href="<?php the_permalink(); ?>"><?php echo esc_html__('View Job', 'wp-recruitment'); ?></a>
                        </div>
                    </div>
                <?php } ?>

                <?php if($i % 4 == 3 || $i == $num){ echo "</div>"; } ?>

            <?php $i++; endwhile; ?>

            <?php wp_reset_postdata(); ?>
        </div>
    <?php } ?>
<?php } ?>