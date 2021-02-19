
<style>
    .conn-success {
        font-family: 'Courier New', Courier, monospace;
        position: fixed;
        bottom: 0;
    }
    .conn-error {
        font-family: 'Courier New', Courier, monospace;
        position: fixed;
        color: #FF0000;
    }
</style>
<?php

    define("_SERVER", "localhost");
    define("_USER", "root");
    define("_PASSWORD", "");
    define("_DB", "crf_db"); 

    class DbQuery {
        private static $conn;

        public static function connect() {
            try {
                self::$conn = new PDO("mysql:host=". _SERVER ."; dbname=". _DB, _USER, _PASSWORD);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                echo "<span class='conn-success'>CONNECTED SUCCESSFULLY</span>";

            } catch(PDOException $e){
                echo "<span class='conn-error'>{$e->getMessage()}</span>";
                die();
            }
            
        }

        public static function find_capacity($booking_date) {
            self::connect();
            echo "<script>alert('$booking_date');</script>";
            $sql = "SELECT COUNT(RoomID) as TOTAL FROM booking WHERE BookingDate = :dt";
            $stmt = self::$conn->prepare($sql);
            $stmt->execute(['dt' => $booking_date]);
            $result = $stmt->fetch();

            echo $result['TOTAL'] . "<br>";
            echo 100 - ((int) $result['TOTAL']) * (100/10) . " %";
            self::$conn = null;
        }


        public static function book($rid, $dt, $name) {
            self::connect();
     
         
                $isFull = false;
                
                try {
                    // is table full?
                    $sql = "SELECT * FROM booking";
                    $stmt = self::$conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->rowCount();
                    
                    // if no record found in booking table
                    $result == 0 ? $isFull = false : $isFull = true;
                    
                    if($isFull == true) {
                        $sql = "SELECT RoomID, BookedBy  FROM booking WHERE RoomID = :rid && BookingDate = :dt";
                        $stmt = self::$conn->prepare($sql);
                        $stmt->execute(['rid' => $rid, 'dt' => $dt]);
                        $count = $stmt->rowCount();
                        $result = $stmt->fetchAll(); // fill

                        print_r($count);


                        //Big Office
                        if($count < 2) {
                            echo "you can book ";

                            

                        } else {
                            echo "Room is already booked out by ";
                            foreach($result as $val) {
                                echo $val['BookedBy'] . " ";
                            }
                            echo "on this day";


                            
                            try {
                                
                                

                                echo "Try room(s): ";

                                

                                for($i = 1; $i <= 6; $i++) {
                              
                                    $sql = "SELECT COUNT(RoomID) as TOTAL FROM booking WHERE RoomID = :rid && BookingDate = :dt";
                                    $stmt = self::$conn->prepare($sql);
                                    $stmt->execute(['rid' => $i, 'dt' => $dt]);
                                    $result = $stmt->fetch();
                                    $result = (int) $result['TOTAL'];
                              
                                    switch($i) {
                                        case 1:
                                            echo $result < 1 ? "Office 1: " . (1 - $result) : "";
                                            break;
                                        case 2:
                                            echo $result < 1 ? "Office 2: " . (1 - $result) : "";
                                            break;
                                        case 3:
                                            echo $result < 2 ? "Office 3: " . (2 - $result) : "";
                                            break;
                                        case 4:
                                            echo $result < 3 ? "Big Office: " . (3 - $result) : "";
                                            break;
                                        case 5:
                                            echo $result < 1 ? "Boss Office: " . (1 - $result) : "";
                                            break;
                                        case 6:
                                            echo $result < 2 ? "Additional Office: " . (2 - $result) : "";
                                            break;
                                    }

                                    
                                }

                            } catch (PDOException $e) {
                                echo $e;
                            }

                            
                        }
                   
                    }
                    

                } catch (PDOException $e) {
                    echo $e;
                }


            self::$conn = null;
        }




    }
?>