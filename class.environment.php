<?php
// class
class Environment {
    // Properties
    private $page;
    private $dbName;
    private $dbIP;
    private $dbUser;
    private $dbPass;
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
        } 
      }
      
    public function conectDB()
    {
        // Conectando com o Banco de Dados
        $conexao = mysqli_connect($this->dbIP, $this->dbUser,  $this->dbPass, $this->dbName);
            
        if (!$conexao) 
        {
            die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
        } 
        if($conexao == TRUE)
        {
            mysqli_query($conexao,"SET NAMES 'utf8'");
            mysqli_query($conexao,'SET character_set_connection=utf8');
            mysqli_query($conexao,'SET character_set_client=utf8');
            mysqli_query($conexao,'SET character_set_results=utf8');
        }
        return ($conexao);
    }
}
