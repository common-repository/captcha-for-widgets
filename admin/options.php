<!-- START RECAPTCHA FOR WIDGETS SETTINGS FORM -->
<?php 
global $cfw_options;

$errors = array();
$fields = array();

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
    $from = $_POST['cfw_options']['from'];
    $errors = array();
    if($from == 'recaptcha') {
        $public_key     = $_POST['cfw_options']['captcha_public_key'];
        $private_key    = $_POST['cfw_options']['captcha_private_key'];

        if( empty($public_key) ) {
            $errors[] = __('The reCAPTCHA public key is mandatory.', 'cfw');
            $fields[] = 'captcha_public_key';
        }

        if( empty($private_key) ) {
            $errors[] = __('The reCAPTCHA private key is mandatory.', 'cfw');
            $fields[] = 'captcha_private_key';
        }
    } else if ($from == 'realperson') {
        $realperson_length     = $_POST['cfw_options']['realperson_length'];
        $realperson_regenerate    = $_POST['cfw_options']['realperson_regenerate'];

        if( empty($realperson_length) ) {
            $errors[] = __('The captcha\'s length field is empty.', 'cfw');
            $fields[] = 'realperson_length';
        } else if ( !empty($realperson_length) && !is_numeric($realperson_length) )
        {
            $errors[] = __('The captcha\'s length must be a number.', 'cfw');
            $fields[] = 'realperson_length';
        }

        if( empty($realperson_regenerate) ) {
            $errors[] = __('The string for captcha refresh is empty..', 'cfw');
            $fields[] = 'realperson_regenerate';
        }
    }
    
    if(empty($errors)) { 
        delete_option('cfw_options');
        add_option('cfw_options', $_POST['cfw_options']);
        $cfw_options = get_option('cfw_options');
        
    }
}

?>
<div class="wrap">
    <h2><?php _e('CAPTCHA for Widgets Settings', 'cfw');?></h2>
    
    <div class="tabs-holder">
        <div class="tab" id="tab_recaptcha"><?php _e('reCAPTCHA');?></div>
    
        <div class="tab" id="tab_realperson"><?php _e('Realperson captcha');?></div>
    </div>
    
    <div class="clear"><!-- --></div>
    <div class="cfw_general_options">
        <div id='errors' class='errors' <?php if(empty($errors)):?> style='display:none;'<?php else:?> style='display:block;'<?php endif;?>><?php echo implode('<br />', $errors);?></div>
        <form action='' method='POST'>
            <div id="cfw_tab_content"><!-- --></div>
            <table>                
                <tr>
                    <td><label for="captcha_location"><?php _e('Place CAPTCHA on', 'cfw');?></label></td>
                    <td>
                        <input type="radio" name="cfw_options[captcha_location]" id='captcha_location_all' value='<?php echo CFW_LOC_FULL_SITE;?>' <?php echo ( ( !isset($cfw_options['captcha_location']) || $cfw_options['captcha_location'] == CFW_LOC_FULL_SITE ? 'checked="checked"' : '' ));?> /> <label for='captcha_location_all'><?php _e('each form in the site', 'cfw');?></label> <span class='description'>(<?php _e('<strong>Recommended</strong> - automatically excluded forms with id="searchform" or id="adminbarsearch". Use reCAPTCHA if you are sure you have only one form per page. If possible multiple forms per page use Realperson.', 'cfw');?>)</span><br />
                        <input type="radio" name="cfw_options[captcha_location]" id='captcha_location_some_pages' value='<?php echo CFW_LOC_SOME_PAGES;?>' <?php echo ( isset($cfw_options['captcha_location']) && $cfw_options['captcha_location'] == CFW_LOC_SOME_PAGES ? 'checked="checked"' : '' );?>/> <label for='captcha_location_some_pages'><?php _e('each form on certain pages in the site', 'cfw');?></label> <span class='description'>(<?php _e('You can select the pages on which captcha will be added to present forms.', 'cfw');?>)</span><br />
                        <input type="radio" name="cfw_options[captcha_location]" id='captcha_location_some_forms' value='<?php echo CFW_LOC_SOME_FORMS;?>' <?php echo ( isset($cfw_options['captcha_location']) &&  $cfw_options['captcha_location'] == CFW_LOC_SOME_FORMS ? 'checked="checked"' : '' );?> /> <label for='captcha_location_some_forms'><?php _e('certain forms in the site', 'cfw');?></label> <span class='description'>(<?php _e('Recommended for developers. You have the possibility to define your own jQuery selector for the <form /> tag element. ', 'cfw');?>)</span>
                    </td>
                </tr>
                <tr id='pages_options' <?php if( isset($cfw_options['captcha_location']) && $cfw_options['captcha_location'] == CFW_LOC_SOME_PAGES):?>style='display:table-row'<?php endif;?>>
                    <td><!-- -->&nbsp;<!-- --></td>
                    <td>
                        <?php $pages = get_posts(array('post_type' => 'page'));
                        $selected_pages = array();
                        if(isset($cfw_options['captcha_location']) && isset($cfw_options['captcha_page']) && !empty($cfw_options['captcha_page']) )
                            $selected_pages = $cfw_options['captcha_page'];
                        if($pages):?>
                        <ul>
                            <?php foreach($pages as $p):?>
                            <li>
                                <input <?php if(in_array($p->ID, $selected_pages)):?>checked="checked"<?php endif;?> type='checkbox' name='cfw_options[captcha_page][]' value='<?php echo $p->ID;?>' id='captcha_page_<?php echo $p->ID;?>' /> <label for='captcha_page_<?php echo $p->ID;?>'><?php echo get_the_title($p->ID);?></label>
                            </li>
                            <?php endforeach; ?>                            
                        </ul>
                        <?php else: ?>
                        <div class='errors'><?php _e('There are no pages defined to select from', 'frw');?></div>
                        <?php endif;?>
                    </td>
                </tr>
                <tr id="form_selector_holder" <?php if( isset($cfw_options['captcha_location']) && $cfw_options['captcha_location'] == CFW_LOC_SOME_FORMS):?>style='display:table-row'<?php endif;?>>
                    <td><label for="form_selector"><?php _e('Form selector definition', 'cfw');?> :</label></td>
                    <td><input type="text" name="cfw_options[form_selector]" id="form_selector" value="<?php echo (isset($cfw_options['form_selector']) ? $cfw_options['form_selector'] : '');?>" /></td>
                </tr>
                <tr>
                    <td colspan='2'><input type='submit' class='button button-primary button-large' value='<?php _e('Save Settings', 'cfw');?>'/></td>
                </tr>
            </table>
        </form>
    </div>
</div>
