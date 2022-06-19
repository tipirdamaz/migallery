<?php

   /**
   * Gallery management for admin or authorized users.
   * 
   * GLOBAL VARIABLES
   * @var {Resource} $db_conn from mi-init.php
   * @var {Array} $config from CONFIG_FILE
   * @var {String} $sessid from mi-session.php
   * 
   * $_GET vars
   * @var {String} $_GET['order'] sort galleries asc or desc
   * @var {Integer} $_GET['rpp'] result per page
   * @var {Integer} $_GET['page'] page number
   * 
   */

require_once('mi-session.php');
include_once('migallery/definitions0.php');

if (!file_exists(CONFIG_FILE)) {
   echo 'Configuration file not found. Please reinstall MIGallery.';
   exit;
}

if (!file_exists(DEFINITIONS_FILE)) {
   echo 'Definitions file not found. Please reinstall MIGallery.';
   exit;
}

require_once(GALLERY_DIR.'/Translator.class.php');
include_once(DEFINITIONS_FILE);
require_once(CONFIG_FILE);
date_default_timezone_set($config['timezone']);
require_once('mi-init.php');
require_once(GALLERY_DIR.'/MIGallery.class.php');
require_once(GALLERY_DIR.'/Pagination.class.php');
Translator::select($config['lang']);
$langCode = Translator::getLangCode();
$langDir = Translator::getDirection();
$langNums = Translator::getLangNumbers();
$lang = Translator::translate();
$date_format = Translator::getLocaleDateFormat().' H:i';
?>
<!doctype html>
<html lang="<?php echo $langCode;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo $lang->gallery_man->title;?></title>

<!-- Compatible with bootstrap 4 and bootstrap 5 -->
<!-- Include the css files according to the version you use. -->

<link href="bootstrap-4.5.3/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs4.css" rel="stylesheet" type="text/css"/>
<!--
<link href="bootstrap-5.0.0-beta2/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs5.css" rel="stylesheet" type="text/css"/>
-->

<link href="icons/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/gallery-man.css" rel="stylesheet" type="text/css"/>
<?php if ($langDir == 'rtl') {?>
<link href="<?php echo GALLERY_URL;?>/css/migallery-rtl.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/gallery-man-rtl.css" rel="stylesheet" type="text/css"/>
<?php if ($config['img_direction'] == 'rtl') {?>
<link href="<?php echo GALLERY_URL;?>/css/gallery-man-img-rtl.css" rel="stylesheet" type="text/css"/>
<?php }?>
<?php }?>
</head>
<body>

<?php require_once('mi-navbar.php');?>

<?php 

   MIGallery::init($config, $db_conn);
   $gal_count = MIGallery::getGalleryCount();

   if (!isset($_GET['order'])) $_GET['order'] = 'asc';
   else if (!($_GET['order'] == 'asc' || $_GET['order'] == 'desc')) {
      $_GET['order'] == 'asc';
   }

   if (!isset($_GET['rpp'])) $_GET['rpp'] = 12; //result per page
   $result_per_page = $_GET['rpp'];

   if (!isset($_GET['page'])) $_GET['page'] = 1; //page number
   $page_num = $_GET['page'];

   $start_record_num = ($page_num-1)*$result_per_page;

   $total_page = ceil($gal_count / $result_per_page);

   $gals = MIGallery::getGalleryList($start_record_num, $result_per_page, $_GET['order']);

?>

<div class="container man-page"><!-- page container -->

  <div class="row mt-4 pt-3"><!-- page row -->

    <div class="col-12"><!-- page col -->

      <div class="row w-100 gallery-man-header border-bottom mx-0 mb-3"><!-- header row -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-1 px-0">
          <div class="float-left float-start">
            <h5><?php echo $lang->gallery_man->header;?></h5>
          </div>
          <div class="float-right float-end">
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-1 px-0">
          <select id="order" style="float:right;margin-left:10px">
            <option value="asc" <?php if($_GET['order']=='asc') echo 'selected';?>><?php echo $lang->gallery_list->sort->asc;?></option>
            <option value="desc" <?php if($_GET['order']=='desc') echo 'selected';?>><?php echo $lang->gallery_list->sort->desc;?></option>
          </select>
          <select id="rpp" style="float:right;margin-left:10px">
            <option value="12" <?php if($_GET['rpp']=='12') echo 'selected';?>><?php echo Translator::convertNumToLang(12);?></option>
            <option value="24" <?php if($_GET['rpp']=='24') echo 'selected';?>><?php echo Translator::convertNumToLang(24);?></option>
            <option value="36" <?php if($_GET['rpp']=='36') echo 'selected';?>><?php echo Translator::convertNumToLang(36);?></option>
          </select>
        </div>
      </div><!--/ header row -->

<?php if ($gal_count > 0) {?>

      <div class="row w-100 alert alert-info gallery-man-alert mx-0 mt-1 mb-3 p-2" role="alert" style="font-size:.85rem;">
        <div class="col-sm-12 col-md-7 col-lg-8 col-xl-9 pt-2 px-0">
          <div class="float-left float-start">
            <span id="move_msg_desktop" class="d-none"><?php echo $lang->gallery_list->card->move;?></span>
            <span id="move_msg_mobile" class="d-none"><?php echo $lang->gallery_list->card->move_mobile;?></span>
          </div>
          <div class="float-left float-start">
          </div>
        </div>
        <div class="col-sm-12 col-md-5 col-lg-4 col-xl-3 pt-1 px-0">
          <div class="float-right float-end">
            <div id="btn-save-changes-gallery" class="btn btn-sm btn-secondary disabled" style="float:right" onclick="saveGallerySorting()">
              <?php echo $lang->gallery_list->save_changes;?>
            </div>
          </div>
          <div class="float-right float-end">
          </div>
        </div>
      </div>

<?php } ?>

      <div id="sortGallery" class="row gallery-man-wrapper"><!-- row gallery sort -->

<?php
      for($i=0; $i<count($gals); $i++) 
      {
         if ($gals[$i]['thumb'] != '') {
      	    $thumb_name = $gals[$i]['thumb'];
      	    if (file_exists(sprintf(IMG_THUMB_C_DIR, $gals[$i]['id']).'/'.$thumb_name)) {
      	       $tn_img = sprintf(IMG_THUMB_C_URL, $gals[$i]['id']).'/'.$thumb_name;
		    } else {
      	       $tn_img = GALLERY_URL.'/no-photo.png';
		    }
		 } else {
      	    $tn_img = GALLERY_URL.'/no-photo.png';
		 }
		 
		 $gal_name = htmlentities($gals[$i]['name'], ENT_QUOTES, 'UTF-8');
		 $gal_desc = htmlentities($gals[$i]['desc'], ENT_QUOTES, 'UTF-8');
		 
		 MIGallery::selectGallery($gals[$i]['id']);
		 $img_count = MIGallery::getImageCountFromGallery();
		
		 if ($_GET['order'] == 'asc') $gal_number = $start_record_num + $i +1;
		 else $gal_number = $gal_count - $start_record_num - $i;
?>
        <div id="col_gal_<?php echo $gals[$i]['id'];?>" class="thumb_gal col-12 col-md-6 col-lg-3 col-xl-3 col-xxl-2">
          <div class="card mb-3"><!-- card -->
            <div class="card-top-bar row w-100 ml-auto ms-auto mr-auto me-auto"></div>
            <div class="card-img-body">
              <div class="gal-number" id="gal_number_<?php echo $gals[$i]['id'];?>"><?php echo Translator::convertNumToLang($gal_number);?></div>
              <div class="del_gallery" id="del_gal_<?php echo $gals[$i]['id'];?>" title="<?php echo $lang->gallery_list->card->delete->title;?>"></div>
              <div class="edit_gallery" id="edit_gal_<?php echo $gals[$i]['id'];?>" title="<?php echo $lang->gallery_list->card->edit->title;?>"></div>
              <div class="move_gallery"></div>
              <img class="card-img" src="<?php echo $tn_img;?>" alt="<?php echo $gal_name;?>" title="<?php echo $lang->gallery_list->card->move;?>">
              <div class="row d-flex mx-auto w-100 btn btn-dark">
                <div class="col-6 d-inline-flex justify-content-start p-0">
                  <span class="mr-2 me-2">
                    <?php echo Translator::convertNumToLang($img_count);?>
                  </span>
                  <span class="text-nowrap">
                    <?php echo $lang->gallery_list->card->images;?>
                  </span>
                </div>
                <div class="col-6 d-inline-flex justify-content-end p-0">
                  <a href="#!" id="view_gal_<?php echo $gals[$i]['id'];?>" class="view_gallery text-white text-nowrap" title="<?php echo $lang->gallery_list->card->view->title;?>">
                    <i class="fa fa-eye fa-lg mr-1 me-1"></i>
                    <span>
                      <?php echo $lang->gallery_list->card->view->button;?>
                    </span>
                  </a>
                </div>
              </div>
            </div>  
            <div class="card-body">
              <h4 class="card-title">
                <?php echo $gal_name;?>
              </h4>
              <p class="card-text">
                <?php echo $gal_desc;?>
              </p>
              <p class="card-date text-secondary">
                <span>
                  <?php echo $lang->gallery_list->card->date;?>
                </span>
                <span>
                  <?php echo date($date_format, intval($gals[$i]['time']));?>
                </span>
              </p>
            </div>
          </div><!-- card -->
          <input type="hidden" id="sort_gal_<?php echo $gals[$i]['id'];?>" value="<?php echo $gals[$i]['sort'];?>">
        </div>

<?php } ?>

      </div><!--/ row gallery sort -->

<?php if ($gal_count == 0) {?>

      <div class="row"><!-- gallery not found -->
        <div class="col-12">
          <div class="row w-100 alert alert-info gallery-man-alert ml-auto ms-auto mr-auto me-auto p-2" role="alert" style="font-size:.85rem;">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-2 px-0">
              <div class="float-left float-start">
                <?php echo $lang->gallery_list->alert;?>
              </div>
              <div class="float-left float-start">
              </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 pt-1 px-0">
              <div class="float-right float-end">
                <a href="mi-uploader.php" class="btn btn-sm btn-primary"><?php echo $lang->nav->uploader;?></a>
              </div>
              <div class="float-right float-end">
              </div>
            </div>
          </div>
        </div>
      </div><!--/ gallery not found -->
<?php
   }

   $result = MIGallery::getResult();
   MIGallery::end();

   if($result['code'] == MIGallery::ERROR) {
      echo '<div class="row"><div class="col-12"><div class="alert alert-danger" role="alert">'.$result['result'].'</div></div></div>';
   }

?>

      <div class="row my-3"><!--/ pagination row -->

        <div class="col-12"><!-- pagination col -->

<?php

$numberOfRecords = $gal_count;
$pageURL = $_SERVER['PHP_SELF'];
$currentPage = $page_num;
$resultsPerPage = $result_per_page;
$maximumLinksToDisplay = 10;
$displayArrows = true;
$additionalQuery = array(
    'rpp' => $_GET['rpp'],
    'order' => $_GET['order']
);

$pagerClass = 'pagination justify-content-center';
$liClass = 'page-item';
$liActiveClass = 'active';
$aClass = 'page-link';
$aActiveClass = '';

$linkLabels = Array(
    'first' => '&laquo;',
    'prev' => '&lt;',
    'next' => '&gt;',
    'last' => '&raquo;'
);

$titleLabels = Array(
    'page' => $lang->pagination->page,
    'first' => $lang->pagination->first,
    'prev' => $lang->pagination->prev,
    'next' => $lang->pagination->next,
    'last' => $lang->pagination->last
);

if ($langDir == 'rtl' && $config['img_direction'] == 'rtl') $paginationDir = 'rtl';
else $paginationDir = 'ltr';

Pagination::setLang($paginationDir, $langNums, $linkLabels, $titleLabels);
Pagination::setClasses($pagerClass, $liClass, $liActiveClass, $aClass, $aActiveClass);
echo Pagination::paging($numberOfRecords, $pageURL, $currentPage, $resultsPerPage, $maximumLinksToDisplay, $displayArrows, $additionalQuery);
?>

        </div><!--/ pagination col -->

      </div><!--/ pagination row -->

    </div><!--/ page col -->

  </div><!--/ page row -->

</div><!--/ page container -->

<!-- delete gallery modal -->

<div class="modal fade" id="galleryDelModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">
              <?php echo $lang->gallery_list->modal->del_gallery->label;?>
            </h5>
            <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-danger font-weight-bold fw-bold"><span style="font-size:1rem;position:relative;top:-3px;"><?php echo $lang->gallery_list->modal->del_gallery->close;?></span> <span style="font-size:1.5rem">&times;</span></span>
            </button>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <form class="form-inline">
          <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
            <div class="col-12">
              <span class="text-danger">
                <?php echo $lang->gallery_list->modal->del_gallery->text;?>
              </span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
          <div class="col-6">
            <button id="btn-del-gallery" type="button" class="btn btn-danger w-75">
              <?php echo $lang->gallery_list->modal->del_gallery->command;?>
            </button>
          </div>
          <div class="col-6 text-right text-end">
            <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary w-75">
              <?php echo $lang->gallery_list->modal->del_gallery->cancel;?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--/ delete gallery modal -->

<!-- Process Result Modal -->

<div class="modal fade" id="processResultModal" tabindex="-1" role="dialog" aria-labelledby="processResultLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="processResultLabel">
              <?php echo $lang->gallery_list->modal->result->label;?>
            </h5>
            <button type="button" class="close border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-danger font-weight-bold fw-bold"><span style="font-size:1rem;position:relative;top:-3px;"><?php echo $lang->gallery_list->modal->result->close;?></span> <span style="font-size:1.5rem">&times;</span></span>
            </button>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <form class="form-inline">
          <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
            <div class="col-12">
              <span class="text-danger" id="processResultMsg"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2">
          <div class="col-12 result-button-wrapper">
            <button type="button" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary w-50">
              <?php echo $lang->gallery_list->modal->result->command;?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--/ Process Result Modal -->

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/jquery-ui-1.12.1.custom.min.js"></script>

<script src="<?php echo GALLERY_URL;?>/plugins/jquery.mobile-events.js"></script>
<script src="<?php echo GALLERY_URL;?>/plugins/jquery.ui.touch-punch.js"></script>

<!-- Compatible with bootstrap 4 and bootstrap 5 -->
<!-- Include the js files according to the version you use. -->

<script src="bootstrap-4.5.3/popper.min.js"></script>
<script src="bootstrap-4.5.3/bootstrap.min.js"></script>
<!--
<script src="bootstrap-5.0.0-beta2/popper.min.js"></script>
<script src="bootstrap-5.0.0-beta2/bootstrap.min.js"></script>
-->

<script src="<?php echo GALLERY_URL;?>/lang/js/<?php echo $langCode;?>.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/util.js"></script>

<script>

$isPhoneOrTablet = isPhoneOrTablet();

if ($isPhoneOrTablet) {
   $('#move_msg_mobile').removeClass('d-none');
   $('.card-img').each(function () {
      $(this).css('pointer-events', 'none');
   });
} else {
   $('#move_msg_desktop').removeClass('d-none');
   $('.card-img').each(function () {
      $(this).css('cursor', 'move');
   });
}

window.MIGallery = {
   sessid: "<?php echo $sessid;?>",

   OK : 200,
   ERROR : 500,

   galleryList: {
      order: '',
      galCount: 0,
      startRecordNum: 0,
      sortOldIndex: 0,
      sortNewIndex: 0,
      gals: {
         ids:[],
         sort:[]
      },

      addGalleries: function (className, order, galCount, startRecordNum) {
         this.order = order;
         this.galCount = galCount;
         this.startRecordNum = startRecordNum;
         document.querySelectorAll('.'+className).forEach(function (elem) {
            var id = elem.id.replace(/col_gal_/g,'');
            var sort = document.getElementById('sort_gal_'+id).value;
            this.gals.ids.push(id);
            this.gals.sort.push(sort);
         }, this);
      },

      sortingStartIndex: function (index) {
         this.sortOldIndex = index;
      },

      sortingStopIndex: function (index) {
         this.sortNewIndex = index;
      },

      reOrderGalleries: function () {
         var item = this.gals.ids.splice(this.sortOldIndex, 1);
         this.gals.ids.splice(this.sortNewIndex, 0, item[0]);
      },

      reNumberThumbs: function (){
         if (this.order == 'asc') {
            var num = this.startRecordNum +1;
            this.gals.ids.forEach(function(id) {
               document.getElementById('gal_number_'+id).innerText = this.convertNumToLang(num);
               num++;
            }, this);
		 } else {
            var num= this.galCount - this.startRecordNum;
            this.gals.ids.forEach(function(id) {
               document.getElementById('gal_number_'+id).innerText = this.convertNumToLang(num);
               num--;
            }, this);
		 }
      },

      removeGallery: function (id) {
         var ind = this.gals.ids.indexOf(id);
         if (ind>=0) {
            this.gals.ids.splice(ind, 1);
         }
      },

      get: function (){
         return this.gals;
      },

      getJSON: function (){
         return JSON.stringify(this.gals);
      },

      convertNumToLang: function (num){
         num = num.toString();
         if (typeof translateJS.numbers !== 'undefined') {
            return num.replace(/\d/g, d => translateJS.numbers[d])
         }
         return num;
      }
   },

   showResultModal: function (message, success=true) {
      if (success) {
         $('#processResultLabel').removeClass('text-danger').addClass('text-success').html(translateJS.uploader.resultModalLabelSuccess);
         $('#processResultMsg').removeClass('text-danger').addClass('text-success').html(message);
      } else {
         $('#processResultLabel').removeClass('text-success').addClass('text-danger').html(translateJS.uploader.resultModalLabelError);
         $('#processResultMsg').removeClass('text-success').addClass('text-danger').html(message);
      }
      $('#processResultModal').modal('show');
   },
};


window.addEventListener('load', function() {

   MIGallery.galleryList.addGalleries('thumb_gal', "<?php echo $_GET['order'];?>", <?php echo $gal_count;?>, <?php echo $start_record_num;?>);

}, false);


$(function(){
  $('.container.man-page').css({ minHeight: $(window).innerHeight() - 150 });
  $(window).resize(function(){
    $('.container.man-page').css({ minHeight: $(window).innerHeight() -150 });
  });
});

var $del_gal_id = '';

$('.del_gallery').on('tap click', function(e) { 
   $del_gal_id = $(this).attr("id").replace(/del_gal_/g,'');
   $('#galleryDelModal').modal('show');
});

$('.edit_gallery').on('tap click', function(e) { 
   var gal_id = $(this).attr("id").replace(/edit_gal_/g,'');
   window.location.href='mi-uploader.php?gal_id='+gal_id;
});

$('.view_gallery').on('tap click', function(e) { 
   var gal_id = $(this).attr("id").replace(/view_gal_/g,'');
   window.location.href='gallery-view.php?gal_id='+gal_id;
});

$('#btn-del-gallery').click(function(){
   
   $.post("<?php echo GALLERY_URL;?>/ajax-gallery-del.php", { 
      sessid: MIGallery.sessid, 
      gal_id: $del_gal_id 
   },
   function(response){
      var respArr = JSON.parse(response)
      if (respArr.code == MIGallery.OK) {
         $('#col_gal_'+$del_gal_id).remove();
         MIGallery.galleryList.removeGallery($del_gal_id);
         MIGallery.galleryList.reNumberThumbs();
         $('#galleryDelModal').modal('hide');
      } else {
         MIGallery.showResultModal(respArr.result, false);
      }
      return false;
   });
});


$("#sortGallery").sortable({
   tolerance: "pointer",
   //revert: true,
   start: function( event, ui ) {
      MIGallery.galleryList.sortingStartIndex(ui.item.index());
   },
   stop: function( event, ui ) {
      MIGallery.galleryList.sortingStopIndex(ui.item.index());
      MIGallery.galleryList.reOrderGalleries();
      MIGallery.galleryList.reNumberThumbs();
      $('#btn-save-changes-gallery').removeClass('btn-secondary disabled').addClass('btn-warning');
      $("#sortGallery").sortable("disable");
      $sortableEnabled = false;
      $tapped = null;
      $('.thumb_gal').each(function() {
          $(this).find('.card').removeClass('active');
       	  $(this).find('.card > .card-img-body > .move_gallery').removeClass('active');
      });
   }
}).sortable("disable");


var $tapped = null;
var $sortableEnabled = false;

$('.thumb_gal').on('taphold tap tapstart tapend tapmove mouseover mouseout', function(e) { 

    if ($isPhoneOrTablet && e.type == 'mouseover') return false;

    var active = $(this);
	var active_class = 'hover';

    if (e.type == 'taphold') {
	   active_class = 'tapped';
       $tapped = $(this);
	}

    if (($isPhoneOrTablet && $(this).is($tapped)) || (!$isPhoneOrTablet && e.type == 'mouseover')) {
	   if (!$sortableEnabled) {
	      $("#sortGallery").sortable("enable");
	      $sortableEnabled = true;
          $('.thumb_gal').each(function() {
             if ($(this).is(active)) {
                $(this).find('.card').addClass('active ' + active_class);
       	        $(this).find('.card > .card-img-body > .move_gallery').addClass('active ' + active_class);
             } else {
       	        $(this).find('.card').removeClass('active ' + active_class);
       	        $(this).find('.card > .card-img-body > .move_gallery').removeClass('active ' + active_class);
             }
          });
	   }
    } else if ($isPhoneOrTablet || (!$isPhoneOrTablet && e.type == 'mouseout')) {
       if ($sortableEnabled) {
         $("#sortGallery").sortable("disable");
         $sortableEnabled = false;
         $tapped = null;
         $('.thumb_gal').each(function() {
            $(this).find('.card').removeClass('active');
            $(this).find('.card > .card-img-body > .move_gallery').removeClass('active');
         });
       }
	}

});

function saveGallerySorting() {

   if ($('#btn-save-changes-gallery').hasClass('disabled')) return false;
   
   $.post("<?php echo GALLERY_URL;?>/ajax-gallery-sort.php", { 
      sessid: MIGallery.sessid, 
      gals_sort: MIGallery.galleryList.getJSON() 
   },
   function(response){
      var respArr = JSON.parse(response)
      if (respArr.code == MIGallery.OK) {
         MIGallery.showResultModal(translateJS.uploader.saveImageInfoChangesOK);
      $('#btn-save-changes-gallery').removeClass('btn-warning').addClass('btn-secondary disabled');
      } else {
         MIGallery.showResultModal(respArr.result, false);
      }
      return false;
   });
}

$('#order').change(function(){
   var order = $(this).val();
   var rpp = $('#rpp').val();
   window.location.href = "<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $_GET['page'];?>&order="+order+"&rpp="+rpp;
});

$('#rpp').change(function(){
   var rpp = $(this).val();
   var order = $('#order').val();
   window.location.href = "<?php echo $_SERVER['PHP_SELF'];?>?page=<?php echo $_GET['page'];?>&order="+order+"&rpp="+rpp;
});

</script>

<?php 
require_once('mi-footer.php');
require_once('mi-final.php');
?>

</body>
</html>