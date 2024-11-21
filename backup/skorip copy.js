console.log('Script loaded');

let fp = '';
let fp1 = '';
const checkInTimeSelect = document.querySelector('select[name="checkin"]');
const checkOutTimeSelect = document.querySelector('select[name="checkout"]');

const bookedTimeSlots = {}; // Store booked timeslots

// Format time in 24-hour format
function formatTime24(date) {
  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');
  return `${hours}:${minutes}`;
}

function getNextDay(date) {
  const currentDate = new Date(date);
  currentDate.setDate(currentDate.getDate() + 1); // Move to the next day

  const year = currentDate.getFullYear();
  const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); 
  const day = currentDate.getDate().toString().padStart(2, '0');

  return `${year}-${month}-${day}`;
}


// Function to check if a time is within booked slots
function isTimeBlocked(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin; 
      const slotEnd = slotEndHour * 60 + slotEndMin;       
      const currentTime = time * 60;                       

      const earliestPossibleCheckIn = slotEnd;  

      // If the current time is within a blocked slot, or if it violates the minimum stay
      if (currentTime >= slotStart && currentTime <= slotEnd) {
        return true;  // Time is blocked
      }
      if (currentTime < earliestPossibleCheckIn) {
        return true;  // Time violates the minimum stay rule
      }

      return false;
    });
  }
  return false;
}

// Function to check if a checkout time is blocked due to future bookings on the checkout date
function isCheckoutTimeBlocked(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin; 
      const slotEnd = slotEndHour * 60 + slotEndMin;       
      const currentTime = time * 60;                       

      // Calculate cleanup time (2 hours before the next booking starts)
      const nextBookingStartWithCleanup = (slotStart + 30) - 120; // Subtract 2 hours (120 minutes) for cleanup

      // Block if the current time overlaps with the booking or if it's after the next booking start minus cleanup
      if (currentTime >= slotStart || currentTime >= nextBookingStartWithCleanup) {
        return true;  // Time is blocked due to future booking
      }

      return false;
    });
  }
  return false;
}

function hasPreviousDaySpillover(date) {
  const prevDate = new Date(date);
  prevDate.setDate(prevDate.getDate() - 1);
  const formattedPrevDate = prevDate.toISOString().split('T')[0];

  if (bookedTimeSlots[formattedPrevDate]) {
    return bookedTimeSlots[formattedPrevDate].some(slot => {
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      return slotEndHour < 6; // Spillover to the next day if the checkout is before 6 AM
    });
  }
  return false;
}

function isTimeAvailable(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin;  // Convert start time to minutes
      const slotEnd = slotEndHour * 60 + slotEndMin;        // Convert end time to minutes
      const currentTime = time * 60;                        // Convert current time to minutes

      // If the current time is within a blocked slot
      if (currentTime >= slotStart && currentTime <= slotEnd) {
        return false;  // Time is blocked
      }

      return true; // Time is available
    });
  }
  return true; // If no booking exists for this date, time is available
}

function isTimeAvailableForCheckIn(date, time) {
  const earliestTime = 6;  // 6:00 AM
  const latestTime = 23.5; // 11:30 PM

  // Only allow time slots between 6:00 AM and 11:30 PM for check-in
  if (time < earliestTime || time > latestTime) {
    return false;  // Time is out of the allowed range for check-in
  }

  return isTimeAvailable(date, time);
}

function isTimeAvailableForCheckOut(date, time) {
  // Allow spillover times from previous bookings (e.g., times before 6:00 AM)
  return isTimeAvailable(date, time) || hasPreviousDaySpillover(date);
}

function isDateFullyBookedForCheckIn(dateStr) {
  const earliestTime = 6; // 6:00 AM
  const latestTime = 23.5; // 11:30 PM

  for (let time = earliestTime; time <= latestTime; time += 0.5) {
    if (isTimeAvailableForCheckIn(dateStr, time)) {
      return false; // There's at least one available slot, so the date is not fully booked
    }
  }
  return true; // No available times, date is fully booked
}

function isDateFullyBookedForCheckOut(dateStr) {
  // Check for both regular and spillover times
  const earliestTime = 0;  // 00:00 AM
  const latestTime = 23.5; // 11:30 PM

  for (let time = earliestTime; time <= latestTime; time += 0.5) {
    if (isTimeAvailableForCheckOut(dateStr, time)) {
      return false; // There's at least one available slot, so the date is not fully booked
    }
  }
  return true; // No available times, date is fully booked
}

function updateDisabledDates() {
  const disabledDatesForCheckIn = [];
  const disabledDatesForCheckOut = [];

  for (const date in bookedTimeSlots) {
    if (isDateFullyBookedForCheckIn(date)) {
      disabledDatesForCheckIn.push(date); // Disable this date for check-in if fully booked
    }

    if (isDateFullyBookedForCheckOut(date)) {
      disabledDatesForCheckOut.push(date); // Disable this date for check-out if fully booked
    }
  }

  // Update flatpickr options for both #date-in and #date-out
  fp.set('disable', disabledDatesForCheckIn);
  fp1.set('disable', disabledDatesForCheckOut);
}

function formatDate(date) {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const day = date.getDate().toString().padStart(2, '0');
  return `${year}-${month}-${day}`;
}

// Fetch today's date
const today = new Date();
const formattedToday = formatDate(today);

// Fetch booked time slots from the server
fetch(`fetch-booking.php?startDate=${formattedToday}`)
    .then(response => response.json())
    .then(bookings => {
      bookings.forEach(booking => {
        const dateIn = booking.dateIn;
        const dateOut = booking.dateOut;
        const checkin = booking.checkin;
        const checkout = booking.checkout;
        
        // Calculate the cleanup end time by adding 2 hours to the checkout time
        const endTime = new Date(`${dateOut} ${checkout}`);
        const cleanupEndTime = new Date(endTime.getTime() + (2 * 60 * 60 * 1000) - (1 * 60 * 1000)); // Add 2 hours and subtract 1 minute
    
        // If dateIn and dateOut are the same (same-day booking)
        if (dateIn === dateOut) {
            if (!bookedTimeSlots[dateIn]) {
                bookedTimeSlots[dateIn] = [];
            }
    
            bookedTimeSlots[dateIn].push({
                date: dateIn,
                start: checkin,
                end: formatTime24(cleanupEndTime) // End after cleanup time
            });
        } 
        // If dateIn and dateOut are different (multi-day booking)
        else {
            const dateInObj = new Date(dateIn);
            const dateOutObj = new Date(dateOut);
            const intermediateDate = new Date(dateInObj);
    
            // Store booking for the check-in date
            if (!bookedTimeSlots[dateIn]) {
                bookedTimeSlots[dateIn] = [];
            }
            bookedTimeSlots[dateIn].push({
                date: dateIn,
                start: checkin,
                end: '23:30' // Block the whole day after checkin on dateIn
            });
    
            // Block intermediate days fully between dateIn and dateOut
            while (intermediateDate.setDate(intermediateDate.getDate() + 1) < dateOutObj.getTime()) {
                const formattedIntermediateDate = intermediateDate.toISOString().split('T')[0]; 
                
                if (!bookedTimeSlots[formattedIntermediateDate]) {
                    bookedTimeSlots[formattedIntermediateDate] = [];
                }
    
                bookedTimeSlots[formattedIntermediateDate].push({
                    date: formattedIntermediateDate,
                    start: '00:00',
                    end: '23:30' // Block the entire intermediate day
                });
            }
    
            // Store booking for the check-out date, including cleanup time
            if (!bookedTimeSlots[dateOut]) {
                bookedTimeSlots[dateOut] = [];
            }
            bookedTimeSlots[dateOut].push({
                date: dateOut,
                start: '00:00',
                end: formatTime24(cleanupEndTime)
            });
        }
    });
        
        console.log(bookedTimeSlots);
        // Initialize flatpickr after booking data is fetched
        initializeFlatpickr();
        updateDisabledDates();
    })
    .catch(error => console.error('Error fetching bookings:', error));

// Function to populate check-in time options
function populateCheckInTimes(checkInDate, checkOutDate) {
  const earliestTime = 6; // 6:00 AM
  const latestTime = 23.5; // 11:30 PM
  const minimumStay = 12; 
  checkInTimeSelect.innerHTML = ''; 

  let maxCheckInTime = checkInDate === checkOutDate ? latestTime - minimumStay : latestTime; 

  // Function to check if the next day's bookings affect today's check-in time
  function isNextDayBookingAffectingCheckIn() {
    const nextDay = getNextDay(checkInDate); 
    const nextDayBookings = bookedTimeSlots[nextDay];
    
    if (nextDayBookings && nextDayBookings.length > 0) {
      const earliestNextDayCheckIn = nextDayBookings[0].start; // Get the earliest booking time for the next day
      const [nextDayStartHour, nextDayStartMin] = earliestNextDayCheckIn.split(':').map(Number);
      const nextDayStartInHours = nextDayStartHour + (nextDayStartMin / 60);
  
      if (nextDayStartInHours > 11.5 || nextDayStartInHours < 6) {
        return maxCheckInTime;  // Return regular maxCheckInTime without adjustment
      }
  
      // Calculate remaining time today, considering cleanup time and minimum stay
      const remainingTimeToday = (latestTime + 0.5) - (minimumStay - nextDayStartInHours) - 2;
      
      return remainingTimeToday;
    } else {
      // If no next-day booking exists, return the regular maxCheckInTime
      return maxCheckInTime;
    }
  }
  
  if(checkInDate !== checkOutDate){
    maxCheckInTime = isNextDayBookingAffectingCheckIn();
  }
  
  console.log(maxCheckInTime);

  // Populate check-in times up to maxCheckInTime
  for (let time = earliestTime; time <= maxCheckInTime; time += 0.5) {
    if (!isTimeBlocked(checkInDate, time)) { // Only add options if time is not blocked and respects 12-hour stay
      const optionDate = new Date();
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);
      const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');
      checkInTimeSelect.add(new Option(optionText, optionValue));
    }
  }

  checkInTimeSelect.value = checkInTimeSelect.options[0]?.value || ''; // Set first available time or clear
}


// Function to populate check-out time options
function populateCheckOutTimes(checkInTime, checkInDate, checkOutDate) {
  const minimumStay = 12; 
  checkOutTimeSelect.innerHTML = ''; 

  const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);
  const checkInDateTime = new Date(checkInDate);
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0);

  // Minimum time the user can check out is 12 hours after check-in
  let checkOutMinTime = new Date(checkInDateTime.getTime() + minimumStay * 60 * 60 * 1000);

  const earliestTime = 6;
  const latestTime = 23.5;

  // Calculate the difference in days between check-in and check-out
  const checkInDateObj = new Date(checkInDate);
  const checkOutDateObj = new Date(checkOutDate);
  const dayDifference = (checkOutDateObj - checkInDateObj) / (1000 * 60 * 60 * 24);

  const checkOutTimes = [];

  if (checkInDate === checkOutDate) {
    // Same-day checkout, ensure the check-out time is at least 12 hours after check-in
    for (let time = checkOutMinTime.getHours() + (checkOutMinTime.getMinutes() / 60); time <= latestTime; time += 0.5) {
      if (!isCheckoutTimeBlocked(checkOutDate, time)) {
        checkOutTimes.push(time);
      }
    }
  } else if (dayDifference === 1) {
    // Check-out on the next day, allow only times that respect the minimum 12-hour stay
    for (let time = 0; time <= latestTime; time += 0.5) {
      const optionDate = new Date(checkOutDate);
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);

      // Only allow times on the second day that are at least 12 hours from check-in
      if (optionDate.getTime() >= checkOutMinTime.getTime() && !isCheckoutTimeBlocked(checkOutDate, time)) {
        checkOutTimes.push(time);
      }
    }
  } else {
    // Check-out after 2 or more days, allow all times on the check-out date
    for (let time = 0; time <= latestTime; time += 0.5) {
      if (!isCheckoutTimeBlocked(checkOutDate, time)) {
        checkOutTimes.push(time);
      }
    }
  }

  // Populate the available check-out times
  checkOutTimes.forEach(time => {
    const optionDate = new Date();
    optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);
    const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');
    checkOutTimeSelect.add(new Option(optionText, optionValue));
  });

  checkOutTimeSelect.value = checkOutTimeSelect.options[0]?.value || ''; // Set first available time or clear
}

function calculateTotalHours() {
  const checkInDate = document.querySelector("#date-in").value;
  const checkOutDate = document.querySelector("#date-out").value;
  const checkInTime = checkInTimeSelect.value;
  const checkOutTime = checkOutTimeSelect.value;

  if (checkInTime === '' || checkOutTime === '' || checkInDate === '' || checkOutDate === '') {
    document.querySelector('input[name="numhours"]').value = '';
    return;
  }

  // Extract hours and minutes from time values
  const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);
  const [checkOutHours, checkOutMinutes] = checkOutTime.split(':').map(Number);

  // Parse check-in and check-out dates
  const checkInDateTime = new Date(checkInDate);
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0); // Set hours and minutes for check-in
  
  console.log(checkInDateTime);

  const checkOutDateTime = new Date(checkOutDate);
  checkOutDateTime.setHours(checkOutHours, checkOutMinutes, 0, 0); // Set hours and minutes for check-out

  // If check-out is before check-in on the same day or if it's a multi-day booking
  if (checkOutDateTime <= checkInDateTime) {
    checkOutDateTime.setDate(checkOutDateTime.getDate() + 1); // Assume it's the next day if the time is earlier
  }

  // Calculate the total hours between check-in and check-out
  const totalHours = (checkOutDateTime - checkInDateTime) / (1000 * 60 * 60);

  // Set the total hours value in the input field
  document.querySelector('input[name="numhours"]').value = totalHours.toFixed(1);
}

// Initialize flatpickr for both check-in and check-out
function initializeFlatpickr() {
  fp = flatpickr("#date-in", {
    enableTime: false,
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1), 
    onChange: function (selectedDates, dateStr, instance) {
      document.querySelector("#date-in").value = dateStr;
      
      fp1.set('minDate', dateStr); 
      fp1.setDate(null); 
      
    }
  });

  fp1 = flatpickr("#date-out", {
    enableTime: false,
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1),  
    onChange: function (selectedDates, dateStr, instance) {
      document.querySelector("#date-out").value = dateStr;
      populateCheckInTimes(document.querySelector("#date-in").value, dateStr);

      const checkInTime = checkInTimeSelect.value;
      if (checkInTime) {
        populateCheckOutTimes(checkInTime, document.querySelector("#date-in").value, dateStr);
      }
    }
  });
  // Event listener for check-in time select change
  checkInTimeSelect.addEventListener('change', function () {
    const checkInDate = document.querySelector("#date-in").value;
    const checkOutDate = document.querySelector("#date-out").value;

    // Repopulate check-out times based on the selected check-in time
    if (checkInDate && checkOutDate) {
      populateCheckOutTimes(this.value, checkInDate, checkOutDate);
      calculateTotalHours();
    }
  });
  checkOutTimeSelect.addEventListener('change', function () {
    calculateTotalHours(); // Call calculateTotalHours when check-out time changes
  });
}

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


function renderCalendar() {
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
      //const selectedDay = e.target.getAttribute("data-day");
      //const selectedDate = new Date(currentYear, currentMonth, selectedDay);

      // Remove the 'selected' class from all clickable days
      clickableDays.forEach(day => day.classList.remove("selected"));

      // Add the 'selected' class to the clicked day
      e.target.classList.add("selected");

      // Update the Flatpickr input with the selected date
      //fp.setDate(selectedDate, true); // This will update the Flatpickr date picker and trigger the change event
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
/* Hide today button logic
function hideTodayBtn() {
  if (currentMonth === new Date().getMonth() && currentYear === new Date().getFullYear()) {
    todayBtn.style.display = "none";
  } else {
    todayBtn.style.display = "flex";
  }
}

hideTodayBtn();*/

// Utility function to format Date to 24-hour time
function formatTime(date) {
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}


// Call renderCalendar to initialize the calendar
renderCalendar();

