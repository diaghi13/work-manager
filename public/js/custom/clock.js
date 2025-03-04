const clock = document.getElementById('clock');
const startTimeDisplay = document.getElementById('start-time');
const toggleButton = document.getElementById('clock-toggle');
const stopButton = document.getElementById('clock-stop');
const modal = document.getElementById('modal');
const confirmEndButton = document.getElementById('confirm-end');
const cancelEndButton = document.getElementById('cancel-end');
const resetClockButton = document.getElementById('reset-clock');
const worksiteSelect = document.getElementById('worksite');
const descriptionInput = document.getElementById('description');
let timer;
let modalOpened = false;
let workdayData = JSON.parse(localStorage.getItem('workdayData')) || { time: 0, isPaused: true, startTime: null, pausedDuration: 0, lastPausedTime: null };

function updateClock() {
    const elapsedTime = workdayData.time;
    const hours = String(Math.floor(elapsedTime / 3600)).padStart(2, '0');
    const minutes = String(Math.floor((elapsedTime % 3600) / 60)).padStart(2, '0');
    const seconds = String(elapsedTime % 60).padStart(2, '0');
    clock.innerHTML = `${hours}:${minutes}:${seconds}`;
    if (workdayData.startTime) {
        const startTime = new Date(workdayData.startTime);
        startTimeDisplay.innerHTML = `Start Time: ${startTime.toLocaleTimeString()}`;
    } else {
        startTimeDisplay.innerHTML = '';
    }
}

function startClock() {
    if (!workdayData.startTime) {
        workdayData.startTime = new Date();
    }
    if (workdayData.lastPausedTime) {
        const now = new Date();
        workdayData.pausedDuration += Math.floor((now - new Date(workdayData.lastPausedTime)) / 1000);
        workdayData.lastPausedTime = null;
    }
    timer = setInterval(() => {
        if (!modalOpened) {
            const now = new Date();
            workdayData.time = Math.floor((now - new Date(workdayData.startTime)) / 1000) - workdayData.pausedDuration;
            localStorage.setItem('workdayData', JSON.stringify(workdayData));
            updateClock();
        }
    }, 1000);
}

function pauseClock() {
    clearInterval(timer);
    workdayData.lastPausedTime = new Date();
    localStorage.setItem('workdayData', JSON.stringify(workdayData));
}

function toggleClock() {
    clearInterval(timer);
    if (workdayData.isPaused) {
        startClock();
        toggleButton.textContent = 'PAUSE';
        toggleButton.classList.replace('bg-green-500', 'bg-yellow-500');
        toggleButton.classList.replace('hover:bg-green-600', 'hover:bg-yellow-600');
    } else {
        pauseClock();
        toggleButton.textContent = 'START';
        toggleButton.classList.replace('bg-yellow-500', 'bg-green-500');
        toggleButton.classList.replace('hover:bg-yellow-600', 'hover:bg-green-600');
    }
    workdayData.isPaused = !workdayData.isPaused;
    localStorage.setItem('workdayData', JSON.stringify(workdayData));
}

function stopClock() {
    pauseClock();
    modalOpened = true;
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    }, 10);
}

function resumeClock() {
    if (!workdayData.isPaused) {
        startClock();
    }
}

function resetClock() {
    clearInterval(timer);
    workdayData = { time: 0, isPaused: true, startTime: null, pausedDuration: 0, lastPausedTime: null };
    localStorage.setItem('workdayData', JSON.stringify(workdayData));
    updateClock();
    toggleButton.textContent = 'START';
    toggleButton.classList.replace('bg-yellow-500', 'bg-green-500');
    toggleButton.classList.replace('hover:bg-yellow-600', 'hover:bg-green-600');
}

function resetForm() {
    worksiteSelect.value = '';
    descriptionInput.value = '';
}

function clearClockData() {
    localStorage.removeItem('workdayData');
    location.reload();
    resetClock();
}

toggleButton.addEventListener('click', toggleClock);
stopButton.addEventListener('click', stopClock);
resetClockButton.addEventListener('click', clearClockData);

confirmEndButton.addEventListener('click', (event) => {
    event.preventDefault();
    if (!worksiteSelect.value) {
        worksiteSelect.reportValidity();
        return;
    }
    const endTime = new Date();
    const data = {
        startTime: workdayData.startTime,
        endTime: endTime,
        time: workdayData.time,
        worksite: worksiteSelect.value,
        description: descriptionInput.value
    };
    fetch('/api/v1/end-workday', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    }).then(data => {
        console.log(data);
        resetForm();
        resetClock();
        location.reload(); // Reload the page to reset the entire state
    }).catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
    modal.classList.add('opacity-0');
    modal.querySelector('div').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
});

cancelEndButton.addEventListener('click', () => {
    modal.classList.add('opacity-0');
    modal.querySelector('div').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        modalOpened = false;
        resumeClock();
    }, 300);
});

if (workdayData.startTime && !workdayData.isPaused) {
    startClock();
    toggleButton.textContent = 'PAUSE';
    toggleButton.classList.replace('bg-green-500', 'bg-yellow-500');
    toggleButton.classList.replace('hover:bg-green-600', 'hover:bg-yellow-600');
} else {
    updateClock();
}

// Fetch worksite data from the server and populate the select field
fetch('/api/v1/worksites')
    .then(response => response.json())
    .then(data => {
        data.forEach(worksite => {
            const option = document.createElement('option');
            option.value = worksite.id;
            option.textContent = worksite.name;
            worksiteSelect.appendChild(option);
        });
    });

// Immediately update the clock on page load
updateClock();
