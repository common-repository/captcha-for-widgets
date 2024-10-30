<!-- START RECAPTCHA FOR WIDGETS SETTINGS FORM -->
<?php 
global $cfw_options;

$errors = array();
$fields = array();

if( $_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['action']))
{
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
}
?>
<div class='recaptcha_icon'><!-- --></div>
<h4><?php _e('reCAPTCHA Options', 'cfw');?></h4>
<input type="hidden" name="cfw_options[from]" value="tab_recaptcha" />
<table>    
    <tr>
        <td><label for="captcha_public_key"><span class="required">*</span> <?php _e('reCAPTCHA Public Key', 'cfw');?>:</label></td>
        <td>
            <input <?php if(in_array('captcha_public_key', $fields)):?>class="errorInput"<?php endif;?> type="text" id="captcha_public_key" name="cfw_options[captcha_public_key]" value='<?php echo ( isset($cfw_options['captcha_public_key']) ? $cfw_options['captcha_public_key'] : '' );?>' />
            <span class="description"><?php _e('You need to get the reCAPTCHA Public and Private Key from <a href="http://www.google.com/captcha" target="_blank">http://www.google.com/captcha</a>', 'cfw');?></span>
        </td>
    </tr>
    <tr>
        <td><label for="captcha_private_key"><span class="required">*</span> <?php _e('reCAPTCHA Private Key', 'cfw');?>:</label></td>
        <td><input <?php if(in_array('captcha_private_key', $fields)):?>class="errorInput"<?php endif;?> type="text" id="captcha_private_key" name="cfw_options[captcha_private_key]" value='<?php echo ( isset($cfw_options['captcha_private_key']) ? $cfw_options['captcha_private_key'] : '' );?>' /></td>
    </tr>
    <tr>
        <td><label for="captcha_theme"><?php _e('reCAPTCHA Theme', 'cfw');?>: </label></td>
        <td><select id="captcha_theme" name="cfw_options[captcha_theme]">
                <optgroup label="<?php _e('Standard Themes', 'cfw');?>">
                    <option value="red" <?php echo ( (!isset($cfw_options['captcha_theme']) || $cfw_options['captcha_theme'] == 'red') ? 'selected="selected"' : '' );?>><?php _e('Red (default theme)', 'cfw');?></option>
                    <option value="white" <?php echo ( isset($cfw_options['captcha_theme']) && $cfw_options['captcha_theme'] == 'white' ? 'selected="selected"' : '' );?>><?php _e('White', 'cfw');?></option>
                    <option value="blackglass" <?php echo ( isset($cfw_options['captcha_theme']) && $cfw_options['captcha_theme'] == 'blackglass' ? 'selected="selected"' : '' );?>><?php _e('Blackglass', 'cfw');?></option>
                    <option value="clean" <?php echo ( isset($cfw_options['captcha_theme']) && $cfw_options['captcha_theme'] == 'clean' ? 'selected="selected"' : '' );?>><?php _e('Clean', 'cfw');?></option>                            
                </optgroup>
                <optgroup label="<?php _e('Custom Themes', 'cfw');?>">
                    <option value="custom" <?php echo ( isset($cfw_options['captcha_theme']) && $cfw_options['captcha_theme'] == 'custom' ? 'selected="selected"' : '' );?>><?php _e('Custom', 'cfw');?></option>
                </optgroup>
            </select>
            <span class="description"><?php _e('For more information regarding reCAPTCHA Themes you can read <a href="https://developers.google.com/captcha/docs/customization" target="_blank">here</a>.', 'cfw');?></span>
        </td>
    </tr>
</table>