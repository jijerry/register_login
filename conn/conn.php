<?php
//连接数据库文件
/**
 * Created by PhpStorm.
 * User: jerry
 * Date: 2016/11/22
 * Time: 18:01
 */

class opmysql{
    private $host = 'localhost';    //服务器地址
    private $name = 'root';         //登录账号
    private $pwd = '';              //登录密码
    private $dBase = 'db_reglog';   //数据库名称
    private $conn = '';             //数据库链接资源
    private $result = '';           //结果集
    private $msg = '';              //返回结果
    private $fields;                //返回字段
    private $fieldsNum = 0;          //返回字段数
    private $rowsNum = 0;           //返回结果数
    private $rowRst = '';           //返回单条记录的字段数组
    private $filesArray = array();  //返回字段数组
    private $rowArray = array();    //返回结果数组
    //初始化类
    function __construct($host='', $name='', $pwd='', $dBase='')
    {
        if ($host != ''){
            $this->host = $host;
        }
        if ($name != ''){
            $this->name = $name;
        }
        if ($pwd != ''){
            $this->pwd = $pwd;
        }
        if ($dBase != ''){
            $this->dBase = $dBase;
        }
        $this->init_conn();
    }
    //链接数据库
    function init_conn()
    {
        $this->conn = @mysqli_connect($this->host, $this->name, $this->pwd);
        @mysqli_select_db($this->conn, $this->dBase);
        mysqli_query("set NAMES gb2312");
    }
    //查询结果
    function mysql_query_rst($sql)
    {
        if ($this->conn = ''){
            $this -> init_conn();
        }
        $this->result = @mysqli_query($this->conn, $sql);
    }
    //取得字段数
    function getFieldsNum($sql)
    {
        $this -> mysql_query_rst($sql);
        $this->fieldsNum = @mysqli_num_fields($this->result);   //取得结果集的字段数目
    }
    //取得查询结果
    function  getRowsNum($sql)
    {
        $this -> mysql_query_rst($sql);
        if (mysqli_errno($this->conn) == 0){
            return @mysqli_num_rows($this->result);             //取得结果集中行的数目
//            return $this->rowsNum = mysqli_num_rows($this->result);
        }else{
            return '';
        }
    }
    //取得单条记录数组
    function getRowsRst($sql)
    {
        $this -> mysql_query_rst($sql);
        if (mysqli_errno($this->conn) == 0){
            $this->rowRst = mysqli_fetch_array($this->result, MYSQLI_ASSOC);
            return $this->rowRst;
        }else{
            return '';
        }
    }
    //取得多条记录结果
    function getRowsArray($sql)
    {
        $this -> mysql_query_rst($sql);
        if (mysqli_errno($this->conn) == 0){
            while ($row = mysqli_fetch_array($this->result, MYSQLI_ASSOC)){
                $this->rowArray = $row;
            }
            return $this->rowArray;
        }else{
            return '';
        }
    }
    //更新，删除，添加记录
    function uidRst($sql)
    {
        if ($this->conn == 0){
            $this->init_conn();
        }
        @mysqli_query($this->conn, $sql);
        $this->rowsNum = mysqli_affected_rows($this->conn);         //取得前一次mysql操作影响的记录行数目
        if (mysqli_errno($this->conn) == 0){
            return $this->rowsNum;
        }else{
            return '';
        }
    }
    //获取对应字段值
    function getFields($sql, $fields)
    {
        $this->mysql_query_rst($sql);
        if (mysqli_errno($this->conn) == 0){
            if (mysqli_num_rows($this->result) > 0){
                $tmpfld = mysqli_fetch_array($this->result);
                $this->fields = $tmpfld[$fields];
            }
            return $this->fields;
        }else{
            return '';
        }
    }
    //错误信息
    function msg_error()
    {
        if (mysqli_errno($this->conn) != 0){
            $this->msg = mysqli_error($this->conn);
        }
        return $this->msg;
    }
    //释放结果集
    function close_rst()
    {
        mysqli_free_result($this->result);
        $this->msg = '';
        $this->fieldsNum = 0;
        $this->rowsNum = 0;
        $this->filesArray = '';
        $this->rowsArray = '';
    }
    //关闭数据库
    function close_conn()
    {
        $this->close_rst();
        mysqli_close($this->conn);
        $this->conn = '';
    }
}
$conne = new opmysql();
?>