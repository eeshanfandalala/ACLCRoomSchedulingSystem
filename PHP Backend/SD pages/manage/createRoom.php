<div class="main">
    <div class="create-new-room">
        <div class="text">
            <span>Create New Room</span>
        </div>
        <form action="" method="post" class="side-by-side">
            <div>
                <label>Room Name</label><br>
                <input type="text" name="RoomName" required><br>
            </div>

            <div>
                <label>Room Type</label><br>
                <select name="RoomType">
                    <option value="Lecture">Lecture</option>
                    <option value="Laboratory">Laboratory</option>
                </select><br>
            </div>

            <div>
                <label>Room Floor</label><br>
                <select name="RoomFloor" id="">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
                <!-- <input type="text" name="RoomFloor" id=""> -->
            </div>

            <div>
                <label>Room Building</label><br>
                <select name="RoomBuilding" id="">
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select>
                <!-- <input type="text" name="RoomBuilding" id=""> -->
                <input type="submit" name="sub" value="Add" class="submit-room">
            </div>
        </form>
    </div>

    <div class="list">
        <div class="text">
            <span>Room List</span>
        </div>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for rooms..">
        <table id="roomTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Room</th>
                    <th>Building</th>
                    <th>Floor</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // include('../../config.php');
                $getRooms = $con->query("SELECT * FROM room_tb");
                $i = 1;
                while ($row = $getRooms->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row['room_name']; ?></td>
                        <td><?php echo $row['room_building']; ?></td>
                        <td><?php echo $row['room_floor']; ?></td>
                        <td><?php echo $row['room_type']; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <script src="searchtable.js"></script>
    </div>
</div>


<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $RoomName = $_POST['RoomName'];
    $RoomType = $_POST['RoomType'];
    $RoomFloor = $_POST['RoomFloor'];
    $RoomBuilding = $_POST['RoomBuilding'];

    $validateRoom = $con->prepare("SELECT * FROM room_tb WHERE room_name = ?");
    $validateRoom->bind_param("s", $RoomName);
    $validateRoom->execute();
    $result = $validateRoom->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Room name already exists')</script>";
    } else {
        // Prepare the SQL statement to insert into room_tb
        $insertRoomSql = $con->prepare("INSERT INTO room_tb(room_name, room_type, room_floor, room_building) VALUES (?, ?, ?, ?)");

        // Bind parameters
        $insertRoomSql->bind_param("ssss", $RoomName, $RoomType, $RoomFloor, $RoomBuilding);

        // Execute the statement to insert into room_tb
        if ($insertRoomSql->execute()) {
            echo "Room record inserted successfully<br>";
        } else {
            echo "Error inserting room record: " . $insertRoomSql->error;
        }

        // Close the statement
        $insertRoomSql->close();
    }
}

?>