<?php
// class
class Environment {
    // Properties
    private $page;
    private $dbName;
    private $dbIP;
    private $dbUser;
    private $dbPass;
    private $arrayPath=array();
    public $path;
    public $erro;
    public $name;
    public $color_fundo;
    public $color_menu;

    function __construct($configPage) {
        $arquivo = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/php/json/configEnvironment.json');
        $json = json_decode($arquivo);
        foreach($json as $registro){
            if($registro->page == $configPage)
            {
                $this->dbName = $registro->dbName;
                $this->dbIP = $registro->dbIP;
                $this->dbUser = $registro->dbUser;
                $this->dbPass = $registro->dbPass;
                $this->path = $registro->path;
                $this->erro = $registro->erro;
                $this->name = $registro->name;
                $this->color_fundo = $registro->color_fundo;
                $this->color_menu = $registro->color_menu;
            }
            $temp=Environment::dirToArray($GLOBALS['ROOT_PATH'] . '/' . $registro->page);
            $this->arrayPath[$registro->page]=$temp;
        } 
      }
      
    public function conectDB()
    {
        // Conectando com o Banco de Dados
        $consction = mysqli_connect($this->dbIP, $this->dbUser,  $this->dbPass, $this->dbName);
            
        if (!$consction) 
        {
            die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
        } 
        if($conexao == TRUE)
        {
            mysqli_query($consction,"SET NAMES 'utf8'");
            mysqli_query($consction,'SET character_set_connection=utf8');
            mysqli_query($consction,'SET character_set_client=utf8');
            mysqli_query($consction,'SET character_set_results=utf8');
        }
        return ($consction);
    }
    private function dirToArray($dir) {
  
        $result = array();
        $arrayDir = scandir($dir);
        foreach ($arrayDir as $key => $value)
        {
           if (!in_array($value,array(".","..")))
           {
              if (is_dir($dir . '/' . $value))
              {
                 $result=array_merge($result,Environment::dirToArray($dir . '/' . $value));
              }
              else
              {
                 array_push($result, $dir . '/' . $value);
              }
           }
        }
        return $result;
    }

    public function diffFiles($page) {
        foreach ($this->arrayPath as $key => $value)
        {
            if($key==$this->page){
                $arrMain=$value;
            }
            elseif ($key==$page) {
                $arrSecond=$value;
            } 
        }
        $result=array();
        $modFile=array();
        $newFile=array();
        $delFile=array();
        foreach ($arrMain as $value)
        {
            $newPathFile = str_replace($this->page, $page, $value);
            if(in_array($newPathFile,$arrSecond)){
                $tempThisFile = file_get_contents($value);
                $tempNewFile = file_get_contents($newPathFile);
                if($tempThisFile<>$tempNewFile)
                {
                    array_push($modFile,$value);
                }
            }
            else 
            {
                array_push($newFile,$value);
            }
        }
        //check if this any file was delete
        foreach ($arrSecond as $value)
        {
            $newPathFile = str_replace($page, $this->page, $value);
            if(!in_array($newPathFile,$arrMain)){
                array_push($delFile,$value);
            }
        }
        $result['newFile']=$newFile;
        $result['modFile']=$modFile;
        $result['delFile']=$delFile;

        return $result;
    }

    public function exportFilesTo($page) {  //Copy files from this local path to $page path
        foreach ($this->arrayPath as $key => $value)
        {
            if($key==$this->page){
                $arrMain=$value;
            }
        } 
        $temp=array();
        foreach ($arrMain as $value)
        {
            $newPathFile = str_replace($this->page, $page, $value);
            if(!is_dir (dirname($newPathFile))){
                mkdir(dirname($newPathFile), 0777, true);
            }
            $tempFile=file_get_contents($value);
            file_put_contents($newPathFile,$tempFile);

            array_push($temp,$newPathFile);            
        }
        if(in_array($page,$this->arrayPath))
        {
            array_push($this->arrayPath,$page,$temp);
        }  
        return $temp;
    }   
    
    public function importFilesFrom($page) {  //Copy files from $page local path to this local path
        foreach ($this->arrayPath as $key => $value)
        {
            if($key==$page){
                $arrMain=$value;
            }
        } 
        $temp=array();
        foreach ($arrMain as $value)
        {
            $newPathFile = str_replace($page, $this->page, $value);
            if(!is_dir (dirname($newPathFile))){
                mkdir(dirname($newPathFile), 0777, true);
            }
            $tempFile=file_get_contents($value);
            file_put_contents($newPathFile,$tempFile);   
            array_push($temp,$newPathFile);    
        }
        return $temp;
    }         
}
