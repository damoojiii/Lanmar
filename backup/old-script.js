console.log('Script loaded');

const daysContainer = document.querySelector(".days"),
  nextBtn = document.querySelector(".next-btn"),
  prevBtn = document.querySelector(".prev-btn"),
  month = document.querySelector(".month"),
  todayBtn = document.querySelector(".today-btn");

const months = [
  "January", "February", "March", "April", "May", "June", 
  "July", "August", "September", "October", "November", "December"
];

// Get current date
const date = new Date();
let currentMonth = date.getMonth();
let currentYear = date.getFullYear();

let fp = '';

document.addEventListener("DOMContentLoaded", function () {
  fp = flatpickr("#date-picker", {
     enableTime: false,
     dateFormat: "Y-m-d",
     minDate: new Date().fp_incr(1), // Disable past days and today
     onChange: function (selectedDates, dateStr, instance) {
         document.querySelector("#date-picker").value = dateStr;
     }
 });
});


function renderCalendar() {
  // Log to confirm the calendar rendering
  console.log("Entering renderCalendar function");

  date.setDate(1); // Set the date to the first of the month
  const firstDay = new Date(currentYear, currentMonth, 1);
  const lastDay = new Date(currentYear, currentMonth + 1, 0);
  const lastDayIndex = lastDay.getDay();
  const lastDayDate = lastDay.getDate();
  const prevLastDay = new Date(currentYear, currentMonth, 0);
  const prevLastDayDate = prevLastDay.getDate();
  const nextDays = 7 - lastDayIndex - 1;

  month.innerHTML = `${months[currentMonth]} ${currentYear}`;

  let days = "";

  // Render previous month days
  for (let x = firstDay.getDay(); x > 0; x--) {
    days += `<div class="day prev disabled">${prevLastDayDate - x + 1}</div>`;
  }

  // Render current month days
  for (let i = 1; i <= lastDayDate; i++) {
    const today = new Date();
    const currentDate = new Date(currentYear, currentMonth, i);

    if (currentDate <= today.setHours(0, 0, 0, 0)) {
      days += `<div class="day disabled">${i}</div>`;
    } else {
      days += `<div class="day clickable" data-day="${i}">${i}</div>`;
    }
  }

  // Render next month days
  for (let j = 1; j <= nextDays; j++) {
    days += `<div class="day next disabled">${j}</div>`;
  }

  // Populate the days container
  daysContainer.innerHTML = days;

  // Attach click event listeners to all clickable days
  const clickableDays = document.querySelectorAll(".day.clickable");
  clickableDays.forEach(day => {
    day.addEventListener("click", (e) => {
      const selectedDay = e.target.getAttribute("data-day");
      const selectedDate = new Date(currentYear, currentMonth, selectedDay);

      // Remove the 'selected' class from all clickable days
      clickableDays.forEach(day => day.classList.remove("selected"));

      // Add the 'selected' class to the clicked day
      e.target.classList.add("selected");

      // Update the Flatpickr input with the selected date
      fp.setDate(selectedDate, true); // This will update the Flatpickr date picker and trigger the change event

      // Update the check-in and check-out dropdowns based on the selected date
      updateCheckInCheckOutOptions(selectedDate);
    });
  });
}


// Next month button
nextBtn.addEventListener("click", () => {
  currentMonth++;
  if (currentMonth > 11) {
    currentMonth = 0;
    currentYear++;
  }
  renderCalendar();
});

// Previous month button
prevBtn.addEventListener("click", () => {
  currentMonth--;
  if (currentMonth < 0) {
    currentMonth = 11;
    currentYear--;
  }
  renderCalendar();
});

// Go to today
todayBtn.addEventListener("click", () => {
  currentMonth = date.getMonth();
  currentYear = date.getFullYear();
  renderCalendar();
});

const bookedTimeSlots = {};

// Fetch booked time slots from the server
fetch('fetch-booking.php')
    .then(response => response.json())
    .then(bookings => {
        console.log(bookings);
        bookings.forEach(booking => {
            const date = booking.date;
            const checkin = booking.checkin;
            const totalHours = booking.hours;

            // Calculate the check-out time by adding the total hours to the check-in time
            const startTime = new Date(`${date} ${checkin}`);
            const endTime = new Date(startTime.getTime() + (totalHours * 60 * 60 * 1000)); // Add booking hours
            const cleanupEndTime = new Date(endTime.getTime() + (2 * 60 * 60 * 1000)); // Add 2 hours for cleanup

            if (!bookedTimeSlots[date]) {
                bookedTimeSlots[date] = [];
            }

            // Store the time slot with the check-out time including cleanup
            bookedTimeSlots[date].push({ 
                date: date,
                start: convertTo24Hour(checkin), 
                end: convertTo24Hour(formatTime(cleanupEndTime)), 
                hours: totalHours 
            });

            console.log('Check-in (24-hour):', convertTo24Hour(checkin));
            console.log('Check-out + Cleanup (24-hour):', convertTo24Hour(formatTime(cleanupEndTime)));
        });

        // Call renderCalendar after populating bookedTimeSlots
        renderCalendar();
    })
    .catch(error => console.error('Error fetching bookings:', error));
    

function formatTime(dateTime) {
  let hours = dateTime.getHours();
  const minutes = dateTime.getMinutes().toString().padStart(2, '0');
  const ampm = hours >= 12 ? 'PM' : 'AM';

  // Convert hours to 12-hour format
  hours = hours % 12 || 12; // Convert 0 (midnight) to 12
  const formattedHours = hours.toString().padStart(2, '0');

  return `${formattedHours}:${minutes} ${ampm}`;
}

function convertTo24Hour(timeString) {
  const [time, period] = timeString.split(' ');
  let [hours, minutes] = time.split(':');

  hours = parseInt(hours, 10);

  if (period === 'PM') {
    if (hours !== 12) {
      hours += 12;
    }
  } else if (period === 'AM') {
    if (hours === 12) {
      hours = 0; // Convert 12 AM to 00
    }
  }

  return `${hours.toString().padStart(2, '0')}:${minutes}`;
}


const checkInTimeSelect = document.querySelector('select[name="checkin"]');
const checkOutTimeSelect = document.querySelector('select[name="checkout"]');

checkInTimeSelect.addEventListener('change', updateCheckOutOptions);
checkOutTimeSelect.addEventListener('change', calculateTotalHours);

function updateCheckOutOptions() {
  const checkInTime = checkInTimeSelect.value;

  if (checkInTime === '') {
    checkOutTimeSelect.innerHTML = '';
    checkOutTimeSelect.add(new Option('Select check-in time first', '', true, true));
    return;
  }

  const [checkInHoursStr, checkInMinutesStr] = checkInTime.split(':');
  let checkInHours = parseInt(checkInHoursStr, 10);
  const checkInMinutes = parseInt(checkInMinutesStr.slice(0, 2), 10);
  const checkInPeriod = checkInTime.includes('PM') ? 'PM' : 'AM';

  // Convert check-in time to 24-hour format
  if (checkInPeriod === 'PM' && checkInHours !== 12) {
    checkInHours += 12;
  }
  if (checkInPeriod === 'AM' && checkInHours === 12) {
    checkInHours = 0;
  }

  const checkInDateTime = new Date();
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0);

  // Calculate the 12-hour and 22-hour options based on the check-in time
  const option12Hours = new Date(checkInDateTime.getTime());
  option12Hours.setHours(option12Hours.getHours() + 12);

  const option22Hours = new Date(checkInDateTime.getTime());
  option22Hours.setHours(option22Hours.getHours() + 22);

  checkOutTimeSelect.innerHTML = '';

  // Add the correct checkout options formatted properly
  checkOutTimeSelect.add(new Option(`${formatTime(option12Hours)}`, convertTo24Hour(formatTime(option12Hours))));
  checkOutTimeSelect.add(new Option(`${formatTime(option22Hours)}`, convertTo24Hour(formatTime(option22Hours))));
}


let checkOutHours, checkOutPeriod;

function calculateTotalHours() {
  const checkInTime = checkInTimeSelect.value;
  const checkOutTime = checkOutTimeSelect.value;

  if (checkInTime === '' || checkOutTime === '') {
    const totalHoursInput = document.querySelector('input[name="numhours"]');
    totalHoursInput.value = '';
    return;
  }

  const [checkInHoursStr, checkInMinutesStr] = checkInTime.split(':');
  const [checkOutHoursStr, checkOutMinutesStr] = checkOutTime.split(':');

  let checkInHours = parseInt(checkInHoursStr, 10);
  let checkInMinutes = parseInt(checkInMinutesStr.slice(0, 2), 10);
  const checkInPeriod = checkInTime.includes('PM') ? 'PM' : 'AM';

  let checkOutHours = parseInt(checkOutHoursStr, 10);
  let checkOutMinutes = parseInt(checkOutMinutesStr.slice(0, 2), 10);
  const checkOutPeriod = checkOutTime.includes('PM') ? 'PM' : 'AM';

  // Convert check-in time to 24-hour format
  if (checkInPeriod === 'PM' && checkInHours !== 12) {
    checkInHours += 12;
  }
  if (checkInPeriod === 'AM' && checkInHours === 12) {
    checkInHours = 0;
  }

  // Convert check-out time to 24-hour format
  if (checkOutPeriod === 'PM' && checkOutHours !== 12) {
    checkOutHours += 12;
  }
  if (checkOutPeriod === 'AM' && checkOutHours === 12) {
    checkOutHours = 0;
  }

  // Create date objects for check-in and check-out
  const checkInDateTime = new Date();
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0);

  const checkOutDateTime = new Date();
  checkOutDateTime.setHours(checkOutHours, checkOutMinutes, 0, 0);

  // If check-out is before check-in, assume it is the next day
  if (checkOutDateTime <= checkInDateTime) {
    checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
  }

  // Calculate total hours between check-in and check-out
  const totalHours = (checkOutDateTime.getTime() - checkInDateTime.getTime()) / (1000 * 60 * 60);

  const totalHoursInput = document.querySelector('input[name="numhours"]');
  totalHoursInput.value = totalHours.toFixed(0);
}


function populateCheckInTimes(bookedSlotsForDate) {
  const minimumReservationDuration = 12; // Minimum hours for a reservation
  const earliestTime = 6; // 6:00 AM
  const latestTime = 23.5; // 11:30 PM

  // Loop through possible check-in times from 7:00 AM to 11:30 PM
  for (let time = earliestTime; time <= latestTime; time += 0.5) {
    const optionDate = new Date();
    optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);
    const optionText = formatTime(optionDate);
    const optionValue = convertTo24Hour(optionText);

    let isAvailable = true;

    // Check for booking conflicts
    bookedSlotsForDate.forEach(slot => {
      if (!slot.date) {
        console.error('Slot date is undefined:', slot);
        return; // Skip if date is undefined
      }

      const slotStart = convertTo24Hour(slot.start);
      const slotEnd = convertTo24Hour(slot.end);
      const [year, month, day] = slot.date.split('-');
      const [endHours, endMinutes] = slotEnd.split(':');

      // Create Date object for slot end time
      let slotCleanupEnd = new Date(year, month - 1, day, endHours, endMinutes);

      // If the slot duration is 22 hours, set the cleanup end time to the next day
      if (slot.hours === '22') {
        slotCleanupEnd.setDate(slotCleanupEnd.getDate() + 1);
      }

      // Format cleanup end time to 24-hour format
      const cleanupEndTime = convertTo24Hour(formatTime(slotCleanupEnd));

      // Check if the new booking overlaps with an existing one or cleanup time
      if (
        (optionValue >= slotStart && optionValue < cleanupEndTime) || // Overlap with booking/cleanup
        (slot.hours === '22' && optionValue >= slotEnd) // Check for 22-hour slots
      ) {
        console.log(optionValue);
        console.log(slotStart);
        isAvailable = false;
      }
    });

    // Add the time option if it's available
    if (isAvailable) {
      checkInTimeSelect.add(new Option(optionText, optionValue));
    }
  }
}

function updateCheckInCheckOutOptions(selectedDate) {
  const year = selectedDate.getFullYear();
  const month = (selectedDate.getMonth() + 1).toString().padStart(2, '0');
  const day = selectedDate.getDate().toString().padStart(2, '0');

  const formattedDate = `${year}-${month}-${day}`;
  console.log(`Selected date: ${selectedDate}`);
  console.log(`Formatted date: ${formattedDate}`);

  const bookedSlotsForDate = bookedTimeSlots[formattedDate] || [];
  console.log(bookedSlotsForDate);

  // Check if the next day has a booking
  const nextDay = new Date(selectedDate);
  nextDay.setDate(nextDay.getDate() + 1);
  const nextDayFormatted = `${nextDay.getFullYear()}-${(nextDay.getMonth() + 1).toString().padStart(2, '0')}-${nextDay.getDate().toString().padStart(2, '0')}`;
  const nextDayBooking = bookedTimeSlots[nextDayFormatted] ? bookedTimeSlots[nextDayFormatted][0] : null;

  console.log("Next day formatted:", nextDayFormatted);
  console.log("Next day Booking:", nextDayBooking);


  checkInTimeSelect.innerHTML = '';
  checkOutTimeSelect.innerHTML = '';

  populateCheckInTimes(bookedSlotsForDate, nextDayBooking);
  checkOutTimeSelect.add(new Option('Select check-in time first', '', true, true));
}

// Call renderCalendar to initialize the calendar
renderCalendar();

