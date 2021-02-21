
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

            $sql = "SELECT COUNT(RoomID) as TOTAL FROM booking WHERE BookingDate = :dt";
            $stmt = self::$conn->prepare($sql);
            $stmt->execute(['dt' => $booking_date]);
            $result = $stmt->fetch();

            $result = 100 - ((int) $result['TOTAL']) * (100/10);

            echo "<script>alert('THE CAPACITY OF FREE WORKING PLACES ON $booking_date IS {$result}%');</script>";
            self::$conn = null;
        }


        public static function book($rid, $dt, $name) {
            self::connect();
                $split = explode("-", $rid);

                $rid = (int) $split[0];
                $max = (int) $split[1];
                $rname = $split[2];

                $isFull = false;

                $text = "";

                try {
                    // is table full?
                    $sql = "SELECT * FROM booking";
                    $stmt = self::$conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->rowCount();
                    
                    // if no record found in booking table
                    $result == 0 ? $isFull = false : $isFull = true;
                    
                    if($isFull == true) {
              
                        $sql = "SELECT RoomID, BookedBy FROM booking WHERE RoomID = :rid && BookingDate = :dt";
                        
                        $stmt = self::$conn->prepare($sql);
                        $stmt->execute(['rid' => $rid, 'dt' => $dt]);
                        $stmt->execute();

                        
                        $count = $stmt->rowCount();
                        $result = $stmt->fetchAll();
                        
                        if($count < $max) {
                            $sql = "INSERT INTO booking (RoomID, BookedBy, BookingDate, MaxWorkPlace) VALUES (:rid, :n, :dt, :mwp)";
                            $stmt = self::$conn->prepare($sql);
                            $stmt->execute(['rid' => $rid, 'n' => $name, 'dt' => $dt, 'mwp' => $max]);

                            echo "<script>alert('We booked successfully a place in $rname on $dt for you');</script>";
              
                        } else {
                            $text = $text . "Room is already booked out by ";
                            foreach($result as $val) {
                                $text = $text . $val['BookedBy'] . " ";
                            }
                            $text = $text . "on this day. Try room(s): ";

                        for($i = 1; $i <= 6; $i++) {
                        
                            $sql = "SELECT COUNT(RoomID) as TOTAL, MaxWorkPlace FROM booking WHERE RoomID = :rid && BookingDate = :dt";
                            $stmt = self::$conn->prepare($sql);
                            $stmt->execute(['rid' => $i, 'dt' => $dt]);
                            $result = $stmt->fetch();
                       
                            $result = (int) $result['TOTAL'];
                            
                            switch($i) {
                                case 1:
                                    $result < 1 ? $text = $text . "Office 1 WITH OPEN PLACES: " . (1 - $result) . " | ": "";
                                    break;
                                case 2:
                                    $result < 1 ? $text = $text . "Office 2 WITH OPEN PLACES: " . (1 - $result) . " | " : "";
                                    break;
                                case 3:
                                    $result < 2 ? $text = $text . "Office 3 WITH OPEN PLACES: " . (2 - $result) . " | " : "";
                                    break;
                                case 4:
                                    $result < 3 ? $text = $text . "Big Office WITH OPEN PLACES: " . (3 - $result) . " | " : "";
                                    break;
                                case 5:
                                    $result < 1 ? $text = $text . "Boss Office WITH OPEN PLACES: " . (1 - $result) . " | " : "";
                                    break;
                                case 6:
                                    $result < 2 ? $text = $text . "Additional Office WITH OPEN PLACES: " . (2 - $result) . " | " : "";
                                    break;
                            }   
                        }
                            echo "<script>alert('$text');</script>";
                        }
                   
                    } else {
                        $sql = "INSERT INTO booking (RoomID, BookedBy, BookingDate, MaxWorkPlace) VALUES (:rid, :n, :dt, :mwp)";
                        $stmt = self::$conn->prepare($sql);
                        $stmt->execute(['rid' => $rid, 'n' => $name, 'dt' => $dt, 'mwp' => $max]);
                     
                        echo "<script>alert('We booked successfully a place in $rname on $dt for you');</script>";
                    }

                } catch (PDOException $e) {
                    echo $e;
                }
            self::$conn = null;
        }




    }
?>