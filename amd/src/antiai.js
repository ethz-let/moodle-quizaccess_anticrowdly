define(['jquery', 'core/ajax'], function($, ajax) {

      
      function checkaiextension() {
      var extension_id = "idipjdgkafkkbklacjonnhkammdpigol";
      
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
        s.src = "chrome-extension://" + extension_id + '/src/styles.css';
      }
    }
    return {
        init: function() {
           checkaiextension();
        }
    };
});