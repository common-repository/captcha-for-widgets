var $ = $ || jQuery.noConflict();

$(document).ready(function(){     

   if(typeof Vars != 'undefined' && Vars != null && Vars.cfw_options.from) {
       switch(Vars.cfw_options.captcha_location) {
            case "1" :
               $('form').mycaptcha(Vars);   
            break;
            case "2" :               
                var validPages = Vars.cfw_options.captcha_page;
                var validPagesLen = validPages.length;
                for( var i = 0; i < validPagesLen; i++) { 
                    if( validPages[i] === Vars.page_id) {
                        $('form').mycaptcha(Vars);      
                    }
                }
            break;
            case "3" :
                if(Vars.cfw_options.form_selector && $(Vars.cfw_options.form_selector))
                    $(Vars.cfw_options.form_selector).mycaptcha(Vars);
            break;
            default: ; // do nothing
        } // end switch
    } // end if have Vars 
});