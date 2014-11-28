<?php
class page_index extends Page {
    function init(){
        parent::init();

        if(strtotime('2014-11-30')==strtotime(date('Y-m-d')))
        throw new Exception('Software Licesne Expired, Kindly Contact To Your Service Provider');
    }
}

