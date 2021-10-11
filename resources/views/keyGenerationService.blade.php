<?php
  $servername = "mysql";
  $username = "root";
  $password = "root";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=url-shortener", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database is Connected <br><br>";
    //$stmt = $conn->prepare("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'url-shortener'");
    //$stmt->execute();

    //$rows = $stmt->fetchAll();
    //echo '<pre>' . var_export($rows, true) . '</pre>';
    /* foreach ($rows as $row) {
      echo $row['TABLE_NAME'] . '<br />';
      echo $row['TABLE_ROWS'] . '<br />';
      echo $row['TABLE_COLLATION'] . '<br />';
      echo $row['DATA_LENGTH'] . '<br />';
    } */
  } catch(PDOException $e) {
    echo "Connection Failure: " . $e->getMessage();
  }

  $inputUrl = 'https://www.google.com';
  $totalRecords;
  $encodedUrl;

  function keyGenerationService($a) {
    $randomizer = substr(strval(rand()), 0, 6);
    echo("inputted url: " . $a . "<br>");
    echo("randomizer: " . $randomizer . "<br>");
    $a = $a.$randomizer;
    echo("url + randomizer: " . $a . "<br>");
    $encodedUrl = base64_encode($a);
    echo $encodedUrl;

    function sampling($chars, $size, $combinations = array()) {

      # if it's the first iteration, the first set 
      # of combinations is the same as the set of characters
      if (empty($combinations)) {
          $combinations = $chars;
      }
  
      # we're done if we're at size 1
      if ($size == 1) {
          return $combinations;
      }
  
      # initialise array to put new values in
      $new_combinations = array();
  
      # loop through existing combinations and character set to create strings
      foreach ($combinations as $combination) {
          foreach ($chars as $char) {
              $new_combinations[] = $combination . $char;
          }
      }
  
      # call same function again for the next iteration
      return sampling($chars, $size - 1, $new_combinations);
  
  }
  
  $chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9','+','/');
  $output = sampling($chars, 3);
  //echo('<pre>' . var_export($output, true) . '</pre>');
  echo(count($output));

  $servername = "mysql";
  $username = "root";
  $password = "root";

      try {
        $conn = new PDO("mysql:host=$servername;dbname=url-shortener", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Database is Connected <br><br>";
        

        for ($i = 0; $i < count($output); $i++) {
          $stmt = $conn->prepare("INSERT INTO `url-shortener`.keys(suffix) VALUES ('$output[$i]')");
          $stmt->execute();
        }

      } catch(PDOException $e) {
        echo "Connection Failure: " . $e->getMessage();
      }


  }

  keyGenerationService($inputUrl);
  echo "<br>";

?>

@section("content")
@endsection

<h1>URL Form</h1>
<form>
  @csrf
  <label>Slug
    <input type="text" name="slug" />
  </label>
</form>