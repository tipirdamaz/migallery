(function (window){
   'use strict';
   
   var

   translateJS = {
      uploader: {
         submit_onselect_msgHTML: "On kuvia, joita ei ole vielä ladattu. Lähetä kuvia <b> Lähetä kuvia </b> -painikkeella tai peruuta kuvien lataaminen napsauttamalla <i class='fa fa-times-circle-o'></i> kuvake.",
         submit_onupload_msgHTML: "Kuvia ei ole vielä ladattu. Odota.",
         submit_onchange_msgHTML: "Tallenna ensin muutokset napsauttamalla <b> Tallenna kuvamuutokset </b> -painiketta",
         saveImageInfoChangesOK: 'Muutokset tallennettu onnistuneesti.',
         galleryInsertAlert: "Gallerian tiedot tallentuvat. Odota ...",
         galleryInsertAlertSuccess: "Gallerian tiedot tallennettu onnistuneesti.",
         galleryInsertAlertError: "Gallerian tietoja ei voitu tallentaa.",
         galleryUpdateAlert: "Gallerian tietoja päivitetään. Odota ...",
         galleryUpdateAlertSuccess: "Gallerian tietojen päivitys onnistui.",
         galleryUpdateAlertError: "Gallerian tietoja ei voitu päivittää.",
         lessThanMinDimensionsError: "{0} -mitat ovat pienempiä kuin pienimmät sallitut {1} x {2} px-mitat",
         exceedsMaxDimensionsError: "{0} -mitat ylittävät sallitut mitat {1} x {2} px",
         exceedsMaxFileSizeError: "{0} tiedostokoko {1} ylittää enimmäiskoon {2}",
         exceedsNumberOfMaxFilesError: "Valittujen tiedostojen määrä ylittää enimmäismäärän {0}",
         resultModalLabelSuccess: "Menestys",
         resultModalLabelError: "Virhe",
},
      charCount: {
         exceedsMaxCharLength: "Liittämäsi teksti on pidempi kuin sallittu merkkien enimmäismäärä. Kun {0} merkkiä on katkaistu. Tarkista.",
}
   };

   window.translateJS = translateJS;

})(window);