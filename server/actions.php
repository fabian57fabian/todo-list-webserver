<?php
	/* REQUIRE DB
		CREATE TABLE to_do (
  			id int(11) NOT NULL AUTO_INCREMENT,
  			text text NOT NULL,
  			completed tinyint(255) NOT NULL DEFAULT '0',
  			date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  			PRIMARY KEY (id)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;
	*/

	/* setta il formato di risposta */
	header('Content-Type: text/json');
	require_once("config.php");
	$action = $_POST['action'];

	/* conterrà la stringa di query al database */
	$query_string = "";

	
	/* smista secondo il tipo di richiesta */
	switch($action) {
		case "load" : 
			loadData();
		break;
		case "insert" :
			//echo($action);
			insertData();
		break; 
		case "delete" :
		    deleteData();
		break;
		case "update":
		    updateData();
		break;
	}
	
	function loadData() {
		$query_string = 'SELECT * FROM to_do ORDER BY date DESC'; 
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
	
    	// esegui la query
		$result = $mysqli->query($query_string); 
	
    	$todos = array();	
    
    	// cicla sul risultato
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		
			$todo_id = $row['id'];
			$todo_text = $row['text'];
			$to_do_completed = $row['completed'];
			$todo_date = $row['date'];
  
			$todoo = array('id' => $todo_id,'text' =>$todo_text, 'completed' => $to_do_completed, 'date' => $todo_date);
			array_push($todos, $todoo);
		}
	
    	$response = array('todos' => $todos, 'type' => 'load');

		// encodo l'array in JSON
		echo json_encode($response);	
	
}
	
	function insertData() {
		if (isset($_POST['text'])) {
			$to_do_text = $_POST['text'];
		} else {
			echo "No text specified";
			return;
		}
		
		$query_string = "INSERT INTO to_do (text) values ('". htmlspecialchars($to_do_text) . "')";
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
	
    	// esegui la query per inserire il to do nel db
		$result = $mysqli->query($query_string); 
	
	
    	$query_string = 'SELECT * FROM to_do WHERE ID=' . $mysqli->insert_id; 
	
		//$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
	
    	// esegui la query per rileggere il record inserito
		$result = $mysqli->query($query_string); 
	
    	$todos = array();	
    
    	// cicla sul risultato
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		
			$todo_id = $row['id'];
  			$todo_text = $row['text'];
			$to_do_completed = $row['completed'];
  			$todo_date = $row['date'];
  
			$todoo = array('id' => $todo_id,'text' =>$todo_text, 'completed' => $to_do_completed, 'date' => $todo_date);
			array_push($todos, $todoo);
		}
	
    	$response = array('todos' => $todos, 'type' => 'insert');

		// encodo l'array in JSON
		echo json_encode($response);
	}
	
	function deleteData(){
		if (isset($_POST['id'])) {
			$id = intval($_POST['id']);
		} else {
			echo json_encode(array('result' => "invalid id!"));
			return;
		}
		
		$query_string = "delete from to_do where id=".$id.";";
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		
		// esegui la query per rimuovere il to do dal db
		$result = $mysqli->query($query_string);

		if($mysqli->affected_rows>0){
            echo json_encode(array('result' => "OK"));
        }else{
            echo json_encode(array('result' => "No rows changed in db!"));
        }
	}

	function updateData(){
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		} else {
			echo json_encode(array('result' => "invalid id!"));
			return;
		}

		if (isset($_POST['status'])) {
            $status = $_POST['status'];
        } else {
            echo json_encode(array('result' => "invalid status!"));
            return;
        }

		$query_string = "update to_do set completed = " . $status . " where id = ". $id .";";
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

		// esegui la query per aggiornare il to do dal db
		$result = $mysqli->query($query_string);

		if($mysqli->affected_rows>0){
            echo json_encode(array('result' => "OK"));
        }else{
            echo json_encode(array('result' => "No rows changed in db!"));
        }
	}

?>