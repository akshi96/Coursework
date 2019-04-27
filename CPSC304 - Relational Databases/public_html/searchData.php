<?php include "header.php"; ?>

<?php

$db_conn = OCILogon("ora_y4t0b", "a20665148", "dbhost.ugrad.cs.ubc.ca:1522/ug");


if(!$db_conn){
    $m = oci_error();
    echo $m['message'], "\n" ; //error function returns an Oracle error message
    $success = False;
    exit;
}


if(isset($_POST['LID'])){
    $LID = $_POST['LID']; //PatronID number

}

if(!empty($_POST['callNum'])){
    $callNum = $_POST['callNum'];//Primary key, search by this
    $callNumQuery ="SELECT callNum, title, genre, type, availability
                FROM material
                WHERE callNum=:callNum
                order by availability desc";
    $callNumParse = oci_parse($db_conn, $callNumQuery);
    ocibindbyname($callNumParse, ":callNum", $callNum);
    executeSQL($callNumParse);
}
if(!empty($_POST["title"] )) {
    $title = $_POST['title']; //title of material being searched for

    if(!empty($_POST['type'])){
        $type = $_POST['type'];
        if(!($_POST['genre'] == 'genre' ) ){
            $genre = $_POST['genre'];
            //search by type, genre and title
            $titleQuery = "SELECT callNum, title, genre, type, availability
                            FROM material
                            WHERE title=:title AND type=:type AND genre=:genre
                            order by availability desc";
            $titleParse = ociparse($db_conn,$titleQuery);
            ocibindbyname($titleParse, ":title", $title);
            ocibindbyname($titleParse,":genre", $genre);
            ocibindbyname($titleParse,":type", $type);
            executeSQL($titleParse);
    }
   //if no genre provided, search by title and type
            $titleQuery = "SELECT callNum, title, genre, type, availability
                            FROM material
                            WHERE title=:title AND type=:type
                            order by availability desc";
            $titleParse = ociparse($db_conn,$titleQuery);
            ocibindbyname($titleParse, ":title", $title);
            ocibindbyname($titleParse,":type", $type);
            executeSQL($titleParse);   
}

    if(!($_POST['genre'] == 'genre' ) ){
         $genre = $_POST['genre'];
            $titleQuery = "SELECT callNum, title, genre, type, availability
                            FROM material
                            WHERE title=:title AND genre=:genre
                            order by availability desc";
            $titleParse = ociparse($db_conn,$titleQuery);
            ocibindbyname($titleParse, ":title", $title);
            ocibindbyname($titleParse,":genre", $genre);
            executeSQL($titleParse);
            //if no type provided, search by genre and title
            

    }if(empty($_POST['type']) && ($_POST['genre'] == 'genre' )){

    $titleQuery = "SELECT callNum, title, genre, type, availability
                            FROM material
                            WHERE title=:title 
                            order by availability desc";
    $titleParse = ociparse($db_conn,$titleQuery);
    ocibindbyname($titleParse, ":title", $title);
    executeSQL($titleParse);
    //if only title, search by title
}
   
}



function executeSQL($cmdstr){
    oci_execute($cmdstr);
    printResult($cmdstr);

}

function printResult($result){
    echo "<br>Search results below: <br>";
    echo "<table>";
    echo "<tr> <th>callNum</th> <th>Title</th> <th>Genre</th> <th>Type</th> <th>Availability</th> </tr>";
    while($row = oci_fetch_array($result, OCI_BOTH)){
            echo "<tr><td>" . $row["CALLNUM"] . "</td><td>" . $row['TITLE'] ."</td><td>" .$row['GENRE']."</td><td>" . $row['TYPE'] . "</td><td>" . $row['AVAILABILITY']."</td></tr>"; 
            }

    }

?>



<?php include "footer.php"; ?>
