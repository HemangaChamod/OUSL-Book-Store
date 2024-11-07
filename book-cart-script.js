let cart = []; // Empty array to store items added to the shopping cart
let totalPrice = 0; // It will keep track of the total cost of all items in the cart

// Load cart from local storage when the page loads
window.onload = function() {
    const savedCart = localStorage.getItem('cart'); // This line retrieves any previously saved cart data from the browser's local storage using the key 'cart'. The retrieved data is stored in the savedCart variable.
    if (savedCart) { // This checks if savedCart contains any data
        cart = JSON.parse(savedCart); // If there is saved cart data, this line parses the JSON string back into an array and assigns it to the cart variable.
        // This allows the cart to be restored to its previous state.

        updateCartDisplay(); // Show the loaded cart items on the page
        calculateTotal(); // Calculate and display the total price
    }
    
};

function addToCart(title, price, image) {
    let itemExists = false; // It will be used to check if the item is already in the cart.

    // Loop through the cart to check for existing items
    for (let i = 0; i < cart.length; i++) {
        if (cart[i].title === title) { // This line checks if the title of the current cart item matches the title of the item being added.
            cart[i].quantity += 1; // Increase quantity if item already exists
            itemExists = true; // itemExists is set to true to indicate that the item was found in the cart.
            break; // Stop the loop once the item is found
        }
    }
    
    // If the item does not exist, add it to the cart
    if (!itemExists) { // This checks if itemExists is still false, meaning the item was not found in the cart.
        cart.push({ title, price, image, quantity: 1 }); // If the item does not exist, a new object representing the item is pushed onto the cart array.
    }

    // Save the updated cart to local storage
    localStorage.setItem('cart', JSON.stringify(cart)); // The updated cart array is converted to a JSON string and saved to local storage under the key 'cart'.
    
    const notification = document.getElementById('cartNotification');
    notification.style.display = 'block'; //The style.display property of the notification element is set to 'block'. This changes the element's display style from none (hidden) to block, making the notification visible on the page.
    setTimeout(() => {//The setTimeout function is used to delay the execution of the code inside it for a specified amount of time.2000 milliseconds (or 2 seconds)
        notification.style.display = 'none'; //After the delay, an arrow function is executed that sets notification.style.display back to 'none', which hides the notification again.
    }, 2000);
    
    updateCartDisplay(); // Update the cart display
    calculateTotal(); // Calculate and update the total
}

function updateCartDisplay() {//will handle updating the visible cart on the webpage.
    const cartContainer = document.getElementById('cart-items'); //This retrieves the HTML element with the ID cart-items, which is where the cart contents will be displayed, and assigns it to the variable cartContainer.
    cartContainer.innerHTML = ''; //This line clears any existing content in the cartContainer, preparing it to display the updated cart.
    
    if (cart.length === 0) {//This checks if the cart is empty.
        cartContainer.innerHTML = '<p>Your cart is empty</p>';//If the cart is empty, it sets the inner HTML of the cartContainer to display a message indicating that the cart is empty.
        totalPrice = 0; // Reset total price to 0 if cart is empty
        document.getElementById("total-price").innerText = `$${totalPrice.toFixed(2)}`; // Display total price
        return; // Exit if cart is empty
    }

    for (let i = 0; i < cart.length; i++) {//This begins a for loop to iterate over each item in the cart
        const item = cart[i]; //this assigns the current item to the variable item.
        
        const cartItem = document.createElement('div'); //creates a new div element that will represent the current item in the cart
        cartItem.className = 'cart-item';//Assigns a CSS class named cart-item to the cartItem div for styling purposes
    
        // Set inner HTML with item details and buttons for update/remove
        cartItem.innerHTML = `
            <img src="${item.image}" alt="${item.title}" />
            <h2>${item.title}</h2>
            <p>Quantity: <span id="quantity-${i}">${item.quantity}</span></p>
            <p>Price: $${(item.price * item.quantity).toFixed(2)}</p>
            <div class="cart-item-controls">
                <button class="increase-button" onclick="updateItem(${i}, 1)">
                    <i class="fas fa-plus"></i> 
                </button>
                <button class="decrease-button" onclick="updateItem(${i}, -1)">
                    <i class="fas fa-minus"></i> 
                </button>
                <button class="remove-button" onclick="removeFromCart(${i})">
                    <i class="fas fa-trash-alt"></i> 
                </button>
            </div>
        `;
    
        cartContainer.appendChild(cartItem); //This line appends the created cartItem div to the cartContainer, making it visible in the cart display.
    }
}

function updateItem(index, change) {
    // Update the quantity of an item in the cart
    if (change === 1) {
        // Increase quantity
        cart[index].quantity += 1;
    } else if (change === -1) {
        // Decrease quantity, but not below 1
        if (cart[index].quantity > 1) {
            cart[index].quantity -= 1;
        }
    }
    
    localStorage.setItem('cart', JSON.stringify(cart)); // Update local storage
    updateCartDisplay(); // Refresh the cart display
    calculateTotal(); // Recalculate the total
}

function removeFromCart(index) {
    // Remove an item from the cart
    cart.splice(index, 1); //This uses the splice method to remove one item from the cart array at the specified index.

    localStorage.setItem('cart', JSON.stringify(cart)); // Update local storage
    updateCartDisplay(); // Refresh the cart display
    calculateTotal(); // Recalculate the total
}

function calculateTotal() {
    // Calculate the total price of items in the cart
    totalPrice = 0; // Reset total price
    for (let i = 0; i < cart.length; i++) {
        totalPrice += cart[i].price * cart[i].quantity; //For each item, it calculates the total price of that item (price multiplied by quantity) and adds it to totalPrice.
    }
    document.getElementById("total-price").innerText = `$${totalPrice.toFixed(2)}`; // Update the displayed total
}

function clearCart() {
    localStorage.removeItem('cart'); // Remove only the cart
    cart = []; // Clear the cart array
    updateCartDisplay(); // Update the cart display
    totalPrice = 0; // Reset total price
    document.getElementById("total-price").innerText = `$${totalPrice.toFixed(2)}`; // Update the displayed total
}
