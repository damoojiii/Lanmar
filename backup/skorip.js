console.log('Script loaded');

let fp = '';
let fp1 = '';
const checkInTimeSelect = document.querySelector('select[name="checkin"]');
const checkOutTimeSelect = document.querySelector('select[name="checkout"]');

const bookedTimeSlots = {};

function formatTime24(date) {
  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');
  return `${hours}:${minutes}`;
}

// Fetch booked time slots from the server
fetch('fetch-booking.php')
    .then(response => response.json())
    .then(bookings => {
      bookings.forEach(booking => {
        const dateIn = booking.dateIn;
        const dateOut = booking.dateOut;
        const checkin = booking.checkin;
        const checkout = booking.checkout;
    
        // Calculate the cleanup end time by adding 2 hours to the checkout time
        const endTime = new Date(`${dateOut} ${checkout}`);
        const cleanupEndTime = new Date(endTime.getTime() + (2 * 60 * 60 * 1000)); // Add 2 hours for cleanup
    
        // If dateIn and dateOut are the same (same-day booking)
        if (dateIn === dateOut) {
            // Store the booking time slot for the same day, including cleanup time
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
            // Store the booking time slot for dateIn
            if (!bookedTimeSlots[dateIn]) {
                bookedTimeSlots[dateIn] = [];
            }
            bookedTimeSlots[dateIn].push({
                date: dateIn,
                start: checkin,
                end: '23:30' // Block the whole day after checkin on dateIn
            });
    
            // Store the booking time slot for dateOut, including cleanup time
            if (!bookedTimeSlots[dateOut]) {
                bookedTimeSlots[dateOut] = [];
            }
            bookedTimeSlots[dateOut].push({
                date: dateOut,
                start: '00:00', // Start from the beginning of dateOut
                end: formatTime24(cleanupEndTime)
            });
        }
      });
      console.log(bookedTimeSlots);
        // Call renderCalendar or other functions here after populating bookedTimeSlots
    })
    .catch(error => console.error('Error fetching bookings:', error));

// Function to populate check-in time options
function populateCheckInTimes(checkInDate, checkOutDate) {
  const earliestTime = 6; // 6:00 AM
  const latestTime = 23.5; // 11:30 PM
  const minimumStay = 12;
  checkInTimeSelect.innerHTML = '';

  // Same-day check-in and check-out: Limit times based on the 12-hour minimum stay
  if (checkInDate === checkOutDate) {
    const maxCheckInTime = latestTime - minimumStay; // Latest possible check-in time

    for (let time = earliestTime; time <= maxCheckInTime; time += 0.5) {
      const optionDate = new Date();
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);

      const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');

      console.log(optionValue);
      // Check if the time slot is booked
      const isBooked = bookedTimeSlots[checkInDate]?.some(slot => {
        const slotStart = slot.start;
        const slotEnd = slot.end;
        const optionTime = optionValue;
        
        return optionTime >= slotStart && slotEnd < optionTime;
      });

      console.log(isBooked);

      if (!isBooked) {
        // Add the option if not booked
        checkInTimeSelect.add(new Option(optionText, optionValue));
      }
    }
  } else {
    // Different-day check-in and check-out: Show all available times
    for (let time = earliestTime; time <= latestTime; time += 0.5) {
      const optionDate = new Date();
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);

      const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');

      // Check if the time slot is booked
      const isBooked = bookedTimeSlots[checkInDate]?.some(slot => {
        const slotStart = slot.start;
        const slotEnd = slot.end;
        const optionTime = optionValue;

        return optionTime >= slotStart && optionTime < slotEnd;
      });

      if (!isBooked) {
        checkInTimeSelect.add(new Option(optionText, optionValue));
      }
    }
  }

  // Set the first available time as default after populating
  checkInTimeSelect.value = checkInTimeSelect.options[0]?.value || '';
}



// Function to populate check-out time options
function populateCheckOutTimes(checkInTime, checkInDate, checkOutDate) {
  const minimumStay = 12; 
  checkOutTimeSelect.innerHTML = ''; 

  const [checkInHours, checkInMinutes] = checkInTime.split(':').map(Number);
  const checkInDateTime = new Date(checkInDate);
  checkInDateTime.setHours(checkInHours, checkInMinutes, 0, 0);

  // Minimum time the user can check out is 12 hours after check-in
  let checkOutMinTime = new Date(checkInDateTime.getTime() + minimumStay * 60 * 60 * 1000); // Add 12 hours

  const earliestTime = 6;
  const latestTime = 23.5; 

  // Calculate the difference in days between check-in and check-out
  const checkInDateObj = new Date(checkInDate);
  const checkOutDateObj = new Date(checkOutDate);
  const dayDifference = (checkOutDateObj - checkInDateObj) / (1000 * 60 * 60 * 24);

  if (checkInDate === checkOutDate) {
    // Same-day check-out, apply minimum 12-hour stay
    for (let time = checkOutMinTime.getHours() + (checkOutMinTime.getMinutes() / 60); time <= latestTime; time += 0.5) {
      const optionDate = new Date();
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);
      const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');
      checkOutTimeSelect.add(new Option(optionText, optionValue));
    }
  } else if (dayDifference === 1) {
    // Check-out within 2 days, apply minimum 12-hour stay on the second day
    for (let time = 0; time <= latestTime; time += 0.5) {
      const optionDate = new Date(checkOutDate);
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);

      // Only show times on the second day that respect the minimum 12-hour stay
      if (optionDate.getTime() >= checkOutMinTime.getTime()) {
        const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');
        checkOutTimeSelect.add(new Option(optionText, optionValue));
      }
    }
  } else {
    // Check-out 3 or more days after check-in, allow all times
    for (let time = 0; time <= latestTime; time += 0.5) {
      const optionDate = new Date(checkOutDate);
      optionDate.setHours(Math.floor(time), (time % 1) * 60, 0, 0);
      const optionText = optionDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const optionValue = optionDate.getHours().toString().padStart(2, '0') + ':' + optionDate.getMinutes().toString().padStart(2, '0');
      checkOutTimeSelect.add(new Option(optionText, optionValue));
    }
  }

  // Set the first available time as default after populating
  checkOutTimeSelect.value = checkOutTimeSelect.options[0].value;
}


document.addEventListener("DOMContentLoaded", function () {
  fp = flatpickr("#date-in", {
    enableTime: false,
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1), // Disable past days and today
    onChange: function (selectedDates, dateStr, instance) {
      document.querySelector("#date-in").value = dateStr;
      
      fp1.set('minDate', dateStr); 
      fp1.setDate(null); // Reset check-out date when check-in changes
    }
  });

  fp1 = flatpickr("#date-out", {
    enableTime: false,
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1), 
    onChange: function (selectedDates, dateStr, instance) {
      document.querySelector("#date-out").value = dateStr;

      const checkInDate = document.querySelector("#date-in").value;
      const checkOutDate = document.querySelector("#date-out").value;

      populateCheckInTimes(checkInDate,checkOutDate);

      // Ensure check-in time is selected before proceeding
      const checkInTime = checkInTimeSelect.value;
      if (checkInTime) {
        const checkInDate = document.querySelector("#date-in").value;
        populateCheckOutTimes(checkInTime, checkInDate, dateStr);
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
});

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

