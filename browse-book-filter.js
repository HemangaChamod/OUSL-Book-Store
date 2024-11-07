function filterBooks() {
        const title = document.getElementById("title-search").value.toLowerCase();
        const author = document.getElementById("author-search").value.toLowerCase();
        const genre = document.getElementById("genre-search").value.toLowerCase();
        
        /*

        document.getElementById("title-search") gets the input element where users can type the title they are looking for.

        .value gets the text that the user has entered.

        .toLowerCase() converts the text to lowercase so that the search is case-insensitive

        */
    
        const books = document.querySelectorAll(".book-card");//This line of code gathers all the elements on the webpage that represent books, which are identified by a common class name (book-card).

        //By using document.querySelectorAll(), we are able to select multiple elements that match the specified CSS selector, in this case, all elements with the class book-card.

        //The function returns a NodeList, which is a collection of all the selected book elements. This allows us to manipulate each book element later in the code.
    
        for (let i = 0; i < books.length; i++) { //Alternative method: books.forEach(book => {});
            const book = books[i];  // Access the current book element
            
            const bookTitle = book.querySelector("h2").textContent.toLowerCase();//gets the <h2> element (it contains the book title).
            const bookAuthor = book.querySelector(".author").textContent.toLowerCase();//gets the element that contains the author's name (with the class author).
            const bookGenre = book.querySelector(".genre").textContent.toLowerCase();//gets the element that contains the genre (with the class genre).

            //.textContent retrieves the text content of those elements, and .toLowerCase() converts it to lowercase for case-insensitive comparison.

        
            if (//The if statement checks if the book's title, author, and genre include the user's search inputs.
                bookTitle.includes(title) &&
                bookAuthor.includes(author) &&
                bookGenre.includes(genre)

                //If you’re using || and the search doesn’t work as expected, it might be due to the way each condition is handled in your code. When using ||, even one field needs to match, but if the fields are empty or only partially filled, unexpected results can occur.

                //When using ||, we should consider empty inputs carefully. To achieve this, you can add a condition that checks if a field is empty (meaning no filtering should be applied to that specific field).

            ) {
                book.style.display = "block";  //If any conditions are true, the book is displayed on the page (book.style.display = "block"), meaning it will be visible to the user.
            } else {
                book.style.display = "none";   //If any condition is false, the book is hidden (book.style.display = "none"), meaning it will not appear on the page.
            }
        }
    }


    