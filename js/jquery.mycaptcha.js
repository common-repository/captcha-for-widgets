(function ( $ ) {
    var defaults = {
        'cfw_options'       : {},
        'captcha_api'       :   '',
        'admin_ajax_url'    : ''
    };
    $.fn.mycaptcha = function( options ) {
        
        var settings = $.extend( {}, defaults, options );
        
        var idx = 0;
                                 
        
        return this.each(function() {
            var self = $(this);
            if( !self.attr('id') || (self.attr('id') != 'searchform' && self.attr('id') != 'adminbarsearch') )
            {
                if(settings.cfw_options)
                 {
                     submitCleaner();
                     if(settings.cfw_options.from === 'tab_recaptcha') {                         
                        set_up_the_captcha_image(self, idx, settings);
                        idx++;                                  
                     } else if(settings.cfw_options.from === 'tab_realperson' )
                     {                        
                        set_up_the_realperson_image(self, idx, settings);
                        idx++;                                  
                     } // end if else from                        
                 } // end if sfw_options exists
            } // if right id
        });
        
    }
    
    function set_up_the_realperson_image(widget_holder, idx, settings)
     {           
         var err_id        = 'captcha_validation_error_' + idx;

         widget_holder.append(
             $('<div />').attr('id', 'captcha_holder_' + idx)
         );

         $('#captcha_holder_' + idx).realperson({
             length: settings.cfw_options.realperson_length,
             regenerate: settings.cfw_options.realperson_regenerate,
             includeNumbers: (settings.cfw_options.realperson_numbers != null && settings.cfw_options.realperson_numbers == 1),
             hashName: 'defaultReal' + idx + 'Hash'
         });
          $('#captcha_holder_' + idx).append(
                 $('<input />')
                     .attr('type', 'text')
                     .attr('id', 'defaultReal' + idx)
                     .attr('name', 'defaultReal' + idx)
                     .addClass('hasRealPerson')
                     .css('border', '1px solid #bebebe')
             );
         // add captcha validation before submit and errors field
         widget_holder.prepend(
            $('<div />').attr('id', err_id).css('padding', '5px').css('color', 'red').hide() 
         );
         widget_holder.find('input[type="submit"]').click(function(){


                var response = $('#defaultReal' + idx).val();
                var challenge = $('input[name="defaultReal' + idx + 'Hash"]').val();           

                if( response === '' )
                    $('#' + err_id).html('Please fill in the captch field.').show();
                else {
                    // do the synch ajax call
                    $('#' + err_id).html('').hide();
                $.ajax({
                    url: settings.admin_ajax_url,
                    data:{'action':'cfw_validate_realperson', 'response' : response, 'challenge': challenge},
                    dataType: 'HTML',
                    type: 'POST',
                    asynch: false,
                    success: function(data)
                    {
                        if(data == 'OK') {                          
                            $('#' + err_id).html('').hide();   
                            // validation is OK
                            // SUBMIT THE FORM!
                           widget_holder.submit();
                        } else {
                            $('#' + err_id).html(data).show();  

                            return false;
                        }

                    }
               });
           }
           return false;
         });    
     }
     
    function set_up_the_captcha_image(widget_holder, idx, settings)
    {       
        var err_id        = 'captcha_validation_error_' + idx;

        widget_holder.append(
            $('<div />').attr('id', 'captcha_holder_' + idx)
        );

        // if recaptcha already exists on page, destroy it! => TODO
        if( typeof Recaptcha !== 'undefined' ) {
            Recaptcha.create(
                settings.cfw_options.captcha_public_key,
                "captcha_holder_" + idx,
                {
                    theme: settings.cfw_options.captcha_theme,
                    callback: Recaptcha.focus_response_field
                }
             );

     
            widget_holder.prepend(
               $('<div />').attr('id', err_id).css('padding', '5px').css('color', 'red').hide() 
            );
            widget_holder.find('input[type="submit"], a[id*="submit"]').click(function(){

               var response = $('#recaptcha_response_field').val();
               var challenge = $('#recaptcha_challenge_field').val();           

               if( response === '' )
                   $('#' + err_id).html('Please fill in the captch field.').show();
               else {
                   // do the synch ajax call
                   $('#' + err_id).html('').hide();
                  
                   $.ajax({
                       url: settings.admin_ajax_url,
                       data:{'action':'cfw_validate_captcha', 'response' : response, 'challenge': challenge},
                       dataType: 'HTML',
                       type: 'POST',
                       asynch: false,
                       success: function(data)
                       {
                           if(data == 'OK') {                          
                               $('#' + err_id).html('').hide();   
                               // validation is OK
                               // SUBMIT THE FORM!
                              widget_holder.submit();
                           } else {
                               $('#' + err_id).html(data).show();  
                               Recaptcha.reload();
                               return false;
                           }

                       }
                  });
              }
              return false;
            }); 
        }
    } 
    
    showRecaptcha = function( settings, idx )
    {
        var recaptchaContent = '<noscript>' +
            '<iframe src="http://www.google.com/recaptcha/api/noscript?k=' + settings.cfw_options.captcha_public_key + '"' +
            'height="300" width="500" frameborder="0"></iframe><br>' +
            '<textarea name="recaptcha_challenge_field" rows="3" cols="40">' +
            '</textarea>' +
            '<input type="hidden" name="recaptcha_response_field"' +
            'value="manual_challenge">' +
            '</noscript>';
        $("#captcha_holder_" + idx).append(recaptchaContent);
    }
   
   /******* REMOVE any submit name or id on page - otherwise $('form').submit() will not work ******/
   submitCleaner = function()
   {
        // rename and "submit" button to something else
        if($('input[name="submit"]').length > 0) {
            var index = 0;
            $('input[name="submit"]').each(function(){
                $(this).attr('name', 'submit_' + index);
                index++;
            });
        }

         if($('input#submit').length > 0) {
            var index = 0;
            $('input#submit').each(function(){
                $(this).attr('id', 'submit_' + index);
                index++;
            });
        }    
   }
    
}( jQuery ));