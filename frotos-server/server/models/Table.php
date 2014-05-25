<?
include_once($root."models/Database.php");
class Table
{
	protected $db;
	protected $info;
	protected $table;
	protected $idName;
	protected $id;

	public function __construct($table, $idName, $id = 0, $info = '')
	{
		$this->db = Database::getInstance();
		$this->table = $table;
		$this->id = $id;
		$this->idName = $idName;
		if ($info == '')
		{
			$res = $this->db->runQueryAsArray( "SELECT * FROM $table WHERE ".
					"$idName = '$id'");
			if (count($res) > 0)
				$info = $res[0];
            else
                $info = null;
		}
        
        if (count($info) == 0 || $info === null) {
            $this->info = array();
            $res = $this->db->runQueryAsArray("SELECT * FROM {$table} LIMIT 1");
            if (count($res) == 1) {
                foreach ($res[0] as $k => $v)
                    if (!is_numeric($k))
                        $this->info[$k] = null;
            }
            return;
        }
		$this->info = array();
        foreach ($info as $k => $v)
			if (!is_numeric($k))
					$this->info[$k] = $v;

		if ($this->id == 0)
		{
			if (isset($this->info[$this->idName]))
				$this->id = $this->info[$this->idName];
		}
	}

	public function updateDB()
	{
		$query = "UPDATE $this->table SET ";
		foreach ($this->info as $k => $v)
			if ($v != '' and $k != $this->idName) {
				$k = mysql_escape_string($k);
                $v = mysql_escape_string($v);
                $query .= "$k = '$v',";
            }

		$query = substr($query, 0, strlen($query) - 1);
        $idName = mysql_escape_string($this->idName);
        $id = mysql_escape_string($this->id);
		$query .= " WHERE $idName = '$id'";
		return $this->db->runQuery($query);
	}

	public function deleteDB()
	{
		$query = "DELETE FROM $this->table WHERE $this->idName = '$this->id'";
		return $this->db->runQuery($query);
	}

	public function insertDB()
	{
		$query = "INSERT INTO $this->table(";

		foreach ($this->info as $k => $v) {
			$k = mysql_escape_string($k);
            $query .= $k . ",";
        }
		
		$query[strlen($query) - 1] = ')';
		$query .= " values(";
		
		foreach ($this->info as $k => $v)
		{
			if ($v == "now()")
				$query .= "$v, ";
			else {
				$t = mysql_escape_string($v);
				$query .= "'$t', ";
			}
		}
		$query[strlen($query) - 2] = ')';
		$res = $this->db->runQuery($query);
		if (!isset($this->info[$this->idName]) || 
				$this->info[$this->idName] == '' ||
				$this->info[$this->idName] == 0)
			$this->info[$this->idName] = mysql_insert_id();
		return $res;
	}

	public function printKeys()
	{
		if (count($this->info) > 0)
			foreach ($this->info as $k => $v)
				echo $k . ", ";
		echo "\n";
	}

	public function printValues()
	{
		if (count($this->info) > 0)
			foreach ($this->info as $k => $v)
				echo $k . " => " . $v . "</br>\n";
	}

}

?>
