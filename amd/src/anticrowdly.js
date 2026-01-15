define(['jquery', 'core/ajax'], function($, ajax) {

      function checkaiextension() {
           var crowdvns = document.querySelector('[id^=crowdvns]');
           var crowdly = document.querySelector('[id^=crowdly]');
           
           if(crowdvns !== null || crowdly !== null){
              // Found.
              checkcall(0);
           } else {
              // Not found.
              checkcall(1);
           }
           function checkcall(val) {
               if(val == 0){
                 console.error('Found CrowdlyAI Extention');
              } else {
                 console.error('CrowdlyAI Extention Not Found');
              }
              
               const request = {
                 methodname: 'quizaccess_anticrowdly_manageaccess',
                 args: {
                     status: val
                 },
               };
             ajax.call([request])[0];
           }
    }
    return {
        init: function() {
           checkaiextension();
        }
    };
});