<!-- START RECAPTCHA FOR WIDGETS SETTINGS FORM -->
<?php 
global $cfw_options;

$errors = array();
$fields = array();

if( $_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['action']))
{
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
?>
<div class='realperson_icon'><!-- --></div>
<h4><?php _e('RealPerson Options', 'cfw');?></h4>
<input type="hidden" name="cfw_options[from]" value="tab_realperson" />
<table>
    <tr>
        <td><label for="realperson_length"><span class="required">*</span> <?php _e('Length of captcha string', 'cfw');?></label> :</td>
        <td><input class="smallInput<?php if(in_array('realperson_length', $fields)) echo " errorInput";?>" type="text" name="cfw_options[realperson_length]" id="realperson_length" value="<?php echo (isset($cfw_options['realperson_length']) && !empty($cfw_options['realperson_length'])) ? $cfw_options['realperson_length'] : '5' ;?>" /></td>
    </tr>
    <tr>
        <td><label for="realperson_regenerate"><span class="required">*</span> <?php _e('Text for refresh command', 'cfw');?></label> :</td>
        <td><input type="text" <?php if(in_array('realperson_regenerate', $fields)) echo "class='errorInput'";?> name="cfw_options[realperson_regenerate]" id="realperson_regenerate" value="<?php echo (isset($cfw_options['realperson_regenerate']) && !empty($cfw_options['realperson_regenerate'])) ? $cfw_options['realperson_regenerate'] : 'Click to change' ;?>" /></td>
    </tr>
    <tr>
        <td><input type="checkbox" name="cfw_options[realperson_numbers]" id="realperson_numbers" <?php echo (isset($cfw_options['realperson_numbers']) && $cfw_options['realperson_numbers'] == 1) ? "checked='checked'" : ''; ?> value="1" /></td>
        <td><label for="realperson_numbers"><?php _e('Use numbers as well as letters?', 'cfw');?></label></td>
    </tr>
    <tr>
        <td><input type="checkbox" name="cfw_options[for_newer_jquery]" id="for_newer_jquery" <?php echo (isset($cfw_options['for_newer_jquery']) && $cfw_options['for_newer_jquery'] == 1) ? "checked='checked'" : ''; ?> value="1" /></td>
        <td><label for="for_newer_jquery"><?php _e('Check for use with jQuery 1.9+', 'cfw');?></label></td>
    </tr>
</table>