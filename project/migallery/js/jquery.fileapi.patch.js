(function (api){
   'use strict';

   api.PluginPatch = {

      setFileUploadLimit: function (){
         var selectedCount = api.readyForUpload.getFileCount();
         this.options.maxFiles = api.maxFiles + selectedCount;
      },

      sortUploadQueue: function (sortedImgNames){
         if (this.files.length == sortedImgNames.length) {
            for (var i=0; i<sortedImgNames.length; i++) {
               var ind = this.findIndex(this.files, sortedImgNames[i]);
               if(ind >= 0){
                  var arr = this.files.splice(ind, 1);
                  this.files.push(arr[0]);
               }
               var ind2 = this.findIndex(this.queue, sortedImgNames[i]);
               if(ind2 >= 0){
                  var arr2 = this.queue.splice(ind2, 1);
                  this.queue.push(arr2[0]);
               }
            }
         }
      },

      findIndex: function (files, uid) {
         var ind = -1;
         for (var i in files) {
            if (uid == api.xuid(api.uid(files[i])+api.sessid)) {
               ind = i;
               break;
            }
         }
         return ind;
      },

      /**
	  * 
	  * Override Plugin.emit in jquery.fileapi.js
	  * 
	  */
      emit: function (name, arg){
         var opts = this.options, evt = $.Event(name.toLowerCase()), res;
         evt.widget = this;
         //name = $.camelCase('on-'+name); < jquery version 3.0.0
         name = this.camelCase('on-'+name);
         if( $.isFunction(opts[name]) ){
            res = opts[name].call(this.el, evt, arg);
         }
         this.$el.triggerHandler(evt, arg);
         return	(res !== false) && !evt.isDefaultPrevented();
      },
      
      /**
	  * 
	  * This function is called from Plugin.emit because $.camelCase not working properly for jquery.fileapi Plugin on jquery version 3.0.0 and later.
	  * 
	  */
      camelCase: function( string ) {
         // Matches dashed string for camelizing
         var rmsPrefix = /^-ms-/;
         var rdashAlpha = /-([\da-z])/gi;
         var fcamelCase = function( all, letter ) {
            return letter.toUpperCase();
         };
         return string.replace( rmsPrefix, "ms-" ).replace( rdashAlpha, fcamelCase );
      }
};

})(FileAPI);
