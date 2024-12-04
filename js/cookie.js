function storeCookie()
{
    let currentDate = new Date();
    let expireMinutes = 30;
    let expireDate = new Date(currentDate.getTime() + expireMinutes * 60 * 1000);

    document.cookie = "userId=" + userId + ",searchID=" + searchID + ",recipientID=" + recipientID + ",contractID=" + contractID + ";expires=" + expireDate.toGMTString();
}

function getCookie()
{
    userId = -1;
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
        else if (key == "searchID")
        {
            searchID = value;
        }
        else if (key == "recipientID")
        {
            recipientID = value;
        }
        else if (key == "contractID")
        {
            contractID = value;
        }
    }

    // verify restored information
    if (userId < 0)
    {
        window.location.href = "login.html";
    }
    else if(window.location.pathname != "/profile.html")
    {
      window.location.href = "profile.html";
    }
}