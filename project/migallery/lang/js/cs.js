(function (window){
   'use strict';
   
   var

   translateJS = {
      uploader: {
         submit_onselect_msgHTML: "Existují obrázky, které ještě nebyly nahrány. Nahrajte obrázky pomocí tlačítka <b> Nahrajte obrázky </b> nebo nahrávání zrušte kliknutím na <i class='fa fa-times-circle-o'></i> ikona. ",
         submit_onupload_msgHTML: "Obrázky ještě nejsou nahrány. Čekejte prosím.",
         submit_onchange_msgHTML: "Nejprve uložte změny kliknutím na tlačítko <b> Uložit změny obrázku </b>",
         saveImageInfoChangesOK: 'Změny byly úspěšně uloženy.',
         galleryInsertAlert: "Informace galerie se ukládají. Čekejte prosím ...",
         galleryInsertAlertSuccess: "Informace o galerii byly úspěšně uloženy.",
         galleryInsertAlertError: "Informace o galerii nelze uložit.",
         galleryUpdateAlert: "Informace o galerii se aktualizují. Čekejte prosím ...",
         galleryUpdateAlertSuccess: "Informace o galerii byly úspěšně aktualizovány.",
         galleryUpdateAlertError: "Informace o galerii nelze aktualizovat.",
         lessThanMinDimensionsError: "{0} rozměry jsou menší než minimální povolené rozměry {1} x {2} px",
         exceedsMaxDimensionsError: "{0} rozměry přesahují povolené rozměry {1} x {2} px",
         exceedsMaxFileSizeError: "{0} velikost souboru {1} přesahuje maximální velikost {2}",
         exceedsNumberOfMaxFilesError: "Počet vybraných souborů přesahuje maximální {0}",
         resultModalLabelSuccess: "Úspěch",
         resultModalLabelError: "Chyba",
},
      charCount: {
         exceedsMaxCharLength: "Text, který vkládáte, je delší než maximální povolený počet znaků. Po zkrácení {0} znaků. Zkontrolujte.",
}
   };

   window.translateJS = translateJS;

})(window);