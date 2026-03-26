const calendarEl = document.getElementById("calendar");
const monthYearEl = document.getElementById("monthYear");
const modalEl = document.getElementById("eventModal");
let currentDate = new Date();

function renderCalendar(date) {
  calendarEl.innerHTML = "";

  const year = date.getFullYear();
  const month = date.getMonth();
  const today = new Date();
  const totalDays = new Date(year, month + 1, 0).getDate();
  const firstDay = new Date(year, month, 1).getDay();

  monthYearEl.textContent = date.toLocaleDateString("en-US", {
    month: "long",
    year: "numeric",
  });

  // Day headers
  ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach((d) => {
    const el = document.createElement("div");
    el.className = "day-header";
    el.textContent = d;
    calendarEl.appendChild(el);
  });

  // Empty cells before first day
  for (let i = 0; i < firstDay; i++) {
    const empty = document.createElement("div");
    empty.className = "day empty";
    calendarEl.appendChild(empty);
  }

  // Day cells
  for (let day = 1; day <= totalDays; day++) {
    const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
    const cell = document.createElement("div");
    cell.className = "day";

    const isToday =
      day === today.getDate() &&
      month === today.getMonth() &&
      year === today.getFullYear();

    if (isToday) cell.classList.add("today");

    // Date number
    const dateNum = document.createElement("div");
    dateNum.className = "date-number";
    const dateSpan = document.createElement("span");
    dateSpan.textContent = day;
    if (isToday) dateSpan.className = "today-circle";
    dateNum.appendChild(dateSpan);
    cell.appendChild(dateNum);

    // Events
    const dayEvents = events.filter((e) => e.date === dateStr);
    const eventsContainer = document.createElement("div");
    eventsContainer.className = "events";

    dayEvents.forEach((event) => {
      const ev = document.createElement("div");
      ev.className = "event";
      ev.onclick = (e) => {
        e.stopPropagation();
        openModalForEdit([event]);
      };

      const dot = document.createElement("span");
      dot.className = "event-dot";

      const title = document.createElement("span");
      title.className = "event-title";
      const startFormatted = event.start_time.substring(0, 5);
      title.textContent = `${startFormatted} ${event.title.split(" - ")[0]}`;

      ev.appendChild(dot);
      ev.appendChild(title);
      eventsContainer.appendChild(ev);
    });

    cell.appendChild(eventsContainer);

    // Click to add
    cell.onclick = () => openModalForAdd(dateStr);

    calendarEl.appendChild(cell);
  }
}

// ─── Modal: Add ───
function openModalForAdd(dateStr) {
  document.getElementById("modalTitle").textContent = "New Event";
  document.getElementById("formAction").value = "add";
  document.getElementById("eventId").value = "";
  document.getElementById("deleteEventId").value = "";
  document.getElementById("courseName").value = "";
  document.getElementById("instructorName").value = "";
  document.getElementById("startDate").value = dateStr;
  document.getElementById("endDate").value = dateStr;
  document.getElementById("startTime").value = "09:00";
  document.getElementById("endTime").value = "10:00";
  document.getElementById("deleteForm").style.display = "none";

  const wrapper = document.getElementById("eventSelectorWrapper");
  if (wrapper) wrapper.style.display = "none";

  modalEl.classList.add("active");
}

// ─── Modal: Edit ───
function openModalForEdit(eventsOnDate) {
  document.getElementById("modalTitle").textContent = "Edit Event";
  document.getElementById("formAction").value = "edit";
  document.getElementById("deleteForm").style.display = "block";
  modalEl.classList.add("active");

  const selector = document.getElementById("eventSelector");
  const wrapper = document.getElementById("eventSelectorWrapper");

  selector.innerHTML = '<option disabled selected>Choose event...</option>';
  eventsOnDate.forEach((e) => {
    const opt = document.createElement("option");
    opt.value = JSON.stringify(e);
    opt.textContent = `${e.title} (${e.start} → ${e.end})`;
    selector.appendChild(opt);
  });

  wrapper.style.display = eventsOnDate.length > 1 ? "block" : "none";
  handleEventSelection(JSON.stringify(eventsOnDate[0]));
}

// ─── Autofill Form ───
function handleEventSelection(eventJSON) {
  const event = JSON.parse(eventJSON);
  document.getElementById("eventId").value = event.id;
  document.getElementById("deleteEventId").value = event.id;

  const [course, instructor] = event.title.split(" - ").map((s) => s.trim());
  document.getElementById("courseName").value = course || "";
  document.getElementById("instructorName").value = instructor || "";
  document.getElementById("startDate").value = event.start || "";
  document.getElementById("endDate").value = event.end || "";
  document.getElementById("startTime").value = event.start_time || "";
  document.getElementById("endTime").value = event.end_time || "";
}

// ─── Close Modal ───
function closeModal() {
  modalEl.classList.remove("active");
}

// Close on overlay click
modalEl.addEventListener("click", (e) => {
  if (e.target === modalEl) closeModal();
});

// Close on Escape
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") closeModal();
});

// ─── Navigation ───
function changeMonth(offset) {
  currentDate.setMonth(currentDate.getMonth() + offset);
  renderCalendar(currentDate);
}

function goToToday() {
  currentDate = new Date();
  renderCalendar(currentDate);
}

// ─── Clock ───
function updateClock() {
  const now = new Date();
  document.getElementById("clock").textContent = now.toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    hour12: true,
  });
}

// ─── Theme Toggle ───
function toggleTheme() {
  const html = document.documentElement;
  const current = html.getAttribute("data-theme") || "light";
  const next = current === "light" ? "dark" : "light";
  html.setAttribute("data-theme", next);
  localStorage.setItem("calendar-theme", next);
  updateThemeButton(next);
}

function updateThemeButton(theme) {
  const icon = document.getElementById("themeIcon");
  const label = document.getElementById("themeLabel");
  if (icon && label) {
    icon.textContent = theme === "dark" ? "☀️" : "🌙";
    label.textContent = theme === "dark" ? "Light" : "Dark";
  }
}

// Load saved theme
(function () {
  const saved = localStorage.getItem("calendar-theme") || "light";
  document.documentElement.setAttribute("data-theme", saved);
  updateThemeButton(saved);
})();

// ─── Init ───
renderCalendar(currentDate);
updateClock();
setInterval(updateClock, 1000);
