<?php
/**
 * The Template for displaying select.
 *
 * This template can be overridden by copying it to yourtheme/jobboard/fields/fields/select.php.
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
?>
<?php
    $editable = isset($editable)?$editable:true;
    if($editable):
?>
<select id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($type); ?>" data-placeholder="<?php echo esc_attr($placeholder); ?>"<?php jb_multiple($multi); ?>>

    <option value></option>

    <?php foreach ($options as $k => $v): ?>

    <?php if(is_array($v)): ?>

    <optgroup label="<?php echo esc_attr($k); ?>">

        <?php foreach ($v as $_k => $_v): ?>

        <option value="<?php echo esc_attr($_k); ?>"<?php jb_selected($value, $_k); ?>><?php echo esc_html($_v); ?></option>

        <?php endforeach; ?>

    </optgroup>

    <?php else: ?>

    <option value="<?php echo esc_attr($k); ?>"<?php jb_selected($value, $k); ?>><?php echo esc_html($v); ?></option>

    <?php endif; ?>

    <?php endforeach; ?>

</select>
<?php
    else:
        if(!is_array($value)){
            $value = array($value);
        }
        foreach ($options as $k => $v):
            if(is_array($v)):
                foreach ($v as $_k => $_v):
                    if(in_array($_k, $value)):
?>
                <span class="field-value-label">
                    <?php
                        echo esc_html($_v);
                    ?>
                </span>
<?php
                    endif;
                endforeach;
            elseif(in_array($k, $value)):
                ?>
                    <span class="field-value-label">
                        <?php
                        echo esc_html($v);
                        ?>
                    </span>
                <?php
            endif;
        endforeach;
    endif;
?>
