// Function to fetch and display book data
function fetchBooks() {
    // Make an AJAX request to retrieve book data from the server
    // Replace the URL with the appropriate endpoint to fetch book data
    fetch('api/books')
        .then(response => response.json())
        .then(data => {
            // Update the main content area with the fetched book data
            const mainContent = document.querySelector('.main-content');
            mainContent.innerHTML = '';

            data.forEach(book => {
                const bookElement = document.createElement('div');
                bookElement.classList.add('book');
                bookElement.innerHTML = `
                    <h3>${book.title}</h3>
                    <p>Author: ${book.author}</p>
                    <p>ISBN: ${book.isbn}</p>
                `;
                mainContent.appendChild(bookElement);
            });
        })
        .catch(error => {
            console.error('Error fetching book data:', error);
        });
}

// Function to handle logout
function logout() {
    // Make an AJAX request to logout the user
    // Replace the URL with the appropriate endpoint to handle logout
    fetch('api/logout', { method: 'POST' })
        .then(response => {
            if (response.ok) {
                // Redirect to the login page after successful logout
                window.location.href = 'login.php';
            } else {
                console.error('Logout failed');
            }
        })
        .catch(error => {
            console.error('Error logging out:', error);
        });
}

// Call the fetchBooks function to load initial book data
fetchBooks();
