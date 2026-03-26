<?php include "calendar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Google Calendar Clone</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Header -->
  <header>
    <div class="header-left">
      <span class="logo">📅</span>
      <h1>Calendar</h1>
    </div>
    <div class="header-center">
      <button onclick="changeMonth(-1)" class="nav-btn" title="Previous month">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
      </button>
      <h2 id="monthYear"></h2>
      <button onclick="changeMonth(1)" class="nav-btn" title="Next month">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </button>
      <button onclick="goToToday()" class="today-btn">Today</button>
    </div>
    <div class="header-right">
      <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()" title="Toggle dark/light mode">
        <span class="icon" id="themeIcon">🌙</span>
        <span id="themeLabel">Dark</span>
      </button>
      <span class="clock" id="clock"></span>
    </div>
  </header>

  <!-- Alerts -->
  <?php if (!empty($successMsg)): ?>
    <div class="alert success"><?= $successMsg ?></div>
  <?php endif; ?>
  <?php if (!empty($errorMsg)): ?>
    <div class="alert error"><?= $errorMsg ?></div>
  <?php endif; ?>

  <!-- Calendar -->
  <div class="calendar-wrapper">
    <div class="calendar-grid" id="calendar"></div>
  </div>

  <!-- Modal -->
  <div class="modal-overlay" id="eventModal">
    <div class="modal">
      <div class="modal-header">
        <h3 id="modalTitle">New Event</h3>
        <button type="button" class="modal-close" onclick="closeModal()">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>

      <!-- Event Selector -->
      <div id="eventSelectorWrapper" style="display:none;">
        <label for="eventSelector">Select Event</label>
        <select id="eventSelector" onchange="handleEventSelection(this.value)">
          <option disabled selected>Choose event...</option>
        </select>
      </div>

      <!-- Form -->
      <form method="POST" id="eventForm">
        <input type="hidden" name="action" id="formAction" value="add">
        <input type="hidden" name="event_id" id="eventId">

        <div class="form-row">
          <div class="form-group">
            <label for="courseName">Course Title</label>
            <input type="text" name="course_name" id="courseName" placeholder="e.g. Web Development" required>
          </div>
          <div class="form-group">
            <label for="instructorName">Instructor</label>
            <input type="text" name="instructor_name" id="instructorName" placeholder="e.g. John Doe" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="startDate">Start Date</label>
            <input type="date" name="start_date" id="startDate" required>
          </div>
          <div class="form-group">
            <label for="endDate">End Date</label>
            <input type="date" name="end_date" id="endDate" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="startTime">Start Time</label>
            <input type="time" name="start_time" id="startTime" required>
          </div>
          <div class="form-group">
            <label for="endTime">End Time</label>
            <input type="time" name="end_time" id="endTime" required>
          </div>
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>

      <!-- Delete -->
      <form method="POST" id="deleteForm" onsubmit="return confirm('Delete this event?')" style="display:none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="event_id" id="deleteEventId">
        <div class="modal-actions">
          <button type="submit" class="btn btn-danger">Delete Event</button>
        </div>
      </form>
    </div>
  </div>

  <script>const events = <?= json_encode($eventsFromDB, JSON_UNESCAPED_UNICODE); ?>;</script>
  <script src="calendar.js"></script>

</body>
</html>
