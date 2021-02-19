
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
    require 'class.auth.php';
    define("_SERVER", "localhost");
    define("_USER", "root");
    define("_PASSWORD", "");
    define("_DB", "crf_db"); 

    class User {
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

        public static function UserRegister($username, $pass) {
            self::connect();
                try {
                    $sql = "SELECT username FROM user_info WHERE username = :username;";
                    $stmt = self::$conn->prepare($sql);
                    $stmt->execute(['username' => $username]);
                    $result = $stmt->rowCount();

                    // If $result > 0 then the username exists
                    $result > 0 ? $result = true : $result = false;
    
                } catch(PDOException $e) {
                    echo $e;
                }
                
                if($result == false) {
                    // HASHED WITH 256 BIT WEP SECRET KEY : 1E9CEE7746C9248AA23EEBE49AFDC
                    $hash = hash_hmac('SHA256', $pass, '5FD3C5D1B99DF173ED42536696A57', true);
                    $pass = base64_encode($hash);
    
                    $sql = "INSERT INTO user_info (username, pass) VALUES (:username, :pass);";
                    $stmt = self::$conn->prepare($sql);
                    $stmt->execute(['username'=>$username, 'pass'=>$pass]);
                    echo "<script>alert('USER \'$username\' REGISTERED SUCCESSFULLY!')</script>";
                } else {
                    // If the username is already existing and error message is shown
                    echo "<script>alert('USERNAME \'$username\' ALREADY EXISTS!')</script>";
                }


            self::$conn = null;
        }


        public static function Login($username, $password) {
            self::connect();

            try {
          
                // HASHED WITH 256 BIT WEP SECRET KEY : 1E9CEE7746C9248AA23EEBE49AFDC
                $password = base64_encode(hash_hmac('SHA256', $password, '5FD3C5D1B99DF173ED42536696A57', true));

                $sql = "SELECT username FROM user_info WHERE username= :username && pass= :passw;";
                $stmt = self::$conn->prepare($sql);
                $stmt->execute(['username'=>$username, 'passw'=>$password]);
                $result = $stmt->rowCount();

                if($result > 0) {
                    echo "Hello";
                    $header = array(
                        "alg" => "HS256",
                        "typ" => "JWT"
                    );
                
                    $payload = array(
                        "name" => $username,
                        "iat" => "1516239022"
                    );
                
                    $token = JWTAuth::encode(json_encode($header), json_encode($payload));
                    setcookie("token", $token, time() + (86400 * 30), "/"); // 86400 = 1 day

                    header('Location: main/main.php?');
                }
                else
                    echo "<script>alert('USERNAME/ PASSWORD IS INCORRECT!')</script>";

            } catch(PDOException $e) {
                echo $e;
            }
            self::$conn = null;
        }

    }
?>