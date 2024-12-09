function openFilterModal() {
    document.getElementById('filter-modal').style.display = 'block';
}

function closeFilterModal() {
    document.getElementById('filter-modal').style.display = 'none';
}

async function applyFilter(event) {
    event.preventDefault();
    
    const formData = new FormData(document.getElementById('filter-form'));
    
    const response = await fetch('room-booking.php', {
        method: 'POST',
        body: formData,
    });
    
    const result = await response.json();

    // Update room list based on the filtered results
    const roomsContainer = document.querySelector('.rooms-container');
    roomsContainer.innerHTML = ''; // Clear previous rooms
    
    if (result.length > 0) {
        result.forEach(room => {
            const roomElement = document.createElement('div');
            roomElement.classList.add('room');
            roomElement.innerHTML = `
                <img src="${room.image ? 'data:image/jpeg;base64,' + room.image : 'https://placehold.co/150x150/gray/white'}" alt="Room ${room.room_num}" class="room-image">
                <p><strong>Room Number:</strong> ${room.room_num}</p>
                <p><strong>Department:</strong> ${room.department}</p>
                <p><strong>Capacity:</strong> ${room.capacity} people</p>
                <p><strong>Lab:</strong> ${room.lab ? 'Yes' : 'No'}</p>
                <p><strong>Smartboard:</strong> ${room.smartboard ? 'Yes' : 'No'}</p>
                <p><strong>Datashow:</strong> ${room.datashow ? 'Yes' : 'No'}</p>
                <button class="book-room-button" onclick="openBookingForm(${room.room_num})">Book</button>
            `;
            roomsContainer.appendChild(roomElement);
        });
    } else {
        roomsContainer.innerHTML = "<p>No rooms available with the selected filter criteria.</p>";
    }

    closeFilterModal();
}
