<?php
class Page {
/*
    PaginateIt - A PHP Pagination Class
    ===================================
    Author: Brady Vercher
    Version: 1.1.1
    URL: http://www.bradyvercher.com/


    Copyright And License Information
    =================================
    Copyright (c) 2005 Brady Vercher

    Permission is hereby granted, free of charge, to any person obtaining a copy of this 
    software and associated documentation files (the "Software"), to deal in the Software 
    without restriction, including without limitation the rights to use, copy, modify, merge, 
    publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons 
    to whom the Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or 
    substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING 
    BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
    DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/


    var $currentPage, $itemCount, $itemsPerPage, $linksHref, $linksToDisplay;
    var $pageJumpBack, $pageJumpNext, $pageSeparator;
    var $queryString, $queryStringVar;

    function SetCurrentPage($reqCurrentPage){
        $this->currentPage = (integer) abs($reqCurrentPage);
    }

    function SetItemCount($reqItemCount){
        $this->itemCount = (integer) abs($reqItemCount);
    }

    function SetItemsPerPage($reqItemsPerPage){
        $this->itemsPerPage = (integer) abs($reqItemsPerPage);
    }

    function SetLinksHref($reqLinksHref){
        $this->linksHref = $reqLinksHref;
    }

    function SetLinksFormat($reqPageJumpBack, $reqPageSeparator, $reqPageJumpNext){
        $this->pageJumpBack = $reqPageJumpBack;
        $this->pageSeparator = $reqPageSeparator;
        $this->pageJumpNext = $reqPageJumpNext;
    }

    function SetLinksToDisplay($reqLinksToDisplay){
        $this->linksToDisplay  = (integer) abs($reqLinksToDisplay);
    }

    function SetQueryStringVar($reqQueryStringVar){
        $this->queryStringVar = $reqQueryStringVar;
    }

    function SetQueryString($reqQueryString){
        $this->queryString = $reqQueryString;
    }
    
    function GetCurrentCollection($reqCollection){
        if($this->currentPage < 1){
            $start = 0;
        }
        elseif($this->currentPage > $this->GetPageCount()){
            $start = $this->GetPageCount() * $this->itemsPerPage - $this->itemsPerPage;
        }
        else {
            $start = $this->currentPage * $this->itemsPerPage - $this->itemsPerPage;
        }
        
        return array_slice($reqCollection, $start, $this->itemsPerPage);
    }

    function GetPageCount(){
        return (integer)ceil($this->itemCount/$this->itemsPerPage);
    }

    function GetPageLinks(){
        $strLinks = '';
        $pageCount = $this->GetPageCount();
        $queryString = $this->GetQueryString();
        $linksPad = floor($this->linksToDisplay/2);

        if($this->linksToDisplay == -1){
            $this->linksToDisplay = $pageCount;
        }


        if($pageCount == 0){
            $strLinks = '1';
        }
        elseif($this->currentPage - 1 <= $linksPad || ($pageCount - $this->linksToDisplay + 1 == 0) || $this->linksToDisplay > $pageCount){ 
            $start = 1;
        }
        elseif($pageCount - $this->currentPage <= $linksPad){
            $start = $pageCount - $this->linksToDisplay + 1;
        }
        else {
            $start = $this->currentPage - $linksPad;
        }


        if(isset($start)){
            if($start > 1){
                if(!empty($this->pageJumpBack)){
                    $pageNum = $start - $this->linksToDisplay + $linksPad;
                    if($pageNum < 1){
                        $pageNum = 1;
                    }

                    $strLinks .= '<a href="'.$this->linksHref.$queryString.$pageNum.'">';
                    $strLinks .= $this->pageJumpBack.'</a>'.$this->pageSeparator;
                }

                $strLinks .= '<a href="'.$this->linksHref.$queryString.'1">1...</a>'.$this->pageSeparator;
            }


            if($start + $this->linksToDisplay > $pageCount){
                $end = $pageCount;
            }
            else {
                $end = $start + $this->linksToDisplay - 1;
            }


            for($i = $start; $i <= $end; $i ++){
                if($i != $this->currentPage){
                    $strLinks .= '<a href="'.$this->linksHref.$queryString.($i).'">';
                    $strLinks .= ($i).'</a>'.$this->pageSeparator;
                }
                else {
                    $strLinks .= $i.$this->pageSeparator;
                }
            }
            $strLinks = substr($strLinks, 0, -strlen($this->pageSeparator));


            if($start + $this->linksToDisplay - 1 < $pageCount){
                $strLinks .= $this->pageSeparator.'<a href="'.$this->linksHref.$queryString.$pageCount.'">';
                $strLinks .= '...'.$pageCount.'</a>'.$this->pageSeparator;
                
                if(!empty($this->pageJumpNext)){
                    $pageNum = $start + $this->linksToDisplay + $linksPad;
                    if($pageNum > $pageCount){
                        $pageNum = $pageCount;
                    }
                    
                    $strLinks .= '<a href="'.$this->linksHref.$queryString.$pageNum.'">';
                    $strLinks .= $this->pageJumpNext.'</a>';
                }
            }
        }


        return $strLinks;
    }

    function GetQueryString(){
        $pattern = array('/'.$this->queryStringVar.'=[^&]*&?/', '/&$/');
        $replace = array('', '');
        $queryString = preg_replace($pattern, $replace, $this->queryString);
        $queryString = str_replace('&', '&amp;', $queryString);
        
        if(!empty($queryString)){
            $queryString.= '&amp;';
        }

        return "page/";
    }

    function GetSqlLimit(){
        return $this->itemsPerPage;
    }

    function GetOffset(){
        return $this->currentPage * $this->itemsPerPage - $this->itemsPerPage;
    }

    function GetLimit(){
        return $this->itemsPerPage;
    }
    
    function __Construct(){
        $this->SetCurrentPage(1);
        $this->SetItemsPerPage(10);
        $this->SetItemCount(0);
        $this->SetLinksFormat('&laquo; Back',' &bull; ','Next &raquo;');
        $this->SetLinksHref($_SERVER['PHP_SELF']);
        $this->SetLinksToDisplay(10);
        $this->SetQueryStringVar('page');
        $this->SetQueryString($_SERVER['QUERY_STRING']);

        if(isset($_GET[$this->queryStringVar]) && is_numeric($_GET[$this->queryStringVar])){
            $this->SetCurrentPage($_GET[$this->queryStringVar]);
        }
    }
}

?>