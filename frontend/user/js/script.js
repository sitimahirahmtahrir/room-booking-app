document.getElementById('book-room-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const room = document.getElementById('room-number').value;
    const date = document.getElementById('date').value;
    const startTime = document.getElementById('start-time').value;
    const endTime = document.getElementById('end-time').value;
    const user = document.getElementById('user-name').value;

    fetch('/api/bookings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ room, date, startTime, endTime, user })
    }).then(response => response.json())
      .then(data => {
          alert('Booking successful');
          document.getElementById('book-room-form').reset();
      })
      .catch(error => console.error('Error:', error));
});

function loadBookings(timeFrame) {
    fetch(`/api/bookings/${timeFrame}`)
        .then(response => response.json())
        .then(bookings => {
            const list = document.getElementById('bookings-list');
            list.innerHTML = '';
            bookings.forEach(booking => {
                const div = document.createElement('div');
                div.textContent = `${booking.date} - Room ${booking.room}: ${booking.startTime} to ${booking.endTime} (User: ${booking.user})`;
                list.appendChild(div);
            });
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    loadBookings('today'); // Load today's bookings by default
});
