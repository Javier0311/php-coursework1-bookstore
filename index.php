<?php
    // Building array
    $booksArrays = [
        [
            "title" => "Dune",
            "author" => "Frank Herbert",
            "genre" => "Science Fiction",
            "price" => 29.99
        ],
        [
            "title" => "The Hobbit",
            "author" => "J.R.R. Tolkien",
            "genre" => "Fantasy",
            "price" => 19.99
        ],
        [
            "title" => "The Great Gatsby",
            "author" => "F. Scott Fitzgerald",
            "genre" => "Classic",
            "price" => 14.99
        ],
        [
            "title" => "To Kill a Mockingbird",
            "author" => "Harper Lee",
            "genre" => "Fantasy",
            "price" => 12.99
        ],
        [
            "title" => "1984",
            "author" => "George Orwell",
            "genre" => "Dystopian",
            "price" => 9.99
        ]
    ];
    // Server Info & Timestamp
    date_default_timezone_set("America/Vancouver");
    $timeRequest = date("Y-m-d H:i");
    $adressIP = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // POST information and store in variables
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST['title']) && isset($_POST['author']) && isset($_POST['genre'])&& isset($_POST['price'])){
            $newBook = [
                "title" => $_POST['title'],
                "author" => $_POST['author'],
                "genre" => $_POST['genre'],
                "price" => $_POST['price']
            ];
            array_push($booksArrays, $newBook);
            // File Logging
            $myfile = fopen("bookstore_log.txt", "a") or die ("Unable to open file!");
            $message = "[$timeRequest] IP: $adressIP | $userAgent | Added book: " . $_POST['title'] . "(" . $_POST['genre'] . ", " .  $_POST['price'] . ")\n";
            fwrite($myfile, $message);
            fclose($myfile);
        }
    }

    // Logic part to apply discount
    function applyDiscount(&$booksArrays){
        foreach($booksArrays as &$book){
            if($book["genre"] === "Science Fiction"){ // Search for the key and compare the name to find the genre that we want it
                $book["price"] = round($book["price"] - ($book["price"] * 0.1), 2); // Apply to discounte and rounded to 2 decimals
            }
            if($book["genre"] === "Fantasy"){ // Search for the key and compare the name to find the genre that we want it
                $book["price"] = round($book["price"] - ($book["price"] * 0.05), 2); // Apply to discounte and rounded to 2 decimals
            }   
        }
        // print_r($booksArrays);
    };
    applyDiscount($booksArrays);

    // Calculate the total
    $total = 0;
    foreach($booksArrays as $prices){
        $total = $total + $prices['price'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    
    <form action="index.php" method="POST" id="form">
        <label for="title">Title</label>
        <input type="text" name="title" placeholder="Type title" required>
        <br>

        <label for="author">Author</label>
        <input type="text" name="author" placeholder="Type author" required>
        <br>

        <label for="genre">Genre</label>
        <input type="text" name="genre" placeholder="Type genre" required>
        <br>

        <label for="price">Price</label>
        <input type="number" name="price" placeholder="Type price" step="0.01" required>
        <br>

        <button type="submit">Submit Data</button>
    </form>

    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Price</th>
        </tr> 
        <?php foreach($booksArrays as $books){ ?>
            <tr>
                <td><?php echo $books['title'] ?></td>
                <td><?php echo $books['author'] ?></td>
                <td><?php echo $books['genre'] ?></td>
                <td><?php echo $books['price'] ?></td>
            </tr>     
            <?php } ?>
    </table>

    <div>
        Total price after discounts: <?php echo $total ?>
    </div>

    <div>
        <div>Request time: <?php echo $timeRequest ?></div>
        <div>IP: <?php echo $adressIP ?></div>
        <div>User Agent: <?php echo $userAgent ?></div>
    </div>

    <pre><?php echo file_get_contents("bookstore_log.txt") ? file_get_contents("bookstore_log.txt") : "This file is empty"; ?></pre>

</body>
</html>