(function (window){
   'use strict';
   
   var

   translateJS = {
      uploader: {
         submit_onselect_msgHTML: "There are images that have not yet been uploaded. Upload images with the <b>Upload Images</b> button or cancel image upload by clicking the <i class='fa fa-times-circle-o'></i> icon.",
         submit_onupload_msgHTML: "Images are not uploaded yet. Please wait.",
         submit_onchange_msgHTML: "First, save the changes by clicking the <b>Save Image Changes</b> button",
         saveImageInfoChangesOK: "Changes saved successfully.",
         galleryInsertAlert: "Gallery information is saving. Please wait...",
         galleryInsertAlertSuccess: "Gallery information saved successfully.",
         galleryInsertAlertError: "Gallery information could not be saved.",
         galleryUpdateAlert: "Gallery information is being updated. Please wait...",
         galleryUpdateAlertSuccess: "Gallery information updated successfully.",
         galleryUpdateAlertError: "Gallery information could not be updated.",
         lessThanMinDimensionsError: "{0} dimensions are less than the minimum {1} x {2} px dimensions allowed",
         exceedsMaxDimensionsError: "{0} dimensions exceeds the allowed dimensions {1} x {2} px",
         exceedsMaxFileSizeError: "{0} file size {1} exceeds the maximum size {2}",
         exceedsNumberOfMaxFilesError: "Number of files selected exceeds the maximum {0}",
         resultModalLabelSuccess: "Success",
         resultModalLabelError: "Error",
	  },
      charCount: {
         exceedsMaxCharLength: "The text you are pasting is longer than the maximum number of characters allowed. After {0} characters has been truncated. Please check.",
	  }
   };

   window.translateJS = translateJS;

})(window);
