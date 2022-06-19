<?php 

/**
* 
* MIGallery Installation
* 
*/

require_once('mi-session.php');
include_once("migallery/definitions0.php");
include_once("migallery/definitions1.php");
require_once('migallery/Translator.class.php');

Translator::init($_SERVER['HTTP_ACCEPT_LANGUAGE']);
$langCode = Translator::getLangCode();
$langDir = Translator::getDirection();
$langTable = Translator::getLangTable();
$lang = Translator::translate();

if (file_exists(CONFIG_FILE)) {
?>
<!doctype html>
<html lang="<?php echo $langCode;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo $lang->install->title;?></title>
<link href="bootstrap-4.5.3/bootstrap.min.css" rel="stylesheet"/>
<link href="icons/font-awesome.min.css" rel="stylesheet" type="text/css"/>
</head>
<body style="background-color: #eee;">

<div class="container">
<div class="row d-flex justify-content-center mt-3 ml-auto mr-auto">
<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 text-center">

<div class="modal d-block position-relative">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger text-center w-100"><span class="fa fa-warning"></span> <?php echo $lang->install->title;?></h5>
      </div>
      <div class="modal-body"><p <?php if ($langDir == 'rtl') echo "style='direction:rtl'";?>><?php echo $lang->install->directories->config_alert;?></p></div>
      <div class="modal-footer d-flex justify-content-center">
      </div>
    </div>
  </div>
</div>

</div>
</div><!--row-->
</div><!--container-->

</body>
</html>

<?php 
exit;
}

function select_Timezone($selected = '') {
    $OptionsArray = timezone_identifiers_list();
        $select= '<select id="install_timezone" name="install_timezone" class="form-control w-100" dir="'.$langDir.'" data-width="100%" data-live-search="true" data-header=" " title="'.$lang->timezone->label.'">';
        foreach($OptionsArray as $key => $val) {
            $select .='<option value="'.$key.'"';
            $select .= ($val == $selected ? ' selected' : '');
            $select .= '>'.$val.'</option>';
        }
        $select.='</select>';
return $select;
}
?>
<!doctype html>
<html lang="<?php echo $langCode;?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php echo $lang->install->title;?></title>
<link href="bootstrap-4.5.3/bootstrap.min.css" rel="stylesheet"/>
<link href="css/project-bs4.css" rel="stylesheet" type="text/css"/>
<link href="icons/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/plugins/bootstrap-select-1.13.18/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/form.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/css/install.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo GALLERY_URL;?>/plugins/croppie/css/croppie.css" rel="stylesheet" type="text/css"/>
</head>
<body>
  <div class="container my-3 pt-2 pb-5">
    <div class="row d-flex justify-content-center mt-4 pt-3">
      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-10">

<div class="row mx-4 mb-3 <?php if ($langDir == 'rtl') echo 'd-flex justify-content-end';?>"><!-- row -->

<h5 class="install_header"><?php echo $lang->install->header;?></h5>

</div><!--row-->

<form method="post" id="install_form" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-inline bg-white mx-4 pt-3" style="border: 1px solid tan;" novalidate>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto my-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 pl-0 ml-3 ms-3 border border-primary border-left-0 border-start-0 border-top-0 border-right-0 border-end-0 font-weight-bold fw-bold pr-0 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="lang_title"><?php echo $lang->lang->title;?></span>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="lang_label"><?php echo $lang->lang->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="lang_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="install_lang"><?php echo $lang->lang->label;?></label>
   <div class="form_field">
   <select name="install_lang" id="install_lang" class="form-control w-100" dir="<?php echo $langDir;?>" data-width="100%" data-live-search="true" data-header=" " title="<?php echo $lang->lang->label;?>">
<?php 
   foreach ($langTable as $key => $val) {
      $select_lang = '';
      if ($langCode == $key) $select_lang = ' selected';
      echo "<option value=\"$key\"$select_lang>{$val[0]}</option>";
   }
?>
   </select>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="timezone_label"><?php echo $lang->timezone->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="timezone_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="install_timezone"><?php echo $lang->timezone->label;?></label>
   <div class="form_field">
     <?php echo select_Timezone(date_default_timezone_get());?>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="img_direction_label"><?php echo $lang->install->img_direction->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="img_direction_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>"><?php echo $lang->install->img_direction->label;?></label>
   <div class="form_field mt-3 <?php if ($langDir == 'rtl') echo 'd-flex justify-content-end';?>" id="img_direction_field">
     <input type=radio class="with-gap" id=img_dir_left name=img_direction value="ltr" <?php if ($langDir=='ltr') echo 'checked';?>> <label class="img_dir_left_label" for="img_dir_left"><?php echo $lang->install->img_direction->left;?></label>
     &nbsp;&nbsp;&nbsp;
     <input type=radio class="with-gap" id=img_dir_right name=img_direction value="rtl" <?php if ($langDir=='rtl') echo 'checked'; else echo 'disabled';?>> <label class="img_dir_right_label" for="img_dir_right"><?php echo $lang->install->img_direction->right;?></label>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto my-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 pl-0 ml-3 ms-3 border border-primary border-left-0 border-start-0 border-top-0 border-right-0 border-end-0 font-weight-bold fw-bold pr-0 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="db_info_title"><?php echo $lang->install->db_info->title;?></span>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="db_host_label"><?php echo $lang->install->db_info->host->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="db_host_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="db_host"><?php echo $lang->install->db_info->host->label;?></label>
   <div class="form_field">
   <input type=text name="db_host" id="db_host" value="localhost" class="form-control w-100" placeholder="<?php echo $lang->install->db_info->host->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_db_host"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="db_host_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->db_info->host->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="db_user_label"><?php echo $lang->install->db_info->user->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="db_user_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="db_user"><?php echo $lang->install->db_info->user->label;?></label>
   <div class="form_field">
   <input type=text name="db_user" id="db_user" value="" class="form-control w-100" placeholder="<?php echo $lang->install->db_info->user->placeholder;?>" dir="<?php echo $langDir;?>">
   <div class="invalid-feedback m-0" id="invalid_db_user"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="db_user_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->db_info->user->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="db_pass_label"><?php echo $lang->install->db_info->pass->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="db_pass_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="db_pass"><?php echo $lang->install->db_info->pass->label;?></label>
   <div class="form_field">
   <input type="password" name="db_pass" id="db_pass" value="" class="form-control w-100" placeholder="<?php echo $lang->install->db_info->pass->placeholder;?>" dir="<?php echo $langDir;?>">
   <div class="invalid-feedback m-0" id="invalid_db_pass"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="db_pass_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->db_info->pass->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="db_name_label"><?php echo $lang->install->db_info->db->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="db_name_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="db_name"><?php echo $lang->install->db_info->db->label;?></label>
   <div class="form_field">
   <input type=text name="db_name" id="db_name" value="" class="form-control w-100" placeholder="<?php echo $lang->install->db_info->db->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_db_name"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="db_name_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->db_info->db->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="db_gal_table_label"><?php echo $lang->install->db_info->gal_table->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="db_gal_table_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="db_gal_table"><?php echo $lang->install->db_info->gal_table->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="db_gal_table" id="db_gal_table" value="migallery_gal" class="form-control w-100" placeholder="<?php echo $lang->install->db_info->gal_table->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_db_gal_table"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="db_gal_table_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->db_info->gal_table->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="db_img_table_label"><?php echo $lang->install->db_info->img_table->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="db_img_table_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="db_img_table"><?php echo $lang->install->db_info->img_table->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="db_img_table" id="db_img_table" value="migallery_img" class="form-control w-100" placeholder="<?php echo $lang->install->db_info->img_table->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_db_img_table"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="db_img_table_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->db_info->img_table->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto my-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 pl-0 ml-3 ms-3 border border-primary border-left-0 border-start-0 border-top-0 border-right-0 border-end-0 font-weight-bold fw-bold pr-0 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="directories_title"><?php echo $lang->install->directories->title;?></span>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0 <?php if ($langDir == 'rtl') echo 'text-right text-end';?>" style="margin-right: 0!important;">
    <div id="db_alert" class="alert alert-warning" role="alert" <?php if ($langDir == 'rtl') echo "style='direction:rtl'";?>><?php printf($lang->install->directories->db_alert, "chmod -R 755 ".DB_DIR);?></div>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>" style="margin: 0 1rem 1rem 1rem;">
    <span class="db_dir_label"><?php echo $lang->install->directories->db_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="db_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>"><?php echo $lang->install->directories->db_dir->label;?></label>
   <div class="form_field">
      <?php echo DB_DIR;?>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0 <?php if ($langDir == 'rtl') echo 'text-right text-end';?>" style="margin-right: 0!important;">
    <div id="dir_perm_alert" class="alert alert-warning" role="alert" <?php if ($langDir == 'rtl') echo "style='direction:rtl'";?>><?php echo $lang->install->directories->dir_perm_alert;?></div>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="xml_dir_label"><?php echo $lang->install->directories->xml_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="xml_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="xml_dir"><?php echo $lang->install->directories->xml_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="xml_dir" id="xml_dir" value="<?php echo XML_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->xml_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_xml_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="xml_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->xml_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="tmp_dir_label"><?php echo $lang->install->directories->tmp_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="tmp_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="tmp_dir"><?php echo $lang->install->directories->tmp_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="tmp_dir" id="tmp_dir" value="<?php echo TMP_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->tmp_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_tmp_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="tmp_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->tmp_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="tmp_thumb_dir_label"><?php echo $lang->install->directories->tmp_thumb_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="tmp_thumb_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="tmp_thumb_dir"><?php echo $lang->install->directories->tmp_thumb_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="tmp_thumb_dir" id="tmp_thumb_dir" value="<?php echo TMP_THUMB_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->tmp_thumb_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_tmp_thumb_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="tmp_thumb_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->tmp_thumb_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="tmp_thumb_c_dir_label"><?php echo $lang->install->directories->tmp_thumb_c_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="tmp_thumb_c_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="tmp_thumb_c_dir"><?php echo $lang->install->directories->tmp_thumb_c_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="tmp_thumb_c_dir" id="tmp_thumb_c_dir" value="<?php echo TMP_THUMB_C_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->tmp_thumb_c_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_tmp_thumb_c_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="tmp_thumb_c_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->tmp_thumb_c_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="tmp_slide_dir_label"><?php echo $lang->install->directories->tmp_slide_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="tmp_slide_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="tmp_slide_dir"><?php echo $lang->install->directories->tmp_slide_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="tmp_slide_dir" id="tmp_slide_dir" value="<?php echo TMP_SLIDE_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->tmp_slide_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_tmp_slide_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="tmp_slide_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->tmp_slide_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="img_base_dir_label"><?php echo $lang->install->directories->img_base_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="img_base_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="img_base_dir"><?php echo $lang->install->directories->img_base_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="img_base_dir" id="img_base_dir" value="<?php echo IMG_BASE_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->img_base_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_img_base_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_base_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->img_base_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0 <?php if ($langDir == 'rtl') echo 'text-right text-end';?>" style="margin-right: 0!important;">
    <div id="img_dir_alert" class="alert alert-warning" role="alert" <?php if ($langDir == 'rtl') echo "style='direction:rtl'";?>><?php echo $lang->install->directories->img_dir_alert;?></div>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="img_dir_label"><?php echo $lang->install->directories->img_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="img_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="img_dir"><?php echo $lang->install->directories->img_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="img_dir" id="img_dir" value="<?php echo IMG_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->img_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_img_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->img_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="img_thumb_dir_label"><?php echo $lang->install->directories->img_thumb_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="img_thumb_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="img_thumb_dir"><?php echo $lang->install->directories->img_thumb_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="img_thumb_dir" id="img_thumb_dir" value="<?php echo IMG_THUMB_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->img_thumb_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_img_thumb_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_thumb_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->img_thumb_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="img_thumb_c_dir_label"><?php echo $lang->install->directories->img_thumb_c_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="img_thumb_c_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="img_thumb_c_dir"><?php echo $lang->install->directories->img_thumb_c_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="img_thumb_c_dir" id="img_thumb_c_dir" value="<?php echo IMG_THUMB_C_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->img_thumb_c_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_img_thumb_c_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_thumb_c_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->img_thumb_c_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="img_slide_dir_label"><?php echo $lang->install->directories->img_slide_dir->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="img_slide_dir_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="img_slide_dir"><?php echo $lang->install->directories->img_slide_dir->label;?></label>
   <div class="form_field">
   <input type=text class="form-control w-100" name="img_slide_dir" id="img_slide_dir" value="<?php echo IMG_SLIDE_DIR;?>" class="form-control w-100" placeholder="<?php echo $lang->install->directories->img_slide_dir->placeholder;?>" dir="auto">
   <div class="invalid-feedback m-0" id="invalid_img_slide_dir"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_slide_dir_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->install->directories->img_slide_dir->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto my-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 pl-0 ml-3 ms-3 border border-primary border-left-0 border-start-0 border-top-0 border-right-0 border-end-0 font-weight-bold fw-bold pr-0 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="settings_title"><?php echo $lang->image->settings->title;?></span>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="upload_limit_label"><?php echo $lang->image->settings->upload_limit->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="upload_limit_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="max_upload_limit"><?php echo $lang->image->settings->upload_limit->label;?></label>
   <div class="form_field">
   <input type=tel class="form-control w-100" name="max_upload_limit" id="max_upload_limit" value="40" class="form-control w-100" placeholder="<?php echo $lang->image->settings->upload_limit->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_max_upload_limit"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="upload_limit_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->settings->upload_limit->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="max_file_size_label"><?php echo $lang->image->settings->max_file_size->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="max_file_size_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="max_file_size"><?php echo $lang->image->settings->max_file_size->label;?></label>
   <div class="form_field">
   <input type=tel class="form-control w-100" name="max_file_size" id="max_file_size" value="20" class="form-control w-100" placeholder="<?php echo $lang->image->settings->max_file_size->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_max_file_size"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="max_file_size_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->settings->max_file_size->validate;?></span></div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="up_img_min_resolution_label"><?php echo $lang->image->settings->min_resolution->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 field_block">
    <label class="up_img_min_resolution_label mr-sm-2 ml-0 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="up_img_min_width"><?php echo $lang->image->settings->min_resolution->label;?></label>
   <div class="d-block float-right float-end">
   <div class="form_field" style="width:10%;text-align:center;line-height:2.25;font-size:1rem;">px</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="up_img_min_height" id="up_img_min_height" value="240" class="form-control w-100" placeholder="<?php echo $lang->image->settings->min_resolution->height->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_up_img_min_height"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="up_img_min_height_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->settings->min_resolution->height->validate;?></span></div>
   </div>
   <div class="form_field" style="width:10%;text-align:center;line-height:1.75;font-size:1.25rem;">&times;</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="up_img_min_width" id="up_img_min_width" value="240" class="form-control w-100" placeholder="<?php echo $lang->image->settings->min_resolution->width->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_up_img_min_width"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="up_img_min_width_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->settings->min_resolution->width->validate;?></span></div>
   </div>
  </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="up_img_max_resolution_label"><?php echo $lang->image->settings->max_resolution->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 field_block">
    <label class="up_img_max_resolution_label mr-sm-2 ml-0 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="up_img_max_width"><?php echo $lang->image->settings->max_resolution->label;?></label>
   <div class="d-block float-right float-end">
   <div class="form_field" style="width:10%;text-align:center;line-height:2.25;font-size:1rem;">px</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="up_img_max_height" id="up_img_max_height" value="8000" class="form-control w-100" placeholder="<?php echo $lang->image->settings->max_resolution->height->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_up_img_max_height"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="up_img_max_height_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->settings->max_resolution->height->validate;?></span></div>
   </div>
   <div class="form_field" style="width:10%;text-align:center;line-height:1.75;font-size:1.25rem;">&times;</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="up_img_max_width" id="up_img_max_width" value="8000" class="form-control w-100" placeholder="<?php echo $lang->image->settings->max_resolution->width->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_up_img_max_width"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="up_img_max_width_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->settings->max_resolution->width->validate;?></span></div>
   </div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto my-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 pl-0 ml-3 ms-3 border border-primary border-left-0 border-start-0 border-top-0 border-right-0 border-end-0 font-weight-bold fw-bold pr-0 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="transform_title"><?php echo $lang->image->transform->title;?></span>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="full_resolution_label"><?php echo $lang->image->transform->full_resolution->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 field_block">
    <label class="full_resolution_label mr-sm-2 ml-0 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="img_full_width"><?php echo $lang->image->transform->full_resolution->label;?></label>
   <div class="d-block float-right float-end">
   <div class="form_field" style="width:10%;text-align:center;line-height:2.25;font-size:1rem;">px</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="img_full_height" id="img_full_height" value="1080" class="form-control w-100" placeholder="<?php echo $lang->image->transform->full_resolution->height->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_img_full_height"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_full_height_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->transform->full_resolution->height->validate;?></span></div>
   </div>
   <div class="form_field" style="width:10%;text-align:center;line-height:1.75;font-size:1.25rem;">&times;</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="img_full_width" id="img_full_width" value="1920" class="form-control w-100" placeholder="<?php echo $lang->image->transform->full_resolution->width->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_img_full_width"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_full_width_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->transform->full_resolution->width->validate;?></span></div>
   </div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="thumb_resolution_label"><?php echo $lang->image->transform->thumb_resolution->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 field_block">
    <label class="thumb_resolution_label mr-sm-2 ml-0 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="img_thumb_width"><?php echo $lang->image->transform->thumb_resolution->label;?></label>
   <div class="d-block float-right float-end">
   <div class="form_field" style="width:10%;text-align:center;line-height:2.25;font-size:1rem;">px</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="img_thumb_height" id="img_thumb_height" value="218" class="form-control w-100" placeholder="<?php echo $lang->image->transform->thumb_resolution->height->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_img_thumb_height"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_thumb_height_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->transform->thumb_resolution->height->validate;?></span></div>
   </div>
   <div class="form_field" style="width:10%;text-align:center;line-height:1.75;font-size:1.25rem;">&times;</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="img_thumb_width" id="img_thumb_width" value="328" class="form-control w-100" placeholder="<?php echo $lang->image->transform->thumb_resolution->width->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_img_thumb_width"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_thumb_width_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->transform->thumb_resolution->width->validate;?></span></div>
   </div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="slide_resolution_label"><?php echo $lang->image->transform->slide_resolution->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 field_block">
    <label class="slide_resolution_label mr-sm-2 ml-0 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="img_slide_width"><?php echo $lang->image->transform->slide_resolution->label;?></label>
   <div class="d-block float-right float-end">
   <div class="form_field" style="width:10%;text-align:center;line-height:2.25;font-size:1rem;">px</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="img_slide_height" id="img_slide_height" value="300" class="form-control w-100" placeholder="<?php echo $lang->image->transform->slide_resolution->height->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_img_slide_height"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_slide_height_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->transform->slide_resolution->height->validate;?></span></div>
   </div>
   <div class="form_field" style="width:10%;text-align:center;line-height:1.75;font-size:1.25rem;">&times;</div>
   <div class="form_field" style="width:40%">
   <input type=tel class="form-control w-100" name="img_slide_width" id="img_slide_width" value="435" class="form-control w-100" placeholder="<?php echo $lang->image->transform->slide_resolution->width->placeholder;?>" dir="ltr">
   <div class="invalid-feedback m-0" id="invalid_img_slide_width"><i class="fa fa-exclamation-triangle mt-1 <?php if ($langDir=='rtl') echo 'float-right float-end ml-1 ms-1'; else echo 'float-left float-start mr-1 me-1';?>" aria-hidden="true"></i> <span class="img_slide_width_validate <?php if ($langDir=='rtl') echo 'float-right float-end'; else echo 'float-left float-start';?>"><?php echo $lang->image->transform->slide_resolution->width->validate;?></span></div>
   </div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="jpg_quality_label"><?php echo $lang->image->transform->jpg_quality->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="jpg_quality_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="jpg_quality"><?php echo $lang->image->transform->jpg_quality->label;?></label>
   <div class="form_field d-flex justify-content-between">
     <div class="col-10 px-0 pt-2 mt-1">
       <input type="range" class="slider" name="jpg_quality" id="jpg_quality" min="0" max="100" value="80">
     </div>
     <div class="col-2 p-0 text-center">
       <div class="" id="jpg_quality_val"></div>
     </div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="d-none d-sm-block col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="png_compress_label"><?php echo $lang->image->transform->png_compress->label;?></span>
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5 pl-0">
    <label class="png_compress_label mr-sm-2 ml-2 ms-2 d-sm-none <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="png_compress"><?php echo $lang->image->transform->png_compress->label;?></label>
   <div class="form_field d-flex justify-content-between">
     <div class="col-10 px-0 pt-2 mt-1">
       <input type="range" class="slider" name="png_compress" id="png_compress" min="0" max="9" value="6">
     </div>
     <div class="col-2 p-0 text-center">
       <div class="" id="png_compress_val"></div>
     </div>
   </div>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 pl-0">
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto my-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-9 col-lg-9 col-xl-9 pl-0 ml-3 ms-3 border border-primary border-left-0 border-start-0 border-top-0 border-right-0 border-end-0 font-weight-bold fw-bold pr-0 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
    <span class="watermark_title"><?php echo $lang->image->watermark->title;?></span>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-12 pr-0 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
       <input type="checkbox" id="wm_use" name="wm_use" value="1" class="filled-in" style="width:18px;height:18px" data-toggle="collapse" data-bs-toggle="collapse" data-target="#block-watermark" data-bs-target="#block-watermark" aria-expanded="true" aria-controls="block-watermark">
       <label class="watermark_label d-inline-block <?php if ($langDir == 'rtl') echo 'float-right float-end';?>" for="wm_use" style="vertical-align: middle"><?php echo $lang->image->watermark->label;?></label>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-3 collapse" id="block-watermark">
  <div class="watermark-col col-sm-12 col-md-6 col-lg-4 col-xl-4">

    <div class="my-3 ml-0">
      <span class="watermark_set_label"><?php echo $lang->image->watermark->set;?></span>
    </div>

    <div style="display:grid">
      <div id="wm_browse_other" style="float:left">
	     <input type=button id="select_wm_file" value="<?php echo $lang->image->watermark->browse;?>" style="padding:5px;margin:0 0 10px 0;cursor:pointer;white-space: normal;text-align:left" />
	  </div>
      <div id="wm_browse_msie" style="float:left">
		 <div style="float:left;width:300px;white-space: nowrap">
		   1- <input id="wm_file" type="file" name="wm_file" size="1" style="width:275px;padding:5px 0;cursor:pointer" />
		 </div>
	  </div>
      <div style="float:left;display:block;margin:5px 0 5px 0px;font-size:14.5px;font-family:arial">
        <span class="watermark_adjust_label"><?php echo $lang->image->watermark->adjust;?></span>
	  </div>
      <div style="clear:both"></div>
      <div style="float:left">
        <input type="button" id="buttonUpload" value="<?php echo $lang->image->watermark->upload;?>" style="padding:5px;cursor:pointer" disabled>
	  </div>
      <div style="float:left;margin-left: 10px">
        <div id="loading" class="d-none" style="float:left;margin-top:10px">
          <img src="migallery/css/loading.gif" width="24" height="24"> <span class="watermark_uploading_label"><?php echo $lang->image->watermark->uploading;?></span>
        </div>
        <div id="loaded" class="d-none" style="float:left;margin-top:10px">
          <i class="fa fa-check"></i> <span class="watermark_uploaded_label"><?php echo $lang->image->watermark->uploaded;?></span>
        </div>
	  </div>
      <input id="wmark_sel" type="hidden" name="wmark_sel" value="0">
      <input id="wmark_up" type="hidden" name="wmark_up" value="0">
    </div>

  </div>
  <div class="watermark-col col-sm-12 col-md-6 col-lg-5 col-xl-5 text-center">

    <div class="my-3 ml-0">
      <span class="watermark_image_label"><?php echo $lang->image->watermark->image;?></span>
    </div>
	<div id="croppie-img" style="width:280px;display:inline-block;margin-top:-10px;"></div>

  </div>
  <div class="watermark-col col-sm-12 col-md-5 col-lg-3 col-xl-3 text-center">

    <div class="my-3 ml-0">
      <span class="watermark_position_label"><?php echo $lang->image->watermark->position;?></span>
    </div>
    
    	  <div class="wm-position-block row btn-group" data-toggle="buttons" style="width:150px;display:inline-flex">
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_TOP_LEFT')" class="btn btn-sm btn-outline-info" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_TOP_LEFT"/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_TOP_CENTER')" class="btn btn-sm btn-outline-info" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_TOP_CENTER"/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_TOP_RIGHT')" class="btn btn-sm btn-outline-info" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_TOP_RIGHT"/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_LEFT_MIDDLE')" class="btn btn-sm btn-outline-info" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_LEFT_MIDDLE"/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_CENTER')" class="btn btn-sm btn-outline-info active" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_CENTER" checked/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_RIGHT_MIDDLE')" class="btn btn-sm btn-outline-info" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_RIGHT_MIDDLE"/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_BOTTOM_LEFT')" class="btn btn-sm btn-outline-info" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_BOTTOM_LEFT"/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_BOTTOM_CENTER')" class="btn btn-sm btn-outline-info" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_BOTTOM_CENTER"/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	   	 <div class="col-4">
        <a href="#!" onclick="$('#wm_position').val('WM_BOTTOM_RIGHT')" class="btn btn-sm btn-outline-info" style="width:50px;height:50px;">
          <input type="radio" class="wm-pos" name="_wm_position" value="WM_BOTTOM_RIGHT"/> &nbsp;&nbsp;
        </a>
    	   	 </div>
    	  </div>

        <input type="hidden" name="wm_position" id="wm_position" value="WM_CENTER"/>

  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 px-0 <?php if ($langDir == 'rtl') echo 'text-right text-end';?>" style="margin-right: 0!important;">
    <div id="install_alert" class="alert d-none" role="alert" <?php if ($langDir == 'rtl') echo "style='direction:rtl'";?>></div>
  </div>
</div>

<div class="row w-100 ml-auto ms-auto mr-auto me-auto mb-2 <?php if ($langDir == 'rtl') echo 'd-flex flex-row-reverse';?>">
  <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 form_label2 <?php if ($langDir == 'rtl') echo 'text-right text-end mr-3 me-3';?>">
  </div>
  <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
    <button id="install_submit" type="button" class="btn btn-primary w-100"><?php echo $lang->install->submit;?></button>
  </div>
  <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
  </div>
</div>

</form>

      </div>
    </div>
  </div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="bootstrap-4.5.3/popper.min.js"></script>
<script src="bootstrap-4.5.3/bootstrap.min.js"></script>
<script src="<?php echo GALLERY_URL;?>/plugins/bootstrap-select-1.13.18/js/bootstrap-select.min.js"></script>
<script src="<?php echo GALLERY_URL;?>/plugins/jquery.mask.min.js"></script>
<script src="<?php echo GALLERY_URL;?>/plugins/croppie/js/croppie.js"></script>
<script src="<?php echo GALLERY_URL;?>/lang/js/<?php echo $langCode;?>.js"></script>
<script src="<?php echo GALLERY_URL;?>/js/util.js"></script>

<script type="text/javascript">

// device detection
if(isPhone()) { 
  $('select').selectpicker('mobile');
} else {
  $('select').selectpicker({/*style: 'btn-select',*/ size: 5});
}


var slider_png = document.getElementById("png_compress");
var output_png = document.getElementById("png_compress_val");
output_png.innerHTML = slider_png.value;

slider_png.oninput = function() {
  output_png.innerHTML = this.value;
}

var slider_jpg = document.getElementById("jpg_quality");
var output_jpg = document.getElementById("jpg_quality_val");
output_jpg.innerHTML = slider_jpg.value;

slider_jpg.oninput = function() {
  output_jpg.innerHTML = this.value;
}

</script>

<script>

var $db_installed = false;
var $db_dir = '<?php echo DB_DIR;?>';
var $js_msgs = {
   submit_to_continue: '<?php echo $lang->install->submit_continue;?>',
   alert_installing: '<?php echo $lang->install->alert->installing;?>',
   alert_success: '<?php echo $lang->install->alert->success;?>',
   alert_error: '<?php echo $lang->install->alert->error;?>'
};

(function() {
  'use strict';

var form_is_invalid = false;

var validate_elem = ["db_host", "db_user", "db_name", "db_img_table", "db_gal_table", "xml_dir", "tmp_dir", "tmp_thumb_dir", "tmp_thumb_c_dir", "tmp_slide_dir", "img_base_dir", "img_dir", "img_thumb_dir", "img_thumb_c_dir", "img_slide_dir", "max_upload_limit", "max_file_size", "up_img_min_width", "up_img_min_height", "up_img_max_width", "up_img_max_height", "img_full_width", "img_full_height", "img_thumb_width", "img_thumb_height", "img_slide_width", "img_slide_height"];
  
  window.addEventListener('load', function() {

    $("#max_upload_limit").mask("00",{reverse:true});
    $("#max_file_size").mask("00",{reverse:true});
    $("#up_img_min_width").mask("0000",{reverse:true});
    $("#up_img_min_height").mask("0000",{reverse:true});
    $("#up_img_max_width").mask("00000",{reverse:true});
    $("#up_img_max_height").mask("00000",{reverse:true});
    $("#img_full_width").mask("00000",{reverse:true});
    $("#img_full_height").mask("00000",{reverse:true});
    $("#img_thumb_width").mask("000",{reverse:true});
    $("#img_thumb_height").mask("000",{reverse:true});
    $("#img_slide_width").mask("0000",{reverse:true});
    $("#img_slide_height").mask("0000",{reverse:true});
    //$("#jpg_quality").mask("0.00",{reverse:true});

    var form = document.getElementById('install_form');
    var lang_select = document.getElementById('install_lang');
    var btn = document.getElementById('install_submit');

    for (var i=0; i<validate_elem.length; i++) {
       document.getElementById(validate_elem[i]).required = false;
    }

    for (var i=0; i<validate_elem.length; i++) {
       document.getElementById(validate_elem[i]).addEventListener('change', function(event) {
          form_is_invalid = false;

          var id = this.getAttribute('id');

          if(this.value == '') {
             document.getElementById(id).required = true;
             document.getElementById("invalid_"+id).classList.remove('d-none');
             document.getElementById("invalid_"+id).classList.add('d-block');
             form_is_invalid = true;
          } else {
             document.getElementById(id).required = false;
             document.getElementById("invalid_"+id).classList.remove('d-block');
             document.getElementById("invalid_"+id).classList.add('d-none');
          }
       }, false);
    }

    btn.addEventListener('click', function(event) {
    	
    	$("#install_alert").addClass("d-none");
    	
    	if ($('#install_submit').hasClass('disabled')) return false;
		if ($('#wmark_sel').val()=='1' && $('#wmark_up').val()=='0') {
           $("#install_alert").removeClass("d-none alert-success alert-info").addClass("alert-danger").html("<span class=\"fa fa-times\"></span> First, you have to upload the watermark file you selected to the server.");
           return false;
		}

        form_is_invalid = false;

    	if ($db_installed) {
			window.location.href = 'gallery-man.php';
		} else {
           for (var i=0; i<validate_elem.length; i++) {
              document.getElementById(validate_elem[i]).required = false;
           }

           for (var i=0; i<validate_elem.length; i++) {
              var id = validate_elem[i];
              if(document.getElementById(id).value == '') {
                 document.getElementById(id).required = true;
                  document.getElementById("invalid_"+id).classList.remove('d-none');
                  document.getElementById("invalid_"+id).classList.add('d-block');
                  form_is_invalid = true;
			  } else {
                 document.getElementById(id).required = false;
                 document.getElementById("invalid_"+id).classList.remove('d-block');
                 document.getElementById("invalid_"+id).classList.add('d-none');
			  }
           }

           for (var i=0; i<validate_elem.length; i++) {

              if($('#'+validate_elem[i]).val() == '') {
                 var pos = getOffset(document.getElementById(validate_elem[i])).top -120;
        	     $('html, body').animate({scrollTop: pos}, "slow");
        	     break;
			  }
	       }

           if (form_is_invalid) {
              return false;
           }
        
           var wm_use = 0;
           if ($('#wm_use').is(':checked')) wm_use = 1;

           $('#install_alert').removeClass('d-none alert-danger alert-success').addClass('d-block alert-info').html("<span class=\"icon-loading\"></span> " + $js_msgs.alert_installing);
           $('#install_submit').prop('disabled', true);

           $.post("<?php echo GALLERY_URL;?>/install-ajax-db-config.php", 
           { 
              img_direction:$('input[name="img_direction"]:checked').val(), 
              lang:$('#install_lang').val(), 
              timezone:$('#install_timezone option:selected').text(), 
              db_host:$('#db_host').val(), 
              db_user:$('#db_user').val(), 
              db_pass:$('#db_pass').val(), 
              db_name:$('#db_name').val(), 
              db_img_table:$('#db_img_table').val(), 
              db_gal_table:$('#db_gal_table').val(), 
              xml_dir:$('#xml_dir').val(), 
              tmp_dir:$('#tmp_dir').val(), 
              tmp_thumb_dir:$('#tmp_thumb_dir').val(), 
              tmp_thumb_c_dir:$('#tmp_thumb_c_dir').val(), 
              tmp_slide_dir:$('#tmp_slide_dir').val(), 
              img_base_dir:$('#img_base_dir').val(), 
              img_dir:$('#img_dir').val(), 
              img_thumb_dir:$('#img_thumb_dir').val(), 
              img_thumb_c_dir:$('#img_thumb_c_dir').val(), 
              img_slide_dir:$('#img_slide_dir').val(), 
              max_upload_limit:$('#max_upload_limit').val(), 
              max_file_size:$('#max_file_size').val(), 
              up_img_min_width:$('#up_img_min_width').val(), 
              up_img_min_height:$('#up_img_min_height').val(), 
              up_img_max_width:$('#up_img_max_width').val(), 
              up_img_max_height:$('#up_img_max_height').val(), 
              img_full_width:$('#img_full_width').val(), 
              img_full_height:$('#img_full_height').val(), 
              img_thumb_width:$('#img_thumb_width').val(), 
              img_thumb_height:$('#img_thumb_height').val(), 
              img_slide_width:$('#img_slide_width').val(), 
              img_slide_height:$('#img_slide_height').val(), 
              jpg_quality:$('#jpg_quality').val(), 
              png_compress:$('#png_compress').val(), 
              wm_use:wm_use, 
              wm_position:$('#wm_position').val() 
            },
			  
            function(response){

               if (response == 'ok') {
                  $("#install_alert").removeClass("alert-danger alert-info").addClass("alert-success").html("<span class=\"fa fa-check\"></span> " + $js_msgs.alert_success);
                  $('#install_submit').prop('disabled', false);
                  $('#install_submit').html($js_msgs.submit_to_continue);
                  $db_installed = true;
               } else if (response == 'error') {
                  $("#install_alert").removeClass("alert-success alert-info").addClass("alert-danger").html("<span class=\"fa fa-times\"></span> " + $js_msgs.alert_error);
               } else {
                  $("#install_alert").removeClass("alert-success alert-info").addClass("alert-danger").html("<span class=\"fa fa-times\"></span> " + response);
			   }
                  return false;
            });
		}
      
    }, false);

    lang_select.addEventListener('change', function(event) {

       $.post("<?php echo GALLERY_URL;?>/install-ajax-get-translate.php", { lang:$('#install_lang').val() },
			  
          function(response){

             var arr = JSON.parse(response);
             var dir = arr[0];
             var lang = arr[1];
             
             $('.alert').each(function() {
                $(this).css('direction', dir);
             });

             if (dir == 'rtl') {
                $('#img_direction_field').addClass('d-flex justify-content-end');
                $('#img_dir_right').prop('checked', true);
                $('#img_dir_right').prop('disabled', false);
                $('.install_header').each(function() {
                   $(this).parent().addClass('d-flex justify-content-end');
                });
                $('form > .row').each(function() {
                   $(this).addClass('d-flex flex-row-reverse');
                   if ($(this).children(":first").hasClass('watermark-col')) {
                      $(this).children(":first").addClass("text-right text-end");
				   } else {
                   $(this).children(":first").addClass("text-right text-end mr-3 me-3");
				   }

                   $(this).find("label").not(".img_dir_left_label .img_dir_right_label").addClass("float-right float-end");
                });
                $('.invalid-feedback > i').each(function() {
                   $(this).removeClass('float-left float-start mr-1 me-1').addClass('float-right float-end ml-1 ms-1');
                });
                $('.invalid-feedback > span').each(function() {
                   $(this).removeClass('float-left float-start').addClass('float-right float-end');
                });
			 } else {
                $('#img_direction_field').removeClass('d-flex justify-content-end');
                $('#img_dir_left').prop('checked', true);
                $('#img_dir_right').prop('disabled', true);
                $('.install_header').each(function() {
                   $(this).parent().removeClass('d-flex justify-content-end');
                });
                $('form > .row').each(function() {
                   $(this).removeClass('d-flex flex-row-reverse');
                   if ($(this).children(":first").hasClass('watermark-col')) {
                      $(this).children(":first").removeClass("text-right text-end");
				   } else {
                   $(this).children(":first").removeClass("text-right text-end mr-3 me-3");
				   }
                   $(this).find("label").removeClass("float-right float-end");
                });
                $('.invalid-feedback > i').each(function() {
                   $(this).removeClass('float-right float-end ml-1 ms-1').addClass('float-left float-start mr-1 me-1');
                });
                $('.invalid-feedback > span').each(function() {
                   $(this).removeClass('float-right float-end').addClass('float-left float-start');
                });
			 }
             
             document.title = lang.install.title;
             
             $('.install_header').each(function() {
                $(this).text(lang.install.header);
             });

             $('.lang_title').each(function() {
                $(this).text(lang.lang.title);
             });

             $('.lang_label').each(function() {
                $(this).text(lang.lang.label);
             });

             $('#install_lang').css('direction', dir);

             $('.timezone_label').each(function() {
                $(this).text(lang.timezone.label);
             });

             $('#install_timezone').css('direction', dir);

             $('.img_direction_label').each(function() {
                $(this).text(lang.install.img_direction.label);
             });

             $('.img_dir_left_label').each(function() {
                $(this).text(lang.install.img_direction.left);
             });

             $('.img_dir_right_label').each(function() {
                $(this).text(lang.install.img_direction.right);
             });

             $('.db_info_title').each(function() {
                $(this).text(lang.install.db_info.title);
             });

             $('.db_host_label').each(function() {
                $(this).text(lang.install.db_info.host.label);
             });

             $('#db_host').attr("placeholder", lang.install.db_info.host.placeholder);
             //$('#db_host').css('direction', dir);

             $('.db_host_validate').each(function() {
                $(this).text(lang.install.db_info.host.validate);
             });

             $('.db_user_label').each(function() {
                $(this).text(lang.install.db_info.user.label);
             });

             $('#db_user').attr("placeholder", lang.install.db_info.user.placeholder);
             $('#db_user').css('direction', dir);

             $('.db_user_validate').each(function() {
                $(this).text(lang.install.db_info.user.validate);
             });

             $('.db_pass_label').each(function() {
                $(this).text(lang.install.db_info.pass.label);
             });

             $('#db_pass').attr("placeholder", lang.install.db_info.pass.placeholder);
             $('#db_pass').css('direction', dir);

             $('.db_pass_validate').each(function() {
                $(this).text(lang.install.db_info.pass.validate);
             });

             $('.db_name_label').each(function() {
                $(this).text(lang.install.db_info.db.label);
             });

             $('#db_name').attr("placeholder", lang.install.db_info.db.placeholder);
             //$('#db_name').css('direction', dir);

             $('.db_name_validate').each(function() {
                $(this).text(lang.install.db_info.db.validate);
             });

             $('.db_gal_table_label').each(function() {
                $(this).text(lang.install.db_info.gal_table.label);
             });

             $('#db_gal_table').attr("placeholder", lang.install.db_info.gal_table.placeholder);
             //$('#db_gal_table').css('direction', dir);

             $('.db_gal_table_validate').each(function() {
                $(this).text(lang.install.db_info.gal_table.validate);
             });

             $('.db_img_table_label').each(function() {
                $(this).text(lang.install.db_info.img_table.label);
             });

             $('#db_img_table').attr("placeholder", lang.install.db_info.img_table.placeholder);
             //$('#db_img_table').css('direction', dir);

             $('.db_img_table_validate').each(function() {
                $(this).text(lang.install.db_info.img_table.validate);
             });

             $('.directories_title').each(function() {
                $(this).text(lang.install.directories.title);
             });

             $('#db_alert').html(lang.install.directories.db_alert.replace(/\%s/gi, "chmod -R 755 "+$db_dir));
             $('#dir_perm_alert').html(lang.install.directories.dir_perm_alert);
             $('#img_dir_alert').html(lang.install.directories.img_dir_alert);

             $('.db_dir_label').each(function() {
                $(this).text(lang.install.directories.db_dir.label);
             });

             $('.xml_dir_label').each(function() {
                $(this).text(lang.install.directories.xml_dir.label);
             });

             $('#xml_dir').attr("placeholder", lang.install.directories.xml_dir.placeholder);

             $('.xml_dir_validate').each(function() {
                $(this).text(lang.install.directories.xml_dir.validate);
             });

             $('.tmp_dir_label').each(function() {
                $(this).text(lang.install.directories.tmp_dir.label);
             });

             $('#tmp_dir').attr("placeholder", lang.install.directories.tmp_dir.placeholder);

             $('.tmp_dir_validate').each(function() {
                $(this).text(lang.install.directories.tmp_dir.validate);
             });

             $('.tmp_thumb_dir_label').each(function() {
                $(this).text(lang.install.directories.tmp_thumb_dir.label);
             });

             $('#tmp_thumb_dir').attr("placeholder", lang.install.directories.tmp_thumb_dir.placeholder);

             $('.tmp_thumb_dir_validate').each(function() {
                $(this).text(lang.install.directories.tmp_thumb_dir.validate);
             });

             $('.tmp_thumb_c_dir_label').each(function() {
                $(this).text(lang.install.directories.tmp_thumb_c_dir.label);
             });

             $('#tmp_thumb_c_dir').attr("placeholder", lang.install.directories.tmp_thumb_c_dir.placeholder);

             $('.tmp_thumb_c_dir_validate').each(function() {
                $(this).text(lang.install.directories.tmp_thumb_c_dir.validate);
             });

             $('.tmp_slide_dir_label').each(function() {
                $(this).text(lang.install.directories.tmp_slide_dir.label);
             });

             $('#tmp_slide_dir').attr("placeholder", lang.install.directories.tmp_slide_dir.placeholder);

             $('.tmp_slide_dir_validate').each(function() {
                $(this).text(lang.install.directories.tmp_slide_dir.validate);
             });

             $('.img_base_dir_label').each(function() {
                $(this).text(lang.install.directories.img_base_dir.label);
             });

             $('#img_base_dir').attr("placeholder", lang.install.directories.img_base_dir.placeholder);

             $('.img_base_dir_validate').each(function() {
                $(this).text(lang.install.directories.img_base_dir.validate);
             });

             $('.img_dir_label').each(function() {
                $(this).text(lang.install.directories.img_dir.label);
             });

             $('#img_dir').attr("placeholder", lang.install.directories.img_dir.placeholder);

             $('.img_dir_validate').each(function() {
                $(this).text(lang.install.directories.img_dir.validate);
             });

             $('.img_thumb_dir_label').each(function() {
                $(this).text(lang.install.directories.img_thumb_dir.label);
             });

             $('#img_thumb_dir').attr("placeholder", lang.install.directories.img_thumb_dir.placeholder);

             $('.img_thumb_dir_validate').each(function() {
                $(this).text(lang.install.directories.img_thumb_dir.validate);
             });

             $('.img_thumb_c_dir_label').each(function() {
                $(this).text(lang.install.directories.img_thumb_c_dir.label);
             });

             $('#img_thumb_c_dir').attr("placeholder", lang.install.directories.img_thumb_c_dir.placeholder);

             $('.img_thumb_c_dir_validate').each(function() {
                $(this).text(lang.install.directories.img_thumb_c_dir.validate);
             });

             $('.img_slide_dir_label').each(function() {
                $(this).text(lang.install.directories.img_slide_dir.label);
             });

             $('#img_slide_dir').attr("placeholder", lang.install.directories.img_slide_dir.placeholder);

             $('.img_slide_dir_validate').each(function() {
                $(this).text(lang.install.directories.img_slide_dir.validate);
             });

             $('.settings_title').each(function() {
                $(this).text(lang.image.settings.title);
             });

             $('.upload_limit_label').each(function() {
                $(this).text(lang.image.settings.upload_limit.label);
             });

             $('#max_upload_limit').attr("placeholder", lang.image.settings.upload_limit.placeholder);

             $('.upload_limit_validate').each(function() {
                $(this).text(lang.image.settings.upload_limit.validate);
             });

             $('.max_file_size_label').each(function() {
                $(this).text(lang.image.settings.max_file_size.label);
             });

             $('#max_file_size').attr("placeholder", lang.image.settings.max_file_size.placeholder);

             $('.max_file_size_validate').each(function() {
                $(this).text(lang.image.settings.max_file_size.validate);
             });

             $('.up_img_min_resolution_label').each(function() {
                $(this).text(lang.image.settings.min_resolution.label);
             });

             $('#up_img_min_width').attr("placeholder", lang.image.settings.min_resolution.width.placeholder);

             $('.up_img_min_width_validate').each(function() {
                $(this).text(lang.image.settings.min_resolution.width.validate);
             });

             $('#up_img_min_height').attr("placeholder", lang.image.settings.min_resolution.height.placeholder);

             $('.up_img_min_height_validate').each(function() {
                $(this).text(lang.image.settings.min_resolution.height.validate);
             });

             $('.up_img_max_resolution_label').each(function() {
                $(this).text(lang.image.settings.max_resolution.label);
             });

             $('#up_img_max_width').attr("placeholder", lang.image.settings.max_resolution.width.placeholder);

             $('.up_img_max_width_validate').each(function() {
                $(this).text(lang.image.settings.max_resolution.width.validate);
             });

             $('#up_img_max_height').attr("placeholder", lang.image.settings.max_resolution.height.placeholder);

             $('.up_img_max_height_validate').each(function() {
                $(this).text(lang.image.settings.max_resolution.height.validate);
             });

             $('.transform_title').each(function() {
                $(this).text(lang.image.transform.title);
             });

             $('.full_resolution_label').each(function() {
                $(this).text(lang.image.transform.full_resolution.label);
             });

             $('#img_full_width').attr("placeholder", lang.image.transform.full_resolution.width.placeholder);

             $('.img_full_width_validate').each(function() {
                $(this).text(lang.image.transform.full_resolution.width.validate);
             });

             $('#img_full_height').attr("placeholder", lang.image.transform.full_resolution.height.placeholder);

             $('.img_full_height_validate').each(function() {
                $(this).text(lang.image.transform.full_resolution.height.validate);
             });

             $('.thumb_resolution_label').each(function() {
                $(this).text(lang.image.transform.thumb_resolution.label);
             });

             $('#img_thumb_width').attr("placeholder", lang.image.transform.thumb_resolution.width.placeholder);

             $('.img_thumb_width_validate').each(function() {
                $(this).text(lang.image.transform.thumb_resolution.width.validate);
             });

             $('#img_thumb_height').attr("placeholder", lang.image.transform.thumb_resolution.height.placeholder);

             $('.img_thumb_height_validate').each(function() {
                $(this).text(lang.image.transform.thumb_resolution.height.validate);
             });

             $('.slide_resolution_label').each(function() {
                $(this).text(lang.image.transform.slide_resolution.label);
             });

             $('#img_slide_width').attr("placeholder", lang.image.transform.slide_resolution.width.placeholder);

             $('.img_slide_width_validate').each(function() {
                $(this).text(lang.image.transform.slide_resolution.width.validate);
             });

             $('#img_slide_height').attr("placeholder", lang.image.transform.slide_resolution.height.placeholder);

             $('.img_slide_height_validate').each(function() {
                $(this).text(lang.image.transform.slide_resolution.height.validate);
             });

             $('.jpg_quality_label').each(function() {
                $(this).text(lang.image.transform.jpg_quality.label);
             });

             $('.png_compress_label').each(function() {
                $(this).text(lang.image.transform.png_compress.label);
             });

             $('.watermark_title').each(function() {
                $(this).text(lang.image.watermark.title);
             });

             $('.watermark_label').each(function() {
                $(this).text(lang.image.watermark.label);
             });

             $('.watermark_position_label').each(function() {
                $(this).text(lang.image.watermark.position);
             });

             $('.watermark_image_label').each(function() {
                $(this).text(lang.image.watermark.image);
             });

             $('.watermark_set_label').each(function() {
                $(this).text(lang.image.watermark.set);
             });

             $('#select_wm_file').val(lang.image.watermark.browse);

             $('.watermark_adjust_label').each(function() {
                $(this).text(lang.image.watermark.adjust);
             });

             $('#buttonUpload').val(lang.image.watermark.upload);

             $('.watermark_uploading_label').each(function() {
                $(this).text(lang.image.watermark.uploading);
             });

             $('.watermark_uploaded_label').each(function() {
                $(this).text(lang.image.watermark.uploaded);
             });

             $('#install_submit').html(lang.install.submit);
             
             $js_msgs.submit_to_continue = lang.install.submit_continue;
             $js_msgs.alert_installing = lang.install.alert.installing;
             $js_msgs.alert_success = lang.install.alert.success;
             $js_msgs.alert_error = lang.install.alert.error;

             return false;
       });

    }, false);

  }, false);

})();

</script>

<script type="text/javascript">

var $wm_block_show_first = false;

$('#block-watermark').on('shown.bs.collapse', function () {
 if (!$wm_block_show_first) {
  $uploadCrop = $('#croppie-img').croppie({
    enableExif: true,
    viewport: {
        width: 240,
        height: 240,
        type: 'rectangle'
    },
    boundary: {
        width: 280,
        height: 280
    },
    url: "<?php echo GALLERY_URL;?>/watermark-default.png?"+Math.floor(Date.now() / 1000)
  });
  $wm_block_show_first = true;
 }
});

$('#wm_file').on('change', function () { 
	var reader = new FileReader();
    reader.onload = function (e) {
    	$uploadCrop.croppie('bind', {
    		url: e.target.result
    	}).then(function(){
    	});
    	
    }
    reader.readAsDataURL(this.files[0]);
});


$('#buttonUpload').on('click', function (ev) {

	$uploadCrop.croppie('result', {
		type: 'canvas',
		size: 'viewport'
	}).then(function (resp) {
        $("#loaded").removeClass("d-block").addClass("d-none");
        $("#loading").removeClass("d-none").addClass("d-block");
		$.ajax({
			url: '<?php echo GALLERY_URL;?>/install-ajax-upload-watermark.php',
			type: "POST",
			data: {"wm_file":resp, "sessid":"<?php echo $sessid;?>" },
            success: function (data, status)
            {
               var data = JSON.parse(data);
			   if (data.status == 'OK') {
                  $('#wmark_up').val('1');
                  $("#loading").removeClass("d-block").addClass("d-none");
                  $("#loaded").removeClass("d-none").addClass("d-block");
			   } else {
                  $("#install_alert").removeClass("d-none alert-success alert-info").addClass("alert-danger").html("<span class=\"fa fa-times\"></span> " + data.status);
			   }
            }
		});
	});
});

(function () {
   if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
      $("#wm_browse_other").css('display','none');
   } else {
      $("#wm_browse_msie").css('display','none');
   }

   $("#select_wm_file").click(function () {
      $('#wm_file').trigger('click');
   });

   $('#wm_file').change(function(evt) {
      $('#buttonUpload').removeAttr('disabled');
      $('#buttonUpload').addClass('btn-warning');
      $('#wmark_sel').val('1');
   });
   
})();
</script>


<!--Footer-->
<footer class="page-footer">

    <!--Copyright-->
    <div class="footer-copyright">
        <div class="container-fluid">
            © 2021 Copyright 
        </div>
    </div>
    <!--/.Copyright-->

</footer>
<!--/.Footer-->

</body>
</html>
