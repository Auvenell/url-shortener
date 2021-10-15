<?php
    function keyGenerationService($chars, $size, $combinations = array()) {

      if (empty($combinations)) {
          $combinations = $chars;
      }

      if ($size == 1) {
          return $combinations;
      }

      $new_combinations = array();

      foreach ($combinations as $combination) {
          foreach ($chars as $char) {
              $new_combinations[] = $combination . $char;
          }
      }

      return keyGenerationService($chars, $size - 1, $new_combinations);
  }
  
  $chars = array(
                  'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
                  'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                  '0','1','2','3','4','5','6','7','8','9','+','/'
                );

  $generatedKeys = keyGenerationService($chars, 1);
  $generatedKeys += keyGenerationService($chars, 2);
  $generatedKeys += keyGenerationService($chars, 3);
  $servername = "mysql";
  $username = "root";
  $password = "root";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=url-shortener", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database is Connected <br><br>";
    

    for ($i = 0; $i < count($generatedKeys); $i++) {
      $stmt = $conn->prepare("INSERT INTO `url-shortener`.url_handlers(short_url) VALUES ('/$generatedKeys[$i]')");
      $stmt->execute();
    }

    echo "Key Generation - Success";

  } catch(PDOException $e) {
    echo "Key Generation Failure (SQL Error): " . $e->getMessage();
  }
?>