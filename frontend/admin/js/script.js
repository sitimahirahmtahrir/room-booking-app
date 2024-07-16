document.getElementById('add-room-type').addEventListener('submit', function(event) {
    event.preventDefault();
    const type = document.getElementById('type').value;
    const description = document.getElementById('description').value;

    fetch('http://localhost/api/room-types', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type, description })
    }).then(response => response.json())
      .then(data => {
          alert('Room type added successfully');
          console.log(data);
          // Reload room types list or add to DOM directly
      })
      .catch(error => console.error('Error:', error));
});
