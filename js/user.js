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
        $.post("http://localhost:8080/api/users/login.php", payload, function(data, status)
        {
            if (data.id > 0)
            {
                // do stuff if login is successful, take to new page or something
                alert("Login successful");
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
