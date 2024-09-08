<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Form</title>
</head>
<body>
    <form action="save_student.php" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>

        <label for="city">City:</label>
        <select id="city" name="city" required>
            <?php
            // Fetch cities from the database
            $conn = new mysqli("localhost", "user", "password", "school");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT city_code, city_name FROM city";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["city_code"] . "'>" . $row["city_name"] . "</option>";
                }
            } else {
                echo "<option value=''>No cities available</option>";
            }
            $conn->close();
            ?>
        </select><br>

        <button type="submit">Submit</button>
    </form>

    <h2>Student Data</h2>
    <table border="1">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>City</th>
        </tr>
        <?php
        // Fetch student data from the database
        $conn = new mysqli("localhost", "user", "password", "school");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT student.fname, student.lname, city.city_name as city_name 
                FROM student 
                JOIN city ON student.city_code = city.city_code";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["fname"] . "</td>
                        <td>" . $row["lname"] . "</td>
                        <td>" . $row["city_name"] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No student data available</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>