<style>
    .editable input {
        width: 100%;
        box-sizing: border-box;
    }
</style>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userid']) && isset($_POST['field']) && isset($_POST['value'])) {
    $userid = $_POST['userid'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $stmt = $con->prepare("UPDATE room_tb SET $field = ? WHERE room_id = ?");
    $stmt->bind_param('si', $value, $userid);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    exit;
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $stmt = $con->prepare("DELETE FROM room_tb WHERE room_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!');</script>";
    } else {
        echo "<script>alert('Something went wrong!');</script>";
    }
}
?>

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
                        <td class="editable" data-userid="<?php echo $row['room_id']; ?>" data-field="room_name"><?php echo $row['room_name']; ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['room_id']; ?>" data-field="room_building"><?php echo $row['room_building']; ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['room_id']; ?>" data-field="room_floor"><?php echo $row['room_floor']; ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['room_id']; ?>" data-field="room_type"><?php echo $row['room_type']; ?></td>
                        <td><a href="?del=<?php echo $row['room_id']; ?>" onclick="return confirm('Are you sure you want to delete this item?')"><button>Delete</button></a></td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <script src="searchtable.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const buildingOptions = ['A', 'B'];
                const floorOptions = ['1', '2', '3', '4', '5', '6'];
                const roomTypeOptions = ['Lecture', 'Laboratory'];

                document.querySelectorAll('.editable').forEach(cell => {
                    cell.addEventListener('dblclick', function() {
                        if (!this.querySelector('input')) {
                            let originalValue = this.textContent;
                            let input = document.createElement('input');
                            input.type = 'text';
                            input.value = originalValue;
                            this.textContent = '';
                            this.appendChild(input);
                            input.focus();

                            input.addEventListener('blur', function() {
                                let newValue = this.value;
                                let userId = cell.getAttribute('data-userid');
                                let field = cell.getAttribute('data-field');

                                // Make an AJAX request to update the database
                                let xhr = new XMLHttpRequest();
                                xhr.open('POST', '', true);
                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        if (xhr.responseText.trim() === 'success') {
                                            cell.textContent = newValue;
                                        } else {
                                            cell.textContent = originalValue;
                                            alert('Update failed');
                                        }
                                    }
                                };
                                xhr.send(`userid=${userId}&field=${field}&value=${newValue}`);
                            });
                        }
                    });
                });

                document.querySelectorAll('.editable-dropdown').forEach(cell => {
                    cell.addEventListener('dblclick', function() {
                        if (!this.querySelector('select')) {
                            let originalValue = this.textContent;
                            let userId = this.getAttribute('data-userid');
                            let field = this.getAttribute('data-field');
                            let select = document.createElement('select');

                            let options;
                            switch (field) {
                                case 'room_building':
                                    options = buildingOptions;
                                    break;
                                case 'room_floor':
                                    options = floorOptions;
                                    break;
                                case 'room_type':
                                    options = roomTypeOptions;
                                    break;
                            }

                            options.forEach(optionValue => {
                                let option = document.createElement('option');
                                option.value = optionValue;
                                option.text = optionValue;
                                option.selected = optionValue === originalValue;
                                select.appendChild(option);
                            });

                            this.textContent = '';
                            this.appendChild(select);
                            select.focus();

                            select.addEventListener('blur', function() {
                                cell.textContent = originalValue;
                            });

                            select.addEventListener('change', function() {
                                let newValue = this.value;

                                // Make an AJAX request to update the database
                                let xhr = new XMLHttpRequest();
                                xhr.open('POST', '', true);
                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                        if (xhr.responseText.trim() === 'success') {
                                            cell.textContent = newValue;
                                        } else {
                                            cell.textContent = originalValue;
                                            // alert('Update failed');
                                            cell.textContent = newValue;

                                        }
                                    }
                                };
                                xhr.send(`userid=${userId}&field=${field}&value=${newValue}`);
                            });
                        }
                    });
                });
            });
        </script>
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
        $insertRoomSql = $con->prepare("INSERT INTO room_tb(room_name, room_type, room_floor, room_building) VALUES (?, ?, ?, ?)");
        $insertRoomSql->bind_param("ssss", $RoomName, $RoomType, $RoomFloor, $RoomBuilding);

        if ($insertRoomSql->execute()) {
            echo "<script>alert('Room record inserted successfully');window.location.href = 'admin-manage-room-list.php';</script>'";
        } else {
            echo "Error inserting room record: " . $insertRoomSql->error;
        }

        $insertRoomSql->close();
    }
}

?>