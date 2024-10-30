<?php
$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "php_lab";

try {
    // Create a new PDO instance
    $connect = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_password);

    // Set the PDO error mode to exception
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $page = $_POST['page'];
    $isbn = $_POST['isbn'];

    // Use a prepared statement for the insert query
    $sql = "INSERT INTO books (title, author, available, page, isbn) VALUES (:title, :author, true, :page, :isbn)";
    $stmt = $connect->prepare($sql);

    try {
        $stmt->execute([':title' => $title, ':author' => $author, ':page' => $page, ':isbn' => $isbn]);
        echo "<script>
                alert('New book added successfully');
                window.location.href = 'http://localhost:8000/';
              </script>";
    } catch (PDOException $e) {
        echo "<script>alert('An error occurred: " . $e->getMessage() . "');</script>";
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $page = $_POST['page'];
    $isbn = $_POST['isbn'];

    // Use a prepared statement for the update query
    $sql = "UPDATE books SET title=:title, author=:author, page=:page, isbn=:isbn WHERE id=:id";
    $stmt = $connect->prepare($sql);

    try {
        $stmt->execute([':title' => $title, ':author' => $author, ':page' => $page, ':isbn' => $isbn, ':id' => $id]);
        echo "<script>
            alert('Record updated successfully');
            window.location.href = 'http://localhost:8000/';
        </script>";
    } catch (PDOException $e) {
        echo "<script>alert('An error occurred while updating: " . $e->getMessage() . "');</script>";
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Use a prepared statement for the delete query
    $sql = "DELETE FROM books WHERE id=:id";
    $stmt = $connect->prepare($sql);

    try {
        $stmt->execute([':id' => $id]);
        echo "<script>
            alert('Record deleted successfully');
            window.location.href = 'http://localhost:8000/';
        </script>";
    } catch (PDOException $e) {
        echo "<script>alert('An error occurred while deleting: " . $e->getMessage() . "');</script>";
    }
}

// Fetch all records from the books table
$sql = 'SELECT * FROM books';
$stmt = $connect->query($sql);

if ($stmt->rowCount() > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Title</th><th>Author</th><th>Page</th><th>ISBN</th><th>Actions</th></tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["title"] . "</td>
                <td>" . $row["author"] . "</td>
                <td>" . $row["page"] . "</td>
                <td>" . $row["isbn"] . "</td>
                <td>
                    <a href='?edit_id=" . $row["id"] . "'>Edit</a>
                    <a href='?delete_id=" . $row["id"] . "' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];

    // Fetch a single record for editing
    $sql = "SELECT * FROM books WHERE id=:id";
    $stmt = $connect->prepare($sql);
    $stmt->execute([':id' => $id]);
    $editRow = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!-- HTML form for submitting and updating records -->
<form method="post" action="index.php">
    <input type="hidden" name="id" value="<?php echo isset($editRow) ? $editRow['id'] : ''; ?>">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" value="<?php echo isset($editRow) ? $editRow['title'] : ''; ?>"><br>
    <label for="author">Author:</label><br>
    <input type="text" id="author" name="author" value="<?php echo isset($editRow) ? $editRow['author'] : ''; ?>"><br>
    <label for="page">Page:</label><br>
    <input type="number" id="page" name="page" value="<?php echo isset($editRow) ? $editRow['page'] : ''; ?>"><br>
    <label for="isbn">ISBN:</label><br>
    <input type="text" id="isbn" name="isbn" value="<?php echo isset($editRow) ? $editRow['isbn'] : ''; ?>"><br>
    <input type="submit" value="<?php echo isset($editRow) ? 'Update' : 'Submit'; ?>" name="<?php echo isset($editRow) ? 'update' : 'submit'; ?>">
</form>