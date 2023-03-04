<?php

class Contacts{
    public function createContact($conn, $name, $message){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $name = test_input($name);
        $message = test_input($message);

        $createContact = $conn->query("INSERT INTO contacts (name, message) VALUES ('$name', '$message')");

        if($createContact === TRUE){
            $_SESSION['contact'] = [
                "status" => 200,
                "message" => "Kontak mu sudah berhasil diterima. Terima kasih :)"
            ];
        }else{
            $_SESSION['contact'] = [
                "status" => 400,
                "message" => "Kontak mu tidak berhasil dikirimkan. Coba lagi."
            ];
        }

        header("location: contact.php");
    }
    
    public function readContact($conn, $id, $status){
        $read = $conn->query("UPDATE contacts SET readed=$status WHERE id='$id'");
        if($read === TRUE){
            $response = [
                "status" => 200,
                "id" => $id
            ];
        }else{
            $response = [
                "status" => 400
            ];
        }
        echo json_encode($response);
        return $response;
    }

    public function allContacts($conn, $name, $status, $sort_by, $from, $to){
        $filter = "WHERE ";

        if($name != ""){
            $filter .= "username LIKE %$name%";
        }

        if($status != ""){
            if(strlen($filter) > 6){
                $filter .= " AND ";
            }
            if($status == "belum-dibaca"){
                $filter .= "readed=false";
            }else if($status == "sudah-dibaca"){
                $filter .= "readed=true";
            }
        }

        $min_date = "2023-03-01 00:00:00";
        $max_date = date('Y-m-d H:i:s');
        $now_hours = date("H:i:s");
        if($from != "" || $to != ""){
            if(strlen($filter) > 6){
                $filter .= " AND ";
            }
            if($from != ""){
                $filter .= "created_at BETWEEN $from AND $max_date";
            }else if($to != ""){
                $filter .= "created_at BETWEEN $min_date AND $to";
            }
        }
        
        if($from != "" && $to != ""){
            if(strlen($filter) > 6){
                $filter .= " AND ";
            }
            $to = $to." ".$now_hours;
            $filter .= "created_at BETWEEN $from AND $to";
        }


        $order_by = "created_at DESC";
        if($sort_by != ""){
            if($sort_by == "oldest"){
                $order_by = "created_at";
            }
        }

        if($name == "" && $status == ""){
            $filter = "";
        }

        $getAllContact = $conn->query("SELECT * FROM contacts $filter ORDER BY $order_by");
        if($getAllContact->num_rows > 0){
            while($contact = $getAllContact->fetch_object()){
                $result[] = $contact;
            }
        }else{
            $result = 0;
        }
        return $result;
    }

}