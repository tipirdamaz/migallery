<?php

class Pagination {

    static $current;
    static $queryString;
    static $page;
    static $pageURL;
    static $lastpage;
    static $totalPages;
    static $pagerClass = 'pagination justify-content-center';
    static $liClass = 'page-item';
    static $liActiveClass = 'active';
    static $aClass = 'page-link';
    static $aActiveClass = '';
    
    static $langDir = 'ltr';
    static $langNums = '';

    static $linkLabels = Array(
       'first' => '&laquo;',
       'prev' => '&lt;',
       'next' => '&gt;',
       'last' => '&raquo;'
    );

    static $titleLabels = Array(
       'page' => 'Page',
       'first' => 'First Page',
       'prev' => 'Previous Page',
       'next' => 'Next Page',
       'last' => 'Last Page'
    );
    
    static function setClasses($pagerClass, $liClass, $liActiveClass, $aClass, $aActiveClass) {
       self::$pagerClass = $pagerClass;
       self::$liClass = $liClass;
       self::$liActiveClass = $liActiveClass;
       self::$aClass = $aClass;
       self::$aActiveClass = $aActiveClass;
    }

    static function setLang($langDir, $langNums, $linkLabels, $titleLabels) {
        if ($langDir == 'rtl') {
           self::$linkLabels['first'] = $linkLabels['last'];
           self::$linkLabels['last'] = $linkLabels['first'];
           self::$linkLabels['prev'] = $linkLabels['next'];
           self::$linkLabels['next'] = $linkLabels['prev'];
		}
        self::$langDir = $langDir;
        self::$langNums = $langNums;
        self::$titleLabels = $titleLabels;
	}

   static function convertNumToLang($num)
   {
      $langNums = self::$langNums;
      if ($langNums !== '') {
         $engNums = array('0','1','2','3','4','5','6','7','8','9');
         return str_replace($engNums, preg_split('//u', $langNums, null, PREG_SPLIT_NO_EMPTY), $num);
      }
	  return $num;
   }

   static function paging($records, $pageURL, $start = 0, $maxshown = 50, $numpagesshown = 11, $arrows = true, $additional = array()) {
        self::$pageURL = $pageURL;
        self::$queryString = $additional;
        if ($records > $maxshown) {
            self::$current = $start >= 1 ? intval($start) : 1;
            self::$totalPages = ceil(intval($records) / ($maxshown >= 1 ? intval($maxshown) : 1));
            self::$lastpage = self::$totalPages;
            self::getPage($records, $maxshown, $numpagesshown);
            
            $paging = '<ul class="'.self::$pagerClass.'">'.self::preLinks($arrows);
            while (self::$page <= self::$lastpage) {
                $paging .= self::buildLink(self::$page, self::$page, (self::$current == self::$page));
                self::$page = (self::$page + 1);
            }
            return $paging.self::postLinks($arrows).'</ul>';
        }
        return false;
   }
    
   static function buildLink($link, $page, $current = false, $title='') {
        return '<li'.(!empty(self::$liClass) || ($current === true && !empty(self::$liActiveClass)) ? ' class="'.trim(self::$liClass.(($current === true && !empty(self::$liActiveClass)) ? ' '.self::$liActiveClass : '').'"') : '').'><a href="'.self::$pageURL.(!empty(self::buildQueryString($link)) ? '?'.self::buildQueryString($link) : '').'" title="'.($title!='' ? (self::$langDir=='rtl' ? '('.self::convertNumToLang($link).') '.$title : $title.' ('.self::convertNumToLang($link).')') : (self::$langDir=='rtl' ? self::convertNumToLang($page).' '.self::$titleLabels['page'] : self::$titleLabels['page'].' '.self::convertNumToLang($page))).'"'.(!empty(self::$aClass) || ($current === true && !empty(self::$aActiveClass)) ? ' class="'.trim(self::$aClass.(($current === true && !empty(self::$aActiveClass)) ? ' '.self::$aActiveClass : '')).'"' : '').'>'.self::convertNumToLang($page).'</a></li>';
   }

   static function buildQueryString($page) {
        $pageInfo = is_numeric($page) ? ['page' => $page] : [];
        return http_build_query(array_filter(array_merge($pageInfo, self::$queryString)), '', '&amp;');
   }
    
   static function getPage($records, $maxshown, $numpages) {
        $show = floor($numpages / 2);
        if (self::$lastpage > $numpages) {
            self::$page = (self::$current > $show ? (self::$current - $show) : 1);
            if (self::$current < (self::$lastpage - $show)) {
                self::$lastpage = ((self::$current <= $show) ? (self::$current + ($numpages - self::$current)) : (self::$current + $show));
            }
            else { self::$page = self::$current - ($numpages - ((ceil(intval($records) / ($maxshown >= 1 ? intval($maxshown) : 1)) - self::$current)) - 1); }
        }
        else { self::$page = 1; }
   }
    
   static function preLinks($arrows = true) {
        $paging = '';
        if (self::$current != 1 && $arrows) {
            if (self::$current != 2) { $paging .= self::buildLink('', self::$linkLabels['first'], false, self::$titleLabels['first']); }
            $paging .= self::buildLink((self::$current - 1), self::$linkLabels['prev'], false, self::$titleLabels['prev']);
        }
        return $paging;
   }
    
   static function postLinks($arrows = true) {
        $paging = '';
        if (self::$current != self::$totalPages && $arrows) {
            $paging .= self::buildLink((self::$current + 1), self::$linkLabels['next'], false, self::$titleLabels['next']);
            if (self::$current != (self::$totalPages - 1)) { $paging .= self::buildLink(self::$totalPages, self::$linkLabels['last'], false, self::$titleLabels['last']); }
        }
        return $paging;
   }
}
