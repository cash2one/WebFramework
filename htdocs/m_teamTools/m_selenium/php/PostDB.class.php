<?php
header("Content-type: text/html; charset=utf-8");
define("POSTDB", "../data/forum.sqlite");

class PostDB{
    var $db;

    //constructor
    function PostDB(){
        $this->db = sqlite_open(POSTDB, 0666, $sqliteerror);
        if (!$this->db){
            die($sqliteerror);
        }
        date_default_timezone_set("Asia/Shanghai");
    }
    //关闭与数据库的链接    
    function close(){
        sqlite_close($this->db);
    }

    //创建三张表
    public function createTables(){
        sqlite_query($this->db, 'CREATE TABLE postInfo(historyid INTEGER PRIMARY KEY NOT NULL UNIQUE,title VARCHAR NOT NULL ,type VARCHAR NOT NULL,tag VARCHAR, author VARCHAR NOT NULL , content TEXT NOT NULL ,createTime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, reverse1 VARCHAR(200), reverse2 VARCHAR(200))');
        sqlite_query($this->db, 'CREATE TABLE "postIndex" ("postid" INTEGER PRIMARY KEY NOT NULL UNIQUE , "historyid" INTEGER NOT NULL , "deleted" BOOL NOT NULL , "reverse1" VARCHAR(200), "reverse2" VARCHAR(200))');
        sqlite_query($this->db, 'CREATE TABLE "associationInfo" ("associd" INTEGER PRIMARY KEY NOT NULL UNIQUE , "historyid" INTEGER NOT NULL , "postid" INTEGER NOT NULL , "reverse1" VARCHAR(200), "reverse2" VARCHAR(200))');
    }

    //将postInfo表格中插入一行记录，返回插入记录的id
    public function insertPostInfo($title, $author, $type, $tag, $content){
        $query = "INSERT INTO  postInfo (title, author, type, tag, content, createTime) values (\"".$title ."\", \"".$author."\", \"".$type."\", \"".$tag."\", \"".$content."\", \"".date('Y-m-d H:i:s', time())."\")";
        $result = sqlite_query($this->db,$query);
        $historyid = sqlite_last_insert_rowid($this->db);
        return $historyid;
    }

    //向postIndex表格中插入一行记录,并返回插入记录的id
    public function insertPostIndex($historyid, $deleted){
        $query = "INSERT INTO  postIndex (historyid, deleted) values (\"".$historyid."\", \"".$deleted."\")";
        $result = sqlite_query($this->db,$query);
        $postid = sqlite_last_insert_rowid($this->db);
        return $postid;
    }

    //向associationInfo表格中插入一行记录
    public function insertAssociationInfo($historyid, $postid){
        $query = "INSERT INTO  associationInfo (historyid, postid) values (\"".$historyid."\", \"".$postid."\")";
        sqlite_query($this->db,$query);
    }

    //根据postid来更新postIndex表格中的一行记录
    public function updatePostIndex($postid, $historyid, $deleted){
        $query = "UPDATE postIndex SET historyid=".$historyid.", deleted=".$deleted." WHERE postid=".$postid;
        sqlite_query($this->db,$query);
    }

    //根据historyid来获取postInfo中的一行记录
    public function getPostInfoByHistoryId($historyid){
        $query = "SELECT * FROM postInfo WHERE historyid=\"".$historyid."\"";
        $result = sqlite_query($this->db, $query);
        return sqlite_fetch_all($result,SQLITE_ASSOC);
    }

    //根据postid来获取postInfo中的一行记录
    public function getPostInfoByPostId($postid){
        $query = "SELECT * FROM postInfo,postIndex WHERE postIndex.postid = ".$postid." and postIndex.historyid = postInfo.historyid";
        $result = sqlite_query($this->db, $query);
        return sqlite_fetch_all($result,SQLITE_ASSOC);
    }

    //根据postid来获取postInfo中的一行记录
    public function getPostIndexById($postid){
        $query = "SELECT * FROM postIndex WHERE postid = ".$postid;
        $result = sqlite_query($this->db, $query);
        return sqlite_fetch_all($result,SQLITE_ASSOC);
    }

    //执行输入的sql语句
    public function execSQL($query){
        $result = sqlite_query($this->db, $query);
        return sqlite_fetch_all($result,SQLITE_ASSOC);
    }
}
############## TEST CODE #################3
/*
$postDB = new PostDB();
#$postDB->updatePostIndex(5,2,0);
$result = $postDB->getAllPostInfo();
foreach ($result as $entry){
    echo $entry["postIndex.postid"]."||".$entry["postIndex.historyid"]."||".$entry["postInfo.title"]."||".$entry["postInfo.createTime"]."\n</br>";
}

$postDB->close(); 
*/
#$postDB->createTables();
#$postDB->deletePostInfoById(3);


?>
