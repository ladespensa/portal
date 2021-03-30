<?php
/**
 * Database
 *
 * Database system configuration file
 *
 * @package		App Polar
 * @author		Charly
 * @copyright	(c) 2020
 * @license		
 *******************************************************************
 */

/**
 * Database
 *
 * This system uses PDO to connect to MySQL, SQLite, or PostgreSQL, SQLSERVER , ORACLE.
 */

class database {

	public $pdo=false;
	public $type='mssql';
	public $username='';
	public $password='';
	public $host='localhost';
	public $database='';
	public $persistent=false;
	public $showErrors=false;
	public $queryCounter=0;
	private $connected=false;
	public $filas = '';

    private static $instance = null;
    private $connection;
    var $queryCache = array();
    var $dataCache = array();
    var $result;
    var $resource;

    


    public static function getInstance()
    {
        if(self::$instance == null)
        {
            self::$instance = new self;
        }
        return self::$instance;
    }



    public function __construct($type=TYPE,$host=HOST,$database=DB,$username=USERDB,$password=PWDDB,$showErrors=true,$persistent=false)
    {

    	$this->type=$type;
		$this->host=$host;
		$this->database=$database;
		$this->username=$username;
		$this->password=$password;
		$this->showErrors=$showErrors;
		$this->persistent=$persistent;

        $this->newConnection();
    }




    public function __destruct(){
		$this->dbDisconnect();
	}



    function newConnection()
    {

        

	   switch($this->type){
			case 'mysql':

				if(extension_loaded('pdo_mysql')){
					$this->pdo=new PDO('mysql:'.$connectLine,$this->username,$this->password,array(PDO::ATTR_PERSISTENT=>$this->persistent,PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES \'UTF8\''));
					if($this->pdo){
						$this->connected=true;
					} else {
						trigger_error('Cannot connect to database',E_USER_ERROR);
					}
				} else {
					trigger_error('PDO MySQL extension not enabled',E_USER_ERROR);
				}
				break;
			case 'sqlite':

				if(extension_loaded('pdo_sqlite')){
					$this->pdo=new PDO('sqlite:'.$connectLine,$this->username,$this->password,array(PDO::ATTR_PERSISTENT=>$this->persistent));
					if($this->pdo){
						$this->connected=true;
					} else {
						trigger_error('Cannot connect to database',E_USER_ERROR);
					}
				} else {
					trigger_error('PDO SQLite extension not enabled',E_USER_ERROR);
				}
				break;
			case 'postgresql':

				if(extension_loaded('pdo_pgsql')){
					$this->pdo=new PDO('pgsql:'.$connectLine,$this->username,$this->password,array(PDO::ATTR_PERSISTENT=>$this->persistent));
					if($this->pdo){
						$this->pdo->exec('SET NAMES \'UTF8\'');
						$this->connected=true;
					} else {
						trigger_error('Cannot connect to database',E_USER_ERROR);
					}
				} else {
					trigger_error('PDO PostgreSQL extension not enabled',E_USER_ERROR);
				}
				break;
			case 'oracle':

				if(extension_loaded('pdo_oci')){
					$this->pdo=new PDO('oci:'.$connectLine.';charset=AL32UTF8',$this->username,$this->password,array(PDO::ATTR_PERSISTENT=>$this->persistent));
					if($this->pdo){
						$this->connected=true;
					} else {
						trigger_error('Cannot connect to database',E_USER_ERROR);
					}
				} else {
					trigger_error('PDO Oracle extension not enabled',E_USER_ERROR);
				}
				break;
			case 'mssql':
                
				if(extension_loaded('pdo_mssql')){
                    
					$this->pdo=new PDO('dblib:'.$connectLine,$this->username,$this->password,array(PDO::ATTR_PERSISTENT=>$this->persistent));
					if($this->pdo){
						$this->pdo->exec('SET NAMES \'UTF8\'');
                        $this->connected=true;
                        
					} else {
                        
						trigger_error('Cannot connect to database',E_USER_ERROR);
					}
				} else if(extension_loaded('mssql')) {
                    

					if(!($this->connection = mssql_connect($this->host, $this->username, $this->password))){

                        
				echo 'Error inesperado al conectarse a la base de datos.\n';
                die('MSSQL error: ' . mssql_get_last_message());
				exit();



                 }else{

		           mssql_select_db($this->database, $this->connection);
                  
	              }



				}else if(extension_loaded('sqlsrv')){
                   
				$connectionInfo = array( "Database"=>$this->database, "UID"=>$this->username, "PWD"=>$this->password);

				   $this->connection = sqlsrv_connect($this->host, $connectionInfo);
				   if($this->connection){


				   }else{
                    
				   	echo "Connection could not be established.<br />";
                    die( print_r( sqlsrv_errors(), true));
				   }


				}else{

                   echo "Error conexion";
                }

				break;
			default:
            
				trigger_error('This database type is not supported',E_USER_ERROR);
				break;
		}


    }


    public static function getCountRows($query){

        $database = self::GetInstance();

        $params = array();
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$result=sqlsrv_query($database->connection,$query,$params,$options);
                //echo $query;
                if (!$result )
                {
                    echo 'Error in statement execution s.\n';
                    die("Problemas en el select:".sqlsrv_errors());
                }

                $row_count = sqlsrv_num_rows( $result );

                if ($row_count)
                {

                    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                    return $row['MAX'];
                }else{
                    return 0;
                }

    }


    public static function getRow($query)
    {
    	//echo $query;

        $database = self::GetInstance();

        switch($database->type){
			case 'mysql':
                break;

			case 'mssql':

                if(extension_loaded('pdo_mssql')){

                }else if(extension_loaded('mssql')){

				$result = mssql_query($query);
                if (!$result )
                {
                 echo 'Error in statement execution.\n';
                 die('MSSQL error: ' . mssql_get_last_message());
                 }
                if (mssql_num_rows($result))
                {
                $row = mssql_fetch_array($result, MSSQL_ASSOC);


                 return $row;
                 }else{
                 return 0;
                 }


			}else if(extension_loaded('sqlsrv')){

				$params = array();
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$result=sqlsrv_query($database->connection,$query,$params,$options);

                //echo $query;
                if (!$result )
                {
                 echo 'Error in statement execution s.\n';
                 die("Problemas en el select:".sqlsrv_errors());
                 }

                $row_count = sqlsrv_num_rows( $result );

                if ($row_count)
                {

                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                 return $row;
                 }else{
                 return 0;
                 }

			}


			break;

		default:
				// Error is triggered for all other database types
				//trigger_error('This database type is not supported',E_USER_ERROR);
				break;

			}




    }


     public static function getRowDate($date)
    {

       $database = self::GetInstance();

        switch($database->type){
			case 'mysql':
			break;

			case 'mssql':

			if(extension_loaded('pdo_mssql')){

			}else if(extension_loaded('mssql')){//linux



	        $fechar = date('Y-m-d', strtotime($date));
	        $fechar = strtotime($fechar);

	        $fechar1900 = date('Y-m-d', strtotime("1900-1-1"));
	        $fechar1900 = strtotime($fechar1900);


	           if($fechar == $fechar1900 ){

	        	      $fechar = "";

	            }else{

	        	     $fechar = $date;
	            }



				 return $fechar;


			}else if(extension_loaded('sqlsrv')){//microsoft


			$fechar = date('Y-m-d', strtotime($date->format('Y-m-d')));
	        $fechar = strtotime($fechar);

	        $fechar1969 = date('d-m-Y', strtotime("1969-12-31"));//equivalente a 1900-1-1
	        $fechar1969 = strtotime($fechar1969);

	           if($fechar == $fechar1969 ){

	        	     $fechar = "";

	            }else{

	        	     $fechar = ($date->format('Y-m-d'));
	            }



				 return $fechar;


			}


			break;

		default:
				// Error is triggered for all other database types
				//trigger_error('This database type is not supported',E_USER_ERROR);
				break;

			}


    }


    public static function getRows($query)
    {
 
        
    	
       $database = self::GetInstance();

       $database->filas = array();

        switch($database->type){
			case 'mysql':
			break;

			case 'mssql':
			if(extension_loaded('pdo_mssql')){

			}else if(extension_loaded('mssql')){

                 

				$result = mssql_query($query);
                if (!$result )
                {
                 echo 'Error in statement execution.\n';
                 die('MSSQL error: ' . mssql_get_last_message());
                 }
                if (mssql_num_rows($result))
                {
                // $rows = mssql_fetch_array($result, MSSQL_ASSOC);
                while ($rows = mssql_fetch_array($result,MSSQL_ASSOC)) {

	                $database->filas[] = $rows;

                }

                     return $database->filas;
                 }else{
                 return  $database->filas;
                 }


			}else if(extension_loaded('sqlsrv')){

               

				$result=sqlsrv_query($database->connection,$query);

				//echo $query."";

                if (!$result )
                {
                 echo 'Error in statement execution s.\n';
                 die("Problemas en el select:".sqlsrv_errors());
                 }else{
                  
                   
                 while ($rows = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                              
	                 $database->filas[] = $rows;

                 }
                 
                     return $database->filas;

				 }


			}


			break;

		default:
				// Error is triggered for all other database types
				//trigger_error('This database type is not supported',E_USER_ERROR);
				break;

			}
    }



    public static function executeQuery($query,$params=""){


                $database = self::GetInstance();

                $result = sqlsrv_query($database->connection,$query);

                if (!$result )
                {
                    return false;
                 //die("Problemas en el insert:".sqlsrv_errors());
                }else{
                    return true;
                }

    }



    public static function getNumRows($query)
    {
       $database = self::GetInstance();

        // switch($database->type){

				$params = array();
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$result=sqlsrv_query($database->connection,$query,$params,$options);

                //echo $query;

                if (!$result )
                {
                 echo 'Error in statement execution s.\n';
                 die("Problemas en el select:".sqlsrv_errors());
                }

                $row_count = sqlsrv_num_rows( $result );

                if($row_count){return $row_count; }else{ return 0;}

    }


    public static function executeStoreProcedure($storep,$params,$type){

    	$database = self::GetInstance();

        switch($database->type){
			case 'mysql':
			break;

			case 'mssql':

			if(extension_loaded('pdo_mssql')){

			}else if(extension_loaded('mssql')){

				$proc = mssql_init($storep, $database->connection);
                $proc_result = mssql_execute($proc);

                if (!$result )
                 {
             echo 'Error in statement execution.\n';
             die('MSSQL error: ' . mssql_get_last_message());
                 }
                return $result;


			}else if(extension_loaded('sqlsrv')){


				$result=sqlsrv_query($database->connection,$query,$store,$params);

                if ($result === false )
                {
                 echo 'Error in statement execution s.\n';
                 die("Problemas en el store:".sqlsrv_errors());
                }else{
                     if($type == "get"){
					    $arr = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
					    return $arr;
					 }else{
					 	return $result;
					 }

				}

			}

			break;

		default:
				// Error is triggered for all other database types
				//trigger_error('This database type is not supported',E_USER_ERROR);
				break;

			}

	}

    function cacheQuery( $queryStr )
    {
        if( !$result = $this->connections[$this->activeConnection]->query( $queryStr ) )
        {
            trigger_error('Error al ejecutar y cachear a la consulta: '.$this->connections[$this->activeConnection]->error, E_USER_ERROR);
            return -1;
        }
        else
        {
            $this->queryCache[] = $result;
            return count($this->queryCache)-1;
        }
    }

    /**
     * Obtiene el número de filas de la caché
     * @param int El puntero de la consulta en caché
     * @return int the number of rows
     */
    function numRowsFromCache( $cache_id )
    {
        return $this->queryCache[$cache_id]->num_rows;
    }

    /**
     * Recibe las filas de una consulta en la caché
     * @param int El puntero de la consulta en caché
     * @return array the row
     */
    function resultsFromCache( $cache_id )
    {
        return $this->queryCache[$cache_id]->fetch_array(MYSQLI_ASSOC);
    }

    /**
     * Guardar los datos en caché para su posterior uso
     * @param array Los datos
     * @return int El total de registros almacenados en la caché
     */
    function cacheData( $data )
    {
        $this->dataCache[] = $data;
        return count( $this->dataCache )-1;
    }


    function dataFromCache( $cache_id )
    {
        return $this->dataCache[$cache_id];
    }


    public static function deleteRecords( $table, $condition, $limit="")
    {
        $limit = ( $limit == '' ) ? '' : ' LIMIT ' . $limit;
        $delete = "DELETE FROM ".$table." WHERE ".$condition." ".$limit;
        
        return database::executeQuery( $delete );
    }


     public static function	 updateRecords( $table, $changes, $condition )
    {

       $update = "UPDATE " . $table . " SET ";
        foreach( $changes as $field => $value )
        {

            if($value=='NULL'){
                $update .= "" . $field . "=$value,";
            }else{
                $value =  mb_convert_encoding($value, "ISO-8859-1", "UTF-8");
                $update .= "" . $field . "='{$value}',";
            }
        }


        $update = substr($update, 0, -1);
        if( $condition != '' )
        {
            $update .= " WHERE " . $condition;
        }
      
        // echo $update;
    
        return database::executeQuery($update);
        //return true;

    }


    public static function insertRecords( $table, $data )
    {

        // Configuración de variables para campo y valor
        $fields  = "";
        $values = "";

        // Rellena las variables con los campos y sus valores
        foreach ($data as $f => $v)
        {

        	$v =  mb_convert_encoding($v, "ISO-8859-1", "UTF-8");
            $fields  .= "$f,";
            //$values .= ( is_numeric( $v ) && ( intval( $v ) == $v ) ) ? $v."," : "'$v',";
            $values .= "'$v',";

        }

        // Quitamos la coma del final
        $fields = substr($fields, 0, -1);
        // Quitamos la coma del final
        $values = substr($values, 0, -1);

        $insert = "INSERT INTO $table ({$fields}) VALUES({$values})";

		//echo $insert;

		return database::executeQuery($insert);
        
    }



    public static function insertRecordsbyID( $table, $data )
    {
        $database = self::GetInstance();

        // Configuración de variables para campo y valor
        $fields  = "";
        $values = "";

        // Rellena las variables con los campos y sus valores
        foreach ($data as $f => $v)
        {

        	$v =  mb_convert_encoding($v, "ISO-8859-1", "UTF-8");
            $fields  .= "$f,";
            //$values .= ( is_numeric( $v ) && ( intval( $v ) == $v ) ) ? $v."," : "'$v',";
            $values .= "'$v',";

        }

        // Quitamos la coma del final
        $fields = substr($fields, 0, -1);
        // Quitamos la coma del final
        $values = substr($values, 0, -1);

        $insert = "INSERT INTO $table ({$fields}) VALUES({$values}); SELECT SCOPE_IDENTITY()";

        //echo $insert;
        
        $result = sqlsrv_query($database->connection,$insert);
        sqlsrv_next_result($result);
        sqlsrv_fetch($result);  
        
		return sqlsrv_get_field($result, 0);
        
    }



    /**
     * Obtiene el número de las filas afectadas en la última consulta realizada
     * @return int the number of affected rows
     */
    function affectedRows()
    {
        return $this->$this->connections[$this->activeConnection]->affected_rows;
    }

    /**
     * Desinfecta los datos
     * @param String Datos a desinfectar
     * @return String Los datos desinfectados
     */
     function sanitizeData( $data )
    {
        return $this->connections[$this->activeConnection]->real_escape_string( $data );
    }




       public function dbDisconnect($resetQueryCounter=false){


		if($this->connected && !$this->persistent){

			if($resetQueryCounter){
				$this->queryCounter=0;
			}
			$this->pdo=null;
			$this->connected=false;
			return true;
		} else {
			return false;
		}

	}



	private function dbErrorCheck($query,$queryString=false,$variables=array()){


		if($this->connected){
			if($this->showErrors){

				$errors=$query->errorInfo();
				if($errors && !empty($errors)){

					if(!empty($variables)){
						trigger_error('QUERY:'."\n".$this->dbDebug($queryString,$variables)."\n".'FAILED:'."\n".$errors[2],E_USER_WARNING);
					} else {
						trigger_error('QUERY:'."\n".$queryString."\n".'FAILED:'."\n".$errors[2],E_USER_WARNING);
					}
				}
			}
		} else {
			trigger_error('Database not connected',E_USER_ERROR);
		}


		return true;

	}


}


?>
