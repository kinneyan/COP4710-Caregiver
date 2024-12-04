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
                // do stuff if login is successful, take to new page or something
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
