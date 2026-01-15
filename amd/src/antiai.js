define(['jquery', 'core/ajax'], function($, ajax) {

      
      function checkaiextension() {
      if (navigator.userAgent.indexOf("Cdhrome") != -1) {
        var extension_id = "idipjdgkafkkbklacjonnhkammdpigol";
        var extension_prefix = "chrome-extension://";
        console.error('Found Chrome: ' + extension_id);
      } else if (navigator.userAgent.indexOf("Firefox") != -1) {
        var extension_id = "e71d8a34-0d9b-44ee-84cc-82445c0d8c57";
        var extension_prefix = "moz-extension://";
        console.error('Found Firefox: ' + extension_id);
      } else {
        // Return for now - until we find new browser with such extension.
        console.error('Different Browser');
        checkcall(1);
        return;
      }
      
      detect(
        extension_id,
          function() {checkcall(0);},
          function() {checkcall(1);},
      );
      
      function checkcall(val) {
          if(val == 0){
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
      
      function detect(extension_id, if_installed, if_not_installed) {
        var s = document.createElement('script');
        s.onerror = if_not_installed;
        s.onload = if_installed;
        document.body.appendChild(s);
        console.error("testing "+ extension_prefix + extension_id + '/src/styles.css');
        s.src =  extension_prefix + extension_id + '/src/styles.css';
      }
    }
    return {
        init: function() {
           checkaiextension();
        }
    };
});