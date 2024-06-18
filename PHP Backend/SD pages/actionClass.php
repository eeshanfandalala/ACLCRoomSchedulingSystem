<?php
include '../../config.php';

$nameFetchClassResult = '';
if (isset($_GET['EditschedID'])) {

    $schedID = $_GET['EditschedID'];
    // $roomType = $_GET['roomType'];

    // Fetch the Shedule data with the schedule id
    $fetchSched = $con->prepare("SELECT schedule_time_start, schedule_time_end, schedule_day, teacher_id, class_id, subject_id, room_id, schedule_semester, schedule_SY FROM schedule_tb WHERE schedule_id = ?");
    $fetchSched->bind_param("i", $schedID);
    $fetchSched->execute();
    $fetchSched->store_result();
    $fetchSched->bind_result($schedule_time_start, $schedule_time_end, $schedule_day, $teacher_id, $class_id, $subject_id, $room_id, $schedule_semester, $schedule_SY);
    $fetchSched->fetch();

    // Fetch the data for Teacher
    $fetchTeacher = $con->prepare("SELECT teacher_name FROM teacher_tb WHERE teacher_id = ?");
    $fetchTeacher->bind_param("i", $teacher_id);
    $fetchTeacher->execute();
    $fetchTeacher->store_result();
    $fetchTeacher->bind_result($teacher_name);
    $fetchTeacher->fetch();

    // Fetch the data for Class
    $fetchclass = $con->prepare("SELECT class_id, class_courseStrand, class_year, class_section, class_department FROM class_tb WHERE class_id = ?");
    $fetchclass->bind_param("i", $class_id);
    $fetchclass->execute();
    $fetchclass->store_result();
    $fetchclass->bind_result($classID, $class_courseStrand, $class_year, $class_section, $class_department);
    $fetchclass->fetch();

    $nameFetchClassResult = $class_courseStrand . ' ' . $class_year . ' - ' . $class_section;

    // Fetch the data for Subject
    $fetchSubject = $con->prepare("SELECT subject_name, subject_description FROM subject_tb WHERE subject_id = ?");
    $fetchSubject->bind_param("i", $subject_id);
    $fetchSubject->execute();
    $fetchSubject->store_result();
    $fetchSubject->bind_result($subject_name, $subject_description);
    $fetchSubject->fetch();

    // Fetch the data from Rooms
    $fetchRoom = $con->prepare("SELECT room_name FROM room_tb WHERE room_id = ?");
    $fetchRoom->bind_param("i", $room_id);
    $fetchRoom->execute();
    $fetchRoom->store_result();
    $fetchRoom->bind_result($room_name);
    $fetchRoom->fetch();

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Schedule</title>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

            body {
                background: radial-gradient(circle, #151f69, #010945);
                margin: 0;
                height: 100vh;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                overflow: hidden;
                font-family: 'Poppins', sans-serif;
                box-sizing: border-box;
            }

            main {
                background: linear-gradient(to bottom right, #3d4479, #172278);
                color: white;
                max-width: 500px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 10px;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .side-by-side {
                display: flex;
            }

            form {
                background-color: #5c629564;
                margin-bottom: 20px;
                padding: 10px;
                border-radius: 15px;
            }

            form div {
                display: inline-block;
            }

            h2,
            h4 {
                text-align: center;
                margin: 0;
            }

            label {
                font-size: 12px;
            }

            form div input[type="time"],
            form div select {
                background-color: #5c6295;
                color: white;
                width: auto;
                min-width: 150px;
                padding: 10px;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                transition: all .5s ease;
            }

            input[type="submit"] {
                background-color: #0679E2;
                color: white;
                margin-left: 10px;
                padding: 10px;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                transition: all .5s ease;
            }

            input[type="submit"]:hover {
                background-color: #0056b3;
            }

            .back {
                color: white;
                padding: 5px 10px 5px 5px;
                border-radius: 5px;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                transition: all 0.3s ease;
            }

            .back:hover {
                background-color: #5c6295b3;
            }

            .back i {
                margin-right: 5px;
                font-size: 20px;
            }
        </style>
    </head>

    <body>
        <main>
            <div class="form-container">
                <a href="../../admin-view-class-schedule.php" class="back"><i class='bx bx-chevron-left'></i>Back</a>
                <h2>Edit Schedule</h2>
                <h4>Section: <?php echo $nameFetchClassResult; ?></h4><br>

                <form action="" method="post" class="side-by-side">
                    <input type="hidden" name="schedID" value="<?php echo $_GET['EditschedID']; ?>">
                    <input type="hidden" name="schedule_day" id="" value="<?php echo $schedule_day; ?>">
                    <input type="hidden" name="schedule_SY" id="" value="<?php echo $schedule_SY; ?>">
                    <input type="hidden" name="schedule_semester" id="" value="<?php echo $schedule_semester; ?>">
                    <input type="hidden" name="classID" id="" value="<?php echo $classID; ?>">

                    <div>
                        <label>Room</label><br>

                        <select name="roomID" id="roomID" required>
                            <?php
                            $fetchRooms = $con->query("SELECT * FROM room_tb");
                            while ($row = $fetchRooms->fetch_assoc()) {
                                $roomID = $row['room_id'];
                                $roomName = htmlspecialchars($row['room_name']);

                                $selected = '';
                                if (isset($_POST['roomID'])) {
                                    $selected = $_POST['roomID'] == $roomID ? 'selected' : '';
                                } else {
                                    $selected = isset($room_name) && $room_name == $roomName ? 'selected' : '';
                                }
                            ?>
                                <option value="<?php echo $roomID; ?>" <?php echo $selected; ?>>
                                    <?php echo $roomName; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label></label><br>
                        <input type="submit" name="sub1" value="Next">
                    </div>
                </form>

                <?php
                if (isset($_POST['roomID']) || isset($_POST['sub2'])) {
                ?>
                    <form action="" method="post" class="side-by-side">
                        <input type="hidden" name="roomID" id="" value="<?php echo $_POST['roomID'] ?>">
                        <?php
                        $type = $_POST['roomID'];
                        $getRoomType = $con->prepare("SELECT room_type FROM room_tb WHERE room_id = ?");
                        $getRoomType->bind_param("i", $type);
                        $getRoomType->execute();
                        $getRoomType->store_result();
                        $getRoomType->bind_result($roomType);
                        $getRoomType->fetch();
                        ?>
                        <input type="hidden" name="selected-room" id="selected-room" class="<?php echo $roomType; ?>">

                        <div>
                            <label>Time</label><br>
                            <div>
                                <label>Start</label><br>
                                <input type="time" name="new-time-start" id="startTime" min="07:00" max="22:00" step="1800" value="<?php if (isset($_POST['new-time-start'])) {
                                                                                                                                        echo $_POST['new-time-start'];
                                                                                                                                    } else {
                                                                                                                                        echo $schedule_time_start;
                                                                                                                                    } ?>" required>
                            </div>

                            <div>
                                <label>End</label><br>
                                <input type="time" name="new-time-end" id="endTime" min="07:00" max="22:00" step="1800" value="<?php if (isset($_POST['new-time-end'])) {
                                                                                                                                    echo $_POST['new-time-end'];
                                                                                                                                } else {
                                                                                                                                    echo $schedule_time_end;
                                                                                                                                } ?>" required>
                            </div>
                        </div>

                        <div>
                            <label>Day</label>
                            <div class="side-by-side">
                                <div>
                                    <div class="day-container">
                                        <input type="radio" name="day" value='Monday' <?php if (isset($_POST['day'])) {
                                                                                            echo $_POST['day'] == 'Monday' ? 'checked' : '';
                                                                                        } else {
                                                                                            echo $schedule_day == 'Monday' ? 'checked' : '';
                                                                                        } ?> required>
                                        <label for="Monday">Monday</label>
                                    </div>

                                    <div class="day-container">
                                        <input type="radio" name="day" value='Tuesday' <?php if (isset($_POST['day'])) {
                                                                                            echo $_POST['day'] == 'Tuesday' ? 'checked' : '';
                                                                                        } else {
                                                                                            echo $schedule_day == 'Tuesday' ? 'checked' : '';
                                                                                        } ?> required>
                                        <label for="Tuesday">Tuesday</label>
                                    </div>

                                    <div class="day-container">
                                        <input type="radio" name="day" value='Wednesday' <?php if (isset($_POST['day'])) {
                                                                                                echo $_POST['day'] == 'Wednesday' ? 'checked' : '';
                                                                                            } else {
                                                                                                echo $schedule_day == 'Wednesday' ? 'checked' : '';
                                                                                            } ?> required>
                                        <label for="Wednesday">Wednesday</label>
                                    </div>
                                </div>

                                <div>
                                    <div class="day-container">
                                        <input type="radio" name="day" value='Thursday' <?php if (isset($_POST['day'])) {
                                                                                            echo $_POST['day'] == 'Thursday' ? 'checked' : '';
                                                                                        } else {
                                                                                            echo $schedule_day == 'Thursday' ? 'checked' : '';
                                                                                        } ?> required>
                                        <label for="Thursday">Thursday</label>
                                    </div>

                                    <div class="day-container">
                                        <input type="radio" name="day" value='Friday' <?php if (isset($_POST['day'])) {
                                                                                            echo $_POST['day'] == 'Friday' ? 'checked' : '';
                                                                                        } else {
                                                                                            echo $schedule_day == 'Friday' ? 'checked' : '';
                                                                                        } ?> required>
                                        <label for="Friday">Friday</label>
                                    </div>

                                    <div class="day-container">
                                        <input type="radio" name="day" value='Saturday' <?php if (isset($_POST['day'])) {
                                                                                            echo $_POST['day'] == 'Saturday' ? 'checked' : '';
                                                                                        } else {
                                                                                            echo $schedule_day == 'Saturday' ? 'checked' : '';
                                                                                        } ?> required>
                                        <label for="Saturday">Saturday</label>
                                    </div>
                                </div>
                            </div>

                            <input type="submit" name="sub2" value="Next" style="margin-top: 18px">
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['sub2']) || isset($_POST['update'])) {
                        $roomID = $_POST['roomID'];
                        $newTimeStart = strtotime($_POST['new-time-start']);
                        $newTimeEnd = strtotime($_POST['new-time-end']);
                        $day = $_POST['day'];

                        // Check if conflict with another class in the same room, time, day
                        $findConflictRoom = $con->prepare("SELECT c.class_courseStrand,c.class_year,c.class_section,c.class_department,r.room_name,s.schedule_time_start,s.schedule_time_end
                                                        FROM class_tb c
                                                        JOIN schedule_tb s ON c.class_id = s.class_id
                                                        JOIN room_tb r ON r.room_id = s.room_id
                                                        WHERE r.room_id = ? AND s.schedule_SY = ? AND s.schedule_semester = ? AND s.schedule_day = ? AND s.schedule_id != ?;");
                        $findConflictRoom->bind_param("isssi", $roomID, $schedule_SY, $schedule_semester, $day, $schedID);
                        $findConflictRoom->execute();
                        $resultFindConflictRoom = $findConflictRoom->get_result();

                        $roomName = '';
                        $occupiedByClassName = '';
                        $isConflictRoom = false;

                        while ($row = $resultFindConflictRoom->fetch_assoc()) {
                            $occupiedTimeStart = strtotime($row['schedule_time_start']);
                            $occupiedTimeEnd = strtotime($row['schedule_time_end']);

                            if (($newTimeStart < $occupiedTimeEnd) && ($newTimeEnd > $occupiedTimeStart)) {
                                $roomName = $row['room_name'];
                                $occupiedByClassName = $row['class_courseStrand'] . ' ' . $row['class_year'] . ' - ' . $row['class_section'];

                                $isConflictRoom = true;
                                break;
                            }
                        }

                        // Check if the class is already schedule in the submitted time, day
                        $findConflictClass = $con->prepare("SELECT c.class_courseStrand,c.class_year,c.class_section,c.class_department,s.schedule_time_start,s.schedule_time_end
                                                        FROM class_tb c
                                                        JOIN schedule_tb s ON c.class_id = s.class_id
                                                        WHERE c.class_id = ? AND s.schedule_SY = ? AND s.schedule_semester = ? AND s.schedule_day = ? AND s.schedule_id != ?;");
                        $findConflictClass->bind_param("isssi", $class_id, $schedule_SY, $schedule_semester, $day, $schedID);
                        $findConflictClass->execute();
                        $resultFindConflictClass = $findConflictClass->get_result();

                        $isConflictClass = false;

                        while ($row = $resultFindConflictClass->fetch_assoc()) {
                            $occupiedTimeStart = strtotime($row['schedule_time_start']);
                            $occupiedTimeEnd = strtotime($row['schedule_time_end']);

                            if (($newTimeStart < $occupiedTimeEnd) && ($newTimeEnd > $occupiedTimeStart)) {
                                $isConflictClass = true;
                                break;
                            }
                        }


                        if ($isConflictRoom) {
                            echo "<script>alert('Room $roomName in " . date('h:i A', $newTimeStart) . " - " . date('h:i A', $newTimeEnd) . " conflicts with $occupiedByClassName schedule.');</script>";
                        } else if ($isConflictClass) {
                            echo "<script>alert('Class $nameFetchClassResult is already scheduled on $day from " . date('h:i A', $newTimeStart) . " - " . date('h:i A', $newTimeEnd) . ".');</script>";
                        } else {
                    ?>
                            <form action="" method="post">
                                <input type="hidden" name="schedID" value="<?php echo $_GET['EditschedID']; ?>">
                                <input type="hidden" name="schedule_SY" id="" value="<?php echo $schedule_SY; ?>">
                                <input type="hidden" name="schedule_semester" id="" value="<?php echo $schedule_semester; ?>">
                                <input type="hidden" name="classID" id="" value="<?php echo $classID; ?>">
                                <input type="hidden" name="day" id="" value="<?php echo $_POST['day']; ?>">
                                <input type="hidden" name="roomID" id="" value="<?php echo $_POST['roomID'] ?>">
                                <input type="hidden" name="new-time-start" id="" value="<?php echo $_POST['new-time-start'] ?>">
                                <input type="hidden" name="new-time-end" id="" value="<?php echo $_POST['new-time-end'] ?>">

                                <div>
                                    <label for="subjectID">Subject</label><br>
                                    <select name="subjectID" id="subjectID" required>
                                        <?php
                                        // Fetch subjects from the database based on subject type
                                        $fetchSubjects = $con->query("SELECT * FROM subject_tb WHERE subject_type = '$roomType'");
                                        while ($row = $fetchSubjects->fetch_assoc()) {
                                            $subjectID = $row['subject_id'];
                                            $subjectName = htmlspecialchars($row['subject_name']);
                                            // Check if the subjectID is selected
                                            $selected = '';
                                            if (isset($_POST['subjectID'])) {
                                                $selected = $_POST['subjectID'] == $subjectID ? 'selected' : '';
                                            } else {
                                                $selected = isset($subject_name) && $subject_name == $subjectName ? 'selected' : '';
                                            }
                                        ?>
                                            <option value="<?php echo $subjectID; ?>" <?php echo $selected; ?>>
                                                <?php echo $subjectName; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="teacherID">Teacher</label><br>
                                    <select name="teacherID" id="teacherID" required>
                                        <?php
                                        // Fetch teachers from the database who are active (status = 1)
                                        $fetchTeachers = $con->query("SELECT * FROM teacher_tb WHERE `status` = 1");
                                        while ($row = $fetchTeachers->fetch_assoc()) {
                                            $teacherID = $row['teacher_id'];
                                            $teacherName = htmlspecialchars($row['teacher_name']); // Use htmlspecialchars to prevent XSS
                                            // Check if the teacherID is selected
                                            $selected = '';
                                            if (isset($_POST['teacherID'])) {
                                                $selected = $_POST['teacherID'] == $teacherID ? 'selected' : '';
                                            } else {
                                                $selected = isset($teacher_name) && $teacher_name == $teacherName ? 'selected' : '';
                                            }
                                        ?>
                                            <option value="<?php echo $teacherID; ?>" <?php echo $selected; ?>>
                                                <?php echo $teacherName; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div>
                                    <input type="submit" name="update" value="Update">
                                </div>
                            </form>
                    <?php
                        }
                    }
                    ?>

                <?php
                }
                ?>
            </div>
        </main>
    </body>

    </html>

    <script>
        function roundMinutesToNearest30(time) {
            var timeParts = time.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = parseInt(timeParts[1]);

            var roundedMinutes = Math.round(minutes / 30) * 30 % 60;
            var formattedHours = ('0' + hours).slice(-2);
            var formattedMinutes = ('0' + roundedMinutes).slice(-2);

            return formattedHours + ':' + formattedMinutes;
        }

        function roundToNearest30Minutes(time) {
            var timeParts = time.split(':');
            var hours = parseInt(timeParts[0]);
            var minutes = parseInt(timeParts[1]);
            var roundedMinutes = Math.round(minutes / 30) * 30 % 60;
            var roundedHours = hours + Math.floor(minutes / 30);
            roundedHours = roundedHours % 24;
            var formattedHours = ('0' + roundedHours).slice(-2);
            var formattedMinutes = ('0' + roundedMinutes).slice(-2);
            return formattedHours + ':' + formattedMinutes;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const startTimeInput = document.getElementById('startTime');
            const endTimeInput = document.getElementById('endTime');
            const selectedRoom = document.getElementById('selected-room');
            const roomType = selectedRoom ? selectedRoom.className : "";

            startTimeInput.addEventListener('change', function() {
                const roundedStartTime = roundMinutesToNearest30(this.value);
                this.value = roundedStartTime;
                const [startHour, startMinute] = roundedStartTime.split(':').map(Number);
                let endHour, endMinute;
                if (roomType === "Lecture") {
                    endHour = startHour + 1;
                    endMinute = startMinute;
                } else if (roomType === "Laboratory") {
                    endHour = startHour + Math.floor((startMinute + 90) / 60);
                    endMinute = (startMinute + 30) % 60;
                }

                endHour = endHour % 24;
                const endTimeValue = `${String(endHour).padStart(2, '0')}:${String(endMinute).padStart(2, '0')}`;
                endTimeInput.value = endTimeValue;
            });

            endTimeInput.addEventListener('change', function() {
                this.value = roundToNearest30Minutes(this.value);
            });
        });
    </script>

<?php
}
if (isset($_GET['DelschedID'])) {
    $schedID = $_GET['DelschedID'];
    $delSched = $con->prepare("DELETE FROM schedule_tb WHERE schedule_id = ?");
    $delSched->bind_param("i", $schedID);
    if ($delSched->execute()) {
        echo "<script>alert('Deleted Successfully'); window.location.href = '../../admin-view-class-schedule.php';</script>";
        exit;
    }
}

if (isset($_POST['update'])) {
    $schedule_SY = htmlspecialchars($_POST['schedule_SY']);
    $schedule_semester = htmlspecialchars($_POST['schedule_semester']);
    $new_time_start = htmlspecialchars($_POST['new-time-start']);
    $new_time_end = htmlspecialchars($_POST['new-time-end']);
    $submittedDay = htmlspecialchars($_POST['day']);
    $schedID = intval($_POST['schedID']);
    $roomID = intval($_POST['roomID']);
    $teacherID = intval($_POST['teacherID']);
    $subjectID = intval($_POST['subjectID']);
    $classID = intval($_POST['classID']);

    // Convert new start and end times to a format suitable for comparison
    $newTimeStart = strtotime($new_time_start);
    $newTimeEnd = strtotime($new_time_end);

    // $isConflictRoom = false;
    // $isConflictClass = false;


    // Check if the class is occupied within the submitted day and time
    // $findConflictClass = $con->prepare("SELECT c.class_courseStrand,c.class_year,c.class_section,c.class_department,s.schedule_time_start,s.schedule_time_end
    //                                     FROM class_tb c
    //                                     JOIN schedule_tb s ON c.class_id = s.class_id
    //                                     WHERE c.class_id = ? AND s.schedule_SY = ? AND s.schedule_semester = ? AND s.schedule_day = ?;");
    // $findConflictClass->bind_param("isss", $classID, $schedule_SY, $schedule_semester, $submittedDay);
    // $findConflictClass->execute();
    // $resultFindConflictClass = $findConflictClass->get_result();
    // $occupiedByClassName = '';

    // Check if Teacher is scheduled with the submitted time, day
    $findConflictTeacher = $con->prepare("SELECT s.schedule_time_start, s.schedule_time_end, t.teacher_name
                                            FROM schedule_tb s
                                            JOIN teacher_tb t ON s.teacher_id = t.teacher_id
                                            WHERE t.teacher_id = ? AND s.schedule_day = ? AND s.schedule_semester = ? AND s.schedule_SY = ? AND s.schedule_id != ?");
    $findConflictTeacher->bind_param("isssi", $teacherID, $submittedDay, $schedule_semester, $schedule_SY, $schedID);
    $findConflictTeacher->execute();
    $resultfindConflictTeacher = $findConflictTeacher->get_result();
    $teacherName = '';
    $isConflictClass = false;

    while ($row = $resultfindConflictTeacher->fetch_assoc()) {
        $occupiedTimeStart = strtotime($row['schedule_time_start']);
        $occupiedTimeEnd = strtotime($row['schedule_time_end']);

        if (($newTimeStart < $occupiedTimeEnd) && ($newTimeEnd > $occupiedTimeStart)) {
            $teacherName = $row['teacher_name'];
            $isConflictClass = true;
            break;
        }
    }

    if ($isConflictClass) {
        echo "<script>alert('Teacher $teacherName is already scheduled on $day from " . date('h:i A', $newTimeStart) . " - " . date('h:i A', $newTimeEnd) . ".');</script>";
    } else {
        $updateSched = $con->prepare("UPDATE schedule_tb SET schedule_day = ?, schedule_time_start = ?, schedule_time_end = ?, teacher_id = ?, room_id = ?, subject_id = ? WHERE schedule_id = ?");
        $updateSched->bind_param("sssiiii", $submittedDay, $new_time_start, $new_time_end, $teacherID, $roomID, $subjectID, $schedID);
        $updateSched->execute();

        echo "<script>alert('Updated Successfully'); window.location.href = '../../admin-view-class-schedule.php';</script>";
    }
}
