<style>
.calendar {
    flex: 1;
    max-width: 600px;
    padding: 30px 20px;
    background: linear-gradient(45deg,rgb(29, 69, 104),#19315D);
    border-radius: 10px;
}

.calendar .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #ccc;
}

.calendar .header .month {
    display: flex;
    align-items: center;
    font-size: 25px;
    font-weight: 600;
    color: #fff;
}

.calendar .header .btns {
    display: flex;
    gap: 10px;
}

.calendar .header .btns .btn {
    width: 50px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    color: #fff;
    background-color: #001A3E;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
}

.weekdays {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.weekdays .day {
    width: calc(100% / 7 - 10px);
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
}

.days {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.days .day {
    width: calc(100% / 7 - 10px);
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 400;
    color: #000; /* Ensure valid days have visible text */
    background-color: #fff;
    transition: all 0.3s;
    cursor: pointer; /* Add cursor for clickable days */
}

.days .day.clickable:hover, .days .day.selected {
    color: #fff;
    background-color: #001A3E;
    transform: scale(1.05);
}

/* Disable past days (already handled in JS) */
.days .day.disabled {
    background-color: #D9D9D9;
    color: #ccc;
    cursor: not-allowed;
}

.days .day.next,
.days .day.prev {
    background-color: #D9D9D9;
    color: #ccc;
    cursor: not-allowed;
}

</style>