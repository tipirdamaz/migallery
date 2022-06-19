(function (window){
   'use strict';
   
   var

   translateJS = {
      uploader: {
         submit_onselect_msgHTML: "ਇੱਥੇ ਕੁਝ ਤਸਵੀਰਾਂ ਹਨ ਜੋ ਅਜੇ ਤੱਕ ਅਪਲੋਡ ਨਹੀਂ ਕੀਤੀਆਂ ਗਈਆਂ ਹਨ. <b> ਤਸਵੀਰਾਂ ਅਪਲੋਡ ਕਰੋ </b> ਬਟਨ ਨਾਲ ਚਿੱਤਰ ਅਪਲੋਡ ਕਰੋ ਜਾਂ <i class='fa fa-times-circle-o'></i> ਆਈਕਾਨ ਤੇ ਕਲਿਕ ਕਰਕੇ ਚਿੱਤਰ ਅਪਲੋਡ ਨੂੰ ਰੱਦ ਕਰੋ.",
         submit_onupload_msgHTML: "ਚਿੱਤਰ ਹਾਲੇ ਅਪਲੋਡ ਨਹੀਂ ਕੀਤੇ ਗਏ ਹਨ. ਕ੍ਰਿਪਾ ਕਰਕੇ ਉਡੀਕ ਕਰੋ.",
         submit_onchange_msgHTML: "ਪਹਿਲਾਂ, <b> ਚਿੱਤਰ ਬਦਲਾਅ ਸੇਵ ਕਰੋ </b> ਬਟਨ ਤੇ ਕਲਿਕ ਕਰਕੇ ਤਬਦੀਲੀਆਂ ਨੂੰ ਸੁਰੱਖਿਅਤ ਕਰੋ",
         saveImageInfoChangesOK: "ਤਬਦੀਲੀਆਂ ਸਫਲਤਾਪੂਰਵਕ ਸੁਰੱਖਿਅਤ ਕੀਤੀਆਂ ਗਈਆਂ.",
         galleryInsertAlert: "ਗੈਲਰੀ ਜਾਣਕਾਰੀ ਸੇਵ ਕਰ ਰਹੀ ਹੈ. ਕ੍ਰਿਪਾ ਕਰਕੇ ਉਡੀਕ ਕਰੋ...",
         galleryInsertAlertSuccess: "ਗੈਲਰੀ ਜਾਣਕਾਰੀ ਸਫਲਤਾਪੂਰਵਕ ਸੁਰੱਖਿਅਤ ਕੀਤੀ ਗਈ.",
         galleryInsertAlertError: "ਗੈਲਰੀ ਜਾਣਕਾਰੀ ਨੂੰ ਸੁਰੱਖਿਅਤ ਨਹੀਂ ਕੀਤਾ ਜਾ ਸਕਿਆ.",
         galleryUpdateAlert: "ਗੈਲਰੀ ਜਾਣਕਾਰੀ ਅਪਡੇਟ ਕੀਤੀ ਜਾ ਰਹੀ ਹੈ. ਕ੍ਰਿਪਾ ਕਰਕੇ ਉਡੀਕ ਕਰੋ...",
         galleryUpdateAlertSuccess: "ਗੈਲਰੀ ਜਾਣਕਾਰੀ ਸਫਲਤਾਪੂਰਵਕ ਅਪਡੇਟ ਕੀਤੀ ਗਈ.",
         galleryUpdateAlertError: "ਗੈਲਰੀ ਜਾਣਕਾਰੀ ਨੂੰ ਅਪਡੇਟ ਨਹੀਂ ਕੀਤਾ ਜਾ ਸਕਿਆ.",
         lessThanMinDimensionsError: "{0} ਮਾਪ ਬਹੁਤ ਘੱਟ ਆਗਿਆ ਦਿੱਤੇ {1} x {2} px ਮਾਪ ਤੋਂ ਘੱਟ ਹਨ",
         exceedsMaxDimensionsError: "{0} ਮਾਪ ਮਾਪਦੰਡ {1} x {2} px ਤੋਂ ਵੱਧ ਗਏ ਹਨ",
         exceedsMaxFileSizeError: "{0} ਫਾਈਲ ਅਕਾਰ {1} ਵੱਧ ਤੋਂ ਵੱਧ ਆਕਾਰ {2} ਤੋਂ ਵੱਧ ਗਿਆ ਹੈ",
         exceedsNumberOfMaxFilesError: "ਚੁਣੀਆਂ ਗਈਆਂ ਫਾਈਲਾਂ ਦੀ ਗਿਣਤੀ ਵੱਧ ਤੋਂ ਵੱਧ {0} ਤੋਂ ਵੱਧ ਗਈ ਹੈ",
         resultModalLabelSuccess: "ਸਫਲਤਾ",
         resultModalLabelError: "ਗਲਤੀ",
	  },
      charCount: {
         exceedsMaxCharLength: "ਤੁਸੀਂ ਜੋ ਟੈਕਸਟ ਪੇਸਟ ਕਰ ਰਹੇ ਹੋ ਉਹ ਆਗਿਆ ਦਿੱਤੇ ਅੱਖਰਾਂ ਦੀ ਅਧਿਕਤਮ ਸੰਖਿਆ ਤੋਂ ਲੰਮਾ ਹੈ. {0} ਤੋਂ ਬਾਅਦ ਅੱਖਰ ਕੱਟੇ ਗਏ ਹਨ. ਕ੍ਰਿਪਾ ਜਾਂਚ ਕਰੋ.",
	  }
   };

   window.translateJS = translateJS;

})(window);
