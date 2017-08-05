/**
 * Contao bundle contao-om-fastaccess
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */


/**
 * Class Ajax
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
var Ajax =
{
    /**
     * Set a new request token
     *
     * @param string
     */
    createToken: function(base)
    {
        if (window.XMLHttpRequest)
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            http = new XMLHttpRequest();
        }
        else
        {
            // code for IE6, IE5
            http = new ActiveXObject("Microsoft.XMLHTTP");
        }
    
        http.onreadystatechange=function()
        {
            if (http.readyState==4 && http.status==200)
            {
                arrUrl = document.getElementById("buttonCopy").getAttribute('data-clipboard-text').split("?");
        
                document.getElementById("ctrl_fastAccessToken").value = http.responseText;
                document.getElementById("buttonCopy").setAttribute('data-clipboard-text', arrUrl[0] + "?token=" + http.responseText);
            }
        }
    
        // open and send with post header
        http.open("POST", base + "bundles/omosdecontaoomfastaccess/php/Ajax.php", true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.send();
    }
}


/**
 * Add event listener
 */
window.addEvent('domready', function()
{
    // add an event
    function addEvent( obj, type, fn )
    {
        if ( obj.attachEvent )
        {
            obj['e'+type+fn] = fn;
            obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
            obj.attachEvent( 'on'+type, obj[type+fn] );
        } else
            obj.addEventListener( type, fn, false );
    }

    //
    if (document.getElementById('ctrl_fastAccessToken') != null)
    {
        addEvent( document.getElementById('ctrl_fastAccessToken'), 'keyup', function()
        {
            arrUrl = document.getElementById("buttonCopy").getAttribute('data-clipboard-text').split("?");
            document.getElementById("buttonCopy").setAttribute('data-clipboard-text', arrUrl[0] + "?token=" + document.getElementById('ctrl_fastAccessToken').value);
        });
    }
});
