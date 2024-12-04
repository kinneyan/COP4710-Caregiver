function searchUser() {

    const payload = JSON.stringify({ "id": searchID });

    try
    {
        $.post("http://caregivers.kinneyan.com/api/users/getUser.php", payload, function(data, status)
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

    try
    {
        $.post("http://caregivers.kinneyan.com/api/recipients/getRecipientByUser.php", payload, function(data, status)
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

function getRecipient() {

    const payload = JSON.stringify({"id": recipientID});

    try
    {
        $.post("http://caregivers.kinneyan.com/api/contracts/getRecipient.php", payload, function(data, status)
        {
            if (data.id > 0) {
		var recipient = {
			userID: data.id,
			firstName: data.fname,
			lastName: data.lname,
			age: data.age,
			address: data.address,
			notes: data.notes,
			childID: data.user_id,
			dateCreated: data.date_created
			};
		return recipient;		
	    }
            else {
                alert("An error has occurred");
                return false;
            }
        });
    }
    catch(error)
    {
        console.error('Error:', error);
        alert('Load failed');
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

    const payload = JSON.stringify({ 
			"caregiver_id": caregiverID, 
			"hiring_user_id": hiringID, 
			"recipient_id": recipientID, 
			"start_date": startDate, 
			"end_date": endDate, 
			"daily_hours": hours, 
			"rate": rate});

    try
    {
        $.post("http://caregivers.kinneyan.com/api/contracts/createContract.php", payload, function(data, status)
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
        alert('Contract failed');
    }
    return false;
}

function getContract() {

    const payload = JSON.stringify({"id": contractID});

    try
    {
        $.post("http://caregivers.kinneyan.com/api/contracts/getContract.php", payload, function(data, status)
        {
            if (data.id > 0) {
		var contract = {
			contractID: data.id,
			caregiverID: data.caregiver_id,
			employerID: data.hiring_user_id,
			recipientID: data.recipient_id,
			startDate: data.start_date,
			endDate: data.end_date,
			hours: data.daily_hours,
			rate: data.rate,
			approved: data.approved,
			dateCreated: data.date_created
			};
		return contract;		
	    }
            else {
                alert("An error has occurred");
                return false;
            }
        });
    }
    catch(error)
    {
        console.error('Error:', error);
        alert('Load failed');
    }
    return false;
}