<?php
$types = get_terms('jobboard-tax-types');
$arr_type = array(
    __('Select Job Type', 'wp-recruitment') => '',
);
if(!is_wp_error($types)){
    foreach ($types as $type){
        $arr_type[$type->name] = $type->term_id;
    }
}
vc_map(array(
    "name" => esc_html__('Jobs List', "wp-recruitment"),
    "base" => "cms_jobs_grid",
    "icon" => "cs_icon_for_vc",
    "category" => esc_html__('JobBoard', "wp-recruitment"),
    "params" => array(
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Layout", "wp-recruitment"),
            "param_name"    => "layout",
            "admin_label"   => true,
            "value"         => array(
                esc_html__("Layout 1", 'wp-recruitment') => "layout1",
                esc_html__("Layout 2", 'wp-recruitment') => "layout2",
            )
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Hide Location", "wp-recruitment"),
            "param_name"    => "hide_location",
            "admin_label"   => true,
            "value"         => array(
                esc_html__("Yes", 'wp-recruitment') => "1",
                esc_html__("No", 'wp-recruitment') => "",
            ),
            "default" => '',
            "dependency" => array(
                'element' => 'layout',
                'value' => array(
                    'layout2'
                )
            )
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Hide Salary", "wp-recruitment"),
            "param_name"    => "hide_salary",
            "admin_label"   => true,
            "value"         => array(
                esc_html__("Yes", 'wp-recruitment') => "1",
                esc_html__("No", 'wp-recruitment') => "",
            ),
            "default" => '',
            "dependency" => array(
                'element' => 'layout',
                'value' => array(
                    'layout2'
                )
            )
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__('Title', "wp-recruitment"),
            "param_name"    => "title",
            "admin_label"   => true,
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Job Types", "wp-recruitment"),
            "param_name"    => "job_type",
            "admin_label"   => true,
            "value"         => $arr_type,
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Query Type", "wp-recruitment"),
            "param_name"    => "type",
            "admin_label"   => true,
            "value"         => array(
                esc_html__("Recent", 'wp-recruitment') => "recent",
                esc_html__("Featured", 'wp-recruitment') => "featured",
                esc_html__("Interest", 'wp-recruitment') => "interest"
            )
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__('Query Limit', "wp-recruitment"),
            "param_name"    => "limit",
            "std"           => 12,
            "description"   => esc_html__('Limit jobs in query.', "wp-recruitment")
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__('Custom Class', "wp-recruitment"),
            "param_name"    => "custom_class",
            "admin_label"   => true,
        ),
    )
));

class WPBakeryShortCode_cms_jobs_grid extends CmsShortCode
{

    protected function content($atts, $content = null)
    {

        $atts = shortcode_atts(array(
            'title'         => '',
            'layout'     => 'layout1',
            'hide_salary' => '',
            'hide_location' => '',
            'custom_class'  => '',
            'job_type' => '',
            'type'          => 'recent',
            'limit'         => 12,
            'items'         => 1,
            'class'         => '',
        ),$atts);

        $query = array(
            'post_type'     => 'jobboard-post-jobs',
            'post_status'   => 'publish',
            'posts_per_page'=> $atts['limit']
        );

        if($atts['type'] == 'featured'){
            $query['meta_query'] = array(
                array(
                    'key'     => '_featured',
                    'value'   => '1'
                )
            );
        } elseif ($atts['type'] == 'interest' && function_exists('jb_similar')){
            $query = jb_similar()->similar($query);
        }

        if(!empty($atts['job_type'])){
            $query['tax_query'] = array(
                array(
                    'taxonomy' => 'jobboard-tax-types',
                    'field' => 'id',
                    'terms' => $atts['job_type'],
                )
            );
        }

        $content = new WP_Query($query);

        if(!is_wp_error($content) && !empty($content)) {
            return parent::content($atts, $content);
        }
    }
}