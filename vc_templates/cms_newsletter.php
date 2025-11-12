<?php
extract(shortcode_atts(array(      
    'form' => '',      
    'title' => '',      
    'description' => '',      
    'layout' => 'layout1',      
    'email_label' => '',      
    'button_label' => '',      
    'el_class' => '',      
), $atts));
$defined_forms = array( 'form_1', 'form_2', 'form_3', 'form_4', 'form_5', 'form_6', 'form_7', 'form_8', 'form_9', 'form_10' );
if(class_exists('Newsletter')) {
    switch ($layout) {
        case 'layout2': ?>
            <div class="job-newsletter box-modern job-newsletter-layout2 <?php echo esc_attr($el_class); ?>">
                <div class="job-newsletter-heading">
                    <h3><?php echo esc_attr( $title ); ?></h3>
                    <p><?php echo wp_kses_post($description); ?></p>
                </div>
                <div class="job-newsletter-form">
                    <?php
                        if ( in_array( $form, $defined_forms ) )
                        {
                            $form = str_replace( 'form_', '', $form );
                            echo do_shortcode( '[newsletter_form form="' . esc_attr( $form ) . '"]' );
                        }
                        else
                        {
                            echo NewsletterSubscription::instance()->get_subscription_form();
                        }
                    ?>
                </div>
            </div>
            <?php break;

        case 'layout3': ?>
            <div class="job-newsletter job-newsletter-layout3 <?php echo esc_attr($el_class); ?>">
                <?php if(!empty($title) || !empty($description)) : ?>
                    <div class="job-newsletter-heading">
                        <h3><?php echo esc_attr( $title ); ?></h3>
                        <p><?php echo wp_kses_post($description); ?></p>
                    </div>
                <?php endif; ?>
                <div class="job-newsletter-inner">
                    <div class="job-newsletter-form">
                        <?php
                            if ( in_array( $form, $defined_forms ) )
                            {
                                $form = str_replace( 'form_', '', $form );
                                echo do_shortcode( '[newsletter_form button_label="'.$button_label.'" form="' . esc_attr( $form ) . '"][newsletter_field name="email" label="'.$email_label.'"][/newsletter_form]' );
                            }
                            else
                            {
                                echo do_shortcode( '[newsletter_form button_label="'.$button_label.'"][newsletter_field name="email" label="'.$email_label.'"][/newsletter_form]' );
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php break;
        
        default: ?>
            <div class="job-newsletter box-modern row job-newsletter-layout1 <?php echo esc_attr($el_class); ?>">
                <div class="job-newsletter-heading col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <h3><?php echo esc_attr( $title ); ?></h3>
                    <p><?php echo wp_kses_post($description); ?></p>
                </div>
                <div class="job-newsletter-form col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?php
                        if ( in_array( $form, $defined_forms ) )
                        {
                            $form = str_replace( 'form_', '', $form );
                            echo do_shortcode( '[newsletter_form form="' . esc_attr( $form ) . '"]' );
                        }
                        else
                        {
                            echo NewsletterSubscription::instance()->get_subscription_form();
                        }
                    ?>
                </div>
            </div>
        <?php break;
    }
} ?>