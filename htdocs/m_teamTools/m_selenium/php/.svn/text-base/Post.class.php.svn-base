<?php
header("Content-type: text/html; charset=utf-8");
require_once "PostDB.class.php";
require_once "PostInfo.class.php";

class Post{
    var $postDB;

    //constructor
    function Post(){
        $this->postDB = new postDB();
    }

    function closeDB(){
        $this->postDB->close();
    }
    //插入一个分享,返回postid
    function insert($title,$author,$type,$tag,$content){ 
        $historyid = $this->postDB->insertPostInfo($title, $author, $type, $tag, $content);
        $postid = $this->postDB->insertPostIndex($historyid,0);
        $this->postDB->insertAssociationInfo($historyid,$postid);
        return $postid;
    }
    //更新一个分享
    function update($postid,$title,$author,$type,$tag,$content){
        $historyid = $this->postDB->insertPostInfo($title, $author, $type, $tag, $content);
        $this->postDB->insertAssociationInfo($historyid,$postid);
        $this->postDB->updatePostIndex($postid,$historyid,0);
    }

    //回滚一个分享到从前的版本
    function rollback($postid,$historyid){
        $this->postDB->updatePostIndex($postid,$historyid,0);
    }

    //删除一个分享
    function delete($postid){
        $result = $this->postDB->getPostIndexById($postid);
        $historyid = $result[0]["historyid"];
        $this->postDB->updatePostIndex($postid,$historyid,1);
    }
   
    //从回收站恢复一个分享
    function recover($postid){
        $result = $this->postDB->getPostIndexById($postid);
        $historyid = $result[0]["historyid"];
        $this->postDB->updatePostIndex($postid,$historyid,0);
    }

    function getPostInfo($postid){
        $result = $this->postDB->getPostInfoByPostId($postid);
        $postInfo = new PostInfo();
        $postInfo->setPostId($result[0]["postIndex.postid"]);
        $postInfo->setHistoryId($result[0]["postIndex.historyid"]);
        $postInfo->setTitle($result[0]["postInfo.title"]);
        $postInfo->setAuthor($result[0]["postInfo.author"]);
        $postInfo->setType($result[0]["postInfo.type"]);
        $postInfo->setTag($result[0]["postInfo.tag"]);
        $postInfo->setContent($result[0]["postInfo.content"]);
        $postInfo->setCreateTime($result[0]["postInfo.createTime"]);
        return $postInfo->toArray();
    }

    //获取当前所有未删除的分享
    function getAll(){
        $query = "SELECT postIndex.postid as postid,postIndex.historyid as historyid,postInfo.title as title,postInfo.author as author,postInfo.type as type,postInfo.tag as tag,postInfo.content as content,postInfo.createTime as createTime FROM postIndex,postInfo where postIndex.historyid = postInfo.historyid and postIndex.deleted = 0";
        $result = $this->postDB->execSQL($query);
        $returnArray = array();
        $postInfo;
        foreach ($result as $entry){
            $postInfo = new PostInfo();
            $postInfo->setPostId($entry["postid"]);
            $postInfo->setHistoryId($entry["historyid"]);
            $postInfo->setTitle($entry["title"]);
            $postInfo->setAuthor($entry["author"]);
            $postInfo->setType($entry["type"]);
            $postInfo->setTag($entry["tag"]);
            $postInfo->setContent($entry["content"]);
            $postInfo->setCreateTime($entry["createTime"]);
            array_push($returnArray,$postInfo->toArray());
        }
        return $returnArray;
    }

    //获取回收站的所有分享
    function getTrash(){
        $query = "SELECT postIndex.postid,postIndex.historyid,postInfo.title,postInfo.author,postInfo.type,postInfo.tag,postInfo.content,postInfo.createTime FROM postIndex,postInfo where postIndex.historyid = postInfo.historyid and postIndex.deleted = 1";
        $result = $this->postDB->execSQL($query);
        $returnArray = array();
        $postInfo;
        foreach ($result as $entry){
            $postInfo = new PostInfo();
            $postInfo->setPostId($entry["postIndex.postid"]);
            $postInfo->setHistoryId($entry["postIndex.historyid"]);
            $postInfo->setTitle($entry["postInfo.title"]);
            $postInfo->setAuthor($entry["postInfo.author"]);
            $postInfo->setType($entry["postInfo.type"]);
            $postInfo->setTag($entry["postInfo.tag"]);
            $postInfo->setContent($entry["postInfo.content"]);
            $postInfo->setCreateTime($entry["postInfo.createTime"]);
            array_push($returnArray,$postInfo->toArray());
        }
        return $returnArray;
    }

    //获取一个帖子所有的历史记录
    function getHistoryVersions($postid){
        $query = "SELECT associationInfo.postid,associationInfo.historyid,postInfo.title,postInfo.author,postInfo.type,postInfo.tag,postInfo.content,postInfo.createTime FROM postInfo,associationInfo where associationInfo.postid = ".$postid." and associationInfo.historyid = postInfo.historyid";        
        $result = $this->postDB->execSQL($query);
        $returnArray = array();
        $postInfo;
        foreach ($result as $entry){
            $postInfo = new PostInfo();
            $postInfo->setPostId($entry["associationInfo.postid"]);
            $postInfo->setHistoryId($entry["associationInfo.historyid"]);
            $postInfo->setTitle($entry["postInfo.title"]);
            $postInfo->setAuthor($entry["postInfo.author"]);
            $postInfo->setType($entry["postInfo.type"]);
            $postInfo->setTag($entry["postInfo.tag"]);
            $postInfo->setContent($entry["postInfo.content"]);
            $postInfo->setCreateTime($entry["postInfo.createTime"]);
            array_push($returnArray,$postInfo->toArray());
        }
        return $returnArray;
    }

    function getRank(){
        $query = "SELECT COUNT(postIndex.historyid) as count,postInfo.author as author FROM postIndex,postInfo WHERE postIndex.historyid = postInfo.historyid and postIndex.deleted = 0 GROUP BY postInfo.author ORDER BY count asc";
        $result = $this->postDB->execSQL($query);
        $returnArray = Array();
        foreach($result as $entry){
            array_push($returnArray,Array($entry["count"],$entry["author"])); 
        }
        return $returnArray;
    }
}
############## TEST CODE #################3
#$post = new Post();
#$post->insert("hellowword","test","测试","java,code","这就是一个测试");
#$post->update(5,"java技术分享","searcher","测试","java,code","这就是一个测试");
#$post->delete(2);
#$result = $post->getHistoryVersions(5);
/*
$result = $post->getRank();
foreach($result as $entry){
    echo $entry["count"].",".$entry["author"]."\n</br>";
}
$result = $post->getAll();
foreach($result as $entry){
    echo "postid:".$entry["postid"]."\n</br>";
    echo "historyid:".$entry["historyid"]."\n</br>";
    echo "title:".$entry["title"]."\n</br>";
    echo "content:".$entry["content"]."\n</br>";
    echo "createTime:".$entry["createTime"]."\n</br>";
    echo "\n</br>";
}*/
#$result = $post->getPostInfo(1);
#echo json_encode($result);
#$post->getHistoryVersions(5);
#$post->closeDB(); 
#$postDB->createTables();
#$postDB->deletePostInfoById(3);
?>
