function searchUser() {

    const payload = JSON.stringify({ "id": searchID });
		return user = {
			userID : 2,
			firstName: "Bob",
			lastName: "Cat",
			address: "1234 Kitty Cove",
			email: "email@kitties.com",
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

function searchRecipientsByUser() {

    const payload = JSON.stringify({ "user_id": searchID });
		var recipientList = {"recipients":[]};
		for (let i = 0; i < 2; i++) {
			recipientList.recipients.push({
				userID: 100 + i,
				firstName: "Daddy",
				lastName: "Ster",
				age: 14,
				address: "1111 Lion's Den",
				notes: "Requires 3 fish per day",
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


function createContract() {
    const caregiverID = userID;
    const hiringID = document.getElementById('hireid').value;
    const recipientID = document.querySelector('#selectRecipient').value;
    const startDate = document.getElementById('start').value;
    const endDate = document.getElementById('end').value;
    const hours = document.getElementById('hours').value;
    const rate = document.getElementById('rate').value;

    const payload = JSON.stringify({ "caregiver_id": caregiverID, "hiring_user_id": hiringID, "recipient_id": recipientID, "start_date": startDate, "end_date": endDate, "daily_hours": hours, "rate": rate});

    try
    {
        $.post("http://localhost:8080/api/contracts/createContract.php", payload, function(data, status)
        {
            if (!success) 
            {
                alert("An error has occurred");
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

