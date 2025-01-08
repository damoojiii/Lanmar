console.log('Script loaded');

let fp = '';
let fp1 = '';

const bookedTimeSlots = {}; // Store booked timeslots

const earliestTime = 6; // 6:00 AM
const earliestTime24hour = 0; // 12:00 AM
const latestTime = 23.5; // 11:30 PM
const minimumStay = 12;
const cleanupTime = 2;

// Fetch todays date
const today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();
const formattedToday = formatDate(today);

const checkInTimeSelect = document.querySelector('select[name="checkin"]');
const checkOutTimeSelect = document.querySelector('select[name="checkout"]');

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
      //console.log(date);
      //console.log((earliestTime <= slotStart || currentTime >= slotStart) && currentTime <= slotEnd);

      // If the current time is within a blocked slot, or if it violates the minimum stay
      if ((earliestTime <= slotStart || currentTime >= slotStart) && currentTime <= slotEnd) {
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

// Function to check if a checkout time is blocked due to future bookings
function isCheckoutTimeBlocked(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin;  // Start time in minutes
      const slotEnd = slotEndHour * 60 + slotEndMin;        // End time in minutes
      const currentTime = time * 60;                        // Current time in minutes

      // Calculate cleanup time (2 hours before the next booking starts)
      const nextBookingStartWithCleanup = slotStart - (cleanupTime * 60); // Subtract cleanup period (120 minutes)

      // Block if the current time overlaps with the booking 
      if ((currentTime >= slotStart && currentTime <= slotEnd) || currentTime >= nextBookingStartWithCleanup) {
        return true;  // Time is blocked
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
      return slotEndHour < earliestTime; // Spillover to the next day if the checkout is before 6 AM
    });
  }
  return false;
}

function isTimeAvailable(date, time) {
  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].some(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);
      
      const slotStart = slotStartHour * 60 + slotStartMin;  
      const slotEnd = slotEndHour * 60 + slotEndMin;        
      const currentTime = time * 60;                        
      // If the current time is within a blocked slot
      if (currentTime >= slotStart && currentTime <= slotEnd) {
        return false;  // Time is blocked
      }
      if(currentTime < slotEnd){
        return false;
      }

      return true; // Time is available
    });
  }
  return true; // If no booking exists for this date, time is available
}
function isTimeAvailableCheckIn(date, time) {
  const currentTime = time * 60;  

  if (bookedTimeSlots[date]) {
    return bookedTimeSlots[date].every(slot => {
      const [slotStartHour, slotStartMin] = slot.start.split(':').map(Number);
      const [slotEndHour, slotEndMin] = slot.end.split(':').map(Number);

      const slotStart = slotStartHour * 60 + slotStartMin;  
      const slotEnd = slotEndHour * 60 + slotEndMin;        

      // Ensure times are correctly compared when the slot starts at or after midnight
      if (slotStart === 0 && currentTime < slotEnd) {
        return false;  
      }

      // Block time if it overlaps with a booked time slot
      if ((currentTime >= slotStart && currentTime <= slotEnd) || currentTime < slotEnd) {
        return false;  // Time is blocked
      }

      return true;  // Time is available
    });
  }
  
  return true; // If no booking exists for this date, time is available
}

//Convert time to minutes
function timeToMinutes(time) {
  const [hour, min] = time.split(':').map(Number);
  return hour * 60 + min;
}

function isTimeAvailableForCheckIn(date, time) {
  const minimumStayMinutes = minimumStay * 60;  // Minimum 12-hour stay in minutes

  // Only allow time slots between 6:00 AM and 11:30 PM for check-in
  if (time < earliestTime || time > latestTime) {
    return false;  // Time is out of the allowed range for check-in
  }

  // Check if the time is available
  console.log(date);
  console.log(isTimeAvailableCheckIn(date, time));
  if (!isTimeAvailableCheckIn(date, time)) {
    return false;
  }

  // Find the first booking slot after the selected check-in time
  if (bookedTimeSlots[date]) {
    const futureSlots = bookedTimeSlots[date].filter(slot => timeToMinutes(slot.start) > time * 60);
    
    if (futureSlots.length > 0) {
      // Get the start time of the first booking slot after the check-in time
      const firstFutureSlotStart = timeToMinutes(futureSlots[0].start);

      // Check if the gap between the selected check-in time and the next booking is less than 12 hours
      if (firstFutureSlotStart - (time * 60) < minimumStayMinutes) {
        return false; // Gap is too small, doesnt allow a minimum stay of 12 hours
      }
    }
  }

  return true;
}

function isTimeAvailableForCheckOut(date, time) {
  // Allow spillover times from previous bookings
  return isTimeAvailable(date, time) || hasPreviousDaySpillover(date);
}

function isDateFullyBookedForCheckIn(dateStr) {

  for (let time = earliestTime; time <= latestTime; time += 0.5) {
    if (isTimeAvailableForCheckIn(dateStr, time)) {
      return false; // Theres at least one available slot, so the date is not fully booked
    }
  }
  return true; // No available times, date is fully booked
}

function isDateFullyBookedForCheckOut(dateStr) {
  const cutoffTimeMinutes = 4 * 60;
  // If the date has bookings, check if the date is fully booked or not
  if (bookedTimeSlots[dateStr]) {
    const slots = bookedTimeSlots[dateStr];

    // Sort the slots by their start time to find the earliest booking
    const sortedSlots = slots.sort((a, b) => timeToMinutes(a.start) - timeToMinutes(b.start));

    const firstSlotStart = timeToMinutes(sortedSlots[0].start);
    // check-out can be allowed until then
    if (firstSlotStart < cutoffTimeMinutes) {
      return true;  // Date is not fully booked, check-out is allowed before the first booking
    }

    // If the first booking starts at or before the cutoff time, block the entire date
    for (let time = earliestTime24hour; time <= latestTime; time += 0.5) {
      if (isTimeAvailableForCheckOut(dateStr, time)) {
        return false;  // Theres at least one available slot for check-out
      }
    }
  }
  
  return true;  // No available times for check-out, or the day is fully booked
}

function findFirstFullyBookedDate(selectedCheckInDate) {
  const sortedDates = Object.keys(bookedTimeSlots).sort(); 
  for (let date of sortedDates) {
    if (date > selectedCheckInDate && isDateFullyBookedForCheckOut(date)) {
      return date; // Return the first fully booked date after the check-in date
    }
  }
  return null; // No fully booked date found
}

function updateDisabledDates(selectedCheckInDate) {
  const disabledDatesForCheckIn = [];
  const disabledDatesForCheckOut = [];

  for (const date in bookedTimeSlots) {
    if (isDateFullyBookedForCheckIn(date)) {
      disabledDatesForCheckIn.push(date); 
    }

    if (isDateFullyBookedForCheckOut(date)) {
      disabledDatesForCheckOut.push(date); 
    }
  }

  // Find the maximum check-out date
  const maxCheckOutDate = findFirstFullyBookedDate(selectedCheckInDate);

  // Update flatpickr options for both #date-in and #date-out
  fp.set('disable', disabledDatesForCheckIn);
  fp1.set('maxDate', null);

  // Set maxDate for #date-out based on the found date
  if (maxCheckOutDate) {
    fp1.set('maxDate', maxCheckOutDate); // Limit checkout to the first fully booked date
  }

  fp1.set('disable', disabledDatesForCheckOut);
}


function formatDate(date) {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const day = date.getDate().toString().padStart(2, '0');
  return `${year}-${month}-${day}`;
}

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
    
        // If dateIn and dateOut are the same
        if (dateIn === dateOut) {
            if (!bookedTimeSlots[dateIn]) {
                bookedTimeSlots[dateIn] = [];
            }
    
            bookedTimeSlots[dateIn].push({
                date: dateIn,
                start: checkin,
                end: formatTime24(cleanupEndTime) 
            });
        } 
        // If dateIn and dateOut are different
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
                end: '23:30' 
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
        // Disable Dates
        updateDisabledDates();
    })
    .catch(error => console.error('Error fetching bookings:', error));

// Function to populate check-in time options
function populateCheckInTimes(checkInDate, checkOutDate) {
 
  checkInTimeSelect.innerHTML = ''; 

  let maxCheckInTime = checkInDate === checkOutDate ? latestTime - minimumStay : latestTime; 

  // Function to check if the next days bookings affect todays check-in time
  function isNextDayBookingAffectingCheckIn() {
    const nextDay = getNextDay(checkInDate); 
    const nextDayBookings = bookedTimeSlots[nextDay];
    
    if (nextDayBookings && nextDayBookings.length > 0) {
      const earliestNextDayCheckIn = nextDayBookings[0].start; // Get the earliest booking time for the next day
      const [nextDayStartHour, nextDayStartMin] = earliestNextDayCheckIn.split(':').map(Number);
      const nextDayStartInHours = nextDayStartHour + (nextDayStartMin / 60);
  
      if (nextDayStartInHours > 11.5 || nextDayStartInHours < earliestTime) {
        return maxCheckInTime;  // Return regular maxCheckInTime without adjustment
      }
  
      // Calculate remaining time today, considering cleanup time and minimum stay
      const remainingTimeToday = (latestTime + 0.5) - (minimumStay - nextDayStartInHours) - cleanupTime;
      
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

  checkInTimeSelect.innerHTML = `<option value="" hidden selected>Select check-in time</option>`;

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

  checkOutTimeSelect.innerHTML = ''; 

  checkOutTimeSelect.innerHTML = `<option value="" hidden selected>Select check-out time</option>`;

  const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);
  const checkInDateTime = new Date(checkInDate);
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0);

  // Minimum time the user can check out is 12 hours after check-in
  let checkOutMinTime = new Date(checkInDateTime.getTime() + minimumStay * 60 * 60 * 1000);

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
    for (let time = earliestTime24hour; time <= latestTime; time += 0.5) {
      const optionDate = new Date(checkOutDate);
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);

      // Only allow times on the second day that are at least 12 hours from check-in
      if (optionDate.getTime() >= checkOutMinTime.getTime() && !isCheckoutTimeBlocked(checkOutDate, time)) {
        checkOutTimes.push(time);
      }
    }
  } else {
    // Check-out after 2 or more days, allow all times on the check-out date
    for (let time = earliestTime24hour; time <= latestTime; time += 0.5) {
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

  const checkInDateTime = new Date(checkInDate);
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0); // Set hours and minutes for check-in
  
  console.log(checkInDateTime);

  const checkOutDateTime = new Date(checkOutDate);
  checkOutDateTime.setHours(checkOutHours, checkOutMinutes, 0, 0); // Set hours and minutes for check-out

  // If check-out is before check-in on the same day or if its a multi-day booking
  if (checkOutDateTime <= checkInDateTime) {
    checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
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
    showMonths: 1,
    disableMobile: "true", 
    onChange: function (selectedDates, dateStr, instance) {
      document.querySelector("#date-in").value = dateStr;

      fp1.set('minDate', dateStr); // Set min date for checkout based on check-in date
      fp1.setDate(null); // Reset checkout date

      // Update the disabled dates and max checkout range based on check-in date
      updateDisabledDates(dateStr);
    }
  });

  fp1 = flatpickr("#date-out", {
    enableTime: false,
    dateFormat: "Y-m-d",
    disableMobile: "true",
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

function resetTimesAndHours() {
  // Reset total hours
  document.querySelector('input[name="numhours"]').value = '';
}

document.getElementById("date-in").addEventListener("change", function() {
  resetTimesAndHours(); // Reset when check-in date changes
});

document.getElementById("date-out").addEventListener("change", function() {
  resetTimesAndHours(); // Reset when check-out date changes
});


const daysContainer = document.querySelector(".days"),
  nextBtn = document.querySelector(".next-btn"),
  prevBtn = document.querySelector(".prev-btn"),
  month = document.querySelector(".month"),
  todayBtn = document.querySelector(".today-btn");

const months = [
  "January", "February", "March", "April", "May", "June", 
  "July", "August", "September", "October", "November", "December"
];

function renderCalendar() {
  console.log("Entering renderCalendar function");

  today.setDate(1); // Set the date to the first of the month
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
  currentMonth = today.getMonth();
  currentYear = today.getFullYear();
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

// Call renderCalendar to initialize the calendar
renderCalendar();

