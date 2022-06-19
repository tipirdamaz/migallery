(function (window){
   'use strict';
   
   var

   translateJS = {
      uploader: {
         submit_onselect_msgHTML: "まだアップロードされていない画像があります。 <b>画像のアップロード</b> ボタンを使用して画像をアップロードするか、<i class='fa fa-times-circle-o'></i> アイコンをクリックして画像のアップロードをキャンセルします。",
         submit_onupload_msgHTML: "画像はまだアップロードされていません。 お待ちください。",
         submit_onchange_msgHTML: "まず、<b>画像の変更を保存</b> ボタンをクリックして変更を保存します",
         saveImageInfoChangesOK: '変更は正常に保存されました。',
         galleryInsertAlert: "ギャラリー情報を保存しています。 お待ちください...",
         galleryInsertAlertSuccess: "ギャラリー情報が正常に保存されました。",
         galleryInsertAlertError: "ギャラリー情報を保存できませんでした。",
         galleryUpdateAlert: "ギャラリー情報を更新中です。 お待ちください...",
         galleryUpdateAlertSuccess: "ギャラリー情報が正常に更新されました。",
         galleryUpdateAlertError: "ギャラリー情報を更新できませんでした。",
         lessThanMinDimensionsError: "{0} のサイズが許容される最小の {1} x {2} px のサイズよりも小さい",
         exceedsMaxDimensionsError: "{0} のサイズが許容サイズを超えています {1} x {2} px",
         exceedsMaxFileSizeError: "{0} ファイルサイズ {1} が最大サイズ {2} を超えています",
         exceedsNumberOfMaxFilesError: "選択されたファイルの数が最大 {0} を超えています",
         resultModalLabelSuccess: "成功",
         resultModalLabelError: "エラー",
	  },
      charCount: {
         exceedsMaxCharLength: "貼り付けるテキストが、許可されている最大文字数を超えています。 {0} 文字が切り捨てられた後。 チェックしてください。",
	  }
   };

   window.translateJS = translateJS;

})(window);
