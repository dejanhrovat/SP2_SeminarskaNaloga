<?php
class API{
    private $connect = '';
    function __construct(){
        $this->database_connection();
    }
    function database_connection(){
        $this->connect = new PDO("mysql:host=152.89.234.80;dbname=dule10_test", "dule10" , "gVd7*e^w8mj@CzMT");
    }
    function fetch_all(){
        $query = "SELECT * FROM uporabnik ORDER BY id";
        $statement = $this->connect->prepare($query);
        if($statement->execute()){
            while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                $data[] = $row;
            }
            return $data;
        }
    }
    
    function insert(){
        if(isset($_POST["first_name"])){
            $form_data=array(
                ':first_name'=>$_POST["first_name"],
                ':last_name'=>$_POST["last_name"],
                ':homeAddress'=>$_POST["homeAddress"]
            );
            $query="INSERT INTO uporabnik (ime, priimek, naslov) VALUES (:first_name, :last_name, :homeAddress)";
            $statement=$this->connect->prepare($query);
            if($statement->execute($form_data)){
                $data[]=array(
                    'success'=>'1'
                );
            }else{
                $data[]=array(
                    'success'=>'0'
                );
            }
        }else{
            $data[] = array(
                'success' => '0'
            );
        }
        return $data;
    }

    function fetch_single($id){
        $query="SELECT * FROM uporabnik WHERE id='".$id."'";
        $statement=$this->connect->prepare($query);
        if($statement->execute()){
            foreach($statement->fetchAll() as $row){
                $data['first_name']=$row['ime'];
                $data['last_name']=$row['priimek'];
                $data['homeAddress']=$row['naslov'];
            }
            return $data;
        }
    }

    function update(){
        if(isset($_POST["first_name"])){
            $form_data=array(
                ':first_name' => $_POST['first_name'],
                ':last_name' => $_POST['last_name'],
                ':homeAddress' => $_POST['homeAddress'],
                ':id' => $_POST['id']
            );
            $query="UPDATE uporabnik SET ime=:first_name, priimek=:last_name, naslov=:homeAddress WHERE id=:id";
            $statement=$this->connect->prepare($query);
            if($statement->execute($form_data)){
                $data[]=array(
                    'success' => '1'
                );
            }else{
                $data[] = array(
                    'success' => '0'
                );
            }
            return $data;
        }
    }

    function delete($id){
        $query="DELETE FROM uporabnik WHERE id='".$id."'";
        $statement=$this->connect->prepare($query);
        if($statement->execute()){
            $data[]=array(
                'success'=>'1'
            );
        }else{
            $data[]=array(
                'success'=>'0'
            );
        }
        return $data;
    }
}
?>