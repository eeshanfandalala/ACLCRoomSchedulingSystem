<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>


<div style="display: flex;">
    <div>
        <h2>Room List</h2>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for rooms..">
        <br><br>
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

    <fieldset>
        <legend>Create Room</legend>
        <form action="" method="post">
            <label for="">Room name</label>
            <input type="text" name="RoomName" id="">

            <label for="">Room type</label>
            <select name="RoomType" id="">
                <option value="Lecture">Lecture</option>
                <option value="Laboratory">Laboratory</option>
            </select>

            <label for="">Room floor</label>
            <select name="RoomFloor" id="">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>
            <!-- <input type="text" name="RoomFloor" id=""> -->

            <label for="">Room building</label>
            <select name="RoomBuilding" id="">
                <option value="A">A</option>
                <option value="B">B</option>
            </select>
            <!-- <input type="text" name="RoomBuilding" id=""> -->

            <input type="submit" name="sub">
        </form>
    </fieldset>
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