<?php
// Include database connection
include '../admin_session_init.php';

$conn = new mysqli('localhost', 'root', '', 'mocks');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle question deletion
if (isset($_POST['delete'])) {
    $sno = $_POST['sno'];
    
    $deleteQuery = "DELETE FROM questions WHERE SNO = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $sno);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Question deleted successfully!');</script>";
    } else {
        echo "<script>alert('Deletion failed.');</script>";
    }
}

// Function to update question
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $sno = $_POST['sno'];
    $questionText = $_POST['questionText'];
    $optA = $_POST['optA'];
    $optB = $_POST['optB'];
    $optC = $_POST['optC'];
    $optD = $_POST['optD'];
    $correctOpt = $_POST['correctOpt'];
    $exam_id = $_POST['exam_id'];
    $picture = $_FILES['picture']['name'];

    if (!empty($picture)) {
        // Absolute path to the uploads folder
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/TEST/uploads/"; // This creates an absolute path
    
        // Ensure the uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
        }
    
        // Full path to the file
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
    
        // Attempt to move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
            // Store relative path in the database
            $picture_path =  basename($_FILES["picture"]["name"]);
        } else {
            echo "<script>alert('File upload failed.');</script>";
        }
    } else {
        // Keep the previous image if no new image is uploaded
        $picture_path = $_POST['existing_image'];
    }
    // Update query
    $updateQuery = "UPDATE questions 
                    SET QuestionText = ?, OptA = ?, OptB = ?, OptC = ?, OptD = ?, CorrectOpt = ?, Picture = ?, exam_id = ? 
                    WHERE SNO = ?";
    
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssssssi", $questionText, $optA, $optB, $optC, $optD, $correctOpt, $picture_path, $exam_id, $sno);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Question updated successfully!');</script>";
    } else {
        echo "<script>alert('No changes made or update failed.');</script>";
    }
}

// Fetch questions again for display
$sql = "SELECT * FROM questions";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Questions</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Questions</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SNO</th>
                    <th>Question Text</th>
                    <th>OptA</th>
                    <th>OptB</th>
                    <th>OptC</th>
                    <th>OptD</th>
                    <th>CorrectOpt</th>
                    <th>Picture</th>
                    <th>Exam ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <form method="POST" enctype="multipart/form-data">
                            <td>
                                <?= $row['SNO'] ?>
                                <input type="hidden" name="sno" value="<?= $row['SNO'] ?>">
                            </td>
                            <td><input type="text" name="questionText" value="<?= $row['QuestionText'] ?>" class="form-control"></td>
                            <td><input type="text" name="optA" value="<?= $row['OptA'] ?>" class="form-control"></td>
                            <td><input type="text" name="optB" value="<?= $row['OptB'] ?>" class="form-control"></td>
                            <td><input type="text" name="optC" value="<?= $row['OptC'] ?>" class="form-control"></td>
                            <td><input type="text" name="optD" value="<?= $row['OptD'] ?>" class="form-control"></td>
                            <td><input type="text" name="correctOpt" value="<?= $row['CorrectOpt'] ?>" class="form-control"></td>
                            <td>
                                <input type="file" name="picture" class="form-control">
                                <input type="hidden" name="existing_image" value="<?= $row['Picture'] ?>">
                                <?php if ($row['Picture'] != 'None') : ?>
                                    <img src="../<?= $row['Picture'] ?>" width="100" alt="Image">
                                <?php endif; ?>
                            </td>
                            <td><input type="text" name="exam_id" value="<?= $row['exam_id'] ?>" class="form-control"></td>
                            <td>
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                <button type="submit" name="delete" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FontAwesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
