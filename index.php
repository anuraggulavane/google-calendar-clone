<?php

include "calendar.php";

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Google Calendar Clone</title>
  <meta name="description" content="Google Calendar Clone — Course Scheduling App">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <header>
    <h1>📅 Google Calendar Clone</h1>
  </header>

  <?php if (!empty($successMsg)): ?>
    <div class="alert success"><?= $successMsg ?></div>
  <?php endif; ?>
  <?php if (!empty($errorMsg)): ?>
    <div class="alert error"><?= $errorMsg ?></div>
  <?php endif; ?>

  <!-- ⏰ Clock -->
  <div class="clock-container">
    <span id="clock"></span>
  </div>

  <!-- 📅 Calendar -->
  <div class="calendar">
    <div class="nav-btn-container">
      <button onclick="changeMonth(-1)" class="nav-btn">◀</button>
      <h2 id="monthYear" style="margin: 0"></h2>
      <button onclick="changeMonth(1)" class="nav-btn">▶</button>
    </div>

    <div class="calendar-grid" id="calendar"></div>
  </div>

  <!-- 📌 Modal -->
  <div class="modal" id="eventModal">
    <div class="modal-content">

      <!-- Dropdown Selector -->
      <div id="eventSelectorWrapper" style="display: none;">
        <label for="eventSelector">Select Event</label>
        <select id="eventSelector" onchange="handleEventSelection(this.value)">
          <option disabled selected>Choose Event...</option>
        </select>
      </div>

      <!-- 📝 Form -->
      <form method="POST" id="eventForm">
        <input type="hidden" name="action" id="formAction" value="add">
        <input type="hidden" name="event_id" id="eventId">

        <label for="courseName">Course Title</label>
        <input type="text" name="course_name" id="courseName" required>

        <label for="instructorName">Instructor Name</label>
        <input type="text" name="instructor_name" id="instructorName" required>

        <label for="startDate">Start Date</label>
        <input type="date" name="start_date" id="startDate" required>

        <label for="endDate">End Date</label>
        <input type="date" name="end_date" id="endDate" required>

        <label for="startTime">Start Time</label>
        <input type="time" name="start_time" id="startTime" required>

        <label for="endTime">End Time</label>
        <input type="time" name="end_time" id="endTime" required>

        <button type="submit">Save Event</button>
      </form>

      <!-- 🗑️ Delete -->
      <form method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?')">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="event_id" id="deleteEventId">
        <button type="submit" class="submit-btn">Delete Event</button>
      </form>

      <!-- ❌ Cancel -->
      <button type="button" onclick="closeModal()">Cancel</button>
    </div>
  </div>

  <script>
    const events = <?= json_encode($eventsFromDB, JSON_UNESCAPED_UNICODE); ?>;
  </script>

  <script src="calendar.js"></script>

</body>

</html>
