/**
 * Contao bundle contao-om-fastaccess
 *
 * @copyright OMOS.de 2025 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */

let ContaoOmFastaccess =
  {
    createToken: function (base) {
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        http = new XMLHttpRequest();
      } else {
        // code for IE6, IE5
        http = new ActiveXObject("Microsoft.XMLHTTP");
      }

      http.onreadystatechange = function () {
        if (http.readyState === 4 && http.status === 200) {
          let arrUrl = document.getElementById("buttonCopy").getAttribute('data-clipboard-text').split("?");
          let response = JSON.parse(http.responseText);

          document.getElementById("ctrl_fastaccessToken").value = response.token;
          document.getElementById("buttonCopy").setAttribute('data-clipboard-text', arrUrl[0] + "?token=" + response.token);
        }
      }

      // Open and send the request
      http.open("GET", base + "_contao_om_fastaccess_token", true);
      http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http.send();
    },

    handleClipboard() {
      document.addEventListener("DOMContentLoaded", function (e) {
        let inputToken = document.getElementById("ctrl_fastaccessToken");
        let buttonCopy = document.getElementById("buttonCopy");
        if (inputToken) {
          inputToken.addEventListener('keyup', function () {
            let arrUrl = buttonCopy.getAttribute('data-clipboard-text').split("?");
            buttonCopy.setAttribute('data-clipboard-text', arrUrl[0] + "?token=" + document.getElementById('ctrl_fastAccessToken').value);
          });
        }
      });
    }
  }

ContaoOmFastaccess.handleClipboard();
