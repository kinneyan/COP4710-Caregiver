function storeCookie()
{
    let currentDate = new Date();
    let expireMinutes = 30;
    let expireDate = new Date(currentDate.getTime() + expireMinutes * 60 * 1000);

    document.cookie = "userID=" + userID + ";expires=" + expireDate.toGMTString();
}

function getCookie()
{
    userID = -1;
    const cookie = document.cookie;
    const data = cookie.split(";")[0].split(",");


    for (var i = 0; i < data.length; i++)
    {
        let currentVar = data[i].trim();
        currentVar = currentVar.split("=");
        let key = currentVar[0];
        let value = currentVar[1];
        if (key == "userID")
        {
            userID = value;
        }
    }

    // verify restored information
    if (userID < 0)
    {
        window.location.href = "login.html";
    }
}

function logout()
{
    userId = -1;
    searchID = -1;
    recipientID = -1;
    contractID = -1;
    window.location.href = "index.html";
    document.cookie = "";
    return;
}
