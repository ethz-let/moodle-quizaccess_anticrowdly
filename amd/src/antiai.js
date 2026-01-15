// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/* eslint-disable no-console */
/* eslint-disable @babel/no-unused-expressions */

define(['jquery', 'core/ajax'], function($, ajax) {

      /**
       * Checks for the presence of the AI extension in the user's browser
       */
      function checkaiextension() {
      var extensionId = "idipjdgkafkkbklacjonnhkammdpigol";

      detect(
        extensionId,
          function() {
            checkcall(0);
            },
          function() {
            checkcall(1);
            },
      );

      /**
       * Sends the result of the extension check to the server
       *
       * @param {number} val - Status: 0 = extension found, 1 = extension not found
       */
      function checkcall(val) {
          if (val === 0) {
            console.error('Found AI Extention');
          } else {
            console.error('AI Extention Not Found');
          }

          const request = {
            methodname: 'quizaccess_antiai_manageaccess',
            args: {
                status: val
            },
          };
         ajax.call([request])[0];
      }

       /**
        * Checks if the specified Chrome extension is installed by attempting to
        * load a resource from the extension. Depending on the result, calls the
        * appropriate callback.
        *
        * @param {string} extensionId - The extension ID.
        * @param {Function} ifInstalled - Called if the extension loads successfully.
        * @param {Function} ifNotInstalled - Called if loading fails.
        */
        function detect(extensionId, ifInstalled, ifNotInstalled) {
        var s = document.createElement('script');
        s.onerror = ifNotInstalled;
        s.onload = ifInstalled;
        document.body.appendChild(s);
        s.src = "chrome-extension://" + extensionId + '/src/styles.css';
      }
    }
    return {
        init: function() {
           checkaiextension();
        }
    };
});