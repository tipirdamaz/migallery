(function (window){
   'use strict';

   /**
   * Override FileAPI._getFormData in FileAPI.core.js
   */
   window.FileAPI._getFormData = function (options, data, fn){
     var
         file = data.file
       , name = data.name
       /*, filename = file.name*/ //this line changed to the following line.
       , filename = FileAPI.uid(options)
       , filetype = file.type
       , trans = FileAPI.support.transform && options.imageTransform
       , Form = new FileAPI.Form
       , queue = FileAPI.queue(function (){ fn(Form); })
       , isOrignTrans = trans 
       && function (trans){
             var key;
             for( key in trans ){
                if( trans.hasOwnProperty(key) ){
                   if( !(trans[key] instanceof Object || key === 'overlay' || key === 'filter') ){
                      return true;
                   }
                }
             }
             return false;
          }
       , postNameConcat = FileAPI.postNameConcat
       , _each = function (obj, fn, ctx){
            if( obj ){
               if( function (obj){return ar && ('length' in ar)}){
                  for( var i = 0, n = obj.length; i < n; i++ ){
                     if( i in obj ){
                        fn.call(ctx, obj[i], i, obj);
                     }
                  }
               }
               else {
                  for( var key in obj ){
                     if( obj.hasOwnProperty(key) ){
                        fn.call(ctx, obj[key], key, obj);
                     }
                  }
               }
            }
         }
        ;

      // Append data
      _each(options.data, function add(val, name){
         if( typeof val == 'object' ){
            _each(val, function (v, i){
               add(v, postNameConcat(name, i));
            });
         }
         else {
            Form.append(name, val);
         }
      });

      (function _addFile(file/**Object*/){
         if( file.image ){ // This is a FileAPI.Image
            queue.inc();

            file.toData(function (err, image){
               // @todo: требует рефакторинга и обработки ошибки
               if (file.file) {
                  image.type = file.file.type;
                  image.quality = file.matrix.quality;
                  filename = file.file && file.file.name;
               }

               filename = filename || (new Date).getTime()+'.png';

               _addFile(image);
               queue.next();
            });
         }
         else if( FileAPI.Image && trans && (/^image/.test(file.type) || _rimgcanvas.test(file.nodeName)) ){
            queue.inc();

            if( isOrignTrans ){
               // Convert to array for transform function
               trans = [trans];
            }

            FileAPI.Image.transform(file, trans, options.imageAutoOrientation, function (err, images){
               if( isOrignTrans && !err ){
                  if( !dataURLtoBlob && !FileAPI.flashEngine ){
                     // Canvas.toBlob or Flash not supported, use multipart
                     Form.multipart = true;
                  }

                  Form.append(name, images[0], filename,  trans[0].type || filetype);
               }
               else {
                  var addOrigin = 0;

                  if( !err ){
                     _each(images, function (image, idx){
                        if( !dataURLtoBlob && !FileAPI.flashEngine ){
                           Form.multipart = true;
                        }

                        if( !trans[idx].postName ){
                           addOrigin = 1;
                        }

                        Form.append(trans[idx].postName || postNameConcat(name, idx), image, filename, trans[idx].type || filetype);
                     });
                  }

                  if( err || options.imageOriginal ){
                     Form.append(postNameConcat(name, (addOrigin ? 'original' : null)), file, filename, filetype);
                  }
               }

               queue.next();
            });
         }
         else if( filename !== FileAPI.expando ){
            Form.append(name, file, filename);
         }
      })(file);

      queue.check();
   };

  /**
   * Override FileAPI.uid in FileAPI.core.js
  */
  window.FileAPI.uid = function (obj){
     if (typeof obj !== 'undefined' && 'name' in obj) FileAPI.expando = this.cuid(obj.name+obj.size)
     return obj
        ? (obj[FileAPI.expando] = obj[FileAPI.expando] || this.uid())
        : FileAPI.expando
     ;
  };

  window.FileAPI.cuid = function (s){
     s = unescape(encodeURIComponent(s));
     s = btoa(s);
     var o = "";
     for (var i=0; i < s.length; i++) {
        var x = s[i].charCodeAt(0).toString(2);
        var len = x.length;
        if (len<8) {
           for (var j=0; j<8-len; j++) x = '0'+x;
        }
        else if (len>8 & len<12) {
           for (j=0; j<12-len; j++) x = '0'+x;
        }
        else if (len>12 & len<16) {
           for (j=0; j<16-len; j++) x = '0'+x;
        }
        o += x;
     }
     var o2 = "";
     for (i=0; i < o.length; i+=4) {
        var n = o.substring(i, i+4);
        o2 +=parseInt(n, 2).toString(16);
     }
     return MD5(o2);
  };

  window.FileAPI.xuid = function (s){
     return this.cuid(s).substr(0,14);
  };

  window.FileAPI.formatBytes = function (size, precision = 0){
     var log = function(n, base) {
        return Math.log(n)/(base ? Math.log(base) : 1);
     };
     var base = log(size, 1024);
     var suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

     return Math.round(Math.pow(1024, base - Math.floor(base))).toFixed(precision) +' '+ suffixes[Math.floor(base)];
  };

  window.FileAPI.readyForUpload = {
     files: [], 
     findIndex: function (uid) {
        var ind = -1;
        for (var i in this.files) {
           if (uid == this.files[i].uid) {
              ind = i;
              break;
           }
        }
        return ind;
     },
     getFileCount: function (){
        return this.files.length;
     },
     getFile: function (uid){
        var ind = this.findIndex(uid);
        if (ind>=0) {
           return this.files[ind].file;
        }
        return false;
     },
     addFile: function (uid, file) {
        var ind = this.findIndex(uid);
        if (ind<0) {
           var selImg = {
              uid : uid,
              file : file,
           }; 
           this.files.push(selImg);
        }
     },
     removeFile: function (uid) {
        var ind = this.findIndex(uid);
        if (ind>=0) {
           this.files.splice(ind, 1);
        }
     }
  };

  if (FileAPI.wmUse) {
     FileAPI.wmOverlay = { x: 10, y: 10, src: FileAPI.imgBaseUrl + '/watermark.png?' + Math.floor(Date.now() / 1000), rel: FileAPI.wmPosition };
  }

})(window);
