<?
class Database
{
    public static $instance = null;
    public  $salut = 0;
    private $connection;
    
    private $HOST = "localhost";
    private $DATABASE = "intervention";
    private $PASSWORD = "1BillionDollar";
    private $USER = "server";
    
    public function __construct ()
    {    	
        $this->connection = mysql_connect($this->HOST, $this->USER, 
		$this->PASSWORD, true) or die("Unable to connect do database!");

	mysql_select_db( $this->DATABASE, $this->connection ) 
	    or die("Unable to select database!");
    }
	
	public function reconnect($db = null) {
		if (mysql_ping($this->connection)) {
			return;
		}
		if ($db == null)
			$db = $this->DATABASE;
		mysql_close($this->connection);
		$this->connection = mysql_connect($this->HOST, $this->USER,
				$this->PASSWORD, true) or die("Unable to reconnect to db");
		mysql_select_db($db, $this->connection)
				or die("Unable to select database!");
	 }

    public function selectDB($db) {
        $this->DATABASE = $db;
        mysql_select_db( $db, $this->connection )
                or die("Unable to select new database!");
    }

    public static function getInstance ()
    {
	if (!self::$instance)
	    self::$instance = new self ();
	return self::$instance;
    }

    public function select ($flds, $table, $where = '', $ord = '',$group = '',
	    $limit = '', $lnk = false)
    {	   	
	$q = 'SELECT '.$flds.' FROM '.$table.' WHERE 1';
		
    	if(strlen($where))
            $q .= ' AND '.$where;
        if(strlen($group))
            $q .= ' GROUP BY '.$group;
        if(strlen($ord))
            $q .= ' ORDER BY '.$ord;
        if(strlen($limit))
            $q .= ' LIMIT '.$limit;
        
        if(!$lnk)
	    $lnk = $this->connection;
    
        $r = $this->runQuery($q , $lnk);        
		
	$arr = array();  
		
	if( mysql_num_rows ( $r ) )
            while( $d = mysql_fetch_assoc($r))
            {
                $keys = array_keys($d);
                foreach($keys AS $key)
                    $d[$key] = stripslashes($d[$key]);
                $arr[] = $d;
            }
        if((count($arr) == 1) && (count($arr[0]) == 1))
            $arr = current($arr[0]);
        
        return $arr;
    }
    
    function insert($table, $vals, $id_fld = 'id')
    {
	/* Inserts a new entry in the $table, getting the data from $vals, We 
	   need a global variable, $db_tables, which contains the tables structure. 
	*/

        global $db_tables;
        
        $id = $vals[$id_fld];
        $q_flds = $q_vals = '';
        
        foreach($db_tables[$table] AS $fld)
        {
            if($fld != $id_fld && isset($vals[$fld]))
                if($id)
                    $q_vals .= $fld."='".escape($vals[$fld])."',";
                else
                {
                    $q_flds .= $fld.',';
                    $q_vals .= "'".escape($vals[$fld])."',";
                }
        }
        
        if($id)
            $q = "UPDATE ".$table." SET ".substr($q_vals,0,strlen($q_vals)-1).
				" WHERE ".$id_fld."='".$id."'";
        else
            $q = "INSERT INTO ".$table." (".substr($q_flds,0,strlen($q_flds)-1).
		") VALUES (".substr($q_vals,0,strlen($q_vals)-1).")"; 
        
        $r = $this->runQuery($q);
        
        if($id)
            return $id;
        else
            return mysql_insert_id($this->connection);
    }
    
    public function delete( $table, $where )
    {
	/* Deletes entries from $table, considering $where.  */

        $this->runQuery("DELETE FROM ".$table." WHERE ".$where);
    }
    
    public function runQuery($q,$lnk = false)
    {
		$this->reconnect();
	/* Runs the query $q  */

        global $debug, $debug_mes;
		if($debug && !$_GET['ajax'])
            ob_start();
        if(!$lnk)
            $lnk = $this->connection;
		$qStart = microtime( true );
		$r = mysql_query( $q, $lnk) or die(mysql_error());
		$qEnd = microtime( true );
        $err = mysql_error( $lnk );
        if($debug && !$_GET['ajax'])
        {
            print_v($q.' [Rows: '.mysql_affected_rows($lnk).
					'] [Time: '.round($qEnd-$qStart,4).']');
            if(strlen($err))
                print_v($err);
                
            $debug_mes .= ob_get_contents();
            ob_clean();
        }
        return $r;
    }
	
	public function runQueryAsArray($q)
	{
		$res = $this->runQuery($q);
		$arr = array();
		while ($r = mysql_fetch_array($res))
			$arr[] = $r;
		return $arr;
	}

    public function shift($arr)
    {
	/* Returns the first element from an array */

        if(count($arr))
            return $arr[0];
        else
            return array();
    }
    
    public function escape($str)
    {
	/* Escapes $str fron slashes & other evil stuff */

        if(get_magic_quotes_gpc())
            return $str;
        else
            return mysql_real_escape_string ( $str , $this->connection );
    }
}	
?>
