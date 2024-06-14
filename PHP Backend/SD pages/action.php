<?php
include '../../config.php';

if (isset($_GET['EditschedID'])) {

    $schedID = $_GET['EditschedID'];
    $roomType = $_GET['roomType'];

    $fetchSched = $con->prepare("SELECT schedule_time_start, schedule_time_end, schedule_day, teacher_id, class_id, subject_id, room_id FROM schedule_tb WHERE schedule_id = ?");
    $fetchSched->bind_param("i", $schedID);
    $fetchSched->execute();
    $fetchSched->store_result();
    $fetchSched->bind_result($schedule_time_start, $schedule_time_end, $schedule_day, $teacher_id, $class_id, $subject_id, $room_id);
    $fetchSched->fetch();

    // Fetch the data for Teacher
    $fetchTeacher = $con->prepare("SELECT teacher_name FROM teacher_tb WHERE teacher_id = ?");
    $fetchTeacher->bind_param("i", $teacher_id);
    $fetchTeacher->execute();
    $fetchTeacher->store_result();
    $fetchTeacher->bind_result($teacher_name);
    $fetchTeacher->fetch();

    // Fetch the data for Class
    $fetchclass = $con->prepare("SELECT class_courseStrand, class_year, class_section, class_department FROM class_tb WHERE class_id = ?");
    $fetchclass->bind_param("i", $class_id);
    $fetchclass->execute();
    $fetchclass->store_result();
    $fetchclass->bind_result($class_courseStrand, $class_year, $class_section, $class_department);
    $fetchclass->fetch();

    $nameFetchClassResult = $class_courseStrand . ' ' . $class_year . ' - ' . $class_section;

    // Fetch the data for Subject
    $fetchSubject = $con->prepare("SELECT subject_name, subject_description FROM subject_tb WHERE subject_id = ?");
    $fetchSubject->bind_param("i", $subject_id);
    $fetchSubject->execute();
    $fetchSubject->store_result();
    $fetchSubject->bind_result($subject_name, $subject_description);
    $fetchSubject->fetch();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Schedule</title>
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

            .form-container {
                background: linear-gradient(to bottom right, #3d4479, #172278);
                color: white;
                max-width: 500px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 8px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                text-align: left;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .form-container h2 {
                margin-bottom: 20px;
                text-align: center;
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                font-size: 12px;
            }

            .form-group input[type="time"],
            .form-group select {
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

            .form-group input[type="submit"] {
                background-color: #0679E2;
                color: white;
                margin: 0 auto;
                padding: 15px 20px;
                border: none;
                border-radius: 10px;
                display: block;
                cursor: pointer;
                transition: all .5s ease;
            }

            .form-group input[type="submit"]:hover {
                background-color: #0056b3;
            }
        </style>
    </head>

    <body>
        <div class="form-container">
            <h2>Edit Schedule</h2>
            <form action="" method="post">
                <input type="hidden" name="schedID" value="<?php echo $_GET['EditschedID']; ?>">
                <input type="hidden" name="selected-room" id="selected-room" class="<?php echo htmlspecialchars($roomType); ?>">

                <div class="form-group">
                    <label>Time</label><br>
                    <div>
                        <label for="startTime">From</label>
                        <input type="time" name="new-time-start" id="startTime" min="07:00" max="22:00" step="1800" value="<?php echo $schedule_time_start; ?>">
                        <label for="endTime">to</label>
                        <input type="time" name="new-time-end" id="endTime" min="07:00" max="22:00" step="1800" value="<?php echo $schedule_time_end; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="classID">Program</label><br>
                    <select name="classID" id="classID">
                        <?php
                        $fetchclasses = $con->query("SELECT * FROM class_tb");
                        while ($row = $fetchclasses->fetch_assoc()) {
                            $nameFetchClassResults = $row['class_courseStrand'] . ' ' . $row['class_year'] . ' - ' . $row['class_section'];
                        ?>
                            <option value="<?php echo $row['class_id'] ?>" <?php echo $nameFetchClassResult == $nameFetchClassResults ? 'selected' : ''; ?>>
                                <?php echo $nameFetchClassResults ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subjectID">Subject</label><br>
                    <select name="subjectID" id="subjectID">
                        <?php
                        $fetchSubjects = $con->query("SELECT * FROM subject_tb");
                        while ($row = $fetchSubjects->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['subject_id']; ?>" <?php echo $subject_name == $row['subject_name'] ? 'selected' : ''; ?>>
                                <?php echo $row['subject_name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="teacherID">Teacher</label><br>
                    <select name="teacherID" id="teacherID">
                        <?php
                        $fetchTeachers = $con->query("SELECT * FROM teacher_tb WHERE `status` = 1");
                        while ($row = $fetchTeachers->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['teacher_id'] ?>" <?php echo $teacher_name == $row['teacher_name'] ? 'selected' : ''; ?>>
                                <?php echo $row['teacher_name'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <input type="submit" name="update" value="Update">
                </div>
            </form>
        </div>
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
} else if (isset($_GET['DelschedID'])) {
    $schedID = $_GET['DelschedID'];
    $delSched = $con->prepare("DELETE FROM schedule_tb WHERE schedule_id = ?");
    $delSched->bind_param("i", $schedID);
    if ($delSched->execute()) {
        echo "<script>alert('Deleted Successfully'); window.location.href = '../../admin-view-class-schedule.php';</script>";
        exit;
    }
}

if (isset($_POST['update'])) {
    $schedID = $_POST['schedID'];
    $new_time_start = $_POST['new-time-start'];
    $new_time_end = $_POST['new-time-end'];
    $classID = $_POST['classID'];
    $teacherID = $_POST['teacherID'];
    $subjectID = $_POST['subjectID'];
    $updateSched = $con->prepare("UPDATE schedule_tb SET schedule_time_start =?, schedule_time_end=? , teacher_id=?, class_id=?, subject_id=? WHERE schedule_id = ?");
    $updateSched->bind_param("ssiiii", $new_time_start, $new_time_end, $teacherID, $classID, $subjectID, $schedID);
    $updateSched->execute();
    echo "<script>alert('Updated Successfully'); window.location.href = '../../admin-view-class-schedule.php';</script>";
}
