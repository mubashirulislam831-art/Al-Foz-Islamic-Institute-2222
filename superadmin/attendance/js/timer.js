/* Live Timer Engine */
function startTimer(elementId, startTimeStr) {
    const element = document.getElementById(elementId);
    if (!element) return;

    let startTime = startTimeStr ? new Date(startTimeStr) : new Date();
    
    function update() {
        const now = new Date();
        const diff = Math.floor((now - startTime) / 1000);
        
        const h = Math.floor(diff / 3600).toString().padStart(2, '0');
        const m = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
        const s = (diff % 60).toString().padStart(2, '0');
        
        element.innerText = `${h}:${m}:${s}`;
    }
    
    update();
    return setInterval(update, 1000);
}

window.timers = {};
