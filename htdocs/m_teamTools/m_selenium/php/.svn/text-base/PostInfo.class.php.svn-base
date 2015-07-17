<?php
header("Content-type: text/html; charset=utf-8");
require_once "PostDB.class.php";

class PostInfo{
    var $historyid;
    var $postid;
    var $deleted;
    var $title;
    var $author;
    var $content;
    var $createTime;
    var $type;
    var $tag;

    //constructor
    function PostInfo(){
    }

    function getHistoryId(){
        return $this->historyid;
    }
    function getPostId(){
        return $this->postid;
    }
    function getTitle(){
        return $this->title;
    }
    function getAuthor(){
        return $this->author;
    }
    function getContent(){
        return $this->content;
    }
    function getType(){
        return $this->type;
    }
    function getTag(){
        return $this->tag;
    }
    function getCreateTime(){
        return $this->createTime;
    }
    function getDeleted(){
        return $this->deleted;
    }
    
    function setHistoryId($historyid){
        $this->historyid = $historyid; 
    }
    function setPostId($postid){
        $this->postid = $postid; 
    }
    function setTitle($title){
        $this->title = $title; 
    }
    function setAuthor($author){
        $this->author = $author; 
    }
    function setContent($content){
        $this->content = $content; 
    }
    function setType($type){
        $this->type = $type; 
    }
    function setTag($tag){
        $this->tag = $tag; 
    }
    function setCreateTime($createTime){
        $this->createTime = $createTime; 
    }
    function setDeleted($deleted){
        $this->deleted = $deleted; 
    }

    function  toArray(){
        $postArray = array("postid"=>$this->getPostId(),
                           "historyid"=>$this->getHistoryId(),
                           "title"=>$this->getTitle(),
                           "author"=>$this->getAuthor(),
                           "content"=>$this->getContent(),
                           "type"=>$this->getType(),
                           "tag"=>$this->getTag(),
                           "createTime"=>$this->getCreateTime(),
                           "deleted"=>$this->getDeleted()
        );
        return $postArray;
    }
}

?>
