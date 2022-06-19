
/* global jQuery, FileAPI, translateJS */

(function ($, FileAPI, lang){
   "use strict";
   var

   Uploader = {

      OK : 200,
      ERROR : 500,

      imageInfo: {
         up: '',
         uid: '',
         file: '',
         size: '',
         title: '',
         desc: '',
         keyw: '',
         time: ''
	  },
      
      delImg: {
         uid: '',
         name: '',
         ext: ''
	  },
	  
	  successfulUploads: 0,
	  failedUploads: 0,
	  
	  checkedImgsForDel: [],

      submit_onselect_blocked: false,
      submit_onupload_blocked: false,
      submit_onchange_blocked: false,
      submit_onselect_msgHTML: lang.uploader.submit_onselect_msgHTML,
      submit_onupload_msgHTML: lang.uploader.submit_onupload_msgHTML,
      submit_onchange_msgHTML: lang.uploader.submit_onchange_msgHTML,

      /**
	  * 
	  * @param {string} event
	  * @param {bool} disabled
	  *
	  * if disabled==true block submit button in the following cases:
	  *  
	  * event == 'select' : if images are browsed and selected
	  * event == 'upload' : while images are uploading to the site
	  * event == 'change' : if the images have been changed
	  * 
	  */
      submit_button_status: function (event, disabled=false) {

         if (event == 'select-upload') {
   	        this.submit_onselect_blocked = disabled;
   	        this.submit_onupload_blocked = disabled;
         }
         if (event == 'select') {
   	        this.submit_onselect_blocked = disabled;
         }
         if (event == 'upload') {
   	        this.submit_onupload_blocked = disabled;
         }
         if (event == 'change') {
   	        this.submit_onchange_blocked = disabled;
         }

         var msgHTML = '';
         if (this.submit_onchange_blocked) msgHTML = this.submit_onchange_msgHTML;
         if (this.submit_onupload_blocked) msgHTML = this.submit_onupload_msgHTML;
         if (this.submit_onselect_blocked) msgHTML = this.submit_onselect_msgHTML;

         $('#gallery_submit').prop("disabled", this.submit_onselect_blocked || this.submit_onupload_blocked || this.submit_onchange_blocked);
         $('#gallery_submit_ttip').tooltip('dispose');
         if (msgHTML != '') {
            $('#gallery_submit_ttip').attr('title', msgHTML).tooltip({placement:'top',trigger : 'hover focus'});
         }
      },

      /**
	  * Crop image
	  * 
	  * @param {string} uid
	  * uid: image identity
	  * 
	  */
      imageCrop: function (uid) {

         var file = FileAPI.readyForUpload.getFile(uid);
         if( file ){
            $('#crop-popup').modal2({
               closeOnEsc: true,
               closeOnOverlayClick: false,
               onOpen: function (overlay){
                  this.coords_tmp = null;
                  var me = this;
                  $(overlay).on('click', '.js-crop', function (){
                     $('#uploader').fileapi('crop', file, me.coords_tmp);
                     me.coords_tmp = null;
                     $.modal2().close();
                  });
                  $(overlay).on('click', '.js-cancel', function (){
                     $.modal2().close();
                  });
                  $('.js-img', overlay).cropper({
                     file: file,
                     allowSelect: false,
                     bgColor: '#fff',
                     maxSize: [($(window).width()-100)<360?360:$(window).width()-100, $(window).height()-100],
                     minSize: [$(window).width()<400?180:240, $(window).width()<400?180:240],
                     selection: '100%',
                     aspectRatio: false,
                     onSelect: function (coords){
                        me.coords_tmp = coords;
                     }
                  });
               }
            }).open();
         }
      },
	
      /**
	  * 
	  * If change image info, delete image or sorting images
	  * update image info on server db
	  * 
	  */
      saveImageInfoChanges: function () {

         if ($('#btn-save-changes').hasClass('disabled')) return false;

         $.post(FileAPI.galleryUrl+"/ajax-image-info-post.php", { 
            sessid: FileAPI.sessid, 
            gal_id: FileAPI.gal_id, 
            imgs_info: this.fileInfoList.getJSON() 
         },
         function(response){
         	var respArr = JSON.parse(response)
            if (respArr.code == this.OK) {
               this.showResultModal(lang.uploader.saveImageInfoChangesOK);
               $('#btn-save-changes').css('cursor', 'default');
               $('#btn-save-changes').removeClass('btn-warning').addClass('btn-secondary disabled');
               this.submit_button_status('change', false);
            } else {
               this.showResultModal(respArr.result, false);
            }
            return false;
         }.bind(this));
      },

      /**
	  * 
	  * Insert gallery info to server db
	  * 
	  */
      galleryInsert: function () {

         $.post(FileAPI.galleryUrl+"/ajax-gallery-insert.php", { 
            sessid: FileAPI.sessid, 
            gal_name:$('#gal_name').val(), 
            gal_description:$('#gal_description').val(),
            gal_keywords:$('#gal_keywords').val()
         },
         function(response){
         	var respArr = JSON.parse(response)
            if (respArr.code == this.OK) {
               FileAPI.gal_id = respArr.result;
               $("#gallery_save_info").removeClass("alert-danger alert-info").addClass("alert-success").html("<span class=\"fa fa-check-circle-o\"></span> " + lang.uploader.galleryInsertAlertSuccess);
               $('#gallery_submit').prop("disabled", true);
            } else {
               $("#gallery_save_info").removeClass("alert-success alert-info").addClass("alert-danger").html("<span class=\"fa fa-times-circle-o\"></span> " + lang.uploader.galleryInsertAlertError + " " + respArr.result);
            }
            return false;
         }.bind(this));
      },

      /**
	  * 
	  * update gallery info on server db
	  * 
	  */
      galleryUpdate: function () {

         $.post(FileAPI.galleryUrl+"/ajax-gallery-update.php", { 
            sessid: FileAPI.sessid, 
            gal_id: FileAPI.gal_id, 
            gal_name:$('#gal_name').val(), 
            gal_description:$('#gal_description').val(),
            gal_keywords:$('#gal_keywords').val()
         },
         function(response){
         	var respArr = JSON.parse(response)
            if (respArr.code == this.OK) {
               $("#gallery_save_info").removeClass("alert-danger alert-info").addClass("alert-success").html("<span class=\"fa fa-check-circle-o\"></span> " + lang.uploader.galleryUpdateAlertSuccess);
               $('#gallery_submit').prop("disabled", true);
            } else {
               $("#gallery_save_info").removeClass("alert-success alert-info").addClass("alert-danger").html("<span class=\"fa fa-times-circle-o\"></span> " + lang.uploader.galleryUpdateAlertError + " " + respArr.result);
            }
            return false;
         }.bind(this));
      },

      /**
	  * 
	  * @param {string} uid
	  * @param {string} up
	  * 
	  * uid: image identity
	  * up == 'new' ready for upload images
	  * up == 'exist' images on the server
	  * 
	  */
      imageInfoShow: function (uid, up) {

         this.imageInfo.up = up;
         this.imageInfo.uid = uid;

         if (up == 'new') {
            var file = FileAPI.readyForUpload.getFile(uid);
            this.imageInfo.file = file.name;
         }
         else { //up == 'exist'
            this.imageInfo.file = $('#file'+uid).text();
         }

         this.imageInfo.size = $('#size'+uid).val();
         this.imageInfo.time = $('#time'+uid).val();
         this.imageInfo.title = this.fileInfoList.getData(uid, 'title', up);
         this.imageInfo.desc = this.fileInfoList.getData(uid, 'desc', up);
         this.imageInfo.keyw = this.fileInfoList.getData(uid, 'keyw', up);
   
         $('#imageInfoModal').modal('show');
      },

      /**
	  * 
	  * save image info on client
	  * 
	  */
      imageInfoSave: function () {

         var img_info_changed = false;

         if (this.imageInfo.title != $('#image_info_title').val()) {
            this.fileInfoList.setData(this.imageInfo.uid, $('#image_info_title').val(), 'title', this.imageInfo.up);
            img_info_changed = true;
         }

         if (this.imageInfo.desc != $('#image_info_desc').val()) {
            this.fileInfoList.setData(this.imageInfo.uid, $('#image_info_desc').val(), 'desc', this.imageInfo.up);
            img_info_changed = true;
         }

         if (this.imageInfo.keyw != $('#image_info_keyw').val()) {
            this.fileInfoList.setData(this.imageInfo.uid, $('#image_info_keyw').val(), 'keyw', this.imageInfo.up);
            img_info_changed = true;
         }

         $('#imageInfoModal').modal('hide');
   
         if (img_info_changed) {
            if (/*FileAPI.gal_id != '' &&*/ FileAPI.readyForUpload.getFileCount()==0) {
               $('#btn-save-changes').css('cursor', 'pointer');
               $('#btn-save-changes').removeClass('btn-secondary disabled').addClass('btn-warning');
               this.submit_button_status('change', true);
            }
         }
      },

      /**
	  * delete 'new' uploaded image from server
	  * 
	  * @param {string} uid, image identity
	  * @param {string} type, image type
	  * 
	  */
      imageDelUploaded: function (uid, type) {

         this.delImg.name = FileAPI.xuid(uid+FileAPI.sessid);
         switch (type)
         {
         case 'image/gif' : 
         case 'image/png' : this.delImg.ext = 'png'; break;
         case 'image/jpeg' : 
         default: this.delImg.ext = 'jpg'; break;
         }
         this.delImg.uid = uid;

         $('#imageDelUploadedModal').modal('show');
      },

      /**
	  * delete 'exist'ing image from server
	  * 
	  * @param {HtmlElement} el
	  * 
	  */
      imageDelExisting: function (el) {

         var arr = $(el).attr("id").replace(/del_exist_/g,'').split('_');
         this.delImg.name = arr[0];
         this.delImg.ext = arr[1];

         $('#imageDelModal').modal('show');
      },

      /**
	  * delete 'exist'ing multiple images from server
	  * 
	  */
      imageDelExistingMulti: function () {

         this.checkedImgsForDel = $('.chkImg:checked').map(function() {
            return this.value;
         }).get();

         if(this.checkedImgsForDel != '') {
            $('#imageMultiDelModal').modal('show');
         } else {
            return false;
         }
      },

      /**
	  * 
	  * Uploaded images are cached before the gallery is saved and id retrieved. 
	  * In the meantime, this method is called if the uploaded image is to be deleted.
	  * 
	  */
      delImageNewUploaded: function () {

         $.post(FileAPI.galleryUrl+"/ajax-image-del-new-uploaded.php", { 
            sessid: FileAPI.sessid, 
            img_name_del: this.delImg.name, 
            img_ext_del: this.delImg.ext, 
            imgs_info: this.fileInfoList.getJSON() 
         },
         function(response){
         	var respArr = JSON.parse(response)
            if (respArr.code == this.OK) {
               FileAPI.maxFiles++;
               $('#uploader').fileapi('setFileUploadLimit');
               $('#'+this.delImg.uid).remove();
               this.fileInfoList.removeUploadedFiles(this.delImg.name);
               this.fileInfoList.reNumberThumbs();
               $('#imageDelUploadedModal').modal('hide');

               if (FileAPI.gal_id != '') {
	              
	              $.post(FileAPI.galleryUrl+"/ajax-image-info-post.php", { 
	                 sessid: FileAPI.sessid, 
	                 gal_id: FileAPI.gal_id, 
	                 imgs_info: this.fileInfoList.getJSON() 
                  },
                  function(response){
         	         var respArr = JSON.parse(response)
                     if (respArr.code == this.OK) {
		             }
                     return false;
                  });
               }
            } else {
               this.showResultModal(respArr.result, false);
            }
            return false;
         }.bind(this));
      },

      /**
	  * 
	  * It deletes the newly uploaded image from the server while the gallery is being edited.
	  * 
	  */
      delImageNewUploadedFromExistingGallery: function () {
                        
         $.post(FileAPI.galleryUrl+"/ajax-image-del-existing.php", { 
            sessid: FileAPI.sessid, 
            img_name_del: this.delImg.name, 
            img_ext_del: this.delImg.ext, 
            gal_id: FileAPI.gal_id, 
            imgs_info: this.fileInfoList.getJSON() 
         },
         function(response){
         	var respArr = JSON.parse(response)
            if (respArr.code == this.OK) {
               FileAPI.maxFiles++;
               $('#uploader').fileapi('setFileUploadLimit');
               $('#'+this.delImg.uid).remove();
               this.fileInfoList.removeUploadedFiles(this.delImg.name);
               this.fileInfoList.reNumberThumbs();
               $('#imageDelUploadedModal').modal('hide');

               if (FileAPI.gal_id != '') {

                  $.post(FileAPI.galleryUrl+"/ajax-image-info-post.php", { 
                     sessid: FileAPI.sessid, 
                     gal_id: FileAPI.gal_id, 
                     imgs_info: this.fileInfoList.getJSON() 
                  },
                  function(response){
         	         var respArr = JSON.parse(response)
                     if (respArr.code == this.OK) {
                     }
                     return false;
                  });
               }
            } else {
               this.showResultModal(respArr.result, false);
            }
            return false;
         }.bind(this));
      },

      /**
	  * 
	  * It deletes the existing preloaded image from the server while the gallery is being edited.
	  * 
	  */
      delImageFromExistingGallery: function () {

         $.post(FileAPI.galleryUrl+"/ajax-image-del-existing.php", { 
            sessid: FileAPI.sessid, 
            img_name_del: this.delImg.name, 
            img_ext_del: this.delImg.ext, 
            gal_id: FileAPI.gal_id, 
            imgs_info: this.fileInfoList.getJSON() 
         },
         function(response){
         	var respArr = JSON.parse(response)
            if (respArr.code == this.OK) {
               FileAPI.maxFiles++;
               $('#uploader').fileapi('setFileUploadLimit');
               $('#exist_'+this.delImg.name+'_'+this.delImg.ext).remove();
               this.fileInfoList.removeUploadedFiles(this.delImg.name);
               this.fileInfoList.reNumberThumbs();
               $('#imageDelModal').modal('hide');
            } else {
               this.showResultModal(respArr.result, false);
            }
            return false;
         }.bind(this));
      },

      /**
	  * 
	  * It deletes the existing preloaded multiple image from the server while the gallery is being edited.
	  * 
	  */
      delMultiImageFromExistingGallery: function () {
         
         $.post(FileAPI.galleryUrl+"/ajax-image-del-existing-multi.php", { 
            sessid: FileAPI.sessid, 
            gal_id: FileAPI.gal_id, 
            imgs_fname: JSON.stringify(this.checkedImgsForDel), 
            imgs_info: this.fileInfoList.getJSON() 
         },
         function(response){
         	var respArr = JSON.parse(response)
            if (respArr.code == this.OK) {
               FileAPI.maxFiles += this.checkedImgsForDel.length;
               $('#uploader').fileapi('setFileUploadLimit');

               $('.chkImg').each(function(){
                  if ($(this).is(':checked')) {
                     $(this).parents().eq(4).remove();
                  }
               });
               this.fileInfoList.removeUploadedFiles(this.checkedImgsForDel);
               this.fileInfoList.reNumberThumbs();
               $('#imageMultiDelModal').modal('hide');
            } else {
               this.showResultModal(respArr.result, false);
            }
            return false;
         }.bind(this));
      },

      showResultModal: function (message, success=true) {
         if (success) {
            $('#processResultLabel').removeClass('text-danger').addClass('text-success').html(lang.uploader.resultModalLabelSuccess);
            $('#processResultMsg').removeClass('text-danger').addClass('text-success').html(message);
		 } else {
            $('#processResultLabel').removeClass('text-success').addClass('text-danger').html(lang.uploader.resultModalLabelError);
            $('#processResultMsg').removeClass('text-success').addClass('text-danger').html(message);
		 }
         $('#processResultModal').modal('show');
      },

      fileInfoList: {
         sortOldIndex: 0,
         sortNewIndex: 0,
         files: [],
         sortingStartIndex: function (index) {
            this.sortOldIndex = index;
         },
         sortingStopIndex: function (index) {
            this.sortNewIndex = index;
         },
         reOrderFiles: function () {
            var item = this.files.splice(this.sortOldIndex, 1);
            this.files.splice(this.sortNewIndex, 0, item[0]);
         },
         addExistingFiles: function (className) {
            document.querySelectorAll('.'+className).forEach(function (elem) {
               if (elem.id.match(/^exist_(.+)$/)) {
                  var arr = elem.id.replace(/exist_/g,'').split("_");
                  var img = {
                     uid : '',
                     name : arr[0],
                     ext : arr[1],
                     title : document.getElementById('title'+arr[0]).value,
                     desc : document.getElementById('desc'+arr[0]).value,
                     keyw : document.getElementById('keyw'+arr[0]).value,
                     up: 'exist'
                  }; 
                  this.files.push(img);
               }
            }, this);
         },
         addFiles: function (uids) {
            uids.forEach(function(uid) {
               var img_name = FileAPI.xuid(uid+FileAPI.sessid);
               var img_ext = '';
               switch (document.getElementById('type'+uid).value)
               {
               case 'image/gif' : 
               case 'image/png' : img_ext = 'png'; break;
               case 'image/jpeg' : 
               default: img_ext = 'jpg'; break;
               }
               var img = {
                  uid : uid,
                  name : img_name,
                  ext : img_ext,
                  title : '',
                  desc : '',
                  keyw : '',
                  time : '',
                  up: 'new'
               }; 
               this.files.push(img); 
               var ind = this.findIndex(img_name);
               document.getElementById('img_number_'+uid).innerText = this.convertNumToLang(parseInt(ind)+1);
            }, this);
         },
         removeFile: function (uid) {
            var name = FileAPI.xuid(uid+FileAPI.sessid);
            var ind = this.findIndex(name);
            if (ind>=0) {
               this.files.splice(ind, 1);
            }
         },
         removeUploadedFiles: function (names) {
            if (Array.isArray(names)) {
               names.forEach(function(name) {
                  var arr = name.split('.');
                  var ind = this.findIndex(arr[0]);
                  if (ind>=0) {
                     this.files.splice(ind, 1);
                  }
               }, this);
            } else {
               var ind = this.findIndex(names);
               if (ind>=0) {
                  this.files.splice(ind, 1);
               }
            }
         },
         setData: function (uid, data, type, up='exist') {
            var name = uid;
            if (up == 'new') {
               var name = FileAPI.xuid(uid+FileAPI.sessid);
		    }
            var ind = this.findIndex(name);
            if (ind>=0) {
               if (type == 'title') {
                  this.files[ind].title = data;
		       } else if (type == 'desc') {
                  this.files[ind].desc = data;
		       } else if (type == 'keyw') {
                  this.files[ind].keyw = data;
		       } else if (type == 'up') {
                  this.files[ind].up = data;
		       }
            }
         },
	     getData: function (uid, type, up='exist') {
            var name = uid;
            if (up == 'new') {
               var name = FileAPI.xuid(uid+FileAPI.sessid);
            }
            var ind = this.findIndex(name);
            if (ind>=0) {
               if (type == 'title') {
                  return this.files[ind].title;
               } else if (type == 'desc') {
                  return this.files[ind].desc;
               } else if (type == 'keyw') {
                  return this.files[ind].keyw;
               } else if (type == 'up') {
                  return this.files[ind].up;
		       }
            }
         },
         findIndex: function (name) {
            var ind = -1;
            for (var i in this.files) {
               if (name == this.files[i].name) {
                  ind = i;
                  break;
               }
            }
            return ind;
         },
         get: function (){
            return this.files;
	     },
         getJSON: function (){
            return JSON.stringify(this.files);
	     },
         getFile: function (uid){
            var name = FileAPI.xuid(uid+FileAPI.sessid);
            var ind = this.findIndex(name);
            if (ind>=0) {
               return this.files[ind];
            } else {
               return [];
			}
	     },
         getFileJSON: function (uid){
            return JSON.stringify(this.getFile(uid));
	     },
         getFileNames: function (up='new'){
            var fileNames = [];
       	    var i=0;
            this.files.forEach(function(file) {
               if (file.up == up) {
                  fileNames[i] = file.name;
                  i++;
               }
            });
      	    return fileNames;
         },
         uploadStatus: function (up='exist'){
            var i=0;
            this.files.forEach(function() {
               this.files[i].up = up;
               i++;
            }, this);
         },
         reNumberThumbs: function (){
            var num=1;
            this.files.forEach(function(file) {
               if (file.up == 'exist') {
                  var id = file.name;
               } else {
                  var id = file.uid;
		       }
               var elem = document.getElementById('img_number_'+id);
               if (typeof(elem) != 'undefined' && elem != null) {
                  elem.innerText = this.convertNumToLang(num);
               } else if (FileAPI.gal_id != '' && file.up == 'exist') {
                  id = file.uid;
                  elem = document.getElementById('img_number_'+id);
                  if (typeof(elem) != 'undefined' && elem != null) {
                     elem.innerText = this.convertNumToLang(num);
                  }
			   }
               num++;
            }, this);
         },
         convertNumToLang: function (num){
            num = num.toString();
            if (typeof translateJS.numbers !== 'undefined') {
               return num.replace(/\d/g, d => translateJS.numbers[d])
		    }
		    return num;
         }
      }
   };

   $('#imageInfoModal').on('shown.bs.modal', function (e) {
      $('#image_info_file').text(Uploader.imageInfo.file);
      $('#image_info_size').text(Uploader.imageInfo.size);
      $('#image_info_date').text(Uploader.imageInfo.time);
      $('#image_info_title').val(Uploader.imageInfo.title);
      $('#image_info_titleCount').text(Uploader.imageInfo.title.length);
      $('#image_info_desc').val(Uploader.imageInfo.desc);
      $('#image_info_descCount').text(Uploader.imageInfo.desc.length);
      $('#image_info_keyw').val(Uploader.imageInfo.keyw);
      $('#image_info_keywCount').text(Uploader.imageInfo.keyw.length);
   });

   $('#btn-img-del-uploaded').click(function(){
      if (FileAPI.gal_id != '') {
         Uploader.delImageNewUploadedFromExistingGallery();
      } else {
         Uploader.delImageNewUploaded();
      }
   });

   $('#btn-del-image').click(function(){
      Uploader.delImageFromExistingGallery();
   });


   $('#'+FileAPI.uploaderId).fileapi({
      url: FileAPI.galleryUrl+'/ajax-image-upload.php',
      multiple: true,
      maxFiles: FileAPI.maxFiles,
      accept: 'image/*',
      maxSize: FileAPI.MB*FileAPI.maxSize,
      imageSize: { minWidth: FileAPI.minWidth, minHeight: FileAPI.minHeight, maxWidth: FileAPI.maxWidth, maxHeight: FileAPI.maxHeight },
      data: { 'sessid': FileAPI.sessid },
      elements: {
         ctrl: { upload: '.js-upload' },
         //empty: { show: '.b-upload__hint' },
         emptyQueue: { hide: '.js-upload' },
         list: '.js-files',
         file: {
            tpl: '.file-tpl',
            preview: {
               el: '.b-thumb__preview',
               width: 130,
               height: 130
            },
            upload: { show: '.progress', hide: '.b-thumb__rotate' },
            complete: { hide: '.progress' },
            progress: '.progress .bar'
         }
      },
      imageTransform: {
           // resize by max side
           maxWidth: FileAPI.resizeMaxWidth,
           maxHeight: FileAPI.resizeMaxHeight,
           //type: 'image/jpeg',
           quality: FileAPI.jpgQuality, // jpeg quality
           // Add watermark
           overlay: [FileAPI.wmOverlay]
      },
      imageAutoOrientation:true,

      onSelect: function (evt, data){

         var error_msg = '';

         for ( var i=0; i<data.other.length; i++ ){
            // errors
            var errors = data.other[i].errors;
            if( errors ){
               if (errors.minWidth || errors.minHeight) {
                  error_msg += lang.uploader.lessThanMinDimensionsError.printf(data.other[i].name, FileAPI.minWidth, FileAPI.minHeight) + '<br/>';
			   }
               if (errors.maxWidth || errors.maxHeight) {
                  error_msg += lang.uploader.exceedsMaxDimensionsError.printf(data.other[i].name, FileAPI.maxWidth, FileAPI.maxHeight) + '<br/>';
			   }
               if (errors.maxSize) {
                  error_msg += lang.uploader.exceedsMaxFileSizeError.printf(data.other[i].name, FileAPI.formatBytes(errors.maxSize), FileAPI.formatBytes(FileAPI.maxSize*FileAPI.MB)) + '<br/>';
			   }
            }
         }

         if ( data.other.length ){
            if (data.other[0].errors.maxFiles) {
               error_msg += lang.uploader.exceedsNumberOfMaxFilesError.printf(FileAPI.maxFiles) + '<br/>';
		    }
            Uploader.showResultModal(error_msg, false);
         }

         FileAPI.maxFiles -= parseInt(data.files.length);

         var uids = [];
      
         for(var i=0; i<data.files.length; i++) {
            var uid = FileAPI.cuid(data.files[i].name+data.files[i].size);
            var file = data.files[i];
            FileAPI.readyForUpload.addFile(uid, file);
            uids[i] = uid;
	     }

         setTimeout(function(){
            Uploader.fileInfoList.addFiles(uids);
            var sortedFileNames = Uploader.fileInfoList.getFileNames('new');
            $('#uploader').fileapi('sortUploadQueue', sortedFileNames);
            window.add_thumb_events();
         }, 500);

         $('#btn-save-changes').css('cursor', 'default');
         $('#btn-save-changes').removeClass('btn-warning').addClass('btn-secondary disabled');
         Uploader.submit_button_status('change', false);

   	     $('#upload_alert').css('display','none');
         $('#upload_error').css('display','none');
         Uploader.submit_button_status('select', true);

         Uploader.successfulUploads = 0;
         Uploader.failedUploads = 0;
      },

      onFileRemove: function (evt, file){

         FileAPI.maxFiles++;
         var uid = FileAPI.cuid(file.name+file.size);
         FileAPI.readyForUpload.removeFile(uid);
         Uploader.fileInfoList.removeFile(uid);
         Uploader.fileInfoList.reNumberThumbs();
         var sortedFileNames = Uploader.fileInfoList.getFileNames('new');
         $('#uploader').fileapi('sortUploadQueue', sortedFileNames);

         if (FileAPI.readyForUpload.getFileCount()==0) {
            Uploader.submit_button_status('select', false);
	     }
      },

      onFilePrepare: function (evt, uiEvt){
      },

      onProgress: function (evt, uiEvt){
         ///var part = uiEvt.loaded / uiEvt.total;
      },

      onUpload: function (evt, uiEvt){
   	     $('#upload_done').css('display','none');
   	     $('#upload_ing').css('display','inline-block');
   	     $('#upload_alert').css('display','inline-block');
         Uploader.submit_button_status('upload', true);
      },

      onFileProgress: function (evt, uiEvt){
	     var cuid = FileAPI.cuid(uiEvt.file.name+uiEvt.file.size);
	     var uid = uiEvt.file[cuid];
   	     $('#'+uid).find('div.b-thumb__crop').css('display','none');
      },

      onFileComplete: function (evt, uiEvt){
	     var cuid = FileAPI.cuid(uiEvt.file.name+uiEvt.file.size);
	     var uid = uiEvt.file[cuid];
         if(uiEvt.error == false) {
            $('#'+uid).find(".card-body").removeClass('thumb_queue_bg').addClass('thumb_uploaded_bg');
            Uploader.successfulUploads++;
         } else {
            $('#'+uid).find(".card-body").removeClass('thumb_queue_bg').addClass('thumb_uploaded_error_bg');
            Uploader.failedUploads++;
         }
         $('#del_new_'+uid).css('display','block');
         if (FileAPI.gal_id != '') {
	        $('#up'+uid).val('exist');
         }
         FileAPI.readyForUpload.removeFile(uid);
      },

      onComplete: function (evt, uiEvt){
         if (uiEvt.error == 'ERROR') {
		    if (FileAPI.debug) {
		       var uploadErrorMsg = 'Some files could not be uploaded to the server.<br/>Check out the error.log file for details.';
		       Uploader.showResultModal(uploadErrorMsg, false);
		    }
            $('#upload_error').css('display','inline-block');
		 } else {
            if (FileAPI.gal_id != '') {
               $.post(FileAPI.galleryUrl+"/ajax-image-info-post.php", { 
                  sessid: FileAPI.sessid, 
                  gal_id: FileAPI.gal_id, 
                  imgs_info: Uploader.fileInfoList.getJSON() 
               },
               function(response){
         	      var respArr = JSON.parse(response)
                  if (respArr.code == Uploader.OK) {
                     Uploader.fileInfoList.uploadStatus('exist');
                     Uploader.submit_button_status('select-upload', false);
                     Uploader.submit_button_status('change', false);
                  } else {
                     Uploader.showResultModal(respArr.result, false);
                  }
                  return false;
               });
            } else {
               $.post(FileAPI.galleryUrl+"/ajax-image-info-post.php", { 
                  sessid: FileAPI.sessid, 
                  imgs_info: Uploader.fileInfoList.getJSON() 
               },
               function(response){
         	      var respArr = JSON.parse(response)
                  if (respArr.code == Uploader.OK) {
                     Uploader.submit_button_status('select-upload', false);
                  } else {
                     Uploader.showResultModal(respArr.result, false);
                  }
                  return false;
               });
            }
		 }
         $('#upload_ing').css('display','none');
         $('#upload_done').css('display','inline-block');
         $('#upload_alert').css('display','inline-block');
      }
   });

   window.Uploader = Uploader;

})(jQuery, FileAPI, translateJS);
