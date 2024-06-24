document.addEventListener('DOMContentLoaded', () => {
    const timerElement = document.getElementById('timer');
    const endTimeKey = 'endTime';
    let endTime = localStorage.getItem(endTimeKey);

    if (!endTime) {
        endTime = Date.now() + 60000; // 1 minuto desde ahora
        localStorage.setItem(endTimeKey, endTime);
    } else {
        endTime = parseInt(endTime, 10);
    }

    const updateTimer = () => {
        const now = Date.now();
        const timeLeft = endTime - now;

        if (timeLeft <= 0) {
            timerElement.textContent = '00:00';
            localStorage.removeItem(endTimeKey);
            clearInterval(timerInterval);
            window.location.href = "/partida/procesarRespuesta";
            return;
        }

        const minutes = Math.floor(timeLeft / 60000);
        const seconds = Math.floor((timeLeft % 60000) / 1000);

        timerElement.textContent = `${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    };

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer();
});

