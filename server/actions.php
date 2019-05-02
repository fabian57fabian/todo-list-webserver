<?php
    header('Context-Type: text/json');
    require_once("config.php");
    $action = $_GET['action'];

    $query_string = "";

    switch($action){
        case "load":
            loadData();
        break;
        case "insert":
            insertData();
        break;
    }

    function loadData(){
        echo "loadData";
    }

    function insertData(){
        echo "insertData";
        if(isset($_GET['text'])){
            $to_do_text = $_GET['text'];
        }else{
            echo "text not defined";
            return;
        }

        $query_string = "INSERT INTO to_do (text) VALUES ('". htmlspecialchars($to_do_text) ."');";

        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        $result = $mysqli->query($query_string);

        $query_String = "SELECT * FROM to_do WHERE ID='".$mysqli->insert_id."';";
        $result = $mysqli->query($query_string);
        $todos = array();
        while($row=$result->fetch_array(MYSQLI_ASSOC)){
            $todo_id = $row['id'];
            $todo_text = $row['text'];
            $todo_completed = $row['completed'];
            $todo_date = $row['date'];

            $todo = array('id' => $todo_id,
                            'text' => $todo_text,
                            'completed' => $todo_completed,
                            'date' => $todo_date);
            array_push($todos, $todo);
        }
        $response = array('todos' => $todos, 'type' => 'insert');
        echo json_encode($response);
        $mysqli->close();
    }
?>