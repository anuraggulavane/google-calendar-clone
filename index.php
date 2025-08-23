<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Calendar Project</title>

        <meta name="description" content="Google Calendar Clone">

        <link rel="stylesheet" href="style.css"/>
    </head>

    <body>
    
        <header>
            <h1>🗓️ Calendar</h1>
        </header>

        <!--Clock-->
            <div class="clock-container">
                <div id="clock"></div>
            </div>


        <!--Calendar Section-->
            <div class="calendar">
                <div class="nav-btn-container">
                    <button class="nav-btn">⏪️</button>
                    <h2 id="monthYear" style="margin: 0"></h2>
                    <button class="nav-btn">⏩️</button>
                </div>

                <div class="calendar-grid" id="calendar"></div>
            </div>


        <!--Modal for Add/Edit/Delete Appointment--> 
         <div class="modal" id="eventModal">
            <div class= "modal-content">

         <div id="eventSelectorWrapper">
            <label for="eventSelector">
                <strong>Select Event:</strong>
            </label>    
            <select id="eventSelector">
                <option disabled selected>Choose Event...</option>
            </select>
         </div>

        <!-- Main Form -->
         
         <form action="POST" id="eventForm">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="event_id" id="eventId">

            <label for="eventName">Title</label>
            <input type="text" name="event_name" id="eventName" required>

            <label for="location">Address</label>
            <input type="text" name="event_location" id="location" required>
            
            <label for="startDate">Start Date:</label>
            <input type="date" name="start_date" id="startDate" required>

            <label for="endDate">End Date:</label>
            <input type="date" name="end_date" id="endDate" required>

            <button type="submit">Save</button>
         </form>

         <!--Delete Form--> 
         <form method="POST" onsubmit="return confirm('Are you sure you want to delete this event?')">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="event_id" id="deleteEvent">
            <button type="submit" class="submit-btn">🗑️ Delete</button>
         </form>

         <!--❌ Cancel-->
         <button type="button" class="submit-btn">❌ Cancel</button>

    </div>
</div>

         <script src="calendar.js"><script>
    </body>
    
</html>