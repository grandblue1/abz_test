<h1>Create User</h1>
<form id="create-user-form" method="post" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" >
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" >
    </div>
    <div>
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" >
    </div>
    <div>
        <label for="position_id">Position ID:</label>
        <input type="number" id="position_id" name="position_id">
    </div>
    <div>
        <label for="photo">Photo:</label>
        <input type="file" id="photo" name="photo">
    </div>
    <button type="submit">Submit</button>
</form>
<script>
    $(document).ready(function () {
        $('#create-user-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            fetch('/api/token', {
                method: 'GET'
            })
                .then(response => response.json())
                .then(data => {
                    let token = data.token;

                    return fetch('/api/users', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Authorization': 'Bearer ' + token,
                        }
                    });
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    h1 {
        text-align: center;
        color: #333;
    }
    form {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    label {
        display: block;
        margin-bottom: 8px;
        color: #333;
    }
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="number"],
    input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    button {
        background-color: #28a745;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
    }
</style>
