var $ = $ || jQuery.noConflict();

$(document).ready(function(){    
    load_options_tab(Vars.activeTab);
    // show pages page when option selected
    if($('#captcha_location_some_pages').length > 0)
    {
        $('input[name="cfw_options[captcha_location]"]').click(function() {
           if( $('#captcha_location_some_pages').is(':checked') )
                $('#pages_options').show();
            else
                $('#pages_options').hide();
        });
    }
    
    if($('#form_selector_holder').length > 0)
    {
        $('input[name="cfw_options[captcha_location]"]').click(function() {
           if( $('#captcha_location_some_forms').is(':checked') )
                $('#form_selector_holder').show();
            else
                $('#form_selector_holder').hide();
        });
    }
    // load tab options when clicked on buttons
    if( $('#tab_recaptcha').length > 0 ) {
        $('#tab_recaptcha, #tab_realperson').click(function(){          
            load_options_tab($(this).attr('id'));            
            
        });
    }
});

function load_options_tab(tab_id)
{
    if(tab_id == null) tab_id = 'tab_recaptcha';
    
    $.ajax({
       url: Vars.admin_ajax_url,
       data: {action:tab_id},
       dataType: 'HTML',
       type: 'POST',
       success: function(data)
       {
           if( $('#cfw_tab_content').length > 0 )
               $('#cfw_tab_content').html(data);
           
           $('.tabs-holder').find('.tab').each(function() {
               if($(this).attr('id') == tab_id)
                   $(this).addClass('cfw-active');
               else
                   $(this).removeClass('cfw-active');
           })
       }
    });
}


