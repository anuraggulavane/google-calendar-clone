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
    </body>

    
</html>