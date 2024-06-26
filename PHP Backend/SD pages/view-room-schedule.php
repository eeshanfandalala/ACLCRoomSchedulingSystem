<!DOCTYPE html>
<html>

<body>
    <div class="main">
        <div class="filter-container">
            <form action="?selectedRoom" method="post">
                <div>
                    <label>School Year</label><br>
                    <select name="AY" id="yearSelect" onchange="this.form.submit()">
                        <?php
                        include 'config.php';
                        $currentYear = date("Y");
                        for ($i = -1; $i < 4; $i++) {
                            $year = $currentYear + $i;
                            $nextYear = $year + 1;
                            $optionValue = $year . "-" . $nextYear;
                        ?>
                            <option value="<?php echo $optionValue ?>" <?php if (isset($_GET['selectedRoom'])) {
                                                                            echo ($_POST['AY'] == "$optionValue") ? "selected" : "";
                                                                        } ?> required><?php echo $optionValue ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label>Semester</label><br>
                    <input type="radio" name="SetSem" id="firstSemester" value="1st" style="margin-top: 10px" onchange="this.form.submit()" <?php if (isset($_GET['selectedRoom'])) {
                                                                                                                                                echo ($_POST['SetSem'] == '1st') ? "checked" : "";
                                                                                                                                            } else {
                                                                                                                                                echo "checked"; // checked by default
                                                                                                                                            } ?> required>

                    <label>1st</label>
                    <input type="radio" name="SetSem" id="secondSemester" value="2nd" onchange="this.form.submit()" <?php if (isset($_GET['selectedRoom'])) {
                                                                                                                        echo ($_POST['SetSem'] == '2nd') ? "checked" : "";
                                                                                                                    } ?> required>
                    <label>2nd</label>
                </div>

                <div>
                    <label>Room</label><br>
                    <select name="room" onchange="this.form.submit()">
                        <option>--Choose a room--</option>
                        <?php
                        $getRoom = $con->prepare("SELECT * FROM room_tb");
                        $getRoom->execute();
                        $resultGetRoom = $getRoom->get_result();
                        while ($rowGetRoom = $resultGetRoom->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $rowGetRoom['room_id'] ?>" <?php if (isset($_GET['selectedRoom'])) {
                                                                                        echo ($_POST['room'] == $rowGetRoom['room_id']) ? "selected" : "";
                                                                                    } ?> required><?php echo $rowGetRoom['room_name'] ?></option>
                        <?php
                        }
                        ?>
                    </select>

                    <!--<input type="submit" value="Filter" class="filter-button">-->
                </div>
            </form>

            <div style="margin-top: 20px">
                <button id="editButton">Enable Edit</button>
                <button id="deleteButton">Enable Delete</button>
                <button onclick="print()" class="print">Print</button>
            </div>
        </div>

        <!-- for printing -->
        <div class="print-info">
            <img src='media/ACLC-logo.png'><br>
            <p class='header'>ACLC College of Ormoc City, Inc.</p>
            <p class='header'>Lilia Avenue, Brgy. Cogon, Ormoc City</p><br><br>
            <div class="print-details">
                <strong>Semester: <span id="printSem"><?php echo isset($_POST['SetSem']) ? $_POST['SetSem'] : ''; ?></span></strong>
                <strong>School Year: <span id="printAY"><?php echo isset($_POST['AY']) ? $_POST['AY'] : ''; ?></span></strong>
                <strong>Room: <span id="printRoom"><?php
                                                    if (isset($_POST['room'])) {
                                                        $roomID = $_POST['room'];
                                                        $getRoomName = $con->prepare("SELECT room_name FROM room_tb WHERE room_id = ?");
                                                        $getRoomName->bind_param("i", $roomID);
                                                        $getRoomName->execute();
                                                        $getRoomName->bind_result($roomName);
                                                        $getRoomName->fetch();
                                                        $getRoomName->close();
                                                        echo $roomName;
                                                    }
                                                    ?></span></strong>
            </div>
        </div>


        <div class="list">
            <?php
            if (isset($_GET['selectedRoom'])) {
                $AY = $_POST['AY'];
                $SetSem = $_POST['SetSem'];
                $room = $_POST['room'];

                $getRoomType = $con->prepare("SELECT room_type FROM room_tb WHERE room_id = ?");
                $getRoomType->bind_param("s", $room);
                $getRoomType->execute();
                $getRoomType->bind_result($roomType);
                $getRoomType->fetch();
                $getRoomType->close();


                $fetchSchedule = $con->prepare("SELECT * FROM schedule_tb WHERE room_id = ? AND schedule_SY = ? AND schedule_semester = ?");
                $fetchSchedule->bind_param("iss", $room, $AY, $SetSem);
                $fetchSchedule->execute();
                $resultfetchSchedule = $fetchSchedule->get_result();
                function findCellValues($nameFetchClassResult, $teacher_name, $subject_name)
                {
                    $cellValue = $nameFetchClassResult . "<br>" . $teacher_name . "<br>" . $subject_name . "<br>";
                    return $cellValue;
                }
            ?>
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $scheduleData = array();

                        while ($rowSchedule = $resultfetchSchedule->fetch_object()) {
                            $schedule_time = [
                                'start' => $rowSchedule->schedule_time_start,
                                'end' => $rowSchedule->schedule_time_end
                            ];
                            $schedule_day = $rowSchedule->schedule_day;


                            for ($i = strtotime($schedule_time['start']); $i < strtotime($schedule_time['end']); $i += 1800) {
                                $time_start = date('h:i A', $i);
                                $time_end = date('h:i A', $i + 1800);

                                $scheduleData[$schedule_day][$time_start] = $rowSchedule;
                            }
                        }

                        for ($i = strtotime('7:00 AM'); $i < strtotime('10:00 PM'); $i += 1800) {
                            $time_start = date('h:i A', $i);
                            $time_end = date('h:i A', $i + 1800);

                        ?>
                            <tr>
                                <td><?php echo $time_start . ' - ' . $time_end; ?></td>
                                <?php
                                foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day) {
                                    if (isset($scheduleData[$day][$time_start])) {
                                        $rowSchedule = $scheduleData[$day][$time_start];

                                        // Fetch the data for class
                                        $fetchclass = $con->prepare("SELECT class_courseStrand, class_year, class_section FROM class_tb WHERE class_id = ?");
                                        $fetchclass->bind_param("i", $rowSchedule->class_id);
                                        $fetchclass->execute();
                                        $fetchclass->store_result();
                                        $fetchclass->bind_result($class_courseStrand, $class_year, $class_section);
                                        $fetchclass->fetch();

                                        $nameFetchClassResult = $class_courseStrand . ' ' . $class_year . ' - ' . $class_section;

                                        // Fetch the data for teacher
                                        $fetchTeacher = $con->prepare("SELECT teacher_name FROM teacher_tb WHERE teacher_id = ?");
                                        $fetchTeacher->bind_param("i", $rowSchedule->teacher_id);
                                        $fetchTeacher->execute();
                                        $fetchTeacher->store_result();
                                        $fetchTeacher->bind_result($teacher_name);
                                        $fetchTeacher->fetch();

                                        // Fetch the data for subject
                                        $fetchSubjects = $con->prepare("SELECT subject_name, subject_description FROM subject_tb WHERE subject_id = ?");
                                        $fetchSubjects->bind_param("i", $rowSchedule->subject_id);
                                        $fetchSubjects->execute();
                                        $fetchSubjects->store_result();
                                        $fetchSubjects->bind_result($subject_name, $subject_description);
                                        $fetchSubjects->fetch();

                                        $schedID = $rowSchedule->schedule_id;

                                        echo "<td><a href='./PHP Backend/SD pages/action.php?schedID=" . urlencode($schedID) . "&roomType=" . urlencode($roomType) . "' class='disabled-link' data-schedid='$schedID' data-roomID='$roomType'>" . findCellValues($nameFetchClassResult, $teacher_name, $subject_name) . "</a></td>";
                                    } else {
                                        // If empty
                                        echo '<td></td>';
                                    }
                                }
                                ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php
            }
            ?>
        </div>
    </div>

    <script>
        document.getElementById('editButton').addEventListener('click', function() {
            var links = document.querySelectorAll('td a');
            var enable = this.textContent === 'Enable Edit';

            links.forEach(function(link) {
                if (enable) {
                    // Enable the link for editing
                    link.classList.remove('disabled-link');
                    link.href = './PHP Backend/SD pages/action.php?EditschedID=' + link.getAttribute('data-schedid') + '&roomType=' + link.getAttribute('data-roomID');
                } else {
                    // Disable the link
                    link.classList.add('disabled-link');
                    link.removeAttribute('href');
                }
            });

            this.textContent = enable ? 'Disable Edit' : 'Enable Edit';
        });

        document.getElementById('deleteButton').addEventListener('click', function() {
            var links = document.querySelectorAll('td a');
            var enable = this.textContent === 'Enable Delete';

            links.forEach(function(link) {
                if (enable) {
                    // Enable the link for deleting
                    link.classList.remove('disabled-link');
                    link.href = './PHP Backend/SD pages/action.php?DelschedID=' + link.getAttribute('data-schedid');
                } else {
                    // Disable the link
                    link.classList.add('disabled-link');
                    link.removeAttribute('href');
                }
            });

            this.textContent = enable ? 'Disable Delete' : 'Enable Delete';
        });
    </script>

</body>

</html>