<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Form</title>
    <style>
        body {
            background-color: #FFFFE0;
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2{
            text-align:center;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #F0E68C;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        form input[type="text"],
        form select {
            width: 100%;
            padding-top: 8px;
            padding-bottom: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .form {
            width: 50%;
        }
        .center {
            margin: auto;
            width: 50%;
            padding: 10px;
        }
        .middle{
         text-align: center;
        }
    </style>
</head>
<body>
    <h2>STUDENT FORM</h2>
    <div class="container">
    <?php
    $conn = new mysqli("localhost", "user", "password", "school");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $student_id = '';
    $first_name = '';
    $last_name = '';
    $city_code = '';
    $telephone = '';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['student_id'])) {
        $student_id = $_GET['student_id'];
        $sql = "SELECT student.fname, student.lname, student.city_code, student.telephone FROM student WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if ($student) {
            $first_name = $student["fname"];
            $last_name = $student["lname"];
            $city_code = $student["city_code"];
            $telephone = $student["telephone"];
        }
        $stmt->close();
    }
    ?>

    <div class="form center">

    <form action="<?php echo $student_id ? 'update_student.php' : 'save_student.php'; ?>" method="post">
        <?php if ($student_id): ?>
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
        <?php endif; ?>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name"" value="<?php echo $first_name; ?>" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required><br>

        <label for="city">City:</label>
        <select id="city" name="city" required>
            <?php
            $sql = "SELECT city_code, city_name FROM city";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $selected = $row["city_code"] == $city_code ? 'selected' : '';
                    echo "<option value='" . $row["city_code"] . "' $selected>" . $row["city_name"] . "</option>";
                }
            }
            ?>
        </select>
        <br>
        <label for="telephone">Telephone</label>
        <input type="text" id="telephone" name="telephone" value="<?php echo $telephone; ?>" required><br>


        <input type="submit" value="<?php echo $student_id ? 'Update' : 'Save'; ?>">
    </form>
        </div>
    <div style="width: 70%" class="center">
        <h2>Student Data</h2>

    <table border="1">
        <tr>
            <th class="middle">First Name</th>
            <th class="middle">Last Name</th>
            <th class="middle">City</th>
            <th class="middle">Telephone</th>
            <th class="middle" style="width:25%">Actions</th>
        </tr>
        <?php
        $sql = "SELECT student.id, student.fname, student.lname, city.city_name as city_name , student.telephone 
                FROM student 
                JOIN city ON student.city_code = city.city_code";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td class='middle'>" . $row["fname"] . "</td>
                        <td class='middle'>" . $row["lname"] . "</td>
                        <td class='middle'>" . $row["city_name"] . "</td>
                        <td class='middle'>" . $row["telephone"] . "</td>
                        <td class='middle'>
                            <form method='POST' action='delete_student.php' style='display:inline;'>
                                <input type='hidden' name='student_id' value='" . $row["id"] . "'>
                                <input type='submit' value='Delete' style='background-color:red;'>
                            </form>
                            <form method='GET' action='index.php' style='display:inline;'>
                                <input type='hidden' name='student_id' value='" . $row["id"] . "'>
                                <input type='submit' value='Update' style='background-color:blue;';>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No student data available</td></tr>";
        }
        $conn->close();
        ?>
    </table>
    </div>
    </div>
</body>
</html>