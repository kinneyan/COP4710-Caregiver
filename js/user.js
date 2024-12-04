function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (!username || !password) {
        alert('Username and password cannot be empty');
        return;
    }

    const payload = JSON.stringify({ "username": username, "password": password });

    try
    {
        $.post("http://caregivers.kinneyan.com/api/users/login.php", payload, function(data, status)
        {
            if (data.id > 0)
            {
		userID = data.id;
                storeCookie();
                window.location.href = "profile.html";
            }
            else 
            {
                alert("Username or password was incorrect. Please try again.");
                return false;
            }
        });
    }
    catch(error)
    {
        console.error('Error:', error);
        alert('Login failed');
    }
    return false;
}

function register() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;
    const firstName = document.getElementById('fname').value;
    const lastName = document.getElementById('lname').value;

    if (!username || !password || !email || !firstName || !lastName) {
        alert('All fields are required');
        return;
    }

    const payload = JSON.stringify({
        "username": username,
        "password": password,
        "email": email,
        "firstName": firstName,
        "lastName": lastName
    });

    try {
        $.post("http://caregivers.kinneyan.com/api/users/register.php", payload, function(data, status) {
            if (data.verdict === "New user created successfully.") {
                // do stuff if registration is successful, take to new page or something
                window.location.href = "profile.html";
            } else {
                alert("Registration failed. Please try again.");
                return false;
            }
        });
    } catch (error) {
        console.error('Error:', error);
        alert('Registration failed');
    }
    return false;
}

function getUser() {

    const payload = JSON.stringify({ "id": userID });
		return user = {
			userID : 1,
			firstName: "Ham",
			lastName: "Ster",
			address: "1234 Hamster Way",
			email: "email@hamster.com",
			phone: 123456790,
			balance: 2000,
			availableHours: 20,
			dateCreated: "12/12/12",
			lastLogin: "21/21/21"
			};
    try
    {
        $.post("http://localhost:8080/api/users/getUser.php", payload, function(data, status)
        {
            if (data.id > 0) //Load user data into an object
            {
		var user = {
			userID: data.id,
			firstName: data.fname,
			lastName: data.lname,
			address: data.address,
			email: data.email,
			phone: data.phone,
			balance: data.balance,
			availableHours: data.available_hours,
			dateCreated: data.date_created,
			lastLogin: data.last_login_date
			};
		return user;
            }
            else 
            {
                alert("Invalid user ID.");
                return false;
            }
        });
    }
    catch(error)
    {
        console.error('Error:', error);
        alert('User load failed');
    }
    return false;
}

function getRecipientsByUser() {

    const payload = JSON.stringify({ "user_id": userID });
		var recipientList = {"recipients":[]};
		for (let i = 0; i < 2; i++) {
			recipientList.recipients.push({
				userID: 100 + i,
				firstName: "Momma",
				lastName: "Ster",
				age: 4,
				address: "1111 Hamster Hovel",
				notes: "Requires 3 sunflower seeds per day",
				dateCreated: "12/12/12"
			});
				
		}
		return recipientList;
    try
    {
        $.post("http://localhost:8080/api/recipients/getRecipientByUser.php", payload, function(data, status)
        {
            if (data.id > 0) //Load recipient data into an object list
            {
		var recipientList = {"recipients":[]};
		for (let i = 0; i < data.length; i++) {
			recipientList.recipients.push({
				userID: data[i].id,
				firstName: data[i].fname,
				lastName: data[i].lname,
				age: data[i].age,
				address: data[i].address,
				notes: data[i].notes,
				dateCreated: data[i].date_created
			});
				
		}
		return recipientList;

            }
            else 
            {
                alert("Invalid recipient ID.");
                return false;
            }
        });
    }
    catch(error)
    {
        console.error('Error:', error);
        alert('Recipient load failed');
    }
    return false;
}

function getContractsByUser() {

    const payload = JSON.stringify({ "user_id": userID });
		var contractList = {"contracts":[]};
		for (let i = 0; i < 2; i++) {
			contractList.contracts.push({
				contractID: 1+i,
				caregiverID: 1+i,
				recipientID: 100+i,
				startDate: "12/12/12",
				endDate: "13/13/13",
				hours: 24,
				rate: 1,
				approved: false
			});
				
		}
		return contractList;

    try
    {
        $.post("http://localhost:8080/api/recipients/getContractsByUser.php", payload, function(data, status)
        {
            if (data.id > 0) //Load contract data into an object list
            {
		var contractList = {"contracts":[]};
		for (let i = 0; i < data.length; i++) {
			contractList.contracts.push({
				contractID: data[i].id,
				cgID: data[i].caregiver_id,
				recipientID: data[i].recipient_id,
				startDate: data[i].start_date,
				endDate: data[i].end_date,
				hours: data[i].daily_hours,
				rate: data[i].rate,
				approved: data[i].approved
			});
				
		}
		return contractList;

            }
            else 
            {
                alert("Invalid contract ID.");
                return false;
            }
        });
    }
    catch(error)
    {
        console.error('Error:', error);
        alert('Recipient load failed');
    }
    return false;
}

function getReviewsByCaregiver() {

    const payload = JSON.stringify({ "caregiver_id": userID });

		var reviewList = {"reviews":[]};
		for (let i = 0; i < 10; i++) {
			reviewList.reviews.push({
				reviewID: 1+i,
				contractID: 12+i,
				reviewerID: 1,
				rating: (i*i+1)%6,
				notes: "Great with Hamsters",
				dateCreated: "1/1/2021",
			});
				
		}
		return reviewList;


    try
    {
        $.post("http://localhost:8080/api/recipients/getReviewsByCaregiver.php", payload, function(data, status)
        {
            if (data.id > 0) //Load review data into an object list
            {
		var reviewList = {"reviews":[]};
		for (let i = 0; i < data.length; i++) {
			reviewList.reviews.push({
				reviewID: data[i].id,
				contractID: data[i].contract_id,
				reviewerID: data[i].user_id,
				rating: data[i].rating,
				notes: data[i].notes,
				dateCreated: data[i].date_created,
			});
				
		}
		return reviewList;

            }
            else 
            {
                alert("Invalid contract ID.");
                return false;
            }
        });
    }
    catch(error)
    {
        console.error('Error:', error);
        alert('Recipient load failed');
    }
    return false;
}