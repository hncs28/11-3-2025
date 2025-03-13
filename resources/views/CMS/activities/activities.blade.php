<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        #menu {
            background: #007bff;
            padding: 0;
            margin: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #menu ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        #menu li {
            width: auto;
            padding: 0 20px;
        }

        #menu li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            display: block;
            padding: 15px 0;
            transition: background-color 0.3s, color 0.3s;
        }

        #menu li a:hover {
            background: #0056b3;
            color: #ffffff;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #007bff;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        td a {
            color: #007bff;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        button {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button.edit {
            background-color: #0ffc07;
        }

        button.delete {
            background-color: red;
            color: #fff;
        }

        button.openModalBtn {
            background: rgb(3, 235, 99);
            color: white;
            height: 1.5cm;
            width: 3cm;
            border-radius: 5px;
        }

        button:hover {
            opacity: 0.8;
        }

        form {
            display: inline;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 30%;
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 25px;
            cursor: pointer;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
</head>

<body>
    <div id="menu">
        <ul>
            <li><a href="/CMS/activities/">Activities</a></li>
            <li><a href="/CMS/projects/">Projects</a></li>
            <li><a href="/CMS/traditional_room/">Traditional Room</a></li>
            <li><a href="/CMS/annual/">Annual List</a></li>
            <li><button id="logoutBtn" class="logoutbutton">Logout</button>
            </li>
        </ul>
    </div>

    <h1>Activities List</h1>
    <table>
        <thead>
            <tr>
                <th>actID</th>
                <th>actName</th>
                <th>actImg</th>
                <th>Tools</th>
            </tr>
        </thead>
        <tbody id="activityTableBody"></tbody>
    </table>

    <div style="text-align: center;">
        <button type="button" id="addNew" class="openModalBtn">Add New Activity</button>
    </div>

    <div id="activityModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Add Activity</h2>
            <form id="activityForm">
                <input type="hidden" id="actID">
                <label>Activity Name:</label>
                <input type="text" id="actName" required>
                <label>Image URL:</label>
                <input type="text" id="actImg" required>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>

<script>
    var baseurl = "{{ config('app.url') }}";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function fetchActivities() {
        $.ajax({
            url: `${baseurl}/api/activities`,
            type: "GET",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('authToken')
            },
            success: function(data) {
                let tableBody = $("#activityTableBody").empty();
                data.forEach(activity => {
                    let imageUrl = activity.actImg || 'default-image.png';
                    tableBody.append(`
                    <tr id="row-${activity.actID}">
                        <td>${activity.actID}</td>
                        <td><a href="/CMS/activities/${activity.actID}">${activity.actName}</a></td>
                        <td><img src="${imageUrl}" width="50"></td>
                        <td>
                            <button type="button" class="edit" onclick="editActivity(${activity.actID})">Edit</button>
                            <button type="button" class="delete" onclick="deleteActivity(${activity.actID})">Delete</button>
                        </td>
                    </tr>
                `);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching activities:", error);
                alert("Failed to load activities. Please try again.");
            }
        });
    }

    function deleteActivity(actID) {
        if (!confirm("Are you sure?")) return;
        $.ajax({
            url: `${baseurl}/api/delete/activities/${actID}`,
            type: "DELETE",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('authToken')
            },
            success: function() {
                $(`#row-${actID}`).remove();
                alert("Activity deleted successfully!");
            }

        });
    }

    function editActivity(actID) {
        $.ajax({
            url: `${baseurl}/api/find/activities/${actID}`,
            type: "GET",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('authToken')
            },
            success: function(activity) {
                $("#modalTitle").text("Edit Activity");
                $("#actID").val(activity.actID);
                $("#actName").val(activity.actName);
                $("#actImg").val(activity.actImg);
                $("#activityModal").show();
            }

        });
    }

    $("#addNew").click(function() {
        $("#modalTitle").text("Add Activity");
        $("#actID").val("");
        $("#activityForm")[0].reset();
        $("#activityModal").show();
    });

    $(".close, .modal").click(function(event) {
        if ($(event.target).is(".modal") || $(event.target).is(".close")) {
            $("#activityModal").hide();
        }
    });

    $("#activityForm").submit(function(event) {
        event.preventDefault();
        let actID = $("#actID").val();
        let url = actID ? `${baseurl}/api/put/activities/${actID}` :
            "{{ route('post') }}";
        let method = actID ? "PUT" : "POST";

        $.ajax({
                url,
                type: method,
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                },
                data: {
                    actName: $("#actName").val(),
                    actImg: $("#actImg").val()
                }
            })
            .done(() => {
                fetchActivities();
                $("#activityModal").hide();
            });
    });

    fetchActivities();
    $(document).ready(function() {
        $("#logoutBtn").click(function() {
            logout();
        });
    });

    function logout() {
        if (!confirm("Wanna get out??")) return;
        $.ajax({
            url: `${baseurl}/api/logout`,
            type: "POST",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('authToken')
            },
            success: function(response) {
                localStorage.removeItem("authToken");
                window.location.href = "{{ route('loginform') }}";
            },
            error: function(xhr) {
                alert("Logout failed: " + xhr.responseText);
            }
        });
    }
</script>
