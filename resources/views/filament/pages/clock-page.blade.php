<div class="flex items-center justify-center h-screen">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <div class="text-center">
        <div id="clock" class="text-4xl font-semibold mb-4">00:00:00</div>
        <div id="start-time" class="text-lg mb-4"></div>
        <div class="flex justify-center space-x-4">
            <button id="clock-toggle" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-all duration-150 ease-in-out">START</button>
            <button id="clock-stop" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-all duration-150 ease-in-out">STOP</button>
        </div>
    </div>
    <!-- Modal -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4 sm:mx-auto transform transition-transform duration-300 ease-in-out scale-95">
            <h2 class="text-xl font-semibold mb-4">End Workday</h2>
            <p class="mb-4">Are you sure you want to end your workday?</p>
            <select id="worksite" class="mb-4 w-full p-2 border rounded">
                <option value="">Select a worksite</option>
                <!-- Options will be populated by JavaScript -->
            </select>
            <textarea id="description" class="mb-4 w-full p-2 border rounded" rows="3" placeholder="Description"></textarea>
            <div class="flex justify-end space-x-4">
                <button id="confirm-end" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-all duration-150 ease-in-out">Yes</button>
                <button id="cancel-end" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-all duration-150 ease-in-out">No</button>
                <button id="reset-clock" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-all duration-150 ease-in-out">Reset Clock</button>
            </div>
        </div>
    </div>
    <script>
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
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            }).then(response => {
                if (!response.ok) {
                    console.log(response);
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
    </script>
</div>
