<?php
class Orders{

    public function create($conn, $all_total, $r_name, $r_phone, $r_address, $r_postcode, $r_province, $r_city, $s_service, $s_cost, $s_weight, $p_m){
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        function getRandomString($code, $n){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';

            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }

            return "$code-$randomString";
        }
        session_start();
        $username = $_SESSION['user']['username'];
        $getuser = $conn->query("SELECT id, store_name FROM users WHERE username='$username'");
        $getid = $getuser->fetch_object();
        $user_id = $getid->id;
        $storename = $getid->store_name;

        if($storename == ""){
            $storename == $_SESSION['user']['username'];
        }

        $cart = $_SESSION['cart'];

        $alltotal = test_input($all_total);

        $receiver_name = test_input($r_name);
        $receiver_phone = test_input($r_phone);
        $receiver_address = test_input($r_address);
        $receiver_postcode = test_input($r_postcode);
        $receiver_province = test_input($r_province);
        $receiver_city = test_input($r_city);

        $shipment_service = test_input($s_service);
        $shipment_cost = test_input($s_cost);
        $shipment_weight = test_input($s_weight);

        $payment_method = test_input($p_m);
        $payment_approve = 0;
        if($payment_method != "COD"){
            $payment_approve = 1;
        }

        $neworder = $conn->query("INSERT INTO orders_orders (order_code, quantity, total, user_id) VALUES ('".getRandomString('SHDW', 10)."', ". count($_SESSION['cart']) .",'$alltotal', '$user_id')");
       
        $id = $conn->insert_id;

        foreach($cart as $carts){
            $newproducts = $conn->query("INSERT INTO orders_products (order_id, product_id, size, quantity, weight, total) VALUES ($id, '".$carts['id']."', '".$carts['size']."', '". $carts['qty']."', '".$carts['total_weight']."', '". $carts['total']."')");
            $getStock = $conn->query("SELECT stock FROM products WHERE id='".$carts['id']."'");
            $retriveStock = $getStock->fetch_object();
            $stock = $retriveStock->stock;
            $stock = $stock - $carts['qty'];
            $updateStock = $conn->query("UPDATE products SET stock='$stock' WHERE id='".$carts['id']."'");
        }

        $newreceiver = $conn->query("INSERT INTO orders_receivers (order_id, name, phone_number, address, post_code, province, city) VALUES ($id, '$receiver_name', '$receiver_phone', '$receiver_address', '$receiver_postcode', '$receiver_province', '$receiver_city')");

        $newshipment = $conn->query("INSERT INTO orders_shipments (order_id, shipment_code, shipper_name, courier, weight, service, cost) VALUES ($id, '". getRandomString("SHDW-SHPMNT", 10) ."', '$storename', 'jne', '$shipment_weight', '$shipment_service', '$shipment_cost')");

        $newpayment = $conn->query("INSERT INTO orders_payments (order_id, payment_code, method, approve_by_admin) VALUES ($id, '".getRandomString("SHDW-PYMNT", 10)."', '$payment_method', '$payment_approve')");

        if($neworder === TRUE && $newreceiver === TRUE && $newshipment === TRUE &&$newpayment === TRUE){
            unset($_SESSION['cart']);
            header("location: user.php");
        }
    }
    public function confirmOrder($conn, $order_code){
        $updateOrder = $conn->query("UPDATE orders_orders SET status=0, status_info='selesai' WHERE order_code='$order_code'");
        if($updateOrder === TRUE){
            header("location: user.php?orders=selesai");
        }
    }
    public function cancelOrder($conn, $order_code){
        $cancelOrder = $conn->query("UPDATE orders_orders SET status=0, status_info='batal' WHERE order_code='$order_code'");
        if($cancelOrder === TRUE){
            header("location: user.php?orders=batal");
        }
    }
    public function approveOrder($conn, $order_code){
        $response = [];
        $getOrderId = $conn->query("SELECT id FROM orders_orders WHERE order_code='$order_code'");
        if($getOrderId->num_rows > 0){

            $getId = $getOrderId->fetch_object();
            $order_id = $getId->id;

            $approve = $conn->query("UPDATE orders_payments SET approve_by_admin=0 WHERE order_id='$order_id'");

            if($approve === TRUE){
                $not_approve_count = $conn->query("SELECT COUNT(id) as not_approve_count FROM orders_payments WHERE approve_by_admin=1");
                $count = $not_approve_count->fetch_object();
                $not_approve = $count->not_approve_count;

                $response += [
                    "status" => 200,
                    "not_approve_count" => $not_approve
                ];
            }

        }else{
            $response += [
                "status" => 400,
                "reload" => true
            ];
        }

        echo json_encode($response);
        return $response;
    }

    public function notApproveOrder($conn){
        $checkNotApprove = $conn->query("SELECT id FROM orders_payments WHERE approve_by_admin=1");
        if($checkNotApprove->num_rows > 0){
            $response = [
                "status" => 200
            ];
        }else{
            $response = [
                "status" => 400
            ];
        }

        echo json_encode($response);
        return $response;
    }
    public function allOrders($conn, $status, $username, $sort_by){
        $filter = "WHERE ";
        
        if($status == "" || $status == "semua"){
        }else if($status == "belum-approve"){
            $filter .= "orders_payments.approve_by_admin=1 AND orders_orders.status_info='dalam-proses'";
        }else if($status == "belum-konfirmasi"){
            $filter .= "orders_payments.approve_by_admin=0 AND orders_orders.status_info='dalam-proses'";
        }else if($status == "selesai"){
            $filter .= "orders_orders.status_info='selesai'";
        }elseif ($status == "batal"){
            $filter .= "orders_orders.status_info='batal'";
        }
        
        if($username != ""){
            if(strlen($filter) > 6){
                $filter .= " AND ";
            }
            $filter .= "users.username LIKE '%$username%'";
        }
        
        $order_by = "ORDER BY orders_orders.created_at DESC";

        if($sort_by != ""){
            if($sort_by == "oldest"){
                $order_by = "ORDER BY orders_orders.created_at";
            }
        }

        if(($status == "" || $status == "semua") && $username == ""){
            $filter = "";
        }

        $getOrders = $conn->query("SELECT orders_orders.*, orders_orders.id as order_id, products.image, products.id as products_id, orders_payments.approve_by_admin as approve, users.id as user_id, users.username FROM orders_orders JOIN orders_products ON orders_products.order_id=orders_orders.id JOIN products ON products.id=orders_products.product_id LEFT JOIN orders_payments ON orders_payments.order_id = orders_orders.id LEFT JOIN users ON users.id=orders_orders.user_id $filter GROUP BY orders_orders.id $order_by");

        if($getOrders->num_rows > 0){
        while($op = $getOrders->fetch_object()){
            $result[] = $op;
        };
        }else{
            $result = 0;
        }

        return $result;
    }
    public function userOrders($conn, $username, $status){
        $getuserid = $conn->query("SELECT id FROM users WHERE username='$username'");
        $getid = $getuserid->fetch_object();
        $user_id = $getid->id;
        $filter = "";

        if($status != ""){
            $filter = "AND orders_orders.status_info='$status'";
        }
        $getOrders = $conn->query("SELECT orders_orders.*, orders_orders.id as orders_id, products.image, products.id as products_id, orders_payments.approve_by_admin as approve FROM orders_orders JOIN orders_products ON orders_products.order_id=orders_orders.id JOIN products ON products.id=orders_products.product_id LEFT JOIN orders_payments ON orders_payments.order_id = orders_orders.id WHERE orders_orders.user_id='$user_id' ". $filter ." GROUP BY orders_orders.id ORDER BY orders_orders.updated_at DESC");
        
        if($getOrders->num_rows > 0){
        while($op = $getOrders->fetch_object()){
            $result[] = $op;
        };
        }else{
            $result = 0;
        }

        return $result;
    }
    public function orderDetail($conn, $order_code){
        $getOrder = $conn->query("SELECT orders_orders.*, orders_orders.id as order_id, orders_orders.quantity as order_quantity, orders_orders.total as order_total, orders_orders.created_at as order_created_at, orders_products.*, orders_products.id as order_products_id, orders_products.product_id as order_product_product, orders_products.quantity as order_product_quantity, orders_products.weight as order_product_weight, orders_products.total as order_product_total, products.*, products.id as products_id, products.name as products_name, orders_receivers.*, orders_receivers.id as receiver_id, orders_receivers.name as receiver_name, orders_shipments.*, orders_shipments.id as shipment_id, orders_shipments.weight as shipment_weight, orders_payments.*, orders_payments.id as payment_id FROM orders_orders LEFT JOIN orders_products ON orders_products.order_id=orders_orders.id LEFT JOIN products ON products.id=orders_products.product_id INNER JOIN orders_receivers ON orders_receivers.order_id=orders_orders.id INNER JOIN orders_shipments ON orders_shipments.order_id=orders_orders.id INNER JOIN orders_payments ON orders_payments.order_id=orders_orders.id WHERE order_code='$order_code'");
        $order_products = null;
        $result = [];
        while($order = $getOrder->fetch_object()){
            $order_id = $order->order_id;
            $order_products_id = $order->order_products_id;
            $products_id = $order->products_id;

            if(!isset($result[$order_id])){
                $result[$order_id] = [
                    "order_code" => $order->order_code,
                    "order_quantity" => $order->order_quantity,
                    "order_total" => $order->order_total,
                    "order_status" => $order->status,
                    "order_info" => $order->status_info,
                    "order_created_at" => $order->order_created_at,
                    "order_products" => [],
                    "products" => [],
                    "receiver" => [],
                    "shipment" => [],
                    "payment" => [],
                ];
            }

            $result[$order_id]['order_products'][$order_products_id] = [
                "order_product_id" => $order_products_id,
                "order_product_product" => $order->order_product_product,
                "order_product_size" => $order->size,
                "order_product_quantity" => $order->order_product_quantity,
                "order_product_weight" =>  $order->order_product_weight,
                "order_product_total" => $order->order_product_total
            ];

            $result[$order_id]['products'][$products_id] = [
                "product_id" => $order->products_id,
                "products_name" => $order->products_name,
                "products_image" => $order->image,
                "products_price" => $order->price
            ];

            $result[$order_id]['receiver'] = [
                "receiver_id" => $order->receiver_id,
                "receiver_name" => $order->receiver_name,
                "receiver_phone_number" => $order->phone_number, 
                "receiver_address" => $order->address, 
                "receiver_post_code" => $order->post_code, 
                "receiver_province" => $order->province, 
                "receiver_city" => $order->city, 
            ];

            $result[$order_id]['shipment'] = [
                "shipment_id" => $order->shipment_id,
                "shipment_code" => $order->shipment_code,
                "shipment_shipper" => $order->shipper_name,
                "shipment_courier" => $order->courier,
                "shipment_weight" => $order->shipment_weight,
                "shipment_service" => $order->service,
                "shipment_cost" => $order->cost,
            ];

            $result[$order_id]['payment'] = [            
                "payment_id" => $order->payment_id,
                "payment_code" => $order->payment_code,
                "payment_method" => $order->method,
                "payment_approve" => $order->approve_by_admin,
            ];

        }
        return $result;
    }

    public function ordersInfo($conn){
        $getOrderCount = $conn->query("SELECT SUM(total) as balance, COUNT(id) as order_count FROM orders_orders WHERE status=0 AND status_info='selesai'");
        $order_total = [];
        while($data = $getOrderCount->fetch_object()){
            $order_total[] = $data;
        }
        $favorite_product = [];
        $getFavoriteProduct = $conn->query("SELECT orders_products.product_id as product_id, COUNT(*) as product_count, products.name as product_name FROM orders_products LEFT JOIN products ON orders_products.product_id=products.id GROUP BY product_id ORDER BY product_count DESC LIMIT 1");
        while($data = $getFavoriteProduct->fetch_object()){
            $favorite_product[] = $data;
        }

        $result = [
            "order_total" => $order_total,
            "favorite_product" => $favorite_product
        ];

        return $result;
    }
}