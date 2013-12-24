<?php
class TaskPage extends SpiderPage{

    public function __construct($config = array()){
        global $settings;
        $this->taskConfig = $settings;
        parent::__construct($config);
    }
    
    protected function get_title($dom, $data){
        $return = $dom->find('h3', 1)->innertext();
        $return = trim($return);
        return $return;
    }


    protected function get_tags($dom, $data){
        $return = '';
        return $return;
    }
    
    protected function get_content($dom, $data){
        $return = $dom->find('.module_passage', 0)->innertext();
        $return = stripTagsFull($return, array('script','div'));
        $return = trim($return);
        return $return;
    }
    
    protected function checkExists($dom, $content){
        return false;
    }

    protected function saveData($data, $dom){

        $fields = '`'.implode('`,`', array_keys($data)).'`';

        foreach($data as &$value){
        $value = is_numeric($value)? $value: "'".addslashes($value)."'";
        }
        $values = implode(',', $data);

        $db = $this->taskConfig['dbConfig'];
        $lnk = mysql_connect($db['host'], $db['username'], $db['password']);

        if(empty($lnk)) {
            logInfo('Master Db Not connected : ' . mysql_error(), 'ERROR');
        }
        mysql_query("set names utf8", $lnk);
        mysql_select_db($db['dbname'], $lnk);


        $sql = "INSERT INTO `dc_jiemeng` ($fields) VALUES($values)";
        $result = mysql_query($sql);
        if(!$result){
            logInfo($sql.':'.mysql_error()."\n", 'ERROR');
        } else {
            echo $data['title']." success\n";
        }
        mysql_close($lnk);


    }
}
